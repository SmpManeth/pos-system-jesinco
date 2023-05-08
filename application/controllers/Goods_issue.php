<?php

/**
 * Description of Goods_iuuse
 *
 * @author DP4
 * Sep 17, 2018 9:44:40 AM
 */
class Goods_issue extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->all();
    }

    public function all() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Recent Goods Issue Notes";
            $this->data["breadcrums"] = array(array("home", "home"), ("Goods Issue Note"));

            $this->load->model("invoice_model");
            $limit = 10;
            $offset = $this->input->get("p");
            $invoices = $this->invoice_model->get_invoices($this->branch, $limit, $offset);

            $this->data["invoices"] = $invoices;
            $this->load_view(array("goods-issue/all"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function new_issue() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "New Goods Issue Note";
            $this->data["breadcrums"] = array(array("home", "Home"), array("goods-issue", "Goods Issue Note"), "New");

            $this->load->model("branch_model");
            $branches = $this->branch_model->get_branches_without_this($this->branch, 1);

            $this->load->model("stock_model");
            $items = $this->stock_model->get_stock($this->branch);

            $this->data["branches"] = $branches;
            $this->data["items"] = $items;

            $this->load_view(array("goods-issue/new-issue"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function edit_issue() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "New Goods Issue Note";
            $this->data["breadcrums"] = array(array("home", "Home"), array("goods-issue", "Goods Issue Note"), "New");

            $this->load->model("branch_model");
            $branches = $this->branch_model->get_branches_without_this($this->branch, 1);

            $this->load->model("stock_model");
            $items = $this->stock_model->get_stock($this->branch);

            $this->data["branches"] = $branches;
            $this->data["items"] = $items;

            $this->load->model("gi_note_model", "gim");
            $this->load->model("gi_item_model", "giim");

            $code = $this->uri->segment(3);
            $gi_id = undecorate_code($code);

            $issue_note = $this->gim->get_note_by_id($gi_id, $this->branch);
            if (!empty($issue_note)) {
                if ($issue_note->status == "0") {
                    $items = $this->giim->get_items($issue_note->id, $this->branch);
                    $this->data["gi_note"] = $issue_note;
                    $this->data["gi_items"] = $items;
                    $this->data["doc_id"] = decorate_code($issue_note->gi_id, "gi", $this->prefixes);


//                Only for Select Supplier Form
                    $this->load->model("wl_supplier_model");
                    $this->load->model("branch_model");
                    $branch = $this->branch_model->get($issue_note->branch);
                    $suppliers = $this->wl_supplier_model->get_all_names_branch_active($branch);
                    $this->data["suppliers"] = $suppliers;
                    $this->load_view(array("goods-issue/edit-issue"));
                } else {
                    redirect("/goods-issue/view/$code");
                }
            } else {
                $this->load_view(array("nothing"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

    public function view() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "View Goods Issue Note";
            $this->data["breadcrums"] = array(array("home", "Home"), array("goods-issue", "Goods Issue Note"), "View");
            $_id = $this->uri->segment(3);
            $id = undecorate_code($_id);

            $this->load->model("gi_note_model", "gim");
            $this->load->model("gi_item_model", "giim");

            $issue_note = $this->gim->get_note($id, $this->branch);
            if (!empty($issue_note)) {
                $this->load->model("gi_return_item_model", "girm");
                $items = $this->giim->get_items($issue_note->id, $this->branch);
                $this->data["gi_note"] = $issue_note;
                $this->data["gi_items"] = $items;
                $this->data["doc_id"] = decorate_code($issue_note->gi_id, "gi", $this->prefixes);
                $this->data["returns"] = $this->girm->get_returns($issue_note->id);

                $this->load_view(array("goods-issue/view"));
            } else {
                $this->load_view(array("nothing"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

    #+--------------------------------------------------------------------------+
    #                                                                           |
    #                              AJAX Requests                                |
    #                                                                           |
    #+--------------------------------------------------------------------------+

    public function get_branch_suppliers() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $shop_id = $this->input->post("shop");
            if (!empty($shop_id) && $shop_id != "") {
                $this->load->model("wl_supplier_model");
                $this->load->model("branch_model");
                $branch = $this->branch_model->get($shop_id);
                $suppliers = $this->wl_supplier_model->get_all_names_branch_active($branch);

                $json["msg_type"] = "OK";
                $json["suppliers"] = $suppliers;
            } else {
                $json["msg_type"] = "ERR";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function new_gi_save() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("shop", "Shop", "trim|required");
            if ($this->form_validation->run()) {
//                $this->form_validation->set_rules("supplier", "Supplier", "trim|required");
//                if ($this->form_validation->run()) {
                $this->form_validation->set_rules("date", "Issue Date", "trim|required");
                if ($this->form_validation->run()) {
                    $this->form_validation->set_rules("item", "Item", "trim|required");
                    if ($this->form_validation->run()) {
                        $this->form_validation->set_rules("qty", "Quantity", "trim|required|greater_than[0]");
                        if ($this->form_validation->run()) {
                            $this->form_validation->set_rules("rate", "Rate", "trim|required|greater_than[0]");
                            if ($this->form_validation->run()) {
                                if (isset($this->prefixes["gi"]) && !empty($this->prefixes["gi"])) {

                                    $item = $this->input->post("item");
                                    $this->load->model("stock_model");
                                    $item_with_stock = $this->stock_model->get_stock_item($this->branch, $item);
                                    if (!empty($item_with_stock)) {
                                        $stock_qty = doubleval($item_with_stock->qty);
                                        $_qty = $this->input->post("qty");
                                        $qty = doubleval($_qty);
                                        if ($stock_qty >= $qty) {

                                            $this->db->trans_start();
                                            try {
                                                $this->load->model("gi_note_model", "gim");
                                                $this->load->model("gi_item_model", "giim");
                                                $gi_id = $this->input->post("id");
                                                if (!isset($gi_id) || empty($gi_id)) {
                                                    $gi_id = $this->gim->get_max_id($this->branch);
                                                }
                                                $id = $this->gim->save_note($gi_id, $this->branch, $this->user);
                                                $this->giim->save_item($id, $this->branch, $item_with_stock);
                                                $_foc = $this->input->post("foc");
                                                $foc = 0;
                                                if (!empty($_foc) && $_foc == "1") {
                                                    $foc = 1;
                                                }
                                                if ($foc == 1) {
                                                    
                                                } else {
                                                    $qty = $this->input->post("qty");
                                                    $rate = $this->input->post("rate");
                                                    $total = doubleval($qty) * doubleval($rate);
                                                    $this->gim->update_total($id, $total, 1);
                                                }
                                                $decor_id = decorate_code($gi_id, "gi", $this->prefixes);
                                                $url = base_url("goods-issue/edit_issue/" . $decor_id);
                                                try {
                                                    $post_id = $this->input->post("id");
                                                    if (empty($post_id)) {
                                                        $this->log_login("issue", "New Goods Issue Created : " . $decor_id);
                                                    }
                                                    $this->log_login("issue", "1 Item" . $foc . " Added to : " . $decor_id);
                                                } catch (Exception $exc) {
                                                    $json["msg_error"] = $exc->getTraceAsString();
                                                }
                                                $json["msg_type"] = "OK";
                                                $json["url"] = $url;
                                                $this->db->trans_complete();
                                            } catch (Exception $exc) {
                                               $this->db->trans_rollback();
                                                $json["msg_type"] = "ERR";
                                                $json["msg"] = $exc->getTraceAsString();
                                            }
                                        } else {
                                            $json["msg_type"] = "ERR";
                                            $json["msg"] = "Not Enough Quantity to Issue.<br/>" . number_format($stock_qty, 2) . " Available";
                                        }
                                    } else {
                                        $json["msg_type"] = "ERR";
                                        $json["msg"] = "Stock Item Not Found.";
                                    }
                                } else {
                                    $json["msg_type"] = "ERR";
                                    $json["msg"] = "Goods Issue Note Prefix is Empty.<br/>Please set it in Settings Panel.";
                                }
                            } else {
                                $json["msg_type"] = "ERR";
                                $json["msg"] = validation_errors();
                            }
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = validation_errors();
                        }
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = validation_errors();
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
//                } else {
//                    $json["msg_type"] = "ERR";
//                    $json["msg"] = validation_errors();
//                }
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

    public function finish() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $_id = $this->input->post("id");
            $this->load->model("gi_note_model", "gim");
            $this->load->model("gi_item_model", "giim");
            $id = undecorate_code($_id);
            $note = $this->gim->get($id);
            if (!empty($note)) {

                if ($note->status == "0") {
                    $items = $this->giim->get_items($note->id, $this->branch);
                    $this->load->model("stock_model");
                    $stock_ok = $this->stock_model->check_for_availablility($this->branch, $items);
                    if ($stock_ok[0]) {
                        $this->db->trans_start();
                        try {
                            $data = array(
                                "status" => 1,
                                "shop_id" => $this->input->post("shop"),
                                "issue_date" => $this->input->post("date"),
                                "issue_ref" => $this->input->post("ref"),
//                            "supplier" => $this->input->post("supplier"),
                            );
                            $this->gim->update($note->id, $data);
                            $this->stock_model->update_stock_bulk($this->branch, $items);
                            $this->log_login("issue", "Goods Issue Finished : " . decorate_code($note->gi_id, "gi", $this->prefixes));
                            $json["msg_type"] = "OK";
                            $json["url"] = base_url("goods-issue/view/" . decorate_code($note->gi_id, "gi", $this->prefixes));
  			 $this->db->trans_complete();
				
                        } catch (Exception $exc) {
                            $this->db->trans_rollback();
                            $json["msg_type"] = "ERR";
                            $json["msg"] = $exc->getTraceAsString();
                        }
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "Stock not Enough for " . $b[1]->itm_name;
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Goods Issue Note is already finished or Cancel.<br/>Please Reload.";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Goods Issue Note not found.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function get_issue_list() {
        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));

        $this->load->model("gi_note_model");
        $gi_count = $this->gi_note_model->get_all_invoices($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE);
        $gis = $this->gi_note_model->get_all_invoices($this->branch, $start, $length, $column, $direction);

        $dt_array = array();

        foreach ($gis as $gi) {
            $_d = array(
                "shop" => $gi->branch_name,
                "plain_inv_no" => $gi->gi_id,
                "gi_id" => decorate_code($gi->gi_id, "gi", $this->prefixes),
                "gi_date" => $gi->issue_date,
                "username" => $gi->username,
                "last_edit_at" => $gi->e_at,
                "total" => is_zero($gi->total),
                "status" => ($gi->status),
            );
            $dt_array[] = $_d;
        }
        $output = array(
            "total" => $gi_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

    public function remove_item() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model("gi_note_model", "gim");
            $this->load->model("gi_item_model", "giim");

            $id = $this->input->post("id");
            $item = $this->giim->get($id);
            if ($item) {
                $qty = doubleval($item->qty);
                $rate = doubleval($item->rate);
                $amount = $qty * $rate;
                $this->giim->delete($id);

                $this->gim->update_total($item->gi_id, $amount, -1);
                $json["msg_type"] = "OK";
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "No Item Found";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function save_discount() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");
            $discount = $this->input->post("discount");

            $this->load->model("gi_note_model", "gim");
            $note = $this->gim->get($id);

            $this->db->set("total", (doubleval($note->sub_total) - doubleval($discount)), FALSE);
            $this->db->set("discount", $discount);
            $this->db->where("id", $id);
            $this->db->update("gi_notes");
            $this->log_login("issue", "Goods Issue Discount Updated : " . $discount . " - " . decorate_code($note->gi_id, "gi", $this->prefixes));

            $json["msg_type"] = "OK";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function return_item() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $this->load->model("gi_note_model", "gim");
            $this->load->model("gi_item_model", "giim");

            $id = $this->input->post("id");
            $qty = doubleval($this->input->post("qty"));
            $item = $this->giim->get($id);
            if ($item) {
                $db_qty = doubleval($item->qty);
                $db_rate = doubleval($item->rate);
//                $amount = $db_qty * $db_rate;

                if ($db_qty >= $qty) {
                    $this->db->trans_start();
                    try {
                        $this->giim->update($id, array("qty" => ($db_qty - $qty)));
                        $deduct_amount = $qty * $db_rate;
                        $this->gim->update_total($item->gi_id, $deduct_amount, -1);

                        $this->load->model("stock_model");
                        $this->load->model("gi_return_item_model", "girm");

                        $this->girm->add_return($item, $qty);
                        $this->stock_model->update_stock($item->itm_id, $this->branch, $qty, 1, TRUE);

                        $json["msg_type"] = "OK";
 $this->db->trans_complete();

                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Return quantity exceeds the issued quantity";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "No Item Found";
            }

            $json["msg_type"] = "OK";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function cancel_return() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->load->model("gi_return_item_model", "girm");

            $cancel_item = $this->girm->get($id);
            if ($cancel_item->status == "1") {
                $this->db->trans_start();
                try {
                    $this->load->model("gi_note_model", "gim");
                    $this->load->model("gi_item_model", "giim");
                    $this->load->model("stock_model");

                    $gi_item = $this->giim->get_by("id", $cancel_item->gii_id);

                    $ret_qty = doubleval($cancel_item->qty);
                    $gi_qty = doubleval($gi_item->qty);
                    $gi_rate = doubleval($gi_item->rate);


                    $this->giim->update($gi_item->id, array("qty" => ($ret_qty + $gi_qty)));
                    $this->gim->update_total($gi_item->gi_id, (($ret_qty) * $gi_rate), 1);
                    $this->girm->update($id, array("status" => 2));
                    $this->stock_model->update_stock($gi_item->itm_id, $this->branch, $ret_qty, -1, TRUE);

                    $json["msg_type"] = "OK";
		 $this->db->trans_complete();
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg_type"] = "This item is already cancelled";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function cancel_issue_note() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->load->model("gi_note_model", "gim");
            $issue_note = $this->gim->get($id);
            if (!empty($issue_note)) {
                if ($issue_note->status == "1") {
                    $this->db->trans_start();
                    try {
                        $this->load->model("gi_item_model", "giim");
                        $this->gim->update($id, array("status" => 2));

                        $this->load->model("stock_model");
                        $items = $this->giim->get_items($issue_note->id, $this->branch);
                        foreach ($items as $item) {
                            $this->stock_model->update_stock($item->itm_id, $this->branch, $item->qty, 1, TRUE);
                        }

                        $this->log_login("issue", "Goods Issue Cancelled : " . decorate_code($issue_note->gi_id, "gi", $this->prefixes));
                        $json["msg_type"] = "OK";
                        $json["msg"] = "Issue Note Calcelled";
		 $this->db->trans_complete();
                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
                    }
                } else {
                    if ($issue_note->status == "2") {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "Issue Note is Already Cancelled.";
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "Issue Note is Not Finished.";
                    }
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Issue Note Found.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function print_note() {
        if ($this->ion_auth->logged_in()) {
            $code = $this->uri->segment(3);
            $id = undecorate_code($code);

            $this->load->model("gi_note_model");
            $grn = $this->gi_note_model->get_note($id, $this->branch);
            if ($grn) {

                $this->load->model("gi_item_model");
                $grn_items = $this->gi_item_model->get_items($grn->id, $this->branch);

                $this->load->library('F_pdf');
                $pdf = new My_pdf("L", "mm", "a5");
                $pdf->set_is_devided(FALSE);
//                $pdf->
                $pdf->AcceptPageBreak();
                $pdf->SetAutoPageBreak(true, 00);
                $pdf->set_footer(FALSE);

                $pdf->AddFont('Consolas', '', 'consola.php');
                $pdf->AddFont('Consolas', 'B', 'consolab.php');

                $columns = ["#", "Code", "Name", "Qty", "Rate", "Total"];
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
                $pdf->SetFont('', '', 11);
                $pdf->Cell(38, 6, "Goods Issue Note");
                $pdf->SetFont('', 'B', '');
                $pdf->Cell(30, 6, ": " . $code);
                $pdf->SetFont('', '', '');
                $pdf->Cell(122, 6, "Date : " . $grn->issue_date, "0", 1, "R");
                $pdf->SetFont('', '', 10);

                $pdf->Cell(38, 6, "Shop");
                $pdf->Cell(114, 6, ": " . $grn->branch_name, 0, 0);
                $pdf->SetFont('', '', 11);
//                $pdf->Cell(40, 6, "Deliver Date : " . $grn->p_date, 0, 1, "R");

                $pdf->Cell(10.5, 6, "REF");
                $pdf->Cell(22, 6, ": " . $grn->issue_ref, 0, 1);
                $pdf->SetFont('', '', 10);

                $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
                $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);

                $i = 0;
                $h = 6;
                $pdf->SetFont('', '', 9);
                $total = 0;
                $curret = 0;
                $foc = 0;
                foreach ($grn_items as $item) {
                    if ($item->foc == "0") {
                        $curret++;
                    }
                    if ($item->foc == "1") {
                        $foc++;
                    }
                }
                if ($curret > 0) {
                    $pdf->Cell(0, $h, "Current Items", 0, 1, "L");
                }

                foreach ($grn_items as $item) {
                    if ($item->foc == "0") {
                        $i++;
                        $qty = $item->qty;
                        $rate = $item->rate;
                        $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                        $pdf->Cell($widths[1], $h, $item->itm_code, 1, 0, $text_direction[1]);
                        $pdf->Cell($widths[2], $h, $item->itm_name, 1, 0, $text_direction[2]);
                        $pdf->Cell($widths[3], $h, $qty, 1, 0, $text_direction[3]);
                        $pdf->Cell($widths[4], $h, is_zero($rate), 1, 0, $text_direction[4]);
                        $pdf->Cell($widths[5], $h, is_zero($rate * $qty), 1, 1, $text_direction[5]);
                        $total += ($rate * $qty);
                    }
                }
                if ($foc > 0) {
                    $pdf->Cell(0, $h, "FOC Items", 0, 1, "L");
                }
                foreach ($grn_items as $item) {
                    if ($item->foc == "1") {
                        $i++;
                        $qty = $item->qty;
                        $rate = $item->rate;
                        $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                        $pdf->Cell($widths[1], $h, $item->itm_code, 1, 0, $text_direction[1]);
                        $pdf->Cell($widths[2], $h, $item->itm_name, 1, 0, $text_direction[2]);
                        $pdf->Cell($widths[3], $h, $qty, 1, 0, $text_direction[3]);
                        $pdf->Cell($widths[4], $h, is_zero($rate), 1, 0, $text_direction[4]);
                        $pdf->Cell($widths[5], $h, is_zero($rate * $qty), 1, 1, "L");
                        $total += ($rate * $qty);
                    }
                }
                $pdf->Cell(0, 2, "", 0, 1, "R");
                $pdf->Cell(array_sum($widths) - $widths[5], $h, "Total", 1, 0, "R");
                $pdf->Cell($widths[5], $h, is_zero($grn->total), 1, 0, "R");


//                $pdf->SetY(20);
                $pdf->Cell(0, 20, "", 0, 1, 0);
                $pdf->Cell(15, 4, "", 0, 0, 0);
                $pdf->Cell(40, 4, "....................", 0, 0, "C");
                $pdf->Cell(85, 4, "", 0, 0, 0);
                $pdf->Cell(40, 4, "....................", 0, 1, "C");

                $pdf->Cell(15, 4, "", 0, 0, 0);
                $pdf->Cell(40, 4, "Date", 0, 0, "C");
                $pdf->Cell(85, 4, "", 0, 0, 0);
                $pdf->Cell(40, 4, "Signature", 0, 1, "C");

                $pdf->Output("purchasing_order_" . $code . ".pdf", "I");
            } else {
                $this->load_view(array("nothing"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

}
