<?php

/*
 * The MIT License
 *
 * Copyright 2019 Dilshan  Jayasnka.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Description of Reservation
 *
 * @author Dilshan  Jayasnka
 */
class Reservation extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->all();
    }

    public function view()
    {
        if ($this->ion_auth->logged_in()) {
            $decorated_id = $this->uri->segment(3);
            $id = undecorate_code($decorated_id);

            if (!empty($id)) {

                $this->data["head"] = "DO Note";
                $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), array("invoice/reservation", "DO Notes"), "Edit");

                $this->load->model("invoice_model");
                $invoice = $this->invoice_model->get_do_by_do_id($this->branch, $id);
                if (!empty($invoice)) {
                    if ($invoice->status == "4" || $invoice->status == "6" || $invoice->status == "3") {
                        $this->load->model("wl_customer_model");
                        $this->load->model("invoice_item_model");
                        $this->load->model("invoice_payment_model", "ipm");

                        $invoice_items = $this->invoice_item_model->get_items($invoice->id);
                        $customer = $this->wl_customer_model->get_customer($invoice->customer);
                        $payments = $this->ipm->get_payments($invoice->id);

                        $this->data["doc_id"] = decorate_code($invoice->do_id, "do", $this->prefixes);
                        $this->data["customer"] = $customer;
                        $this->data["invoice"] = $invoice;
                        $this->data["invoice_items"] = $invoice_items;
                        $this->data["payments"] = $payments;

                        $this->load_view(array("invoice/reservation/view"));
                    } else {
                        redirect(base_url("invoice/edit/" . $decorated_id));
                    }
                } else {
                    $this->load_view(array("nothing"));
                }
            } else {
                redirect(base_url("invoice"));
            }
        } else {
            redirect(base_url("login"));
        }
    }

    public function all()
    {
        if ($this->ion_auth->logged_in()) {

            $this->data["head"] = "Delivery Orders";
            $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "Delivery Orders");

            $this->load->model("invoice_model");
            $users = $this->invoice_model->get_users($this->branch);
            $this->data["users"] = $users;

            $this->load_view(array("invoice/reservation/list"));
        } else {
            redirect("login");
        }
    }

    public function new_note()
    {
        if ($this->ion_auth->logged_in()) {

            $this->data["head"] = "Delivery Order";
            $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), array("reservation", "Delivery Orders"), "New Delivery Order");

            $this->load->model("wl_customer_model");
            $this->load->model("stock_model");
            $items = $this->stock_model->get_stock($this->branch);
            $customers = $this->wl_customer_model->get_all_customers($this->branch);

            $this->data["items"] = $items;
            $this->data["customers"] = $customers;

            $this->load_view(array("invoice/reservation/new-reservation"));
        } else {
            redirect("login");
        }
    }

    public function cancelled_do_list()
    {
        if ($this->ion_auth->logged_in()) {

            $this->load->model("invoice_model");

            $invoices = $this->invoice_model->get_c_c_invoices(FALSE, 6);
            $this->data["invoices"] = $invoices;
            $this->data["type"] = 'do';
            $this->load_view(array('invoice/cancelled_invoices'));
        } else {
            redirect("login");
        }
    }

    public function edit()
    {
        if ($this->ion_auth->logged_in()) {

            $decorated_id = $this->uri->segment(3);
            $id = undecorate_code($decorated_id);

            if (!empty($id)) {
                $this->data["head"] = "Edit Delivery Order";
                $this->data["breadcrums"] = array(array("home", "Home"), array("invoice/reservations", "Delivery Orders"), "Edit");
                $this->load->model("invoice_model");
                $invoice = $this->invoice_model->get_do_by_do_id($this->branch, $id);
                if (!empty($invoice)) {
                    if ($invoice->status == "1" || $invoice->status == "2" || $invoice->status == "3") {
                        redirect(base_url("invoice/view/" . $decorated_id));
                    } else if ($invoice->status == "4") {
                        redirect(base_url("invoice/payments/" . $decorated_id));
                    } else {
                        $this->load->model("wl_customer_model");
                        $this->load->model("stock_model");
                        $this->load->model("invoice_item_model");

                        $items = $this->stock_model->get_stock($this->branch);
                        $customers = $this->wl_customer_model->get_all_customers($this->branch);
                        $invoice_items = $this->invoice_item_model->get_items($invoice->id);

                        $this->data["doc_id"] = decorate_code($invoice->do_id, "do", $this->prefixes);
                        $this->data["items"] = $items;
                        $this->data["customers"] = $customers;
                        $this->data["invoice"] = $invoice;
                        $this->data["branch"] = $this->branch;
                        $this->data["invoice_items"] = $invoice_items;

                        $this->load_view(array("invoice/reservation/edit-note"));
                    }
                } else {
                    $this->load_view(array("nothing"));
                }
            } else {
                redirect(base_url("invoice"));
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

    public function get_reservation_list()
    {
        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));
        $user = ($this->input->get("username"));
        $search = ($this->input->get("search"));

        $this->load->model("invoice_model");
        $invoice_count = $this->invoice_model->get_all_reservations($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE, $user, $search);
        $invoices = $this->invoice_model->get_all_reservations($this->branch, $start, $length, $column, $direction, FALSE, $user, $search);

        $dt_array = array();

        foreach ($invoices as $invoice) {
            $created = $invoice->created;
            $created_date = explode(" ", $invoice->created_at);

            $_d = array(
                "cusname" => $invoice->customer_prefix . " " . $invoice->customer_name,
                "plain_inv_no" => $invoice->do_id,
                "do_number" => $invoice->do_number,
                "balance" => $invoice->balance,
                "cancel_approved" => intval($invoice->cancel_approved),
                "inv_id" => decorate_code($invoice->do_id, "do", $this->prefixes),
                "inv_date" => $invoice->inv_date,
                "username" => $invoice->username,
                "created" => $invoice->created,
                "older" => date_older_than($created_date[0], 10),
                "last_edit_at" => $invoice->last_edit_at,
                "total" => is_zero($invoice->total),
                "status" => ($invoice->status),
            );
            $dt_array[] = $_d;
        }
        $output = array(
            "total" => $invoice_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

    public function mark_as_delivered()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");

            $this->load->model("invoice_model");
            $invoice = $this->invoice_model->get($id);
            if (!empty($invoice)) {
                if ($invoice->status == "3") {

                    $this->load->model("wl_customer_model");
                    $customer = $this->wl_customer_model->get($invoice->customer);
                    if ($customer->approved == "1") {
                        $this->db->trans_start();
                        try {

                            $this->load->model("invoice_item_model");
                            $inv_items = $this->invoice_item_model->get_items($id);
                            $this->load->model("stock_model");
                            $b = $this->stock_model->check_for_availablility($this->branch, $inv_items);
                            if ($b) {
                                $this->invoice_model->update($id, array("status" => 4));
                                // Update The Stock 
                                $this->load->model("stock_model");
                                $this->stock_model->update_stock_bulk($this->branch, $inv_items);

                                $json["msg_type"] = "OK";
                                $json["url"] = base_url("invoice/payments/" . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                            } else {
                                $json["msg_type"] = "ERR";
                                $json["msg"] = "insufficient Stock.";
                            }
                            $this->db->trans_complete();
                        } catch (Exception $exc) {
                            $this->db->trans_rollback();
                            $json["msg_type"] = "ERR";
                            $json["msg"] = $exc->getTraceAsString();
                        }
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "Customer has not approved by the Head Office yet.";
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Invoice Already Deliverd.";
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Invoice Not Found.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function cancel_reservation_note()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("inv_id");
            $type = $this->input->post("type");
            $this->db->trans_start();
            try {
                $this->load->model("invoice_model");
                $invoice = $this->invoice_model->get($id);
                if ($invoice->status == "0" || $invoice->status == "3" || $invoice->status == "4") {

                    $cancel_only = $this->input->post("cancel_only");
                    if ($cancel_only == "1") {

                        $this->invoice_model->update($id,
                            array(
                                "status" => $type == "do" ? 6 : 2,
                                "refund" => 0,
                                "last_edit_by" => $this->user->id,
                                "last_edit_at" => date("Y-m-d H:i:s")
                            ));
                        $json["msg_type"] = "OK";
                        $json["msg"] = $type == "do" ? "DO Note Cancelled." : "Invoice Cancelled.";
                        $dec_code = $type == "do" ? decorate_code($invoice->do_id, "do", $this->prefixes) : decorate_code($invoice->inv_id, "invoice", $this->prefixes);
                        $json["url"] = $type == "do" ? base_url("reservation/view/" . $dec_code) : base_url("invoice/view/" . $dec_code);
                        $this->log_login("do", ($type == "do" ? "DO Note Cancelled" : "Invoice Cancelled") . " : " . $dec_code . " (Cancel Only)");
                    } else {

                        $ret_items = $this->input->post("ret_qty");
                        $damaged_list = $this->input->post("damaged");
                        $damaged = $damaged_list ? $damaged_list : array();
                        $ret_item_ids = array_keys($ret_items);

                        $refund_amount = $this->input->post("total_refund");
                        $damaged_fines = $this->input->post("damaged_fines");
                        $unpaid_fines = $this->input->post("unpaid_fines");
                        $_damaged = $this->input->post("damaged");
                        $remark = $this->input->post("remark");

                        $return_items = FALSE;
                        foreach (array_values($ret_items) as $_ret_itm) {
                            if (!empty($_ret_itm)) {
                                $return_items = TRUE;
                            }
                        }

                        if ($return_items) {

                            $this->db->trans_start();
                            try {
                                $this->load->model("invoice_model");
                                $invoice = $this->invoice_model->get($id);


                                if (doubleval($refund_amount) <= $invoice->total) {

                                    $this->invoice_model->update($id,
                                        array(
                                            "status" => $type == "do" ? 6 : 2,
                                            "refund" => $refund_amount,
                                            "unpaid_fines" => $unpaid_fines,
                                            "damaged_deduction" => $damaged_fines,
                                            "remarks" => $invoice->remarks . PHP_EOL . ($remark ? ("Return note" . $remark) : ""),
                                            "last_edit_by" => $this->user->id,
                                            "last_edit_at" => date("Y-m-d H:i:s")
                                        ));

                                    // If item markes as damages then added to damaged stock, othervise added to main stock
                                    $this->load->model("invoice_item_model");
                                    $items = $this->invoice_item_model->get_items_by_ids($ret_item_ids);
                                    $this->load->model("damage_good_model");
                                    foreach ($items as $itm) {
                                        $qty = intval($ret_items[$itm->id]);
                                        if (array_key_exists($itm->id, $damaged)) {

                                            // I added this because initially only one item in the invoice, 
                                            // if it changed in the future this will help
                                            for ($i = 0; $i < $qty; $i++) {
                                                $this->damage_good_model->insert(array(
                                                    "item_id" => $itm->itm_id,
                                                    "branch_id" => $this->branch->id,
                                                    "status" => 0,
                                                    "added_by" => $this->user->id,
                                                    "create_date" => date("Y-m-d"),
                                                    "remarks" => $remark,
                                                ));
                                            }
                                        } else {
                                            // if not damaged, then added to the main stock
                                            $this->load->model("stock_model");
                                            // Update The Stock 
                                            $this->stock_model->update_stock($itm->itm_id, $this->branch, 1, 1, TRUE);
                                        }
                                        $this->invoice_item_model->update($itm->id, array("ret_qty" => $qty, 'ret_date' => date("Y-m-d")));
                                    }

                                    $json["msg_type"] = "OK";
                                    $dec_code = $type == "do" ? decorate_code($invoice->do_id, "do", $this->prefixes) : decorate_code($invoice->inv_id, "invoice", $this->prefixes);
                                    $json["msg"] = $type == "do" ? "DO Note Cancelled." : "Invoice Cancelled.";
                                    $json["url"] = $type == "do" ? base_url("reservation/view/" . $dec_code) : base_url("invoice/view/" . $dec_code);
                                    $this->log_login("do", ($type == "do" ? "DO Note Cancelled" : "Invoice Cancelled") . " : " . $dec_code . ' ' . (count($damaged) ? "(Marked as Damanged)" : ""));
                                } else {
                                    $json["msg_type"] = "ERR";
                                    $json["msg"] = "Refund Amount Exceeds the Total Amount";
                                }

                                $this->db->trans_complete();
                            } catch (Exception $exc) {
                                $this->db->trans_rollback();
                                $json["msg_type"] = "ERR";
                                $json["msg"] = $exc->getTraceAsString();
                            }
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = "Return Item not found. Please Input a quantity for <b>Return Quantity</b>";
                        }
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Order already cancelled";
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

    public function load_payment_form()
    {
        $id = $this->input->post("inv_id");
        $this->load->model("invoice_model");
        $this->load->model("installment_model");
        $invoice = $this->invoice_model->get($id);
        $installments = $this->installment_model->get_installments();
        $this->load->view("invoice/reservation/payment_form", array("invoice" => $invoice, "installments" => $installments, 'doc_id' => decorate_code($invoice->do_id, "invoice", $this->prefixes)));
    }

    public function make_reservation()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");
            $discount = $this->input->post("discount");
            $customer = $this->input->post("customer");
            $serive_charge = get_option('service-charge', null, $this->branch->id);
            $remarks = $this->input->post("remarks");
            $do_number = $this->input->post("do_number");

            $this->load->model("invoice_model");
            $this->load->model("installment_detail_model", 'idm');

            $invoice = $this->invoice_model->get($id);


            $this->db->trans_start();

            $pending_payments = $this->invoice_model->get_pending_paymetns($customer);
            if (count($pending_payments) > 1) {
                $json["msg_type"] = "ERR";
                $json["msg"] = 'Cannot Make the Reservation.<br/>There is another invoice pending for this Customer.';
            } else {
                try {

                    $total = doubleval($invoice->subtotal) - doubleval($serive_charge);
                    $this->invoice_model->update($id, array(
                        "customer" => $customer,
                        "do_number" => $do_number,
                        "service_charge" => $serive_charge,
                        "balance" => $total,
                        "total" => $total,
                        "remarks" => $remarks,
                        "delivery_date" => date("Y-m-d"),
                        "last_edit_at" => date("Y-m-d H:i:s"),
                        "last_edit_by" => $this->user->id,
                        "is_cash" => 0,
                        "status" => 3,
                    ));

                    $this->load->model("invoice_item_model");
                    $this->load->model("stock_model");

                    $inv_items = $this->invoice_item_model->get_items($id);
                    $this->stock_model->update_stock_bulk($this->branch, $inv_items);

                    $this->db->trans_complete();
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Delivery Order created successfully";
                    $json["url"] = base_url("reservation/view/" . (decorate_code($invoice->do_id, "do", $this->prefixes)));
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function make_invoice()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");

            $pay_day = $this->input->post("pay_day");
            $installments = $this->input->post("installments");

            $this->load->model("invoice_model");
            $this->load->model("wl_customer_model");
            $this->load->model("installment_detail_model", 'idm');

            $invoice = $this->invoice_model->get($id);

            $this->db->trans_start();

            $down_payment = $invoice->down_payment;

            $pending_payments = $this->invoice_model->get_pending_paymetns($invoice->customer);
            $customer = $this->wl_customer_model->get($invoice->customer);
            if ($customer->approved == "1") {
                if (count($pending_payments) > 0) {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = 'Cannot Make the Reservation.<br/>There is another invoice pending for this Customer.';
                } else {
                    try {
                        $inv_id = $this->invoice_model->get_next_invoice_id($this->branch);
                        $balance = doubleval($invoice->balance);
                        $this->invoice_model->update($id, array(
                            "inv_id" => $inv_id,
                            "last_edit_at" => date("Y-m-d H:i:s"),
                            "last_edit_by" => $this->user->id,
                            "inv_created_on" => date("Y-m-d H:i:s"),
                            "inv_created_by" => $this->user->id,
                            "status" => 4,
                        ));

                        $installment_amount = ($installments);

                        $_installment_count = $balance / $installment_amount;
                        $installment_count = ceil($_installment_count);

//                    $next_intallment_date = date_plaus_days(date("Y-m-$pay_day"), 30);
                        $next_intallment_date = date("Y-m-$pay_day", strtotime('+1 month'));

                        $first_installment_date = $this->input->post("first_installment_date");
                        if (!empty($first_installment_date)) {
                            $next_intallment_date = $first_installment_date;
                        }

                        $all_done = ($balance == 0 ? 1 : 0);

                        $this->idm->insert(array(
                            "inv_id" => $id,
                            "installment_count" => $installment_count,
                            "installment_amount" => ($installment_amount),
                            "next_installment_date" => $next_intallment_date,
                            "installment_day" => $pay_day,
                            "all_done" => $all_done
                        ));
                        $this->db->trans_complete();
                        $json["msg_type"] = "OK";
                        $json["msg"] = "Invoice created successfully";
                        $doc_id = decorate_code($invoice->do_id, "do", $this->prefixes);
                        $json["url"] = base_url("reservation/view/" . $doc_id);
                    } catch (Exception $exc) {
                        $this->db->trans_rollback();
                        $json["msg_type"] = "ERR";
                        $json["msg"] = $exc->getTraceAsString();
                    }
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = 'Cannot Make the Reservation.<br/>Customer is Not Approved Yet.';
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

//    public function make_reservation() {
//        $json = array();
//        if ($this->ion_auth->logged_in()) {
//
//            $this->form_validation->set_rules("del_date", "Delivery Date", "trim|required");
//            if ($this->form_validation->run()) {
//
//                $id = $this->input->post("id");
//                $discount = $this->input->post("discount");
//                $customer = $this->input->post("customer");
//                $down_payment = $this->input->post("down_payment");
//                $serive_charge = $this->input->post("serive_charge");
//                $remarks = $this->input->post("remarks");
//                $pay_day = $this->input->post("pay_day");
//                $installments = $this->input->post("installments");
//                $del_date = $this->input->post("del_date");
//
//                $this->load->model("invoice_model");
//                $this->load->model("installment_detail_model", 'idm');
//
//                $invoice = $this->invoice_model->get($id);
//
//                $total = doubleval($invoice->subtotal) - doubleval($invoice->discount) - doubleval($down_payment) - doubleval($serive_charge);
//                $this->db->trans_start();
//
//                $pending_payments = $this->invoice_model->get_pending_paymetns($customer);
//                if (count($pending_payments) > 0) {
//                    $json["msg_type"] = "ERR";
//                    $json["msg"] = 'Cannot Make the Reservation.<br/>There is another invoice pending for this Customer.';
//                } else {
//                    try {
//                        $this->invoice_model->update($id, array(
//                            "customer" => $customer,
//                            "down_payment" => $down_payment,
//                            "service_charge" => $serive_charge,
//                            "balance" => $total,
//                            "total" => $total,
//                            "remarks" => $remarks,
//                            "delivery_date" => $del_date,
//                            "last_edit_at" => date("Y-m-d H:i:s"),
//                            "last_edit_by" => $this->user->id,
//                            "is_cash" => 0,
//                            "status" => 3,
//                        ));
//                        // $installment_amount = $total / intval($installments);
//                        $installment_amount = ($installments);
//                        $this->idm->insert(array(
//                            "inv_id" => $id,
//                            "installment_count" => $installments,
//                            "installment_amount" => ($installment_amount),
//                            "next_installment_date" => $del_date,
//                            "installment_day" => $pay_day,
//                            "all_done" => 0
//                        ));
//                        $this->db->trans_complete();
//                        $json["msg_type"] = "OK";
//                        $json["msg"] = "Delivery Order created successfully";
//                    } catch (Exception $exc) {
//                        $this->db->trans_rollback();
//                        $json["msg_type"] = "ERR";
//                        $json["msg"] = $exc->getTraceAsString();
//                    }
//                }
//            } else {
//                $json["msg_type"] = "ERR";
//                $json["msg"] = validation_errors();
//            }
//        } else {
//            $json["msg_type"] = "LOG";
//            $json["msg"] = "Login Session Expired. Try Again.";
//        }
//        echo json_encode($json);
//    }

    public function load_cancellation_form()
    {
        $inv_id = $this->input->post("inv_id");
        $type = $this->input->post("type");

        $this->load->model("invoice_model");
        $this->load->model("invoice_item_model");
        $this->load->model("invoice_payment_model", 'ipm');
        $invoice = $this->invoice_model->get($inv_id);
        $invoice_items = $this->invoice_item_model->get_items($inv_id);
        $invoice_payments = $this->ipm->get_payments($inv_id);

        $total_paid = 0;
        foreach ($invoice_payments as $ins_data) {
            $total_paid += doubleval($ins_data->payment);
        }

        $total_paid += doubleval($invoice->down_payment);

        $this->load->view("invoice/reservation/cancellation_form",
            array(
                'items' => $invoice_items,
                "invoice" => $invoice,
                "total_paid" => $total_paid,
                "type" => $type
            )
        );
    }
}
