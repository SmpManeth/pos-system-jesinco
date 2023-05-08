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
 * Description of Payment_visit_model
 *
 * @author Dilshan  Jayasnka
 */
class Payment_visit_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "payment_visits";
    }

    public function get_history($id) {

        $this->db->select("payment_visits.*,users.username");
        $this->db->join("users", "payment_visits.user_id=users.id", "LEFT");
        $this->db->where("payment_visits.inv_id", $id);
        return $this->get_all();
    }
    public function get_collection_bill_report($s,$u) {
        
        $this->db->select("payment_visits.*,invoice_payments.payment,users.username,invoice_payments.due_date");
        $this->db->select("invoices.inv_id,items.itm_code,invoices.balance,invoices.inv_id as invoice_id");
        $this->db->select("wl_customers.customer_name,wl_customers.customer_prefix,devisions.devision");
        $this->db->select("wl_doc_codes.prefix,invoice_payments.pay_date,invoice_payments.fine");

        $this->db->join("invoice_payments","payment_visits.payment_id = invoice_payments.id","LEFT");
        $this->db->join("users","payment_visits.user_id = users.id","LEFT");
        $this->db->join("invoices","payment_visits.inv_id = invoices.id","LEFT");
        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("items", "invoice_items.itm_id = items.id", 'left');
        $this->db->join("wl_customers","invoices.customer = wl_customers.id","LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');

        if ($u) {
            $this->db->where_in('payment_visits.user_id', $u);
        }

        if (!empty($s)) {
            $this->db->where('payment_visits.due_date', $s);
        }
        return $this->get_all();

    }

}
