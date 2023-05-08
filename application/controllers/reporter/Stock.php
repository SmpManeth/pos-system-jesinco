<?php

/**
 * Description of Stock
 *
 * @author DP4
 * Aug 29, 2018 5:20:22 PM
 */
class Stock extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function stock_movements() {
        if ($this->ion_auth->logged_in()) {
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");

            $this->load->model("item_model");
            $this->load->model("branch_model");
            $this->load->model("grn_item_model");
            $this->load->model("invoice_item_model");
            $this->load->model("damage_good_model", "dgm");

            $branch = $this->branch_model->get($b);

            $items = $this->item_model->get_items_by_branch($branch);
            $grn_item = $this->grn_item_model->get_item_sum_report($b,$s,$e,TRUE);
            $inv_item = $this->invoice_item_model->get_item_sum_report($b,$s,$e,TRUE);
            $return_to_home = $this->dgm->get_item_sum_report($b,$s,$e,TRUE);
            $ret_item = $this->invoice_item_model->get_return_item_sum_report($b,$s,$e,TRUE);

            // dump_exit($items);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Item Code", "Item Name","GRN","DO", "Transfer H.O", "Returns"];
            $widths = [25, 62, 25,25, 25, 25];
            $text_direction = ["L", "L", "R","R", "R", "R"];
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

            $pdf->Cell(45, 6, "Stock adjustment Report", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . (!empty($s)? (date("M d, Y", strtotime($s))) : " From ". date("M d, Y"))) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 9);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;

            foreach ($items as $item) {

                
                $pdf->Cell($widths[0], $h, $item->itm_code, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $item->itm_name, 1, 0, $text_direction[1]);

                $grn_qty = isset($grn_item[$item->id])?$grn_item[$item->id]->sold :"-";
                $do_qty = isset($inv_item[$item->id])?intval($inv_item[$item->id]->sold) :"-";
                $tr_qty = isset($return_to_home[$item->id])?$return_to_home[$item->id]->sold :"-";
                $ret_qty = isset($ret_item[$item->id])?$ret_item[$item->id]->sold :"-";


                $pdf->Cell($widths[2], $h, $grn_qty, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $do_qty, 1, 0, $text_direction[3]);
                
                $pdf->Cell($widths[4], $h,$tr_qty, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $ret_qty, 1, 1, $text_direction[5]);

            }

            $pdf->Ln(2);

            $pdf->add_signature();
            $pdf->Output("stock-movement-summary.pdf", "I");

        } else {
            redirect(base_url("login"));
        }
    }
    public function current() {
        if ($this->ion_auth->logged_in()) {

            $this->load->model("stock_model");
            $b = $this->input->get("b");
            $stocks = $this->stock_model->get_stock_list_report($b);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["#","Item code", "Item Name", "Current stock", "Total cost", "Total selling"];
            $widths = [15, 20, 65, 27, 25,28];
            $text_direction = ["L", "L", "L", "R", "R", "R"];
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
            $pdf->Cell(38, 6, "Current Stock Report");
            $pdf->SetFont('', '', '');
            $pdf->Cell(155, 6, "Date : " . date("M d, Y "), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);

            $i = 0;
            $h = 6;
            $total = 0;
            $total_stock =0;
            $total_cost =0;
            foreach ($stocks as $item) {
                $i++;
                if (!empty($item->itm_code)) {
// "#","Item code", "Item Name", "Current stock", "Total cost", "Total selling"
                    $rate = doubleval($item->cost);
                    $qty = doubleval($item->qty);
                    $_total = doubleval($item->selling) * $qty;
                    $_cost = doubleval($item->cost) * $qty;
//                    $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                    $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                    $pdf->Cell($widths[1], $h, $item->itm_code, 1, 0, $text_direction[1]);
                    $pdf->Cell($widths[2], $h, $item->itm_name, 1, 0, $text_direction[2]);
                    $pdf->Cell($widths[3], $h, $qty, 1, 0, $text_direction[3]);
                    $pdf->Cell($widths[4], $h, is_zero($_cost), 1, 0, $text_direction[4]);
                    $pdf->Cell($widths[5], $h, is_zero($_total), 1, 1, $text_direction[5]);
                    $total += $_total;
                    $total_cost += $_cost;
                    $total_stock += $qty;
                }
            }

            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell(array_sum($widths) - 80, $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[3], $h, is_zero($total_stock), 1, 0, "R");
            $pdf->Cell($widths[4], $h, is_zero($total_cost), 1, 0, "R");
            $pdf->Cell($widths[5], $h, is_zero($total), 1, 1, "R");

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function item_sales_summary() {
        if ($this->ion_auth->logged_in()) {

            $this->output->enable_profiler();

            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $item_id = $this->input->get("i");
            if (!empty($s) || !empty($e)) {
                $this->load->model("item_model");
                $this->load->model("stock_model");
                $this->load->model("invoice_item_model", "iim");
                $this->load->model("grn_item_model", "grim");
                $this->load->model("supplier_return_item_model", "srim");
                $this->load->model("gi_item_model", "giim");
                $this->load->model("common_model");
                $this->load->model("inbound_item_model", "ibim");

                $item = $this->stock_model->get_stock_item($this->branch, $item_id);

                $invoice_items = $this->iim->get_history_new($this->branch, $item_id, $s, $e);
                $grn_items = $this->grim->get_history_new($this->branch, $item_id, $s, $e);
                $sr_items = $this->srim->get_history_new($this->branch, $item_id, $s, $e);
                $inbound_items = $this->ibim->get_history_new($this->branch, $item_id, $s, $e);
                $adjust_items = $this->common_model->get_history_adjust_new($this->branch, $item_id, $s, $e);
                $issued_items = $this->giim->get_history_new($this->branch, $item_id, $s, $e);

//                $invoice_items =$grn_items = array(array(),0);
//                dump($item);
//                dump($adjust_items);
                $_sorted_data = array_merge_recursive($grn_items[0], $invoice_items[0], $inbound_items[0], $sr_items[0], $adjust_items[0], $issued_items[0]);
                ksort($_sorted_data);

                //                dump($_sorted_data);
                $this->load->library('F_pdf');
                $pdf = new My_pdf("P", "mm", "a4");
                $pdf->set_is_devided(FALSE);
                $pdf->AcceptPageBreak();
                $pdf->SetAutoPageBreak(true, 00);
                $pdf->set_footer(FALSE);

                $pdf->AddFont('Consolas', '', 'consola.php');
                $pdf->AddFont('Consolas', 'B', 'consolab.php');

                $columns = ["Date", "Type", "Reference", "Qty", "Balance"];
                $widths = [35, 60, 35, 30, 30];
                $text_direction = ["L", "L", "L", "C", "R"];
                $pdf->AddPage();
                $pdf->SetFont('Times', 'B', 18);
                $pdf->Cell(0, 6, !empty($this->branch->branch_name_report) ? $this->branch->branch_name_report : $this->branch->branch_name, "0", 1, "C");
                $pdf->SetFont('Consolas', '', 10);
                $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
                if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                    $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
                } else {
                    $pdf->Cell(0, 1, "", "B", 1, "C");
                }
                $pdf->SetFont('', 'B', 11);
                $pdf->Cell(38, 6, "Items History Report");
                $pdf->SetFont('', '', '');
                $pdf->Cell(155, 6, "Date : " . date("M d, Y "), "0", 1, "R");
                $pdf->SetFont('', '', 10);
                $pdf->Cell(25, 6, "Items");
                $pdf->SetFont('', 'B', 10);
                $pdf->Cell(25, 6, ": " . $item->itm_name, 0, 1);
                $pdf->SetFont('', '', 10);
                $pdf->Cell(25, 6, "Generate By");
                $pdf->SetFont('', 'B', 10);
                $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
                $pdf->SetFont('', '', 10);

                $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
                $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);

                $i = 0;
                $h = 6;
                $total = 0;
                $p_qty = 0;
                $s_qty = 0;

                $current_stock = doubleval($item->qty);
//                $current_stock = 0;
                $final_sale_qty = $inbound_items[1] + $invoice_items[1] + $sr_items[1] + $issued_items[1] - ($adjust_items[1] + $grn_items[1]);
//                dump($current_stock);
//                dump($final_sale_qty);
                $starting_stock = $current_stock + $final_sale_qty;

                $pdf->Cell($widths[0], $h, $s, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, "Starting Stock", 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, "", 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $starting_stock, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $starting_stock, 1, 1, $text_direction[4]);
                foreach ($_sorted_data as $date => $rows) {
                    foreach ($rows as $row) {
                        $pdf->Cell($widths[0], $h, $date, 1, 0, $text_direction[0]);
                        $qty = doubleval($row['qty']);
                        $status = 0;
                        switch ($row['type']) {
                            case "inv":
                                $pdf->Cell($widths[1], $h, "Invoice", 1, 0, $text_direction[1]);
                                $pdf->Cell($widths[2], $h, decorate_code($row['doc_number'], "invoice", $this->prefixes), 1, 0, $text_direction[2]);
                                $starting_stock -= $qty;
                                $s_qty +=$qty;
                                $status = 0;
                                break;
                            case "inb":
                                $pdf->Cell($widths[1], $h, "Inbound", 1, 0, $text_direction[1]);
                                $pdf->Cell($widths[2], $h, "#" . $row['doc_number'], 1, 0, $text_direction[2]);
                                $starting_stock -= $qty;
                                $s_qty +=$qty;
                                $status = 0;
                                break;
                            case "gi":
                                $pdf->Cell($widths[1], $h, "Goods Issue", 1, 0, $text_direction[1]);
                                $pdf->Cell($widths[2], $h, decorate_code($row['doc_number'], "invoice", $this->prefixes), 1, 0, $text_direction[2]);
                                $starting_stock -= $qty;
                                $s_qty +=$qty;
                                $status = 0;
                                break;
                            case "grn":
                                $pdf->Cell($widths[1], $h, "G R N", 1, 0, $text_direction[1]);
                                $pdf->Cell($widths[2], $h, decorate_code($row['doc_number'], "grn", $this->prefixes), 1, 0, $text_direction[2]);
                                $starting_stock += $qty;
                                $p_qty += $qty;
                                $status = 1;
                                break;
                            case "sup_ret":
                                $pdf->Cell($widths[1], $h, "Sup. Return", 1, 0, $text_direction[1]);
                                $pdf->Cell($widths[2], $h, decorate_code($row['doc_number'], "supreturn", $this->prefixes), 1, 0, $text_direction[2]);
                                $starting_stock -= $qty;
                                $status = 0;
                                break;
                            case "adjust":
                                $pdf->Cell($widths[1], $h, "Stock Adjustment", 1, 0, $text_direction[1]);
                                $pdf->Cell($widths[2], $h, $row['doc_number'], 1, 0, $text_direction[2]);
                                if ($row['doc_number'] == "Positive") {
                                    $starting_stock += $qty;
                                    $p_qty += $qty;
                                    $status = 1;
                                } else {
                                    $starting_stock -= $qty;
                                    $status = 0;
                                }
                                break;
                            default:
//                                $pdf->Cell($widths[1], $h, "Other", 1, 0, $text_direction[1]);
//                                $starting_stock -= $qty;
//                                $pdf->Cell($widths[2], $h, $row['doc_number'], 1, 0, $text_direction[2]);
                                break;
                        }
                        $pdf->Cell($widths[3], $h, $qty, 1, 0, $text_direction[3]);
                        if ($status == 1) {
                            $pdf->SetFont("", "B");
                        }
                        $pdf->Cell($widths[4], $h, number_format($starting_stock, 3), 1, 1, $text_direction[4]);
                        $pdf->SetFont("", "");
//                        $pdf->Cell($widths[5], $h, is_zero($total), 1, 1, $text_direction[5]);
                    }
                }

                $pdf->add_signature();
                $pdf->Output("current_stock.pdf", "I");
            } else {
                $this->data["message"] = "No Date Selected.";
                $this->data["link"] = base_url("reporter/reports/stock");
                $this->load_view(array("nothing"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

    public function stock_bin_card() {
        if ($this->ion_auth->logged_in()) {
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            if (!empty($s) || !empty($e)) {

                $this->load->model("stock_model");
                $this->load->model("grn_item_model");
                $this->load->model("invoice_item_model");
                $this->load->model("gi_item_model");
                $this->load->model("common_model");

                $items = $this->stock_model->get_stock($this->branch);

                $grn_items = $this->grn_item_model->get_history($this->branch, FALSE, $s, $e);
                $invoice_items = $this->invoice_item_model->get_history($this->branch, FALSE, $s, $e);
                $gi_items = $this->gi_item_model->get_history($this->branch, FALSE, $s, $e);
                $adustments = $this->common_model->get_history_adjust($this->branch, FALSE, $s, $e);

                $this->load->library('F_pdf');
                $pdf = new My_pdf("P", "mm", "a4");
                $pdf->set_is_devided(FALSE);
                $pdf->AcceptPageBreak();
                $pdf->SetAutoPageBreak(true, 00);
                $pdf->set_footer(FALSE);

                $pdf->AddFont('Consolas', '', 'consola.php');
                $pdf->AddFont('Consolas', 'B', 'consolab.php');

                $columns = ["Item Code", "Item Name", "Stating Qty", "Purchasing", "Sale", "Balance"];
                $widths = [30, 60, 25, 25, 25, 25];
                $text_direction = ["L", "L", "C", "C", "C", "C"];
                $pdf->AddPage();
                $pdf->SetFont('Times', 'B', 18);
                $pdf->Cell(0, 6, !empty($this->branch->branch_name_report) ? $this->branch->branch_name_report : $this->branch->branch_name, "0", 1, "C");
                $pdf->SetFont('Consolas', '', 10);
                $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
                if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                    $pdf->Cell(0, 3, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
                } else {
                    $pdf->Cell(0, 1, "", "B", 1, "C");
                }
                $pdf->SetFont('', 'B', 11);
                $pdf->Cell(38, 6, "Stock Bin Cards");
                $pdf->SetFont('', '', '');
                $pdf->Cell(155, 6, "Date : " . date("M d, Y "), "0", 1, "R");
                $pdf->SetFont('', '', 10);
                $pdf->SetFont('', '', 10);
                $pdf->Cell(25, 6, "Generate By");
                $pdf->SetFont('', 'B', 10);
                $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
                $pdf->SetFont('', '', 10);

                $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
                $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);

                $h = 6;

                foreach ($items as $item) {
//                    dump($item);
                    $current_stck = doubleval($item->qty);
                    $gi_qty = 0;
                    $grn_qty = 0;
                    $inv_qty = 0;
                    $ad_p_qty = 0;
                    $ad_n_qty = 0;


                    if (isset($grn_items[0]["g-$item->itm_code"])) {
                        $_grn_items = $grn_items[0]["g-$item->itm_code"];
                        foreach ($_grn_items as $_g_item) {
                            $grn_qty += doubleval($_g_item['qty']);
                        }
                    }
                    if (isset($invoice_items[0]["i-$item->itm_code"])) {
                        $_invoice_items = $invoice_items[0]["i-$item->itm_code"];
                        foreach ($_invoice_items as $_i_item) {
                            $inv_qty += doubleval($_i_item['qty']);
                        }
                    }
                    if (isset($gi_items[0]["gi-$item->itm_code"])) {

                        $_gi_items = $gi_items[0]["gi-$item->itm_code"];
                        foreach ($_gi_items as $_gi_item) {
                            $gi_qty += doubleval($_gi_item['qty']);
                        }
                    }
                    if (isset($adustments[0]["ad-$item->itm_code"])) {
                        $_gi_items = $adustments[0]["ad-$item->itm_code"];
                        foreach ($_gi_items as $_ad_item) {
                            if ($_ad_item["doc_number"] == "Positive") {
                                $ad_p_qty += doubleval($_ad_item['qty']);
                            } else {
                                $ad_n_qty += doubleval($_ad_item['qty']);
                            }
                        }
                    }

                    $starting_stock = doubleval($current_stck) + ($inv_qty + $gi_qty + $ad_n_qty) - ($grn_qty + $ad_p_qty);

                    $pdf->Cell($widths[0], $h, $item->itm_code, 1, 0, $text_direction[0]);
                    $pdf->Cell($widths[1], $h, $item->itm_name, 1, 0, $text_direction[1]);
                    $pdf->Cell($widths[2], $h, ($starting_stock), 1, 0, $text_direction[2]);
                    $pdf->Cell($widths[3], $h, is_zero($grn_qty + $ad_p_qty), 1, 0, $text_direction[3]);
                    $pdf->Cell($widths[4], $h, is_zero($inv_qty + $gi_qty + $ad_n_qty), 1, 0, $text_direction[4]);
                    $pdf->Cell($widths[5], $h, is_zero($current_stck), 1, 1, $text_direction[5]);
                }

                $pdf->add_signature();
                $pdf->Output("stock_bin_card.pdf", "I");
            } else {
                $this->data["message"] = "No Date Selected.";
                $this->data["link"] = base_url("reporter/reports/stock");
                $this->load_view(array("nothing"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

    public function download_all_items() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if ($this->ion_auth->logged_in()) {

            $this->load->model("stock_model");
            $items = $this->stock_model->get_all_items($this->branch);

            $this->load->library('Excel');
            $PHPExcel = new PHPExcel();
            PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            $PHPExcel->setActiveSheetIndex(0);
            $sheet = $PHPExcel->getActiveSheet();

            $styleArray = array(
                'font' => array(
                    'bold' => true
            ));


            $columns = array("", "Item Code", "Item Name", "Current Stock", "Current Cost", "Current Selling Price", "Whole Sale Price", "Minimum stock for Alarm","Added By","Added Time");

            $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
            $sheet->setCellValue("A1", $this->branch->branch_name);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
            $sheet->setCellValue("A2", "All Items List");
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
            $sheet->setCellValue("A3", "Date " . date("Y-m-d"));
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = 5;
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);


            for ($index = 0; $index < count($columns); $index++) {
                $sheet->setCellValue($this->get_Letter($index) . "$row", $columns[$index]);
            }
            $sheet->getStyle($this->get_Letter(1) . "$row:" . $this->get_Letter(count($columns)) . "$row")->applyFromArray($styleArray);

            $row++;
//            $currencyFormat = '_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)';
//            $tot_totalals = 0;

            foreach ($items as $item) {

                $sheet->setCellValue($this->get_Letter(1) . "" . $row, $item->itm_code);
                $sheet->getStyle($this->get_Letter(1) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->setCellValue($this->get_Letter(2) . "" . $row, $item->itm_name);
                $sheet->setCellValue($this->get_Letter(3) . "" . $row, $item->qty);
                $sheet->setCellValue($this->get_Letter(4) . "" . $row, $item->cost);
                $sheet->setCellValue($this->get_Letter(5) . "" . $row, $item->selling);
                $sheet->setCellValue($this->get_Letter(6) . "" . $row, $item->wholesale);

                $sheet->setCellValue($this->get_Letter(7) . "" . $row, $item->minimum_stock_warn);
                $sheet->setCellValue($this->get_Letter(8) . "" . $row, $item->username);
                $sheet->setCellValue($this->get_Letter(9) . "" . $row, $item->e_at);

                $row++;
            }
            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
//            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="stock-summary.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } else {
            redirect(base_url("login"));
        }
    }

    public function items_category_vice() {
        if ($this->ion_auth->logged_in()) {

            $c = $this->input->get("c");
            $sc = $this->input->get("sc");
            $this->load->model("stock_model");
            $this->load->model("item_category_model");
            $this->load->model("item_sub_category_model");
            $category = $this->item_category_model->get($c);
            if ($sc) {
                $s_category = $this->item_sub_category_model->get($sc);
            } else {
                $s_category = FALSE;
            }
            $stocks = $this->stock_model->get_stock_list_category_vice($this->branch, $category, $s_category);
            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Code", "Name", "Qty", "Rate", "Total"];
            $widths = [35, 80, 25, 25, 25];
            $text_direction = ["L", "L", "C", "R", "R"];
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
            $pdf->Cell(38, 6, "Stock Report");
            $pdf->SetFont('', '', '');
            $pdf->Cell(155, 6, "Date : " . date("M d, Y "), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(76, 6, ": " . strtoupper($this->user->username), 0, 0);
            if (isset($category)) {
                $pdf->Cell(91, 6, " Filter By : " . $category->cat_name, 0, 1, "R");
            } else {
                $pdf->Cell(141, 6, "", 0, 1);
            }
            
            if ($s_category) {
                $pdf->Cell(0, 6, " Filter By : " . $s_category->sub_name, 0, 1, "R");
            }
            
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
                    $rate = doubleval($item->cost);
                    $qty = doubleval($item->qty);
                    $_total = $rate * $qty;
//                    $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                    $pdf->Cell($widths[0], $h, $item->itm_code, 1, 0, $text_direction[0]);
                    $pdf->Cell($widths[1], $h, $item->itm_name, 1, 0, $text_direction[1]);
                    $pdf->Cell($widths[2], $h, $qty, 1, 0, $text_direction[2]);
                    $pdf->Cell($widths[3], $h, is_zero($rate), 1, 0, $text_direction[3]);
                    $pdf->Cell($widths[4], $h, is_zero($_total), 1, 1, $text_direction[4]);
                    $total += $_total;
                }
            }

            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell(array_sum($widths) - $widths[4], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[4], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function to_date() {
        if ($this->ion_auth->logged_in()) {

//            $this->output->enable_profiler();
            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $start = $this->input->get("s");
            $_e = date("Y-m-d");
            $e = date_plaus_days($_e, 1);
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);



            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Code", "Name", "Qty", "Rate", "Total"];
            $widths = [35, 80, 25, 25, 25];
            $text_direction = ["L", "L", "C", "R", "R"];
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
            $pdf->Cell(38, 6, "Stock Report - " . $start . " - " . $e);
            $pdf->SetFont('', '', '');
            $pdf->Cell(155, 6, "Date : " . date("M d, Y "), "0", 1, "R");
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

            $this->load->model("stock_model");

            $this->load->model("invoice_item_model", "iim");
            $this->load->model("grn_item_model", "grim");
            $this->load->model("supplier_return_item_model", "srim");
            $this->load->model("gi_item_model", "giim");
            $this->load->model("common_model");
            $this->load->model("inbound_item_model", "ibim");

            $stocks = $this->stock_model->get_stock_list($this->branch);

            foreach ($stocks as $_item) {

                $i++;
                if (!empty($_item->itm_code)) {
                    $invoice_items = $this->iim->get_history($this->branch, $_item->item_id, $start, $e, TRUE);
                    $grn_items = $this->grim->get_history($this->branch, $_item->item_id, $start, $e, TRUE);
                    $sr_items = $this->srim->get_history($this->branch, $_item->item_id, $start, $e, TRUE);
                    $inbound_items = $this->ibim->get_history($this->branch, $_item->item_id, $start, $e, TRUE);
                    $adjust_items = $this->common_model->get_history_adjust($this->branch, $_item->item_id, $start, $e, TRUE);
                    $issued_items = $this->giim->get_history($this->branch, $_item->item_id, $start, $e, TRUE);

                    $current_stock = doubleval($_item->qty);
                    $final_sale_qty = $inbound_items + $invoice_items + $sr_items + $issued_items - ($adjust_items + $grn_items);
                    $starting_stock = $current_stock + $final_sale_qty;

                    $rate = doubleval($_item->cost);
                    $qty = doubleval($starting_stock);
                    $_total = ($rate * $qty);
                    $pdf->Cell($widths[0], $h, $_item->itm_code, 1, 0, $text_direction[0]);
                    $pdf->Cell($widths[1], $h, $_item->itm_name, 1, 0, $text_direction[1]);
                    $pdf->Cell($widths[2], $h, $starting_stock, 1, 0, $text_direction[2]);
                    $pdf->Cell($widths[3], $h, ($rate), 1, 0, $text_direction[3]);
                    $pdf->Cell($widths[4], $h, is_zero($_total), 1, 1, $text_direction[4]);
                    $total += $_total;
                }
            }

            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell(array_sum($widths) - $widths[4], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[4], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("stock_to_" + str_replace("-", "_", $start) + ".pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

}
