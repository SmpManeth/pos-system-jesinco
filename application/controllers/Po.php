<?php

/**
 * Description of po
 *
 * @author dilshan
 */
class Po extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function new_po() {
        if ($this->logged_in()) {
            $this->data["head"] = "New Purchasing Order";
            $this->data["breadcrums"] = array(array("home", "home"), array("po", "Purchasig Order"), "New");
            $this->load->model("stock_model");
            $this->load->model("wl_supplier_model");
            $sups = $this->wl_supplier_model->get_all_branch_active($this->branch, 30);
            $items = $this->stock_model->get_stock_list($this->branch);

            $this->data["items"] = $items;
            $this->data["sups"] = $sups;
            $this->load_view(array("po/new"));
        } else {
            redirect(site_url("login"));
        }
    }

    public function archive() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Purchasing Order Archive";
            $this->load->model("p_order_model");
            $all_orders = $this->p_order_model->get_orders($this->branch);
            $result_count = count($all_orders);
            $this->data['result_count'] = $result_count;
            $this->load_view(array("po/archive"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function index() {
        $this->all();
    }

    public function all() {
        if ($this->logged_in()) {
            $this->data["head"] = "Recent Purchasin Order";
            $this->data["breadcrums"] = array(array("home", "home"), ("Purchasig Order"));
            $this->load->model("p_order_model");
            $orders = $this->p_order_model->get_orders($this->branch,0,50);
            $this->data["orders"] = $orders;
            $this->load_view(array("po/all"));
        } else {
            redirect(site_url("login"));
        }
    }

    public function edit() {
        if ($this->logged_in()) {
            $_id = $this->uri->segment(3);
            $this->load->model("p_order_model");
            $this->load->model("po_item_model");

            $id = undecorate_code($_id);
            $order = $this->p_order_model->get_po($id, $this->branch);
            if (!empty($order)) {
                if ($order->status == "0") {
                    $p_items = $this->po_item_model->get_items_with_details($order->id);
                    $this->data["p_order"] = $order;
                    $this->data["p_items"] = $p_items;
                    $this->data["doc_id"] = decorate_code($id, "po", $this->prefixes);

                    $this->data["head"] = "Edit Purchasin Order";
                    $this->data["breadcrums"] = array(array("home", "home"), array("po", "Purchasig Order"), "Edit");
                    $this->load->model("stock_model");
                    $this->load->model("wl_supplier_model");
                    $sups = $this->wl_supplier_model->get_all_branch_active($this->branch);
                    $items = $this->stock_model->get_stock_list($this->branch);

                    $this->data["items"] = $items;
                    $this->data["sups"] = $sups;
                    $this->load_view(array("po/edit"));
                } else {
                    redirect(site_url("po/view/" . decorate_code($order->po_id, "po", $this->prefixes)));
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
            $this->load->model("p_order_model");
            $this->load->model("po_item_model");

            $order = $this->p_order_model->get_po($id, $this->branch);
            if (!empty($order)) {
                if ($order->status == "1" || $order->status == "2") {
                    $p_items = $this->po_item_model->get_items_with_details($order->id);
                    $this->data["p_order"] = $order;
                    $this->data["p_items"] = $p_items;
                    $this->data["doc_id"] = decorate_code($id, "po", $this->prefixes);

                    $this->data["head"] = "Purchasin Order Information";
                    $this->data["breadcrums"] = array(array("home", "home"), array("po", "Purchasig Order"), "View");

                    $this->load_view(array("po/view"));
                } else {
                    redirect(site_url("po/edit/" . $id));
                }
            } else {
                $this->load_view(array("nothing"));
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function print_po() {
        if ($this->ion_auth->logged_in()) {
            $doc_id = $this->uri->segment(3);
            $this->load->model("p_order_model");
            $id = undecorate_code($doc_id);
            $po = $this->p_order_model->get_po($id, $this->branch);
            if ($po) {
                $this->load->model("po_item_model", "poim");
                $po_items = $this->poim->get_items_with_details($po->id);
//                dump($po_items);

                $this->load->library('F_pdf');
                $pdf = new My_pdf("L", "mm", "a5");
                $pdf->set_is_devided(FALSE);
                $pdf->AcceptPageBreak();
                $pdf->SetAutoPageBreak(true, 00);
                $pdf->set_footer(FALSE);
//            $pdf->set_footer_text("Created By Weblankan");
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
                $pdf->Cell(38, 6, "Purchasin Order");
                $pdf->SetFont('', 'B', '');
                $pdf->Cell(30, 6, ": " . $doc_id);
                $pdf->SetFont('', '', '');
                $pdf->Cell(122, 6, "Date : " . $po->p_date, "0", 1, "R");
                $pdf->SetFont('', '', 10);

                $pdf->Cell(38, 6, "Supplier");
                $pdf->Cell(112, 6, ": " . $po->company_name, 0, 0);
                $pdf->SetFont('', '', 11);
                $pdf->Cell(40, 6, "Deliver Date : " . $po->p_date, 0, 1, "R");

                $pdf->Cell(38, 6, "Delivery Location");
                $pdf->Cell(112, 6, ": " . $po->del_location, 0, 1);
                $pdf->SetFont('', '', 10);

                $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
                $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);

                $i = 0;
                $h = 6;
                $pdf->SetFont('', '', 9);
                $total = 0;
                foreach ($po_items as $item) {
                    if ($item->is_temp != "1") {
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
                if ($i < count($po_items)) {
                    $pdf->Cell(0, $h, "Pre Recieved Items", 0, 1, "L");
                }
                foreach ($po_items as $item) {
                    if ($item->is_temp == "1") {
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
                $pdf->Cell(0, 2, "", 0, 1, "R");
                $pdf->Cell(array_sum($widths) - $widths[5], $h, "Total", 1, 0, "R");
                $pdf->Cell($widths[5], $h, is_zero($po->total), 1, 0, "R");


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

                $pdf->Output("purchasing_order_" . $doc_id . ".pdf", "I");
            } else {
                redirect("po");
            }
        } else {
            redirect(base_url("login"));
        }
    }

    ############################################################################

    public function new_order() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("supplier", "", "trim|required|callback_combo", array("combo" => "Please Selece a Supplier to Continue"));
//            $this->form_validation->set_message("combo", "");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("po_date", "Order Date", "trim|required");
//                $this->form_validation->set_rules("po_ref", "Order Reference", "trim|required");
                if ($this->form_validation->run()) {
                    $this->form_validation->set_rules("item", "Item", "trim|required");
                    $this->form_validation->set_rules("qty", "Quantity", "trim|required|greater_than[0]");
                    $this->form_validation->set_rules("rate", "Item Rate", "trim|required|greater_than[0]");
                    if ($this->form_validation->run()) {

                        $this->load->model("p_order_model");
                        $this->load->model("po_item_model");
                        $po_id = $this->p_order_model->save_po($this->branch, $this->user);
                        $qty = $this->input->post("qty");
                        $rate = $this->input->post("rate");
                        $total = doubleval($qty) * doubleval($rate);

                        $this->p_order_model->update_total($po_id[0], $total, 1);
                        $poi_id = $this->po_item_model->add_item($po_id[0]);

                        $json["p_id"] = $po_id[0];
                        $json["p_id_display"] = decorate_code($po_id[1], "po", $this->prefixes);
                        $json["pi_id"] = $poi_id;
                        $json["msg_type"] = "OK";
                        $json["msg"] = "Item Added Successfully";
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

    public function cancel_po() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("p_order_model");
            $this->load->model("po_item_model");
            $id = undecorate_code($this->input->post("id"));
            $po = $this->p_order_model->get($id);
            if ($po) {
                $items = $this->po_item_model->get_items($id);
                if ($po->status == "0") {
                    $this->p_order_model->update($id, array("status" => 2, "e_by" => $this->user->id, "e_at" => date("Y-m-d H:i:s")));
                    $json["msg_type"] = "OK";
                    $json["msg"] = "The Purchasing Order Cancelled Successfully";
                    $json["url"] = site_url("po/view/" . decorate_code($po->po_id, "po", $this->prefixes));
                } else {
                    $json["msg_type"] = "ERR";
                    if ($po->status == "1") {
                        $json["msg"] = "Can't Cancel<br/>The Purchasing Order is Already Finished.";
                    }
                    if ($po->status == "2") {
                        $json["msg"] = "Can't Cancel<br/>The Purchasing Order is Already Cancelled.";
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

    public function finish_po() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("p_order_model");
            $this->load->model("po_item_model");
            $id = undecorate_code($this->input->post("id"));
            $po = $this->p_order_model->get($id);
            if ($po) {
                $items = $this->po_item_model->get_items($id);
                if (count($items) > 0) {
                    if ($po->status == "0") {

                        $temp_po_items = $this->po_item_model->temp_po_items($id);
                       $all_items_exists = TRUE;
                        $availbale_items = array();
//                      Check if of Current po's id is not available in the available list  
                        foreach ($temp_po_items as $tmp_itm) {
                            if (!in_array($tmp_itm->temp_id, $tgrn_item_ids)) {
                                $all_items_exists = FALSE;
                                break;
                            } else {
                                $availbale_items[] = $tmp_itm->temp_id;
                            }
                        }

                        if ($all_items_exists) {
                            $data = array(
                                "status" => 1,
                                "p_date" => $this->input->post("po_date"),
                                "del_date" => $this->input->post("del_date"),
                                "supplier" => $this->input->post("supplier"),
                                "po_ref" => $this->input->post("po_ref"),
                                "del_location" => $this->input->post("del_location"),
                                "e_by" => $this->user->id,
                                "e_at" => date("Y-m-d H:i:s")
                            );
                            $this->p_order_model->update($id, $data);
                            if (count($availbale_items) > 0) {
                                $this->tgrn->update_po_id($availbale_items, $id);
                            }
                            $json["msg_type"] = "OK";
                            $json["ids"] = $availbale_items;
                            $json["msg"] = "The Purchasing Order Finished Successfully";
                            $json["url"] = site_url("po/view/" . decorate_code($po->po_id, "po", $this->prefixes));
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = "Some Pre Received Items are in a Finished Purchasing Order or Finished Goods Receive Note.";
                        }
                    } else {
                        $json["msg_type"] = "ERR";
                        if ($po->status == "1") {
                            $json["msg"] = "Can't Finished<br/>The Purchasing Order is Already Finished.";
                        }
                        if ($po->status == "2") {
                            $json["msg"] = "Can't Finished<br/>The Purchasing Order is Cancelled.";
                        }
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Can't Finished<br/>There are No items in the Purchasing Order.";
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

    public function pending_po() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("p_order_model");
            $id = undecorate_code($this->input->post("id"));
            $po = $this->p_order_model->get($id);
            if ($po) {
                $this->load->model("gr_note_model");
                $po_s = $this->gr_note_model->find_grn_by_po($id);

                if (count($po_s) == 0) {
                    $this->p_order_model->update($id, array("status" => 0, "e_by" => $this->user->id, "e_at" => date("Y-m-d H:i:s")));
                    $json["msg_type"] = "OK";
                    $json["msg"] = "The Purchasing Marked as Pending Successfully.";
                    $json["url"] = site_url("po/edit/" . decorate_code($po->po_id, "po", $this->prefixes));
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Can't Modify.<br/>" . count($po_s) . " GRN(s) made by this Purchasing Order.";
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
            $this->load->model("p_order_model");
            $this->load->model("po_item_model");

            $po_item = $this->po_item_model->get($id);
            if (!empty($po_item)) {
                $po = $this->p_order_model->get($po_item->order_id);
                if ($po) {
                    if ($po->status == "0") {
                        $this->po_item_model->delete($id);
                        $total = doubleval($po_item->qty) * doubleval($po_item->price);
                        $this->p_order_model->update_total($po_item->order_id, $total, 0);
                        $json["msg_type"] = "OK";
                    } else {
                        if ($po->status == "1") {
                            $json["msg"] = "Can't Complete the Request.<br/>Purchase Order already Finished.";
                        }
                        if ($po->status == "1") {
                            $json["msg"] = "Can't Complete the Request.<br/>Purchase Order already Cancelled.";
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

    public function create_grn() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("p_order_model");
            $this->load->model("po_item_model");
            $this->load->model("gr_note_model");
            $this->load->model("grn_item_model");

            $id = $this->input->post("id");
            $p_order = $this->p_order_model->get($id);
            if ($p_order) {
                $deco_po = decorate_code($p_order->po_id, "po", $this->prefixes);
                $grn = $this->gr_note_model->get_from_po_ref($p_order->id, $this->branch);
                if (empty($grn)) {
                    $undecor_id = undecorate_code($id);
                    $po_items = $this->po_item_model->get_items($undecor_id);

                    $gr_id = $this->gr_note_model->get_next_grn_id($this->branch);
                    $data_grn = array(
                        "branch" => $this->branch->id,
                        "grn_date" => date("Y-m-d"),
                        "system_date" => date("Y-m-d H:i:s"),
                        "po_ref" => $deco_po,
                        "po_id" => $undecor_id,
                        "del_location" => $p_order->del_location,
                        "supplier" => $p_order->supplier,
                        "total" => $p_order->total,
                        "sub_total" => $p_order->total,
                        "gr_id" => $gr_id,
                        "e_by" => $this->user->id,
                        "status" => 0,
                        "e_at" => date("Y-m-d H:i:s")
                    );
                    $grn_id = $this->gr_note_model->insert($data_grn);

                    $grn_item_data = array();
                    foreach ($po_items as $po_item) {
                        $data_item = array(
                            "grn_id" => $grn_id,
                            "item_id" => $po_item->item_id,
                            "qty" => $po_item->qty,
                            "price" => $po_item->price,
                            "is_temp" => $po_item->is_temp,
                            "temp_id" => $po_item->temp_id,
                        );
                        $grn_item_data[] = $data_item;
                    }
                    $this->grn_item_model->insert_many($grn_item_data);

                    $json["msg_type"] = "OK";
                    $json["msg"] = "Good Receive Note Created Successfully";
                    $json["url"] = site_url("grn/edit/" . decorate_code($gr_id, "grn", $this->prefixes));
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Can't Complete Request<br/>There is a GRN Available For this Purchasing Order.<br/><a href='" . site_url("grn/edit/" . decorate_code($grn->gr_id, "grn", $this->prefixes)) . "'>Click Here</a> to View";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Can't Complete Request<br/>Purchasing Order Not Found.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function get_po_list() {
        $output = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model("p_order_model");
            // Datatables Variables
            $start = intval($this->input->get("offset"));
            $length = intval($this->input->get("limit"));
            $direction = ($this->input->get("order"));
            $column = ($this->input->get("sort"));
            $search = ($this->input->get("search"));

            $_orders = $this->p_order_model->get_orders($this->branch, $start, $length, $column, $direction, $search);
            $all_orders = $this->p_order_model->get_orders($this->branch);

            $orders = array();
            foreach ($_orders as $_order) {
                $_order->po_id = decorate_code($_order->po_id, "po", $this->prefixes);
                $orders[] = $_order;
            }

            $output = array(
                "total" => count($all_orders),
                "rows" => $orders
            );
        } else {
            $output = array(
                "total" => 0,
                "rows" => array()
            );
        }
        echo json_encode($output);
    }

    public function get_pre_rec_items() {
//        pre-rec-items
        $this->load->model("temp_grn_item_model", "tgrn");
        $temp_items = $this->tgrn->get_records($this->branch);
        $data = array("tgrn_items" => $temp_items);
        $this->load->view("po/pre-rec-items", $data);
    }

    public function add_pre_rec_items() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $this->form_validation->set_rules("supplier", "", "trim|required|callback_combo", array("callback_combo" => "Please Selece a Supplier to Continue"));
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("po_date", "Order Date", "trim|required");
//                $this->form_validation->set_rules("po_ref", "Order Reference", "trim|required");
                if ($this->form_validation->run()) {
                    $ids = $this->input->post("tgrn_items");
                    $this->load->model("temp_grn_item_model", "tgrn");
                    $this->load->model("p_order_model");
                    $this->load->model("po_item_model");

                    $items = $this->tgrn->get_items($ids);
                    $po_id = $this->p_order_model->save_po($this->branch, $this->user);

                    $temp_po_items = $this->po_item_model->temp_po_items($po_id);
                    $temp_items = array();
                    foreach ($temp_po_items as $tpi) {
                        $temp_items[] = $tpi->item_id;
                    }


                    $return_items = array();
                    $po_totla = 0;
                    foreach ($items as $itm) {
                        $qty = doubleval($itm->qty);
                        $price = doubleval($itm->price);
                        $data = array(
                            "item_id" => intval($itm->item_id),
                            "qty" => $qty,
                            "price" => $price,
                            "order_id" => $po_id,
                            "temp_id" => $itm->id,
                            "is_temp" => 1
                        );
                        if (!in_array($itm->item_id, $temp_items)) {
                            $poi_id = $this->po_item_model->insert($data);
                            $data["id"] = $poi_id;
                            $data["total"] = number_format($qty * $price, 2);
                            $data["item_code"] = $itm->itm_code;
                            $data["item_name"] = $itm->itm_name;
                            $return_items[] = $data;
                            $po_totla += ($qty * $price);
                        }
                    }
                    $this->p_order_model->update_total($po_id, $po_totla, 1);
                    $json["p_id"] = $po_id;
                    $json["display_id"] = decorate_code($po_id, "po", $this->prefixes);
                    $json["msg_type"] = "OK";
                    $json["ret_items"] = $return_items;
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

    public function remove_pre_item() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");
            $this->load->model("po_item_model");

            $item = $this->po_item_model->get($id);
            if (!empty($item)) {
                $this->load->model("p_order_model");

                $qty = $item->qty;
                $price = $item->price;
                $total = doubleval($qty) * doubleval($price);

                $this->po_item_model->delete($id);
                $this->p_order_model->update_total($item->order_id, $total, -1);
                $json["msg_type"] = "OK";
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

}
