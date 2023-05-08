<?php

/**
 * Description of invoice_payment_model
 *
 * @author DP4
 * Aug 23, 2018 9:39:11 AM
 */
class Invoice_payment_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "invoice_payments";
    }

    public function add_payment($inv_id, $payment, $fine, $installment, $due_date, $user, $branch) {
        $data = array(
            "branch" => $branch->id,
            "inv_id" => $inv_id,
            "pay_term" => $installment,
            "payment" => $payment,
            "fine" => $fine,
            "due_date" => $due_date,
            "pay_date" => date("Y-m-d H:i:s"),
            "added_by" => $user->id
        );
        $id = $this->insert($data);

        $this->db->set("balance", "(balance - $payment )", FALSE);
        $this->db->where("id", $inv_id);
        $this->db->update("invoices");

        return $id;
    }

    public function cancel_paymnet($payment_id) {
        $payment = $this->get($payment_id);
        if ($payment) {
            $this->update($payment->id, array("status" => 2));

            $this->db->set("balance", "(balance + " . ($payment->payment) . ")", FALSE);
            $this->db->where("id", $payment->inv_id);
            $this->db->update("invoices");
            return array(
                "msg_type" => "OK",
                "msg" => "Payment Cancelled.",
                "balance"
            );
        } else {
            return array("msg_type" => "ERR", "msg" => "No Payment Found.");
        }
    }

    public function cancel_payments($id) {
        $this->db->set("status", 2);
        $this->db->where("inv_id", $id);
        return $this->db->update("invoice_payments");
    }

    public function get_payments($inv_id) {
        $this->db->select("invoice_payments.*,u1.username as cancelled,payment_visits.long,payment_visits.lat");
        $this->db->join("users as u1", "invoice_payments.added_by = u1.id", "LEFT");
        $this->db->join("payment_visits", "invoice_payments.id = payment_visits.payment_id", "LEFT");
        $this->db->where("invoice_payments.status", 1);
        $this->db->where("invoice_payments.inv_id", $inv_id);
        return $this->get_all();
    }

    public function get_invoice_paymnets($d, $branch, $prefixes) {
        $this->db->select("invoice_payments.*,invoices.inv_date,invoices.inv_id,invoices.total,invoices.balance,wl_customers.customer_name");
        $this->db->join("invoices", "invoice_payments.inv_id = invoices.id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");

        $this->db->where("DATE(invoice_payments.pay_date)", $d);
        $this->db->where("invoices.branch", $branch->id);
        $this->db->where("invoices.is_cash", 0);
        $this->db->where("invoices.status", 1);
        $this->db->where("invoice_payments.status", 1);

        $_inv_payments = $this->get_all();
        $inv_payments = array();

        foreach ($_inv_payments as $_payment) {
            $inv_id = decorate_code($_payment->inv_id, "invoice", $prefixes);
            $data = array(
                "customer" => $_payment->customer_name,
                "inv_date" => $_payment->inv_date,
                "inv_id" => $inv_id,
                "total" => $_payment->total,
                "payment" => $_payment->payment,
                "balance" => $_payment->balance,
                "is_pay" => 1
            );
            $inv_payments[$inv_id][] = $data;
        }
        return $inv_payments;
    }

    public function get_payments_report($from,$branch,$devision,$sales_p) {
        $this->db->select("invoice_payments.*,invoices.inv_id as invoice_id,invoices.total,invoices.balance");
        $this->db->select("branches.branch_name,devisions.devision,u1.username,wl_doc_codes.prefix,wl_customers.customer_name,wl_customers.customer_prefix");
        $this->db->select("u1.username");
        $this->db->join("users as u1", "invoice_payments.added_by = u1.id", "LEFT");
        $this->db->join("invoices", "invoice_payments.inv_id = invoices.id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->join("branches", "invoices.branch = branches.id", "LEFT");
        $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');

        $this->db->where("invoice_payments.status", 1);

        if ($sales_p) {
            $this->db->where_in("invoice_payments.added_by", $sales_p);
        }
        if(!empty($from)){
            $this->db->where("DATE(invoice_payments.pay_date) = '$from'",NULL ,FALSE);
        }else{
            $this->db->where("DATE(invoice_payments.pay_date)",date("Y-m-d"));
        }
        
        if(!empty($branch)){
            $this->db->where_in("invoice_payments.branch",$branch);
        }else{
            $this->db->where_in("wl_customers.devision_id",$devision);
        }
        return $this->get_all();
    }

    public function branch_vice_total_collection_report($from,$to,$branch,$devision) {
        $this->db->select("invoice_payments.*,items.itm_code,invoices.total,invoices.balance,branches.branch_name,devisions.devision,u1.username,wl_doc_codes.prefix,wl_customers.customer_name,wl_customers.customer_prefix,invoices.inv_id as invoice_id");
        $this->db->join("users as u1", "invoice_payments.added_by = u1.id", "LEFT");
        $this->db->join("invoices", "invoice_payments.inv_id = invoices.id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->join("invoice_items", "invoice_payments.inv_id = invoice_items.inv_id", "LEFT");
        $this->db->join("items", "invoice_items.itm_id = items.id", "LEFT");
        $this->db->join("branches", "invoices.branch = branches.id", "LEFT");
        $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');

        $this->db->where("invoice_payments.status", 1);

        if (!empty($from) AND !empty($to)) {
            $this->db->where("DATE(invoice_payments.pay_date) BETWEEN '$from' AND '$to'", NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("DATE(invoice_payments.pay_date)", $from);
            }
            if(!empty($to)){
                $this->db->where("DATE(invoice_payments.pay_date)", $to);
            }
        }
        
        if(!empty($branch)){
            $this->db->where_in("invoice_payments.branch",$branch);
        }else{
            $this->db->where_in("wl_customers.devision_id",$devision);
        }
        return $this->get_all();
    }
    public function get_forward_date_collection_bill_report($from,$branch,$devision,$all=FALSE) {
        $this->db->select("invoice_payments.*,invoices.inv_id as invoice_id,items.itm_code,invoices.total,invoices.balance,branches.branch_name,devisions.devision,u1.username,wl_doc_codes.prefix,wl_customers.customer_name,wl_customers.customer_prefix");
        $this->db->join("users as u1", "invoice_payments.added_by = u1.id", "LEFT");
        $this->db->join("invoices", "invoice_payments.inv_id = invoices.id", "LEFT");
        $this->db->join("invoice_items", "invoice_payments.inv_id = invoice_items.inv_id", "LEFT");
        $this->db->join("items", "invoice_items.itm_id = items.id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->join("branches", "invoices.branch = branches.id", "LEFT");
        $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');

        $this->db->where("invoice_payments.status", 1);

        if(!empty($from)){
            $this->db->where("invoice_payments.due_date = '$from'",NULL ,FALSE);
        }else{
            $this->db->where("invoice_payments.due_date",date("Y-m-d"));
        }
        
        if(!empty($branch)){
            $this->db->where_in("wl_customers.branch",$branch);
        }else{
            $this->db->where_in("wl_customers.devision_id",$devision);
        }
        return $this->get_all();
    }
}
