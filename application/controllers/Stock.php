<?php

/**
 * Description of Stocks
 *
 * @author DP4
 * Jun 26, 2018 11:42:30 AM
 */
class Stock extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Item List";
            $this->data["breadcrums"] = array(array("home", "home"), ("Item"));
            $this->load_view(array("stock/all"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function adjutment() {
        if ($this->ion_auth->logged_in()) {
            $itm_id = $this->uri->segment(3);
            if (!empty($itm_id)) {
                $this->load->model("item_model");
                $this->load->model("stock_model");

                $item = $this->item_model->get_item($itm_id);
                $item_qty = $this->stock_model->get_stock_item($this->branch, $itm_id);
                if ($item) {
                    $this->data["breadcrums"] = array(array("home", "home"), array("stock/adjutment", "Adjustments"), ("Adjust"));
                    $this->data["head"] = "Adjust the Stock for <b>" . $item->itm_name . "</b>";
                    $this->load->model("common_model");
                    $adjustments = $this->common_model->get_adjustments($this->branch, $itm_id);
                    $this->data["item"] = $item;
                    $this->data["item_qty"] = $item_qty;
                    $this->data["adjustments"] = $adjustments;
                    $this->load_view(array("stock/adjust"));
                } else {
                    redirect(base_url("adjustmnet"));
                }
            } else {
                $this->data["breadcrums"] = array(array("home", "home"), ("Adjustments"));
                $this->data["head"] = "Stock Adjustments";
                $this->load->model("common_model");
                $this->load->model("stock_model");
                $items = $this->stock_model->get_stock($this->branch);
                $adjustments = $this->common_model->get_adjustments($this->branch);
                $this->data["adjustments"] = $adjustments;
                $this->data["items"] = $items;
                $this->load_view(array("stock/adjustments"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

    public function damaged() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Damaged Goods";
            $this->data["breadcrums"] = array(array("home", "Home"), array("repair", "Invoce"), "Item Repairs");
            $this->load_view(array("stock/damaged_goods"));
        } else {
            redirect("login");
        }
    }

    public function import() {
        if ($this->ion_auth->logged_in()) {
            $this->data["breadcrums"] = array(array("home", "home"), ("Adjustments"));
            $this->data["head"] = "Stock Import";
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->load->library("Excel");
                $status = "";
                $msg = "";
                $repeated = array();
                $file_element_name = 'userfile';
                if ($status != "error") {
                    $config['upload_path'] = 'public/excel';
                    $config['allowed_types'] = "*";
                    $config['max_size'] = 1024 * 2;
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    $data_file = FALSE;
                    $total_array = array();
                    $total_db_array = array();
                    if (!$this->upload->do_upload($file_element_name)) {
                        $status = 'ERR';
                        $msg = $this->upload->display_errors('', '');
                    } else {
                        $data_file = $this->upload->data();
                        $status = "OK";
                        $msg = "File successfully uploaded";

                        $reader = PHPExcel_IOFactory::createReader('Excel2007');
                        $reader->setReadDataOnly(true);
                        $excel = $reader->load($data_file['full_path']);

                        $sheet = $excel->setActiveSheetIndex(0);
                        $itm_count = 0;

                        foreach ($sheet->getRowIterator() as $r):
                            $row = $r->getRowIndex();
                            if ($row > 4) {
                                $cell_code = $sheet->getCell('A' . $row);
                                $cell_name = $sheet->getCell('B' . $row);
                                $cell_sup = $sheet->getCell('C' . $row);
                                $cell_qty = $sheet->getCell('D' . $row);
                                $cell_cost = $sheet->getCell('E' . $row);
                                $cell_retail = $sheet->getCell('F' . $row);
                                $cell_wholesale = $sheet->getCell('G' . $row);

                                $cell_piece_price = $sheet->getCell('H' . $row);
                                $cell_meter_price = $sheet->getCell('I' . $row);
                                $cell_min_qty_wholesale = $sheet->getCell('J' . $row);
                                $cell_min_qty_alerm = $sheet->getCell('K' . $row);


                                $code = trim($cell_code->getValue());
                                $name = trim($cell_name->getValue());
                                $sup = trim($cell_sup->getValue());
                                $qty = trim($cell_qty->getValue());
                                $cost = trim($cell_cost->getValue());
                                $retail = trim($cell_retail->getValue());
                                $wholesale = trim($cell_wholesale->getValue());
                                $min_qty_wholesale = trim($cell_min_qty_wholesale->getValue());
                                $meter_price = trim($cell_meter_price->getValue());
                                $piece_price = trim($cell_piece_price->getValue());
                                $min_qty_alerm = trim($cell_min_qty_alerm->getValue());
                                $total_array[] = array(
                                    "code" => $code,
                                    "name" => $name,
                                    "sup" => $sup,
                                    "min_qty_wh" => $min_qty_wholesale,
                                    "qty" => $qty,
                                    "cost" => $cost,
                                    "retail" => $retail,
                                    "whole" => $wholesale,
                                    "sec_price" => isset($meter_price) && !empty($meter_price) ? $meter_price : "",
                                    "sec_unit_name" => isset($meter_price) && !empty($meter_price) ? "METER" : "",
                                    "third_price" => isset($piece_price) && !empty($piece_price) ? $piece_price : "",
                                    "third_unit_name" => isset($piece_price) && !empty($piece_price) ? "BAG" : "",
                                    "minimum_stock_warn" => isset($min_qty_alerm) ? $min_qty_alerm : 0,
                                );
                            }
                        endforeach;

                        $this->load->model("item_model");
                        $this->load->model("stock_model");


                        $this->load->model("wl_supplier_model");
                        $_suppliers = $this->wl_supplier_model->get_all_branch($this->branch);
                        $suppliers = array();
                        foreach ($_suppliers as $_sup) {
                            $suppliers[] = array("company_name" => $_sup->company_name, "id" => $_sup->id);
                        }

                        if (count($total_array) > 0) {
                            foreach ($total_array as $row) {
                                if (!empty($row["code"])) {
                                    $item = $this->item_model->get_iem_by_code($row["code"], $this->branch);
                                    if (empty($item)) {
                                        $sup_id = -1;
                                        if (!empty($row["sup"])) {
                                            $sup_exists = $this->sup_exists($suppliers, $row["sup"]);
                                            if ($sup_exists) {
                                                $sup_id = $sup_exists;
                                            } else {
                                                $data_sup = array('company_name' => $row["sup"]);
                                                $sup_id = $this->wl_supplier_model->insert_sup($data_sup);
                                                $sup = array("company_name" => $row["sup"], "id" => $sup_id);
                                                $suppliers[] = $sup;
                                            }
                                        }
                                        $data = array(
                                            "itm_code" => $row["code"],
                                            "itm_cat" => 1,
                                            "itm_name" => $row["name"],
                                            "sub_cat" => -1,
                                            "itm_description" => "",
                                            "bar_code" => "",
                                            "stock_type" => 1,
                                            "u_o_m" => "",
                                            "cost" => $row["cost"],
                                            "rate" => $row["cost"],
                                            "cost_discount" => 0,
                                            "wholesale" => $row["whole"],
                                            "selling" => $row["retail"],
                                            "min_selling" => $row["retail"],
                                            "min_qty_wh" => $row["min_qty_wh"],
                                            "sec_price" => $row["sec_price"],
                                            "sec_unit_name" => $row["sec_unit_name"],
                                            "third_price" => $row["third_price"],
                                            "third_unit_name" => $row["third_unit_name"],
                                            "minimum_stock_warn" => $row["minimum_stock_warn"],
                                            "discount" => 0,
                                            "dis_type" => "p",
                                            "status" => 1,
                                            "visibility" => 1,
                                            "branch" => $this->branch->id,
                                            "e_by" => $this->user->id,
                                            "e_at" => date("Y-m-d H:i:s"),
                                        );
                                        $id = $this->item_model->insert($data);
                                        $this->stock_model->update_stock($id, $this->branch, $row["qty"], 1);
                                        $data["qty"] = $row["qty"];
                                        $total_db_array[] = $data;
                                        $itm_count++;
                                    } else {
                                        $repeated[] = $row;
                                    }
                                }
                            }
                        }
                    }

                    $this->data["status"] = $status;
                    $this->data["msg"] = $msg;
                    $this->data["total_array"] = $total_array;
                    $this->data["total_db_array"] = $total_db_array;
                    $this->data["repeated"] = $repeated;
                    @unlink($_FILES[$file_element_name]);
                }
            }

            $this->load_view(array("stock/import"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function import_prices() {
        if ($this->ion_auth->logged_in()) {
            $this->data["breadcrums"] = array(array("home", "home"), ("Adjustments"));
            $this->data["head"] = "Stock Import Prices";
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->load->library("Excel");
                $status = "";
                $msg = "";
                $repeated = array();
                $file_element_name = 'userfile';
                if ($status != "error") {
                    $config['upload_path'] = 'public/excel';
                    $config['allowed_types'] = "*";
                    $config['max_size'] = 1024 * 2;
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    $data_file = FALSE;
                    if (!$this->upload->do_upload($file_element_name)) {
                        $status = 'ERR';
                        $msg = $this->upload->display_errors('', '');
                    } else {
                        $data_file = $this->upload->data();
                        $status = "OK";
                        $msg = "File successfully uploaded";

                        $reader = PHPExcel_IOFactory::createReader('Excel2007');
                        $reader->setReadDataOnly(true);
                        $excel = $reader->load($data_file['full_path']);

                        $sheet = $excel->setActiveSheetIndex(0);
                        $itm_count = 0;

                        $this->load->model("item_model");
                        $this->load->model("stock_model");
                        foreach ($sheet->getRowIterator() as $r):
                            $row = $r->getRowIndex();
                            if ($row > 4) {
                                $cell_code = $sheet->getCell('A' . $row);
                                $cell_name = $sheet->getCell('B' . $row);
                                $cell_sup = $sheet->getCell('C' . $row);
                                $cell_qty = $sheet->getCell('D' . $row);
                                $cell_cost = $sheet->getCell('E' . $row);
                                $cell_retail = $sheet->getCell('F' . $row);
                                $cell_wholesale = $sheet->getCell('G' . $row);

                                $cell_piece_price = $sheet->getCell('H' . $row);
                                $cell_meter_price = $sheet->getCell('I' . $row);
                                $cell_min_qty_wholesale = $sheet->getCell('J' . $row);
                                $cell_min_qty_alerm = $sheet->getCell('K' . $row);


                                $code = trim($cell_code->getValue());
                                $name = trim($cell_name->getValue());
                                $qty = trim($cell_qty->getValue());
                                $cost = trim($cell_cost->getValue());
                                $retail = trim($cell_retail->getValue());
                                $wholesale = trim($cell_wholesale->getValue());
                                $min_qty_wholesale = trim($cell_min_qty_wholesale->getValue());
                                $meter_price = trim($cell_meter_price->getValue());
                                $piece_price = trim($cell_piece_price->getValue());
                                $min_qty_alerm = trim($cell_min_qty_alerm->getValue());
                                $data = array(
                                    "min_qty_wh" => $min_qty_wholesale,
                                    "cost" => $cost,
                                    "selling" => $retail,
                                    "wholesale" => $wholesale,
                                    "sec_price" => isset($meter_price) && !empty($meter_price) ? $meter_price : "",
                                    "sec_unit_name" => isset($meter_price) && !empty($meter_price) ? "METER" : "",
                                    "third_price" => isset($piece_price) && !empty($piece_price) ? $piece_price : "",
                                    "third_unit_name" => isset($piece_price) && !empty($piece_price) ? "BAG" : "",
                                    "minimum_stock_warn" => isset($min_qty_alerm) ? $min_qty_alerm : 0,
                                );
                                $this->item_model->update_by("itm_code", $code, $data);

                                $item = $this->item_model->get_iem_by_code($code, $this->branch);
                                if ($item) {
                                    $this->stock_model->update_stock_single($item->id, $this->branch, $qty);
                                }
                                $itm_count++;
                            }
                        endforeach;
                    }

                    $this->data["status"] = $status;
                    $this->data["msg"] = $msg;
                    $this->data["updated"] = $itm_count;
                    if ($data_file) {
                        @unlink($data_file['full_path']);
                    }
                }
            }
            $this->load_view(array("stock/import_prices"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function print_sendtocompany() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("damage_good_model", "dgm");
            $_ids = $this->input->get("ids");
            $ids = explode(",", $_ids);


            $repairs = $this->dgm->get_selected($ids);
            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a5");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Item Code", "Item Name", "Receive Date"];
            $widths = [25, 80, 25];
            $text_direction = ["L", "L", "L"];
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

            $pdf->Cell(45, 6, "Damaged Goods Delivery Note", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(85, 6, "Date : " . date("M d, Y"), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(0, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $cahs = 0;
            $credit = 0;
            $balance = 0;
            foreach ($repairs as $repair) {
                $pdf->Cell($widths[0], $h, $repair->itm_code, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $repair->itm_name, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $repair->create_date, 1, 1, $text_direction[2]);
            }

            $pdf->Ln(2);
            $pdf->Cell(105, $h, "Total Items", 1, 0, "R");
            $pdf->Cell(25, $h, count($repairs), 1, 0, "R");

            $pdf->Cell(0, 20, "", 0, 1, 0);
            $pdf->Cell(95, 4, "", 0, 0, 0);
            $pdf->Cell(35, 4, "....................", 0, 1, "C");
            $pdf->Cell(95, 4, "", 0, 0, 0);
            $pdf->Cell(35, 4, "Manager", 0, 1, "C");

            $pdf->Output("delevery_note_" . date("Y_m_d") . ".pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

#+--------------------------------------------------------------------------+
#                                                                           |
#                              Helper Functions                             |
#                                                                           |
#+--------------------------------------------------------------------------+

    private function sup_exists($sups, $sup_name) {
        foreach ($sups as $sup) {
            if (isset($sup['company_name'])) {
                if ($sup['company_name'] == $sup_name) {
                    return $sup['id'];
                }
            }
        }
        return FALSE;
    }

#+--------------------------------------------------------------------------+
#                                                                           |
#                              AJAX Requests                                |
#                                                                           |
#+--------------------------------------------------------------------------+

    public function adjust_item() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("qty", "Adjusting Quantity", "trim|required|greater_than[0]");
            if ($this->form_validation->run()) {
                $id = $this->input->post("id");
                $qty = $this->input->post("qty");
                $remark = $this->input->post("remark");
                $direction = $this->input->post("direction");

                $this->load->model("stock_model");
                $stock = $this->stock_model->get_by("item_id", $id);
                $can_adjust = TRUE;
                $empty_record = FALSE;
                if ($direction == "-1") {
                    if (empty($stock) || doubleval($stock->qty) < doubleval($qty)) {
                        $can_adjust = FALSE;
                    }
                }

                if (!empty($stock)) {
                    $empty_record = TRUE;
                }
                if ($can_adjust) {
                    $b = $this->stock_model->update_stock($id, $this->branch, $qty, $direction, $empty_record);
                    if ($b) {
                        $this->load->model("common_model");
                        $this->common_model->adjust_the_stock($id, $this->branch, $direction, $qty, $remark, $this->user);
                        $json["msg_type"] = "OK";
                        $json["msg"] = "Adjustment Successfull.";
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "Adjustment Error!";
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = " Error! <br/>Decreasing Quantity Exceeds the Current Stock Quantity.";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function get_stock() {
        $output = array();
        if ($this->ion_auth->logged_in()) {
            $start = intval($this->input->get("offset"));
            $length = intval($this->input->get("limit"));
            $direction = ($this->input->get("order"));
            $column = ($this->input->get("sort"));
            $search = ($this->input->get("search"));

            $this->load->model("stock_model");
            $items_all = $this->stock_model->get_stock($this->branch, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE);
            $items = $this->stock_model->get_stock($this->branch, $start, $length, $column, $direction, $search);
            $this->data["items"] = $items;

            $output = array(
                "total" => ($items_all),
                "rows" => $items
            );
        } else {
            $output = array(
                "total" => 0,
                "rows" => array()
            );
        }
        echo json_encode($output);
    }

//    Admin Purpose Only (Do not Give access to this functions to Users)
    public function manual_adjust() {
        if ($this->ion_auth->logged_in()) {
            if ($this->is_admin()) {
                $this->data["head"] = "Superadmin Adjusting";
                $this->data["breadcrums"] = array(array("home", "Home"), array("Stocks", "stock"), "Stock Adjustment");
                $this->load->model("item_model");
                $items = $this->item_model->get_items_by_branch($this->branch);

                $this->data["items"] = $items;
                $this->load_view(array("stock/superadmin_adjust"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

    public function get_starting_stock_super() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $e = date("Y-m-d");
            $s = date_plaus_days($e, 365, "-");
            $id = $this->input->post("id");

            $this->load->model("item_model");
            $this->load->model("stock_model");
            $this->load->model("invoice_item_model", "iim");
            $this->load->model("grn_item_model", "grim");
            $this->load->model("supplier_return_item_model", "srim");
            $this->load->model("gi_item_model", "giim");
            $this->load->model("common_model");
            $this->load->model("inbound_item_model", "ibim");

            $item = $this->stock_model->get_stock_item($this->branch, $id);

            $invoice_items = $this->iim->get_history($this->branch, $id, $s, $e);
            $inbound_items = $this->ibim->get_history($this->branch, $id, $s, $e);
            $grn_items = $this->grim->get_history($this->branch, $id, $s, $e);
            $sr_items = $this->srim->get_history($this->branch, $id, $s, $e);
            $adjust_items = $this->common_model->get_history_adjust($this->branch, $id, $s, $e);
            $issued_items = $this->giim->get_history($this->branch, $id, $s, $e);

            $_sorted_data = array_merge_recursive($grn_items[0], $inbound_items[0], $invoice_items[0], $sr_items[0], $adjust_items[0], $issued_items[0]);
            ksort($_sorted_data);
//            dump($_sorted_data);
            $current_stock = doubleval($item->qty);
            $final_sale_qty = $inbound_items[1] + $invoice_items[1] + $sr_items[1] - $adjust_items[1] + $issued_items[1] - $grn_items[1];
            $starting_stock = $current_stock + $final_sale_qty;



            $json["msg_type"] = "OK";
            $json["starting_stock"] = $starting_stock;
            $json["current_stock"] = $current_stock;
            $json["e"] = $e;
            $json["s"] = $s;
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    private function get_starting_stock_super_rectify($id) {
        if ($this->ion_auth->logged_in()) {

            $e = date("Y-m-d");
            $s = date_plaus_days($e, 365, "-");


            $this->load->model("item_model");
            $this->load->model("stock_model");
            $this->load->model("invoice_item_model", "iim");
            $this->load->model("grn_item_model", "grim");
            $this->load->model("supplier_return_item_model", "srim");
            $this->load->model("gi_item_model", "giim");
            $this->load->model("common_model");
            $this->load->model("inbound_item_model", "ibim");


            $item = $this->stock_model->get_stock_item($this->branch, $id);

            $invoice_items = $this->iim->get_history_new($this->branch, $id, $s, $e);
            $inbound_items = $this->ibim->get_history_new($this->branch, $id, $s, $e);
            $grn_items = $this->grim->get_history_new($this->branch, $id, $s, $e);
            $sr_items = $this->srim->get_history_new($this->branch, $id, $s, $e);
            $adjust_items = $this->common_model->get_history_adjust_new($this->branch, $id, $s, $e);
            $issued_items = $this->giim->get_history_new($this->branch, $id, $s, $e);

            $_sorted_data = array_merge_recursive($grn_items[0], $inbound_items[0], $invoice_items[0], $sr_items[0], $adjust_items[0], $issued_items[0]);
            ksort($_sorted_data);
//            dump($_sorted_data);
            $current_stock = doubleval($item->qty);
            $final_sale_qty = $inbound_items[1] + $invoice_items[1] + $sr_items[1] - $adjust_items[1] + $issued_items[1] - $grn_items[1];
            $starting_stock = $current_stock + $final_sale_qty;

            return $starting_stock;
        } else {
            return -1;
        }
    }

    public function update_current_stock() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $amount = $this->input->post("amount");
            $starting = $this->input->post("starting");

            $qty = doubleval($amount) - doubleval($starting);
            $this->load->model("stock_model");
            $this->stock_model->update_stock($id, $this->branch, $qty, 1, TRUE);

            $json["msg_type"] = "OK";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function damaged_list() {
        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));

        $this->load->model("damage_good_model", "dgm");
        $repair_count = $this->dgm->get_all_damaged_items($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE);
        $repairs = $this->dgm->get_all_damaged_items($this->branch, $start, $length, $column, $direction);

        $dt_array = array();

        foreach ($repairs as $repair) {
            $_d = array(
                "id" => intval($repair->id),
                "itm_name" => $repair->itm_name,
                "itm_code" => $repair->itm_code,
                "branch_name" => $repair->branch_name,
                "create_date" => $repair->create_date,
                "remarks" => $repair->remarks,
                "username" => $repair->username,
                "status" => ($repair->status),
                "sent_date" => $repair->sent_date
            );
            $dt_array[] = $_d;
        }
        $output = array(
            "total" => $repair_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

    public function change_status() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $status = $this->input->post("status");

            $msgs = array(
                "Product mark as pending.",
                "Product sent to company for repair.",
                "Product mark as repairing.",
                "Product mark as Repair Done.",
                "Product Added to Stock.",
            );

            $this->load->model("damage_good_model", "dgm");

            if($status==1){
                $d= array("status" => $status,'sent_date'=>date("Y-m-d"));
            }else{
                $d= array("status" => $status);
            }

            $this->dgm->update($id, $d);
            $dg = $this->dgm->get($id);
            $this->load->model("item_model");
            $item = $this->item_model->get($dg->item_id);
            if ($status == "4") {
                $this->load->model("stock_model");
                $this->stock_model->update_stock_if_not_insert($dg->item_id, $this->branch, 1, 1);
                $this->log_login("repair", $msgs[$status] . " " . ($item->itm_name) . "[" . $item->itm_code . "] to this branch");
            } else {
                $this->log_login("repair", $msgs[$status] . " " . ($item->itm_name) . "[" . $item->itm_code . "]");
            }



            $json["msg_type"] = "OK";
            $json["msg"] = $msgs[$status];
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function load_transfer_form() {
        $id = $this->input->post("id");
        $this->load->model("item_model");
        $this->load->model("branch_model");
        $item = $this->item_model->get($id);
        $branches = $this->branch_model->get_all();
        $this->load->view('stock/transfer_form', array("item" => $item, 'branches' => $branches, 'branch' => $this->branch));
    }

    public function transfer() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $this->form_validation->set_rules("branch", "Branch", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("qty", "Transfer Quantity", "trim|required|numeric");
                if ($this->form_validation->run()) {
                    $this->load->model("stock_model");
                    $this->load->model("item_model");
                    $this->load->model("branch_model");
                    $itm_id = $this->input->post('itm_id');
                    $_branch = $this->input->post('branch');
                    $branch = $this->branch_model->get($_branch);
                    $qty = $this->input->post('qty');
                    $item = $this->item_model->get($itm_id);
                    $br = $this->branch_model->get($_branch);
                    
                    $this->stock_model->update_stock_if_not_insert($itm_id, $branch, $qty, 1);
                    $this->stock_model->update_stock($itm_id, $this->branch, $qty, -1, TRUE);
                    $this->log_login("item", "$qty of " . ($item->itm_name) . "[" . $item->itm_code . "] Transfered to ".$br->branch_name );
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Transfer Successfull.";
                } else {
                    
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

}
