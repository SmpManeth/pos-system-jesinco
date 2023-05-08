<?php

/**
 * Description of grn
 *
 * @author dilshan
 */
class Grn extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function new_grn() {
        if ($this->logged_in()) {
            $this->data["head"] = "New Goods Receive Note";
            $this->data["breadcrums"] = array(array("home", "Home"), array("grn", "Goods Receive Note"), "New");
            $this->load->model("item_model");
            $this->load->model("wl_supplier_model");
            $sups = $this->wl_supplier_model->get_all_branch_active($this->branch);
            $items = $this->item_model->get_all_items($this->branch);

            $this->data["items"] = $items;
            $this->data["sups"] = $sups;
            $this->load_view(array("grn/new"));
        } else {
            redirect(site_url("login"));
        }
    }

    public function index() {
        $this->all();
    }

    public function all() {
        if ($this->logged_in()) {
            $this->data["head"] = "Recent Goods Receive Notes";
            $this->data["breadcrums"] = array(array("home", "home"), ("Goods Receive Note"));
            $this->load->model("gr_note_model");
            $notes = $this->gr_note_model->get_notes($this->branch);
            $this->data["grns"] = $notes;
            $this->load_view(array("grn/all"));
        } else {
            redirect(site_url("login"));
        }
    }

    public function edit() {
        if ($this->logged_in()) {
            $_id = $this->uri->segment(3);
            $id = undecorate_code($_id);
            $this->load->model("gr_note_model");
            $this->load->model("grn_item_model");

            $order = $this->gr_note_model->get_grn($id, $this->branch);
            if (!empty($order)) {
                if ($order->status == "0") {
                    $p_items = $this->grn_item_model->get_items_with_details($order->id);
                    $this->data["grn"] = $order;
                    $this->data["g_items"] = $p_items;
                    $this->data["doc_id"] = decorate_code($order->gr_id, "grn", $this->prefixes);

                    $this->data["head"] = "Edit Goods Receive Note";
                    $this->data["breadcrums"] = array(array("home", "home"), array("grn", "Goods Receive Note"), "Edit");
                    $this->load->model("item_model");
                    $this->load->model("wl_supplier_model");
                    $sups = $this->wl_supplier_model->get_all_branch_active($this->branch);
                    $items = $this->item_model->get_all_items($this->branch);

                    $this->data["items"] = $items;
                    $this->data["sups"] = $sups;
                    $this->load_view(array("grn/edit"));
                } else {
                    redirect(site_url("grn/view/" . decorate_code($order->gr_id, "grn", $this->prefixes)));
                }
            } else {
                $this->load_view(array("nothing"));
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function view() {
        if ($this->logged_in()) {
            $_id = $this->uri->segment(3);
            $id = undecorate_code($_id);
            $this->load->model("gr_note_model");
            $this->load->model("grn_item_model");

            $order = $this->gr_note_model->get_grn($id, $this->branch);
            if (!empty($order)) {
                if ($order->status == "1" || $order->status == "2") {
                    $p_items = $this->grn_item_model->get_items_with_details($order->id);
                    $this->data["grn"] = $order;
                    $this->data["g_items"] = $p_items;
                    $this->data["doc_id"] = decorate_code($id, "grn", $this->prefixes);

                    $this->data["head"] = "View Goods Receive Note";
                    $this->data["breadcrums"] = array(array("home", "home"), array("grn", "Goods Receive Note"), "View");
                    $this->load->model("item_model");
                    $this->load->model("wl_supplier_model");
                    $sups = $this->wl_supplier_model->get_all_branch_active($this->branch);
                    $items = $this->item_model->get_all_items($this->branch);

                    $this->data["items"] = $items;
                    $this->data["sups"] = $sups;
                    $this->load_view(array("grn/view"));
                } else {
                    redirect(site_url("grn/edit/" . $id));
                }
            } else {
                $this->load_view(array("nothing"));
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function temp_entry() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Tempory Goods Receive Entry";
            $this->data["breadcrums"] = array(array("home", "home"), array("grn", "Goods Receive Note"), "Temp");

            $this->load->model("item_model");
            $this->load->model("temp_grn_item_model", "tgrn");

            $items = $this->item_model->get_all_items($this->branch);
            $this->data["items"] = $items;

            $temp_items = $this->tgrn->get_records($this->branch);
            $this->data["tgrn_items"] = $temp_items;

            $this->load_view(array("grn/temp_entry"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function returns() {
        if ($this->ion_auth->logged_in()) {
            $uri_seg = $this->uri->segment(3);

            if ($uri_seg == "new") {
                $this->data["head"] = "New Supplier Return";
                $this->data["breadcrums"] = array(array("home", "home"), array("grn", "Goods Receive Note"), array("grn/returns", "Returns"), "New");
                $this->load->model("wl_supplier_model");
                $sups = $this->wl_supplier_model->get_all_branch_active($this->branch);
                $this->data["sups"] = $sups;
                $this->load_view(array("grn/returns/new"));
            } else if ($uri_seg == "edit") {
                $code = $this->uri->segment(4);
                if ($code) {
                    $id = undecorate_code($code);
                    $this->load->model("supplier_return_model", "srm");
                    $ret_note = $this->srm->get_ret_note($id, $this->branch);
                    if ($ret_note) {
                        if ($ret_note->status == "0") {

                            $this->data["head"] = "Edit Supplier Return";
                            $this->data["breadcrums"] = array(array("home", "home"), array("grn", "Goods Receive Note"), array("grn/returns", "Returns"), "Edit");

                            $this->load->model("supplier_return_item_model", "srim");
                            $ret_items = $this->srim->get_items($id);

                            $this->data["doc_id"] = $code;
                            $this->data["ret_note"] = $ret_note;
                            $this->data["ret_items"] = $ret_items;

                            $this->load_view(array("grn/returns/edit"));
                        } else {
                            redirect("/grn/returns/view/$code");
                        }
                    } else {
                        redirect("/grn/returns/");
                    }
                } else {
                    redirect("/grn/returns");
                }
            } else if ($uri_seg == "view") {
                $code = $this->uri->segment(4);
                if ($code) {
                    $id = undecorate_code($code);
                    $this->load->model("supplier_return_model", "srm");
                    $ret_note = $this->srm->get_ret_note($id, $this->branch);
                    if ($ret_note) {
                        if ($ret_note->status == "0") {
                            redirect("/grn/returns/edit/$code");
                        } else {
                            $this->data["head"] = "View Supplier Return";
                            $this->data["breadcrums"] = array(array("home", "home"), array("grn", "Goods Receive Note"), array("grn/returns", "Returns"), "Edit");

                            $this->load->model("supplier_return_item_model", "srim");
                            $ret_items = $this->srim->get_items($id);

                            $this->data["doc_id"] = $code;
                            $this->data["ret_note"] = $ret_note;
                            $this->data["ret_items"] = $ret_items;

                            $this->load_view(array("grn/returns/view"));
                        }
                    } else {
                        redirect("/grn/returns/");
                    }
                } else {
                    redirect("/grn/returns");
                }
            } else if ($uri_seg == "print") {
                $code = $this->uri->segment(4);
                $this->print_ret_notw($code);
            } else {
                $this->data["head"] = "Supplier Returns";
                $this->data["breadcrums"] = array(array("home", "home"), array("grn", "Goods Receive Note"), "Returns");
                $this->load_view(array("grn/returns/all"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

    public function print_grn() {
        if ($this->ion_auth->logged_in()) {
            $code = $this->uri->segment(3);
            $id = undecorate_code($code);

            $this->load->model("gr_note_model");
            $grn = $this->gr_note_model->get_grn($id, $this->branch);
            if ($grn) {
                $this->load->model("grn_item_model");
                $grn_items = $this->grn_item_model->get_items_with_details($grn->id);

                $this->load->library('F_pdf');
                $pdf = new My_pdf("L", "mm", "a5");
                $pdf->set_is_devided(FALSE);
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
                $pdf->Cell(38, 6, "Goods Recieve Note");
                $pdf->SetFont('', 'B', '');
                $pdf->Cell(30, 6, ": " . $code);
                $pdf->SetFont('', '', '');
                $pdf->Cell(122, 6, "Date : " . $grn->grn_date, "0", 1, "R");
                $pdf->SetFont('', '', 10);

                $pdf->Cell(38, 6, "Supplier");
                $pdf->Cell(114, 6, ": " . $grn->company_name, 0, 0);
                $pdf->SetFont('', '', 11);
//                $pdf->Cell(40, 6, "Deliver Date : " . $grn->p_date, 0, 1, "R");

                $pdf->Cell(10.5, 6, "REF");
                $pdf->Cell(22, 6, ": " . $grn->po_ref, 0, 1);
                $pdf->SetFont('', '', 10);

                $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
                $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);

                $i = 0;
                $h = 6;
                $pdf->SetFont('', '', 9);
                $total = 0;
                $curret = 0;
                $pre = 0;
                $foc = 0;
                foreach ($grn_items as $item) {
                    if ($item->is_temp != "1" && $item->foc == "0") {
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
                    if ($item->is_temp != "1" && $item->foc == "0") {
                        $i++;
                        $qty = $item->qty;
                        $rate = $item->price;
                        $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                        $pdf->Cell($widths[1], $h, $item->itm_code, 1, 0, $text_direction[1]);
                        $pdf->Cell($widths[2], $h, $item->itm_name, 1, 0, $text_direction[2]);
                        $pdf->Cell($widths[3], $h, $qty, 1, 0, $text_direction[3]);
                        $pdf->Cell($widths[4], $h, number_format($rate, 3), 1, 0, $text_direction[4]);
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
                        $rate = $item->price;
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


//                $pdf->Cell(0, 20, "", 0, 1, "R");
                $pdf->SetY(-20);
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
//                redirect("grn/");
            }
        } else {
            redirect(base_url("login"));
        }
    }

    private function print_ret_notw($code) {
        if ($this->ion_auth->logged_in()) {
            $id = undecorate_code($code);
            $this->load->model("supplier_return_model", "srm");
            $ret_note = $this->srm->get_ret_note($id, $this->branch);

            if ($ret_note) {

                $this->load->model("supplier_return_item_model", "srim");
                $ret_items = $this->srim->get_items($id);

                $this->load->library('F_pdf');
                $pdf = new My_pdf("L", "mm", "a5");
                $pdf->set_is_devided(FALSE);
                $pdf->AcceptPageBreak();
                $pdf->SetAutoPageBreak(true, 00);
                $pdf->set_footer(FALSE);
//            $pdf->set_footer_text("Created By Weblankan");
                $pdf->AddFont('Consolas', '', 'consola.php');
                $pdf->AddFont('Consolas', 'B', 'consolab.php');

                $columns = ["#", "G R N", "Code", "Name", "Qty", "Rate", "Total"];
                $widths = [15, 20, 25, 70, 15, 20, 25];
                $text_direction = ["L", "L", "L", "L", "R", "R", "R"];
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
                $pdf->SetFont('', '', 12);
                $pdf->Cell(38, 6, "Return Note");
                $pdf->SetFont('', 'B', '');
                $pdf->Cell(30, 6, ": " . $code);
                $pdf->Cell(122, 6, "Date : " . $ret_note->ret_date, "0", 1, "R");
                $pdf->SetFont('', '', 11);

                $pdf->Cell(38, 6, "Supplier");
                $pdf->Cell(50, 6, ": " . $ret_note->company_name, 0, 1);
                $pdf->Cell(00, 6, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
                $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);

                $i = 0;
                $h = 6;
                $pdf->SetFont('Consolas', '', 9);
                foreach ($ret_items as $item) {
                    $i++;
                    $rate = doubleval($item->rate);
                    $qty = doubleval($item->qty);
                    $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                    $pdf->Cell($widths[1], $h, decorate_code($item->grn_id, "grn", $this->prefixes), 1, 0, $text_direction[1]);
                    $pdf->Cell($widths[2], $h, $item->itm_code, 1, 0, $text_direction[1]);
                    $pdf->Cell($widths[3], $h, $item->itm_name, 1, 0, $text_direction[3]);
                    $pdf->Cell($widths[4], $h, $qty, 1, 0, $text_direction[4]);
                    $pdf->Cell($widths[5], $h, is_zero($rate), 1, 0, $text_direction[5]);
                    $pdf->Cell($widths[6], $h, is_zero($rate * $qty), 1, 1, $text_direction[6]);
                }

                $pdf->Output("filenme.pdf", "I");
            } else {
                redirect("grn/returns");
            }
        } else {
            redirect(base_url("login"));
        }
    }

############################################################################

    public function get_serials() {
        if ($this->logged_in()) {
            $grn_id = $this->input->post("grn_id");
            $itm_id = $this->input->post("itm_id");
            $gi_id = $this->input->post("gi_id");
            $loc = $this->input->post("loc");
            $branch = $this->branch;

            $this->load->model("item_serial_model");
            $this->load->model("item_model");
            $serials = $this->item_serial_model->get_serials($itm_id, "grn_edt", $grn_id, $branch);
            $item = $this->item_model->get($itm_id);
            $data = array(
                "loc" => $loc,
                "serials" => $serials,
                "item" => $item,
                "grn_id" => $grn_id,
                "itm_id" => $itm_id,
                "gi_id" => $gi_id,
                "branch_id" => $branch->id
            );

            $this->load->view("common/serials", $data);
        } else {
            $this->load->view("nothing", array());
        }
    }

    public function add_serial() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("serial", "Serial Number", "trim|required|is_unique[item_serials.serial_no]");
            if ($this->form_validation->run()) {
                $this->load->model("item_serial_model");
                $itm_id = $this->input->post("item_id");
                $grn_id = $this->input->post("g_id");
                $data = array(
                    "itm_id" => $itm_id,
                    "branch" => $this->branch->id,
                    "serial_no" => $this->input->post("serial"),
                    "grn_id" => $grn_id,
                    "status" => 0,
                );
                $id = $this->item_serial_model->insert($data);

                $this->load->model("gr_note_model");
                $this->load->model("grn_item_model");
                $this->load->model("item_model");

                $item = $this->item_model->get($itm_id);
                $this->gr_note_model->update_total($grn_id, $item->cost, 1);
                $this->grn_item_model->update_item_qty($grn_id, $itm_id, 1, 1);

                $json["msg_type"] = "OK";
                $json["id"] = $id;
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function remove_serial() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("item_serial_model");
            $id = $this->input->post("id");
            $serial = $this->item_serial_model->get($id);
            if ($serial) {
                if ($serial->status == 0) {
                    $this->item_serial_model->delete($id);

                    $this->load->model("gr_note_model");
                    $this->load->model("grn_item_model");
                    $this->load->model("item_model");

                    $item = $this->item_model->get($serial->itm_id);
                    $this->gr_note_model->update_total($serial->grn_id, $item->cost, 2);
                    $this->grn_item_model->update_item_qty($serial->grn_id, $serial->itm_id, 1, 2);
                    $json["msg_type"] = "OK";
                    $json["id"] = $id;
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Can't Delete Serial.<br/>This Serial Related GRN has Marked as Finished or Canceled";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Can't Delete Serial.<br/>This Serial Not Found";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

############################################################################

    public function new_grn_save() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("supplier", "", "trim|required|callback_combo", array("combo" => "Please Selece a Supplier to Continue"));
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("grn_date", "GRN Date", "trim|required");
//                $this->form_validation->set_rules("po_ref", "Order Reference", "trim|required");
                if ($this->form_validation->run()) {
                    $this->form_validation->set_rules("item", "Item", "trim|required|callback_combo|greater_than[0]", array("callback_combo" => "Please Selece an Item to Continue"));
                    $this->form_validation->set_rules("rate", "Item Rate", "trim|required|greater_than[0]");
                    if ($this->form_validation->run()) {

                        $this->load->model("item_model");
                        $item = $this->item_model->get($this->input->post("item"));
                        if ($item->unique_type == "0") {
                            $this->form_validation->set_rules("qty", "Quantity", "trim|required|greater_than[0]");
                        } else {
                            $this->form_validation->set_rules("qty", "Quantity", "trim|required");
                        }
                        if ($this->form_validation->run()) {
                            $this->db->trans_start();
                            try {
                                $this->load->model("gr_note_model");
                                $this->load->model("grn_item_model");
                                $grn_id = $this->input->post("id");

                                $po_id = $this->gr_note_model->save_grn($this->branch, $this->user);
                                $foc = $this->input->post("foc");
                                if ($foc == "0") {
                                    $qty = $this->input->post("qty");
                                    $rate = $this->input->post("rate");
                                    $total = doubleval($qty) * doubleval($rate);
                                    $this->gr_note_model->update_total($po_id[0], $total, 1);
                                }

                                $poi_id = $this->grn_item_model->add_item($po_id[0]);

                                $json["p_id"] = $po_id[0];
                                $json["p_id_display"] = decorate_code($po_id[1], "grn", $this->prefixes);
                                $json["pi_id"] = $poi_id;
                                $json["is_uni"] = $item->unique_type;
                                $json["itm_id"] = $item->id;
                                $json["msg_type"] = "OK";
                                $json["msg"] = "Item Added Successfully";

                                $_foc = $this->input->post("foc");
                                $foc = isset($_foc) && $_foc == "1" ? " (FOC) " : "";
                                if (empty($grn_id)) {
                                    $this->log_login("grn", "New GRN Created : " . $json["p_id_display"]);
                                }
                                $this->log_login("grn", "1 Item" . $foc . " Added to : " . $json["p_id_display"]);

                                $this->db->trans_complete();
                            } catch (Exception $exc) {
                                $this->db->trans_rollback();
                                $json["msg_type"] = "ERR";
                                $json["msg"] = $exc->getTraceAsString();
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
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function cancel_grn() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("gr_note_model");
            $id = undecorate_code($this->input->post("id"));
            $po = $this->gr_note_model->get($id);
            if ($po) {
                if ($po->status == "0") {
                    $this->db->trans_start();
                    try {
                        $this->gr_note_model->update($id, array("status" => 2, "e_by" => $this->user->id, "e_at" => date("Y-m-d H:i:s")));
                        $json["msg_type"] = "OK";
                        $json["msg"] = "The G R N Cancelled Successfully";
                        $dec_id = decorate_code($po->gr_id, "grn", $this->prefixes);
                        $json["url"] = site_url("grn/view/" . $dec_id);
                        $this->log_login("grn", "GRN Cancelled : " . $dec_id);
                        $this->db->trans_complete();
                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    if ($po->status == "1") {
                        $json["msg"] = "Can't Cancel<br/>The G R N is Finished.";
                    }
                    if ($po->status == "2") {
                        $json["msg"] = "Can't Cancel<br/>The G R N is Already Cancelled.";
                    }
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Can't Cancel<br/>G R N not Found";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function finish_grn() {
        $json = array();
        if ($this->logged_in()) {

            $this->load->model("gr_note_model");
            $this->load->model("grn_item_model");
            $this->load->model("stock_model");
            $id = ($this->input->post("id"));
            $grn = $this->gr_note_model->get($id);
            if ($grn) {
                $items = $this->grn_item_model->get_items($grn->id);
                if (count($items) > 0) {
                    if ($grn->status == "0") {
                        $b = TRUE;
                        foreach ($items as $item) {
                            if (doubleval($item->qty) == 0) {
                                $b = FALSE;
                                break;
                            }
                        }
                        if ($b) {
                            $availbale_items = array();

                            $this->db->trans_start();
                            try {
                                if (count($items) > 0) {
                                    $this->stock_model->update_stock_batch($this->branch, $items);
                                }
                                $data = array(
                                    "grn_date" => $this->input->post("grn_date"),
                                    "po_ref" => $this->input->post("po_ref"),
                                    "supplier" => $this->input->post("supplier"),
                                    "del_location" => $this->input->post("del_location"),
                                    "status" => 1,
                                    "e_by" => $this->user->id,
                                    "e_at" => date("Y-m-d H:i:s")
                                );

                                $this->gr_note_model->update($id, $data);
                                if (count($availbale_items) > 0) {
                                    $this->tgrn->update_grn_id($availbale_items, $id);
                                }
                                $json["msg_type"] = "OK";
                                $json["msg"] = "The G R N Finished Successfully";
                                $gr_id = decorate_code($grn->gr_id, "grn", $this->prefixes);
                                $json["url"] = site_url("grn/view/" . $gr_id);
                                $this->log_login("grn", "GRN Finished : " . $gr_id);

                                $this->db->trans_complete();
                            } catch (Exception $exc) {
                                $this->db->trans_rollback();
                                $json["msg_type"] = "ERR";
                                $json["msg"] = $exc->getTraceAsString();
                            }
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = "Can't Proceed.<br/>Some Items contains <b>ZERO</b> Quantities.";
                        }
                    } else {
                        $json["msg_type"] = "ERR";
                        if ($grn->status == "1") {
                            $json["msg"] = "Can't Finish<br/>The G R N is Already Finished.";
                        }
                        if ($grn->status == "2") {
                            $json["msg"] = "Can't Finish<br/>The G R N is Already Cancelled.";
                        }
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Can't Cancel<br/>There are No items in the Purchasing Order.";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Can't Finish<br/>G R N not Found";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function pending_grn() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("gr_note_model");
            $id = undecorate_code($this->input->post("id"));
            $po = $this->gr_note_model->get($id);
            if ($po) {
                if ($po->status == "2") {
                    $this->db->trans_start();
                    try {
                        $this->gr_note_model->update($id, array("status" => 0, "e_by" => $this->user->id, "e_at" => date("Y-m-d H:i:s")));
                        $json["msg_type"] = "OK";
                        $json["msg"] = "The G R N Cancelled Successfully";
                        $json["url"] = site_url("grn/edit/" . $id);
                        $this->log_login("grn", "GRN Marked as Pending : " . decorate_code($id, "grn", $this->prefixes));

                        $this->db->trans_complete();
                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    if ($po->status == "1") {
                        $json["msg"] = "Can't Cancel<br/>The G R N is Already Finished.";
                    }
                    if ($po->status == "0") {
                        $json["msg"] = "Can't Cancel<br/>The G R N is Already Mark as Pending.";
                    }
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Can't Finish<br/>Purchasing Order not Found";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function remove_item() {
        $json = array();
        if ($this->logged_in()) {
            $id = $this->input->post("id");
            $this->load->model("gr_note_model");
            $this->load->model("grn_item_model");
            $this->load->model("item_serial_model");

            $po_item = $this->grn_item_model->get($id);
            if (!empty($po_item)) {
                $grn = $this->gr_note_model->get($po_item->grn_id);
                if ($grn) {
                    if ($grn->status == "0") {
                        $this->db->trans_start();
                        try {
                            $this->grn_item_model->delete($id);
                            $total = doubleval($po_item->qty) * doubleval($po_item->price);

                            if ($po_item->foc == "0") {
                                $this->gr_note_model->update_total($po_item->grn_id, $total, 0);
                            }

                            $serials = $this->item_serial_model->get_grn_serial_ids($po_item->grn_id, $po_item->item_id);
                            $this->item_serial_model->delete_serials($po_item->grn_id, $po_item->item_id);
                            $json["msg_type"] = "OK";
                            $json["msg"] = "Item removed Successfully";
                            $json["serials"] = $serials;
                            $string = $po_item->is_temp == "1" ? " (TEMP) " : ($po_item->foc == "1" ? " (FOC) " : "");
                            $this->log_login("grn", "One Item" . $string . "Removed From the GRN : " . decorate_code($po_item->grn_id, "grn", $this->prefixes));
                            $this->db->trans_complete();
                        } catch (Exception $exc) {
                            $this->db->trans_rollback();
                            $json["msg_type"] = "ERR";
                            $json["msg"] = $exc->getTraceAsString();
                        }
                    } else {
                        if ($grn->status == "1") {
                            $json["msg"] = "Can't Complete the Request.<br/>G R N already Finished.";
                        }
                        if ($grn->status == "1") {
                            $json["msg"] = "Can't Complete the Request.<br/>G R N already Cancelled.";
                        }
                        $json["msg_type"] = "ERR";
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Can't Complete the Request.<br/>No Data Found.";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Can't Complete the Request.<br/>No Data Found.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function update_grn_qty() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $qty = $this->input->post("qty");

            $this->form_validation->set_rules("qty", "Quantity", "trim|required|greater_than[0]");
            if ($this->form_validation->run()) {
                $this->load->model("gr_note_model");
                $this->load->model("grn_item_model");


                $grn_item = $this->grn_item_model->get($id);
                if ($grn_item) {
                    $this->db->trans_start();
                    try {
                        $price = doubleval($grn_item->price);
                        $old_qty = doubleval($grn_item->qty);
                        $new_qty = doubleval($qty);

                        $old_tot = $price * $old_qty;
                        $new_tot = $price * $new_qty;

                        $this->grn_item_model->update($id, array("qty" => $qty));

                        $diff = $new_tot - $old_tot;
                        $this->gr_note_model->update_total($grn_item->grn_id, $diff, 1);

                        $json["msg_type"] = "OK";
                        $json["new_price"] = is_zero($new_tot);
                        $this->db->trans_complete();
                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Can't Edit.<br/>G R N Item Not Found...";
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

    public function add_temp_item() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $this->form_validation->set_rules("itm_code", "Item", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("qty", "Quantity", "trim|required|greater_than[0]");
                if ($this->form_validation->run()) {
                    $this->form_validation->set_rules("rate", "Rate", "trim|required|greater_than[0]");
                    if ($this->form_validation->run()) {
                        $this->db->trans_start();
                        try {
                            $this->load->model("temp_grn_item_model", "tmp");
                            $this->load->model("stock_model");
                            $id = $this->tmp->save_entry($this->branch);

                            $itm_id = $this->input->post("itm_code");
                            $qty = $this->input->post("qty");
                            $stock = $this->stock_model->get_stock_by($itm_id, $this->branch);

                            $this->stock_model->update_stock($itm_id, $this->branch, $qty, 1, !empty($stock));

                            $this->load->model("item_model");
                            $item = $this->item_model->get($itm_id);

                            $json["msg_type"] = "OK";
                            $json["id"] = $id;
                            $json["msg"] = "Tempory Goods Receive Entry Addedd.";
                            $this->log_login("grn", "Tempory Goods Receive Entry Addedd ($item->itm_name): " . decorate_code($id, "grn", $this->prefixes));
                            $this->db->trans_complete();
                        } catch (Exception $exc) {
                            $this->db->trans_rollback();
                            $json["msg_type"] = "ERR";
                            $json["msg"] = $exc->getTraceAsString();
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
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function add_pre_rec_items() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("supplier", "", "trim|required|callback_combo", array("combo" => "Please Selece a Supplier to Continue"));
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("grn_date", "G R N Date", "trim|required");
//                $this->form_validation->set_rules("po_ref", "Order Reference", "trim|required");
                if ($this->form_validation->run()) {
                    $ids = $this->input->post("tgrn_items");
                    $this->load->model("temp_grn_item_model", "tgrn");
                    $this->load->model("gr_note_model");
                    $this->load->model("grn_item_model");
                    $this->load->model("po_item_model");

                    $items = $this->tgrn->get_items($ids);
                    $po_id = $this->gr_note_model->save_grn($this->branch, $this->user);

                    $temp_po_items = $this->po_item_model->temp_po_items($po_id);
                    $temp_items = array();
                    foreach ($temp_po_items as $tpi) {
                        $temp_items[] = $tpi->item_id;
                    }
                    $this->db->trans_start();
                    try {

                        $return_items = array();
                        $po_totla = 0;
                        foreach ($items as $itm) {
                            $qty = doubleval($itm->qty);
                            $price = doubleval($itm->price);
                            $data = array(
                                "item_id" => intval($itm->item_id),
                                "qty" => $qty,
                                "price" => $price,
                                "grn_id" => $po_id,
                                "temp_id" => $itm->id,
                                "is_temp" => 1
                            );
                            if (!in_array($itm->item_id, $temp_items)) {
                                $poi_id = $this->grn_item_model->insert($data);
                                $data["id"] = $poi_id;
                                $data["total"] = number_format($qty * $price, 2);
                                $data["item_code"] = $itm->itm_code;
                                $data["item_name"] = $itm->itm_name;
                                $return_items[] = $data;
                                $po_totla += ($qty * $price);
                            }
                        }
                        if (count($return_items) > 0) {
                            $this->gr_note_model->update_total($po_id, $po_totla, 1);
                            $json["p_id"] = $po_id;
                            $json["display_id"] = decorate_code($po_id, "grn", $this->prefixes);
                            $json["msg_type"] = "OK";
                            $json["ret_items"] = $return_items;
                            $this->log_login("grn", "Add " . count($items) . " Pre Received Item(s) to  : " . $json["display_id"]);
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = "No Items Added to List.";
                        }
                        $this->db->trans_complete();
                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
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
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function get_pre_rec_items() {
//        pre-rec-items
        $this->load->model("temp_grn_item_model", "tgrn");
        $temp_items = $this->tgrn->get_records($this->branch);
        $data = array("tgrn_items" => $temp_items);
        $this->load->view("grn/pre-rec-items", $data);
    }

    public function remove_pre_item() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");
            $this->load->model("grn_item_model");

            $item = $this->grn_item_model->get($id);
            if (!empty($item)) {
                $this->db->trans_start();
                try {
                    $this->load->model("gr_note_model");

                    $qty = $item->qty;
                    $price = $item->price;
                    $total = doubleval($qty) * doubleval($price);

                    $this->grn_item_model->delete($id);
                    $this->gr_note_model->update_total($item->grn_id, $total, -1);
                    $json["msg_type"] = "OK";
                    $this->log_login("grn", "1 Pre Received Item Removed from GRN : " . decorate_code($item->grn_id, "grn", $this->prefixes));
                    $this->db->trans_complete();
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function get_return_list() {
        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));

        $this->load->model("supplier_return_model", "srm");

        $returns_count = $this->srm->get_return_list($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE);
        $returns = $this->srm->get_return_list($this->branch, $start, $length, $column, $direction);
        $dt_array = array();

        foreach ($returns as $return) {
            $_d = array(
                "supname" => $return->company_name,
                "ret_id" => decorate_code($return->id, "supreturn", $this->prefixes),
                "ret_date" => $return->ret_date,
                "edit_at" => date("M d, Y h:i a", strtotime($return->create_date)),
                "edit_by" => $return->username,
                "status" => $return->status,
            );
            $dt_array[] = $_d;
        }
        $output = array(
            "total" => $returns_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

    public function create_return_note() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $this->form_validation->set_rules("supplier", "Supplier", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("ret_date", "Return Note Date", "trim|required");
                if ($this->form_validation->run()) {
                    $this->db->trans_start();
                    try {
                        $this->load->model("supplier_return_model", "srm");

                        $data = array(
                            "ret_ref" => $this->input->post("ret_ref"),
                            "supplier" => $this->input->post("supplier"),
                            "ret_date" => $this->input->post("ret_date"),
                            "create_date" => date("Y-m-d H:i:s"),
                            "branch" => $this->branch->id,
                            "edit_by" => $this->user->id,
                            "status" => 0
                        );
                        $id = $this->srm->insert($data);
                        $json["msg_type"] = "OK";
                        $json["dec_id"] = decorate_code($id, "supreturn", $this->prefixes);
                        $json["url"] = base_url("grn/returns/edit/" . $json["dec_id"]);
                        $this->log_login("supreturn", "New Supplier Return Note Created : " . $json["dec_id"]);

                        $this->db->trans_complete();
                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
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
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function update_return_note() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("ret_date", "Return Note Date", "trim|required");
            if ($this->form_validation->run()) {
                $this->db->trans_start();
                try {
                    $this->load->model("supplier_return_model", "srm");
                    $id = $this->input->post("ret_id");
                    $data = array(
                        "ret_ref" => $this->input->post("ret_ref"),
                        "ret_date" => $this->input->post("ret_date"),
                        "edit_by" => $this->user->id,
                    );
                    $this->srm->update($id, $data);
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Update Successfull.";
                    $this->db->trans_complete();
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
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

    public function get_open_grn_returns() {

        $_grn_id = $this->uri->segment(3);
        $grn_id = undecorate_code($_grn_id);

        $this->load->model("gr_note_model");
        $grn = $this->gr_note_model->get($grn_id);
        $this->load->model("supplier_return_model", "srm");
        $return_notes = $this->srm->get_open_notes($this->branch, $grn->supplier);
        $this->load->view("grn/returns/open_tickets", array("notes" => $return_notes));
    }

    public function add_return() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $dec_grn_id = $this->input->post("grn");
            $id = $this->input->post("id");
            $max = $this->input->post("max");
            $ret_id = $this->input->post("ret_id");
            $ret_qty = $this->input->post("ret_qty");

            $this->load->model("gr_note_model");

            $un_dec_id = undecorate_code($dec_grn_id);
            $grn = $this->gr_note_model->get($un_dec_id);
            if ($grn) {

                $this->load->model("grn_item_model", "grnim");
                $this->load->model("supplier_return_model", "srm");
                $this->load->model("supplier_return_item_model", "srim");

                $grn_item = $this->grnim->get($id);
                if ($grn_item) {
                    $this->db->trans_start();
                    try {
                        $this->srim->check_item($un_dec_id, $ret_id, $grn_item->item_id);
                        $data = array(
                            "grn_id" => $un_dec_id,
                            "ret_id" => $ret_id,
                            "item_id" => $grn_item->item_id,
                            "qty" => $ret_qty,
                            "rate" => $grn_item->price,
                        );
                        $this->srim->insert($data);
                        $json["msg_type"] = "OK";
                        $this->log_login("supreturn", "1 Item added to Supplier Return Note : " . $dec_grn_id);
                        $this->db->trans_complete();
                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg_type"] = "G R N Item Not Found...";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg_type"] = "G R N Not Found...";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function remove_from_return() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");
            $this->load->model("supplier_return_item_model", "srim");
            $this->load->model("supplier_return_model", "srm");
            $ret_item = $this->srim->get($id);
            $retun_note = $this->srm->get($ret_item->ret_id);
            if ($retun_note->status == "0") {
                $this->db->trans_start();
                try {
                    $this->srim->delete($id);
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Item Remove From Return List";
                    $this->log_login("supreturn", "1 Item removed from Supplier Return Note : " . $dec_grn_id);
                    $this->db->trans_complete();
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Cannot Remove the item From Return List.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function update_ret_note_status() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model("supplier_return_model", "srm");
            $id = $this->input->post("id");
            $status = $this->input->post("status");
            $this->db->trans_start();
            try {
                if ($status == "2") {
                    $this->srm->update($id, array("status" => 2));
                    $json["msg_type"] = "OK";
                    $dec_id = decorate_code($id, "supreturn", $this->prefixes);
                    $json["url"] = base_url("grn/returns/view/" . $dec_id);
                    $this->log_login("supreturn", "Supplier Return Note Cancelled: " . $dec_id);
                } else {
                    $this->load->model("supplier_return_item_model", "srim");
                    $items = $this->srim->get_item_list($id);

                    $this->load->model("stock_model");
                    $arr = $this->stock_model->check_for_availablility2($this->branch, $items);
                    if ($arr[0]) {
                        foreach ($items as $item) {
                            $this->stock_model->update_ret_stock($this->branch, $item->item_id, $item->qty);
                        }
                        $this->srm->update($id, array("status" => 1));
                        $json["msg_type"] = "OK";
                        $dec_id = decorate_code($id, "supreturn", $this->prefixes);
                        $json["url"] = base_url("grn/returns/view/" . $dec_id);
                        $this->log_login("supreturn", "Supplier Return Note Finished: " . $dec_id);
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "Cannot finish the return note.<br/>Stock Not Sufficient";
                    }
                }
                $this->db->trans_complete();
            } catch (Exception $exc) {
                $this->db->trans_rollback();
                $json["msg_type"] = "ERR";
                $json["msg"] = $exc->getTraceAsString();
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
            $this->db->trans_start();
            try {
                $id = $this->input->post("id");
                $discount = $this->input->post("discount");

                $this->load->model("gr_note_model", "grm");
                $note = $this->grm->get($id);

                $this->db->set("total", (doubleval($note->sub_total) - doubleval($discount)), FALSE);
                $this->db->set("discount", $discount);
                $this->db->where("id", $id);
                $this->db->update("gr_notes");

                $json["msg_type"] = "OK";
                $this->db->trans_complete();
            } catch (Exception $exc) {
                $this->db->trans_rollback();
                $json["msg_type"] = "ERR";
                $json["msg"] = $exc->getTraceAsString();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function save_grn_date() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $_remarks = $this->input->post("grn_date");
            $this->load->model("gr_note_model");
            $remarks = strip_tags($_remarks);
            $grn = $this->gr_note_model->get($id);
            $this->gr_note_model->update($id, array("grn_date" => $remarks, "e_by" => $this->user->id, "e_at" => date("Y-m-d H:i:s")));
            $this->log_login("grn", "G R N Date Updated : " . decorate_code($grn->gr_id, "grn", $this->prefixes));
            $json["date"] = $remarks;
            $json["msg_type"] = "OK";
            $json["msg"] = "G R N Date Updated.";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function mark_as_pending() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");
            $this->load->model("gr_note_model", "grm");
            $this->load->model("grn_item_model", "grim");

            $grn = $this->grm->get($id);
            if ($grn) {
                if ($grn->status == "1") {
                    $this->load->model("stock_model");
                    $grn_items = $this->grim->get_items($grn->id);
                    $is_ok = $this->stock_model->check_for_availablility2($this->branch, $grn_items);
                    if ($is_ok[0]) {
                        $this->db->trans_start();
                        try {
                            $this->grm->update($grn->id, array("status" => 0));
                            foreach ($grn_items as $gr_itm) {
                                $this->stock_model->update_stock($gr_itm->item_id, $this->branch, $gr_itm->qty, -1, TRUE);
                            }
                            $json["msg_type"] = "OK";
                            $json["msg"] = "G R N Marked as Pending Successfully.";
                            $json["url"] = base_url("grn/edit/" . decorate_code($grn->gr_id, "grn", $this->prefixes));
                            $this->db->trans_complete();
                        } catch (Exception $exc) {
                            $this->db->trans_rollback();
                            $json["msg_type"] = "ERR";
                            $json["msg"] = $exc->getTraceAsString();
                        }
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "G R N cannot mark as Pending.<br>Some of Item(s) Has not Enough Stock";
                    }
                    $json["items"] = $grn_items;
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "G R N cannot mark as Pending.";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "G R N Not Found.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

}
