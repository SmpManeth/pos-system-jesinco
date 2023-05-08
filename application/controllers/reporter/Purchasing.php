<?php

/**
 * Description of Purchasing
 *
 * @author DP4
 * Aug 30, 2018 11:41:50 AM
 */
class Purchasing extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function total_purchasing_summary() {
        if ($this->ion_auth->logged_in()) {

            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");

            $this->load->model("grn_item_model");
            $this->load->model("wl_doc_code_model");
            $this->load->model("branch_model");
            $branch = $this->branch_model->get($b);
            $_grns = $this->grn_item_model->get_total_grn_summary($b,$s, $e);
            $grns = array();
            
            $doc_codes = $this->wl_doc_code_model->get_prefixes($b);
            

            foreach ($_grns as $_grn) {
                $grns[$_grn->gr_id]['data'] = array(
                    "gr_id"=> $doc_codes['grn']->prefix. str_pad($_grn->gr_id, $doc_codes['grn']->length, "0", STR_PAD_LEFT),
                    "total"=> $_grn->total,
                    "del_location"=> $_grn->del_location,
                    "grn_date"=> $_grn->grn_date
                );
                $grns[$_grn->gr_id]['items'][] = $_grn;
            }
            
            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Date","GRN ID", "Total", "Location"];
            $widths = [40, 50, 50, 50];
            $text_direction = ["L", "L", "R", "L"];
            $pdf->AddPage();
            $pdf->SetFont('Times', 'B', 18);
            $pdf->Cell(0, 6, $this->branch->branch_name, "0", 1, "C");
            $pdf->SetFont('Consolas', '', 10);
            $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
            if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
            } else {
                $pdf->Cell(0, 1, "", "B", 1, "C");
            }
            $pdf->SetFont('', 'B', 11);
            $pdf->Cell(38, 6, "GRN Summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(150, 6, "Date : $s - $e", "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Branch ");
            $pdf->Cell(114, 6, ": " . strtoupper($branch->branch_name), 0, 1);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont('', 'B', 10);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont('', '', 10);

            $i = 0;
            $h = 6;
            $total = 0;
            // dump_exit($grns);
            foreach ($grns as $grn) {
                $i++;

                $pdf->Cell($widths[0], $h, $grn['data']['grn_date'], 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $grn['data']['gr_id'], 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $grn['data']['total'], 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $grn['data']['del_location'], 1, 1, $text_direction[3]);
                $pdf->Cell(0, 2, "", 0, 1);
                
                foreach ($grn['items'] as $item) {
                    $pdf->Cell($widths[0], $h, "", 0, 0, "L");
                    $pdf->Cell(25, $h, $item->itm_code, 1, 0, "L");
                    $pdf->Cell(60, $h, $item->itm_name, 1, 0, "L");
                    $pdf->Cell(20, $h, $item->qty, 1, 0, "R");
                    $pdf->Cell(20, $h, $item->price, 1, 0, "R");
                    $pdf->Cell(25, $h, is_zero($item->price * $item->qty ), 1, 1, "R");
                }
                
                $pdf->Cell(0, 4, "", 0, 1);
                $total += $grn['data']['total'];
            }

            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell(array_sum($widths) - $widths[3], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[3], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("total-purchasing-summary.pdf", "I");
            
        } else {
            redirect(base_url("login"));
        }
    }
    public function purchasing_order_summary() {
        if ($this->ion_auth->logged_in()) {

            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");

            $this->load->model("p_order_model");
            $this->load->model("branch_model");
            $this->load->model("wl_doc_code_model");
            $branch = $this->branch_model->get($b);
            $pos = $this->p_order_model->get_orders_report($b,$s, $e);
            
            $doc_codes = $this->wl_doc_code_model->get_prefixes($b);
            
            
            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["ID","Supplier","Ref", "Date", "Del Date","Total"];
            $widths = [25, 68, 25, 25, 25, 25];
            $text_direction = ["L", "L", "L", "L", "L","R"];
            $pdf->AddPage();
            $pdf->SetFont('Times', 'B', 18);
            $pdf->Cell(0, 6, $this->branch->branch_name, "0", 1, "C");
            $pdf->SetFont('Consolas', '', 10);
            $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
            if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
            } else {
                $pdf->Cell(0, 1, "", "B", 1, "C");
            }
            $pdf->SetFont('', 'B', 11);
            $pdf->Cell(38, 6, "Purchasing Order Summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(150, 6, "Date : $s - $e", "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Branch ");
            $pdf->Cell(114, 6, ": " . strtoupper($branch->branch_name), 0, 1);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont('', 'B', 10);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont('', '', 10);

            $i = 0;
            $h = 6;

            foreach ($pos as $po) {
                $i++;

                $po_id = $doc_codes['po']->prefix. str_pad($po->po_id, $doc_codes['po']->length, "0", STR_PAD_LEFT);

                $pdf->Cell($widths[0], $h, $po_id, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $po->company_name, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $po->po_ref, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $po->p_date, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $po->del_date, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, is_zero($po->total), 1, 1, $text_direction[5]);
                
            }

            $pdf->add_signature();
            $pdf->Output("purchasing-order-summary.pdf", "I");
            
        } else {
            redirect(base_url("login"));
        }
    }

    public function good_returned_summary() {
        if ($this->ion_auth->logged_in()) {
            $start = $this->input->get("s");
            $end = $this->input->get("e");

            $this->load->model("supplier_return_item_model");
            $items = $this->supplier_return_item_model->get_report_items($this->branch, $start, $end);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Date", "Return No.", "Supplier", "Item Code", "Item Name", "Quantity", "Rate", "Total"];
            $widths = [25, 25, 80, 20, 70, 20, 20, 20];
            $text_direction = ["L", "L", "L", "L", "L", "C", "R", "R"];
            $pdf->AddPage();
            $pdf->SetFont('Times', 'B', 18);
            $pdf->Cell(0, 6, $this->branch->branch_name, "0", 1, "C");
            $pdf->SetFont('Consolas', '', 10);
            $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
            if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
            } else {
                $pdf->Cell(0, 1, "", "B", 1, "C");
            }
            $pdf->SetFont('', 'B', 11);
            $pdf->Cell(38, 6, "Good Returned Summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(242, 6, "Date : $start - $end", "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont('', 'B', 10);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont('', '', 10);

            $i = 0;
            $h = 6;
            $total = 0;
            foreach ($items as $item) {
                $i++;
                $rate = doubleval($item->rate);
                $qty = doubleval($item->qty);
                $_total = $rate * $qty;
                $pdf->Cell($widths[0], $h, $item->ret_date, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, decorate_code($item->ret_id, "supreturn", $this->prefixes), 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $item->company_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $item->itm_code, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $item->itm_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[5], $h, $qty, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[6], $h, is_zero($rate), 1, 0, $text_direction[5]);
                $pdf->Cell($widths[7], $h, is_zero($_total), 1, 1, $text_direction[6]);
                $total += $_total;
            }

            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell(array_sum($widths) - $widths[6], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[6], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function item_sales_summary() {
        if ($this->ion_auth->logged_in()) {

            $d = $this->input->get("d");

            $this->load->model("stock_model");
            $this->load->model("invoice_item_model");
            $this->load->model("grn_item_model");
            $stocks = $this->stock_model->get_stock_list($this->branch);

            $_inv_items = $this->invoice_item_model->get_invoice_sales_summary($this->branch, $d);

            $inv_items = array();
            foreach ($_inv_items as $_itm) {
                if (doubleval($_itm->qty) > 0) {
                    $inv_items[$_itm->itm_id][] = $_itm->qty;
                    $inv_items[$_itm->itm_id][] = $_itm->rate;
                    $inv_items[$_itm->itm_id][] = $_itm->amount;
                }
            }
//            $grn_items = array();
//            foreach ($_grn_items as $_gitm) {
//                if (doubleval($_gitm->qty) > 0) {
//                    $grn_items[$_gitm->item_id] = $_gitm->qty;
//                }
//            }

            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["#", "Code", "Name", "Quantity", "Sales", "Total"];
            $widths = [15, 30, 75, 20, 25, 25];
            $text_direction = ["L", "L", "L", "C", "R", "R"];
            $pdf->AddPage();
            $pdf->SetFont('Times', 'B', 18);
            $pdf->Cell(0, 6, $this->branch->branch_name, "0", 1, "C");
            $pdf->SetFont('Consolas', '', 10);
            $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
            if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
            } else {
                $pdf->Cell(0, 1, "", "B", 1, "C");
            }
            $pdf->SetFont('', 'B', 11);
            $pdf->Cell(38, 6, "Item Sales Summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(155, 6, "Date : " . date("M d, Y ", strtotime($d)), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);

            $i = 0;
            $h = 6;
            $total = 0;
            foreach ($stocks as $item) {
                $i++;
                if (!empty($item->itm_code)) {

                    $_qty = isset($inv_items["$item->item_id"][0]) ? ($inv_items["$item->item_id"][0]) : "0";
                    $_rate = isset($inv_items["$item->item_id"][1]) ? ($inv_items["$item->item_id"][1]) : "0";
                    $_amount = isset($inv_items["$item->item_id"][2]) ? ($inv_items["$item->item_id"][2]) : "0";

                    if ($_qty > 0) {
                        $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                        $pdf->Cell($widths[1], $h, $item->itm_code, 1, 0, $text_direction[1]);
                        $pdf->Cell($widths[2], $h, $item->itm_name, 1, 0, $text_direction[2]);
                        $pdf->Cell($widths[3], $h, is_zero($_qty), 1, 0, $text_direction[3]);
                        $pdf->Cell($widths[4], $h, is_zero($_rate), 1, 0, $text_direction[4]);
                        $pdf->Cell($widths[5], $h, is_zero($_amount), 1, 1, $text_direction[5]);
                    }
                }
            }

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function daily_purchasing_summary() {
        if ($this->ion_auth->logged_in()) {
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $sup = $this->input->get("sup");
            if (empty($s)) {
                $s = date("Y-m-d");
            }

            $this->load->model("grn_item_model");
            $this->load->model("wl_supplier_model");
            if ($s) {
                $supplier = $this->wl_supplier_model->get($s);
            }
            $items = $this->grn_item_model->get_daily_summary($s, $e, $sup, $this->branch);
            $grn_ids = array();
            foreach ($items as $grn_id) {
                if (!in_array($grn_id->grn_id, $grn_ids)) {
                    $grn_ids[] = $grn_id->grn_id;
                }
            }

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Date", "G R N No.", "Supplier", "Item Name", "Quantity", "Rate", "Total"];
            $widths = [25, 25, 80, 75, 25, 25, 25];
            $text_direction = ["L", "L", "L", "L", "C", "R", "R"];
            $pdf->AddPage();
            $pdf->SetFont('Times', 'B', 18);
            $pdf->Cell(0, 6, $this->branch->branch_name, "0", 1, "C");
            $pdf->SetFont('Consolas', '', 10);
            $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
            if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
            } else {
                $pdf->Cell(0, 1, "", "B", 1, "C");
            }
            $pdf->SetFont('', 'B', 11);
            $pdf->Cell(38, 6, "Purchasing Summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(242, 6, "Date : " . date("M d, Y ", strtotime($s)) . (isset($e) && !empty($e) ? " - " . $e : ""), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");
            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 0);
            if (isset($supplier)) {
                $pdf->Cell(141, 6, " Filter By : " . $supplier->company_name, 0, 1, "R");
            } else {
                $pdf->Cell(141, 6, "", 0, 1);
            }
            $pdf->Cell(25, 6, "G R N Count ");
            $pdf->Cell(114, 6, ": " . count($grn_ids), 0, 1);
            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont('', 'B', 10);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont('', '', 10);

            $i = 0;
            $h = 6;
            $total = 0;
            foreach ($items as $item) {
                $i++;
                $rate = doubleval($item->price);
                $qty = doubleval($item->qty);
                $_total = $rate * $qty;
                $pdf->Cell($widths[0], $h, $item->grn_date, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, decorate_code($item->gr_id, "grn", $this->prefixes), 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $item->company_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $item->itm_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $qty, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, is_zero($rate), 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, is_zero($_total), 1, 1, $text_direction[6]);
                $total += $_total;
            }

            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell(array_sum($widths) - $widths[6], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[6], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function purchasing_summary_supplier() {
        if ($this->ion_auth->logged_in()) {
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            if (empty($s)) {
                $s = date("Y-m-d");
            }

            $this->load->model("gr_note_model");
            $grns = $this->gr_note_model->get_daily_summary_supplier($s, $e, $this->branch);


            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Supplier", "G R N Count", "Total"];
            $widths = [ 120, 35, 35];
            $text_direction = ["L", "C", "R"];
            $pdf->AddPage();
            $pdf->SetFont('Times', 'B', 18);
            $pdf->Cell(0, 6, $this->branch->branch_name, "0", 1, "C");
            $pdf->SetFont('Consolas', '', 10);
            $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
            if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
            } else {
                $pdf->Cell(0, 1, "", "B", 1, "C");
            }
            $pdf->SetFont('', 'B', 11);
            $pdf->Cell(38, 6, "Purchasing Summary Supplier Vice");
            $pdf->SetFont('', '', '');
            $pdf->Cell(152, 6, "Date : " . date("M d, Y ", strtotime($s)) . (isset($e) && !empty($e) ? " - " . $e : ""), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");
            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);


            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont('', 'B', 10);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont('', '', 10);

            $i = 0;
            $h = 6;
            $total = 0;
            foreach ($grns as $grn) {
                $i++;
                $_total = doubleval($grn->sum_tot);
                $pdf->Cell($widths[0], $h, $grn->company_name, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $grn->grn_count, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, is_zero($grn->sum_tot), 1, 1, $text_direction[2]);
                $total += $_total;
            }

            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell(array_sum($widths) - $widths[2], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[2], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("grn_summary_supprier_vise.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function total_purchasing_summary_category_vice() {
        if ($this->ion_auth->logged_in()) {
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $c = $this->input->get("c");
            if (empty($s)) {
                $s = date("Y-m-d");
            }

            $this->load->model("grn_item_model");
            $this->load->model("wl_supplier_model");
            
            $this->load->model("item_category_model");
            $category = $this->item_category_model->get($c);
            if ($s) {
                $supplier = $this->wl_supplier_model->get($s);
            }
            $items = $this->grn_item_model->get_daily_summary_category_vice($s, $e, $c, $this->branch);
            $grn_ids = array();
            foreach ($items as $grn_id) {
                if (!in_array($grn_id->grn_id, $grn_ids)) {
                    $grn_ids[] = $grn_id->grn_id;
                }
            }

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Date", "G R N No.", "Supplier", "Item Name", "Quantity", "Rate", "Total"];
            $widths = [25, 25, 80, 75, 25, 25, 25];
            $text_direction = ["L", "L", "L", "L", "C", "R", "R"];
            $pdf->AddPage();
            $pdf->SetFont('Times', 'B', 18);
            $pdf->Cell(0, 6, $this->branch->branch_name, "0", 1, "C");
            $pdf->SetFont('Consolas', '', 10);
            $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
            if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
            } else {
                $pdf->Cell(0, 1, "", "B", 1, "C");
            }
            $pdf->SetFont('', 'B', 11);
            $pdf->Cell(38, 6, "Purchasing Summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(242, 6, "Date : " . date("M d, Y ", strtotime($s)) . (isset($e) && !empty($e) ? " - " . $e : ""), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");
            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 0);
            if (isset($category)) {
                $pdf->Cell(141, 6, " Filter By : " . $category->cat_name, 0, 1, "R");
            } else {
                $pdf->Cell(141, 6, "", 0, 1);
            }
            $pdf->Cell(25, 6, "G R N Count ");
            $pdf->Cell(114, 6, ": " . count($grn_ids), 0, 1);
            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont('', 'B', 10);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont('', '', 10);

            $i = 0;
            $h = 6;
            $total = 0;
            foreach ($items as $item) {
                $i++;
                $rate = doubleval($item->price);
                $qty = doubleval($item->qty);
                $_total = $rate * $qty;
                $pdf->Cell($widths[0], $h, $item->grn_date, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, decorate_code($item->grn_id, "grn", $this->prefixes), 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $item->company_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $item->itm_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $qty, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, is_zero($rate), 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, is_zero($_total), 1, 1, $text_direction[6]);
                $total += $_total;
            }

            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell(array_sum($widths) - $widths[6], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[6], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

}
