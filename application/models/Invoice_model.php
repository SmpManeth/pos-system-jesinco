<?php

/**
 * Description of Invoice_model
 *
 * @author DP4
 * Aug 20, 2018 12:49:57 PM
 */
class Invoice_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "invoices";
    }

    public function save_invoice($id, $do_number, $branch, $user)
    {
        $invoice = $this->get($id);
        $data = array(
            "inv_date" => $this->input->post("inv_date"),
            "customer" => $this->input->post("customer"),
            "do_number" => $do_number,
            "last_edit_by" => $user->id,
            "last_edit_at" => date("Y-m-d H:i:s"),
            "branch" => $branch->id,
        );
        if ($invoice) {
            $this->update($id, $data);
            return array($id, $invoice->inv_id);
        } else {
            $inv_display_id = $this->get_next_do_number($branch);
            $data["do_id"] = $inv_display_id;
            $data["created_at"] = date("Y-m-d H:i:s");
            $data["subtotal"] = 0;
            $data["discount"] = 0;
            $data["total"] = 0;
            $data["created_by"] = $user->id;
            $data["status"] = 0;
        }
        $invoive_id = $this->insert($data);
        return array($invoive_id, $inv_display_id);
    }

    public function get_next_invoice_id($branch)
    {
        $this->db->select("MAX(inv_id) as inv");
        $max = $this->get_by("branch", $branch->id);
        $max_inv = intval($max->inv) + 1;
        return $max_inv;
    }

    public function get_next_do_number($branch)
    {
        $this->db->select("MAX(do_id) as inv");
        $max = $this->get_by("branch", $branch->id);
        $max_inv = intval($max->inv) + 1;
        return $max_inv;
    }

    public function get_invoices($branch, $limit = FALSE, $offset = FALSE, $count = FALSE)
    {
        $this->db->select("invoices.*,wl_customers.customer_prefix,wl_customers.customer_name,users.username");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("users", "invoices.last_edit_by = users.id", "LEFT");
        if ($limit) {
            $this->db->limit($limit, ($offset * $limit));
        }
        if ($count) {
            return $this->count_by("invoices.branch", $branch->id);
        } else {
            $this->db->where("invoices.branch", $branch->id);
            return $this->get_all();
        }
    }

    public function update_total($inv, $_total, $direction)
    {
        $total = round($_total, 2);
        if ($direction == 1) {
            $this->db->set("total", "total+" . $total, FALSE);
            $this->db->set("subtotal", "subtotal+" . $total, FALSE);
            $this->db->set("balance", "balance+" . $total, FALSE);
        } else {
            $this->db->set("total", "total-" . $total, FALSE);
            $this->db->set("subtotal", "subtotal-" . $total, FALSE);
            $this->db->set("balance", "balance-" . $total, FALSE);
        }
        $this->db->where("id", $inv);
        $this->db->update("invoices");
    }

    public function get_invoice_by_invoice_id($branch, $id)
    {
        $this->db->select("invoices.*,users.username");
        $this->db->join("users", "invoices.last_edit_by = users.id", "LEFT");
        $this->db->where("invoices.branch", $branch->id);
        return $this->get_by("invoices.inv_id", $id);
    }

    public function get_invoice_by_id($id, $type = FALSE)
    {
        $this->db->select("invoices.*,users.username,wl_doc_codes.prefix");
        $this->db->join("users", "invoices.last_edit_by = users.id", "LEFT");

        if ($type == 2) {
            $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');
        }
        if ($type == 6) {
            $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='do'", 'left');
        }

        return $this->get_by("invoices.id", $id);
    }

    public function get_invoice($id)
    {
        $this->db->select("invoices.*,users.username,wl_customers.customer_prefix,wl_customers.customer_name");
        $this->db->join("users", "invoices.last_edit_by = users.id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        return $this->get_by("invoices.id", $id);
    }

    public function get_do_by_do_id($branch, $id)
    {
        $this->db->select("invoices.*,users.username");
        $this->db->join("users", "invoices.last_edit_by = users.id", "LEFT");
        $this->db->where("invoices.branch", $branch->id);
        return $this->get_by("invoices.do_id", $id);
    }

    public function get_all_invoices($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $count = FALSE, $devision_id = "", $search = "")
    {
        $this->db->select("invoices.*,wl_customers.customer_prefix,wl_customers.customer_name,users.username");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("users", "invoices.last_edit_by = users.id", "LEFT");

        $this->db->where("invoices.branch", $branch->id);
        $this->db->where_in("invoices.status", array(4, 5, 1, 2));
        if ($devision_id != "") {
            $this->db->where("wl_customers.devision_id", $devision_id);
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like("invoices.inv_id", $search, FALSE);
            $this->db->or_like("invoices.do_number", $search, FALSE);
            $this->db->or_like("wl_customers.customer_name", $search, FALSE);
            $this->db->group_end();
        }
        if ($count) {
            return $this->count_by("invoices.branch", $branch->id);
        } else {
            if ($length) {
                $this->db->limit($length, $start);
            }
            if ($column) {
                $this->db->order_by($column, $direction);
            } else {
                $this->db->order_by("id", "DESC");
            }
            return $this->get_all();
        }
    }

    public function get_all_reservations($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $count = FALSE, $user = FALSE, $search = "")
    {
        $this->db->select("invoices.*,wl_customers.customer_prefix,wl_customers.customer_name,u1.username,u2.first_name as created");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("users as u1", "invoices.last_edit_by = u1.id", "LEFT");
        $this->db->join("users as u2", "invoices.created_by = u2.id", "LEFT");

        $this->db->where("invoices.branch", $branch->id);
        $this->db->where_in("invoices.status", array(0, 3, 6));
        if ($user) {
            $this->db->where('invoices.created_by', $user);
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like("invoices.inv_id", $search, FALSE);
            $this->db->or_like("invoices.do_number", $search, FALSE);
            $this->db->or_like("wl_customers.customer_name", $search, FALSE);
            $this->db->group_end();
        }
        if ($count) {
            return $this->count_by("invoices.branch", $branch->id);
        } else {
            if ($length) {
                $this->db->limit($length, $start);
            }
            if ($column) {
                $this->db->order_by($column, $direction);
            } else {
                $this->db->order_by("id", "DESC");
            }
            return $this->get_all();
        }
    }

    public function get_all_dos($start, $end, $sp)
    {
        $this->db->select("invoices.*,devisions.devision,invoice_items.rate,invoice_items.qty");
        $this->db->select("wl_customers.customer_name,wl_customers.nic,wl_customers.tp1");
        $this->db->select("u2.username as created,items.itm_code,items.itm_name,wl_doc_codes.prefix");
        $this->db->select("branches.branch_name,invoice_returns.id as ret_id");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("users as u2", "invoices.created_by = u2.id", "LEFT");
        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", "LEFT");
        $this->db->join("items", "invoice_items.itm_id = items.id", "LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->join("branches", "invoices.branch=branches.id", 'left');
        $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='do'", 'left');
        $this->db->join("invoice_returns", "invoices.id=invoice_returns.inv_id", 'left');

        if ($sp) {
            $this->db->where_in("invoices.created_by", $sp);
        }

        $this->db->where_in("invoices.status", array(0, 3, 4, 6)); //

        if (!empty($start) && !empty($end)) {
            $this->db->where("invoices.inv_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("invoices.inv_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("invoices.inv_date", $end);
            }
        }

        return $this->get_all();
    }

    public function get_all_invoice($sp, $start, $end)
    {
        $this->db->select("invoices.*,wl_customers.*,devisions.devision,invoice_items.rate,invoice_items.qty");
        $this->db->select("u2.first_name as created,items.itm_code,items.itm_name");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("users as u2", "invoices.created_by = u2.id", "LEFT");
        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", "LEFT");
        $this->db->join("items", "invoice_items.itm_id = items.id", "LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");

        if ($sp) {
            $this->db->where("invoices.created_by", $sp);
        }
        $this->db->where_in("invoices.status", array(4, 5, 1, 2));

        if (!empty($start) && !empty($end)) {
            $this->db->where_in("invoices.created_at BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("invoices.created_at", $start);
            }
            if (!empty($end)) {
                $this->db->where("invoices.created_at", $end);
            }
        }

        return $this->get_all();
    }

    public function get_daily_sales($date, $branch)
    {
        $this->db->select("wl_customers.customer_prefix,wl_customers.customer_name,invoices.inv_id,invoices.total,invoices.inv_date,invoices.balance,invoices.is_cash");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->where("invoices.inv_date", $date);
        $this->db->where("invoices.status", 1);
        $this->db->where("invoices.branch", $branch->id);
        return $this->get_all();
    }

    public function get_monthly_sales($year, $month, $branch)
    {
        $this->db->select("invoices.inv_date,invoices.branch,invoices.inv_id,Sum(invoices.total) AS tot,invoices.is_cash");
        $this->db->where("MONTH(inv_date)", $month);
        $this->db->where("YEAR(inv_date)", $year);
        $this->db->where("status", 1);
        $this->db->where("invoices.branch", $branch->id);

        $this->db->group_by("invoices.inv_date,invoices.is_cash");
        return $this->get_all();
    }

    public function get_outstanding_invoices($branch)
    {
        $this->db->select("invoices.id,invoices.inv_id,invoices.branch,invoices.inv_date,invoices.subtotal,invoices.balance,invoices.`status`,invoices.is_cash,wl_customers.customer_prefix,wl_customers.customer_name,wl_customers.tp1");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->where("invoices.branch", $branch->id);
        $this->db->where("invoices.status", 1);
        $this->db->where("invoices.balance>0", NULL, FALSE);
        return $this->get_all();
    }

    public function get_all_branch_sales($year, $month)
    {
        $this->db->select("branches.branch_name,Sum(invoices.total) as tot,invoices.inv_date");
        $this->db->join("branches", "invoices.branch = branches.id", "LEFT");
        $this->db->where("MONTH(invoices.inv_date)", $month);
        $this->db->where("YEAR(invoices.inv_date)", $year);
        $this->db->where("invoices.status", 1);
        $this->db->group_by("branches.id,invoices.inv_date");
        return $this->get_all();
    }

    public function get_all_branch_sales_daily($start, $end)
    {
        $this->db->select("branches.branch_name,branches.branch_name_report,Sum(invoices.total) as tot,invoices.is_cash,invoices.inv_date");
        $this->db->join("branches", "invoices.branch = branches.id", "LEFT");

        $this->db->where("(invoices.status)", 1);
        if (!empty($start) && !empty($end)) {
            $this->db->where("invoices.inv_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        }
        $this->db->group_by(array("branches.id", "invoices.is_cash", "invoices.inv_date"));
        $this->db->order_by("invoices.inv_date");
        return $this->get_all();
    }

    public function search_invoice($start, $end, $inv, $branch)
    {

        $this->db->select("invoices.*,wl_customers.customer_name");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        if (!empty($start) && !empty($end)) {
            $this->db->where("inv_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("inv_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("inv_date", $end);
            }
        }
        if (!empty($inv)) {
            $this->db->where("invoices.inv_id", $inv);
        }
        $this->db->where("invoices.branch", $branch->id);
        return $this->get_all();
    }

    public function get_daily_credit_sales($d, $branch, $prefixes)
    {
        $this->db->select("invoices.*,wl_customers.customer_name");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->where("invoices.inv_date", $d);
        $this->db->where("invoices.branch", $branch->id);
        $this->db->where("invoices.is_cash", 0);
        $this->db->where("invoices.status", 1);
        $_invoices = $this->get_all();
        $invoices = array();
        foreach ($_invoices as $_invoice) {
            $inv_id = decorate_code($_invoice->inv_id, "invoice", $prefixes);
            $data = array(
                "customer" => $_invoice->customer_name,
                "inv_date" => $_invoice->inv_date,
                "inv_id" => $inv_id,
                "total" => $_invoice->total,
                "payment" => 0,
                "is_pay" => 0,
                "balance" => $_invoice->balance
            );
            $invoices[$inv_id][] = $data;
        }
        return $invoices;
    }

    public function get_credit_sales($s, $e, $branch, $prefixes)
    {
        $this->db->select("invoices.*,wl_customers.customer_name");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        if (!empty($s) && !empty($e)) {
            $this->db->where("invoices.inv_date BETWEEN '$s' AND '$e'", NULL, FALSE);
        } else {
            if (!empty($s)) {
                $this->db->where("invoices.inv_date", $s);
            }
            if (!empty($e)) {
                $this->db->where("invoices.inv_date", $e);
            }
        }
        $this->db->where("invoices.branch", $branch->id);
        $this->db->where("invoices.is_cash", 0);
        $this->db->where("invoices.status", 1);
        $this->db->order_by("inv_date");
        $_invoices = $this->get_all();
        $invoices = array();
        foreach ($_invoices as $_invoice) {
            $inv_id = decorate_code($_invoice->inv_id, "invoice", $prefixes);
            $data = array(
                "customer" => $_invoice->customer_name,
                "inv_date" => $_invoice->inv_date,
                "inv_id" => $inv_id,
                "total" => $_invoice->total,
                "payment" => doubleval($_invoice->total) - doubleval($_invoice->balance),
                "is_pay" => 0,
                "balance" => $_invoice->balance
            );
            $invoices[] = $data;
        }
        return $invoices;
    }

    public function get_today($branch, $date, $list = FALSE)
    {
        if ($list) {
            $this->db->select("SUM(total) as tot");
        }
        $this->db->where("invoices.inv_date", $date);
        $this->db->where("invoices.status", 1);
        if ($list) {
            return $this->get_by("invoices.branch", $branch->id)->tot;
        } else {
            $this->db->where("invoices.branch", $branch->id);
            return $this->get_all();
        }
    }

    public function get_due_payments($branch)
    {
        $this->db->select("invoices.id,invoices.inv_id,invoices.branch,invoices.inv_date,invoices.total,invoices.balance,installment_details.next_installment_date,");
        $this->db->select("installment_details.installment_count,wl_customers.customer_prefix,wl_customers.customer_name,installment_details.installment_amount,wl_customers.devision_id");
        $this->db->join("installment_details", "invoices.id = installment_details.inv_id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->where("invoices.status", "4");
        $start = date("Y-m-d", strtotime("+3 days"));
        $this->db->where("installment_details.next_installment_date <= ", $start);
        $this->db->where("invoices.branch", $branch->id);
        $this->db->order_by("next_installment_date", "ASC");
        return $this->get_all();
    }

    public function get_c24_list($branch)
    {
        $this->db->select("invoices.id,invoices.inv_id,invoices.branch,invoices.inv_date,invoices.total,invoices.balance,installment_details.next_installment_date,invoices.c24_remarks");
        $this->db->select("installment_details.installment_count,wl_customers.customer_prefix,wl_customers.customer_name,installment_details.installment_amount");
        $this->db->select("COUNT(invoice_payments.id) as paymetns");
        $this->db->join("installment_details", "invoices.id = installment_details.inv_id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("invoice_payments", "invoices.id = invoice_payments.inv_id AND invoice_payments.`status`=1", "LEFT");
        $start_2_month = date("Y-m-d", strtotime("-60 days"));
        $start_month = date("Y-m-d");

        $this->db->where("invoices.status", "4");
        if ($branch->main_branch == "0") {
            $this->db->where("invoices.branch", $branch->id);
        }

        $this->db->having("installment_details.next_installment_date <= ", $start_2_month);
        $this->db->or_having("((paymetns =0 OR paymetns =1) AND installment_details.next_installment_date < '$start_month')", NULL);
        $this->db->order_by("next_installment_date", "ASC");
        $this->db->group_by("invoices.id");
        return $this->get_all();
    }

    public function get_pending_paymetns($customer)
    {
        $this->db->where("invoices.customer", $customer);
        $this->db->where_in("invoices.status", array(4));
        return $this->get_all();
    }

    public function get_users($branch)
    {
        $this->db->select("users.id,users.username,users.first_name,users.last_name");
        $this->db->join("users", "invoices.created_by=users.id", 'left');
        $this->db->group_by('users.id');
        $this->db->where('invoices.branch', $branch->id);
        return $this->get_all();
    }

    public function get_c_c_invoices($branch = false, $type)
    {
        $this->db->select("invoices.*,invoices.id as ii_id,users.username,users.first_name,users.last_name,branches.branch_name,wl_doc_codes.prefix");
        $this->db->join("branches", "invoices.branch=branches.id", 'left');
        if ($type == 2 || $type == 1) {
            $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');
        }
        if ($type == 6) {
            $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='do'", 'left');
        }
        $this->db->join("users", "invoices.created_by=users.id", 'left');
        if ($branch) {
            $this->db->where('invoices.branch', $branch->id);
        }
        $this->db->where('invoices.status', $type);
        $this->db->where('invoices.cancel_approved', 0);
        return $this->get_all();
    }

    public function get_due_bills($count)
    {
	$this->db->select("invoices.id as i_id,invoices.inv_id,invoices.inv_date,invoices.created_at,invoices.inv_created_on");
        $this->db->select("wl_customers.tp2,wl_customers.tp1,installment_details.next_installment_date");
// For C24 Filter
        $this->db->select("COUNT(invoice_payments.id) as payment_count,installment_details.installment_count");

        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("installment_details", "invoices.id = installment_details.inv_id", 'left');
// For C24 Filter
        $this->db->join("invoice_payments", "invoices.id = invoice_payments.inv_id AND invoice_payments.`status`=1", "LEFT");

        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");

        $this->db->where('invoices.balance > 0', NULL, FALSE);
        $this->db->where_in("invoices.status", [4]);

        $day_before_3 = date("Y-m-d", strtotime(date("Y-m-d") . "+3 days"));
        $this->db->where('installment_details.next_installment_date', $day_before_3);

        $this->db->group_by(array('invoice_payments.inv_id', 'invoices.id'));

        return $this->get_all();
    }

    public function get_due_bill_report($s, $b, $d)
    {
        $this->db->select("invoices.*,invoices.id as ii_id,branches.branch_name,wl_doc_codes.prefix,items.itm_code");
        $this->db->select("installment_details.next_installment_date,installment_details.installment_amount");
        $this->db->select("branches.branch_name,devisions.devision,wl_customers.customer_name,wl_customers.customer_prefix");
// For C24 Filter
        $this->db->select("COUNT(invoice_payments.id) as paymetns");

        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("items", "invoice_items.itm_id = items.id", 'left');
        $this->db->join("installment_details", "invoices.id = installment_details.inv_id", 'left');
// For C24 Filter
        $this->db->join("invoice_payments", "invoices.id = invoice_payments.inv_id AND invoice_payments.`status`=1", "LEFT");

        $this->db->join("branches", "invoices.branch=branches.id", 'left');
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');

        $this->db->where('invoices.balance > 0', NULL, FALSE);
        $this->db->where_in("invoices.status", [4]);
        if ($b) {
            $this->db->where_in('invoices.branch', $b);
        }
        if ($d) {
            $this->db->where_in("wl_customers.devision_id", $d);
        }
        if (!empty($s)) {
            $month_before = date("Y-m-d", strtotime(date("Y-m-d", strtotime($s)) . "-1 month"));
            $this->db->group_start();
            $this->db->where('installment_details.next_installment_date', $s);
            $this->db->or_where('installment_details.next_installment_date', $month_before);
            $this->db->group_end();
        } else {
            $this->db->where("installment_details.next_installment_date >= '" . date("Y-m-d") . "'", NULL, FALSE);
        }

        $this->db->group_by(array('invoice_payments.inv_id', 'invoices.id'));

        return $this->get_all();
    }

    public function get_live_due_bill_report($b, $d)
    {
        $this->db->select("invoices.*,invoices.id as ii_id,wl_doc_codes.prefix,items.itm_code");
        $this->db->select("installment_details.next_installment_date,installment_details.installment_amount");
        $this->db->select("wl_customers.customer_name,wl_customers.customer_prefix");
        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("items", "invoice_items.itm_id = items.id", 'left');
        $this->db->join("installment_details", "invoices.id = installment_details.inv_id", 'left');
        // $this->db->join("branches", "invoices.branch=branches.id", 'left');
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        // $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');

        $this->db->where('invoices.balance > 0', NULL, FALSE);
        $this->db->where_in('invoices.status', array(4), FALSE);
        if ($b) {
            $this->db->where_in('invoices.branch', $b);
        } else {
            $this->db->where_in("wl_customers.devision_id", $d);
        }
        return $this->get_all();
    }

    public function payment_complete_bills_report($s, $e, $b)
    {
        $this->db->select("invoices.*,branches.branch_name,wl_doc_codes.prefix");
        $this->db->select("branches.branch_name,wl_customers.customer_name,wl_customers.customer_prefix");
        $this->db->select("ip1.pay_date,ip1.payment,users.username,items.itm_code");

        $this->db->join("invoice_payments as ip1", "ip1.id = (select max(id) from invoice_payments as ip2 where ip2.inv_id =invoices.id)", 'left');


        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("items", "invoice_items.itm_id = items.id", 'left');
        $this->db->join("branches", "invoices.branch=branches.id", 'left');
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');
        $this->db->join("users", "ip1.added_by = users.id", "LEFT");

        $this->db->where('invoices.balance = 0', NULL, FALSE);
        if ($b) {
            $this->db->where_in('invoices.branch', $b);
        }
        if (!empty($s) and !empty($e)) {
            $this->db->where("ip1.pay_date BETWEEN '$s' AND '$e'", NULL, FALSE);
        } else {
            if (!empty($s)) {
                $this->db->where("ip1.pay_date", $s);
            }
            if (!empty($e)) {
                $this->db->where("ip1.pay_date", $e);
            }
        }
        return $this->get_all();
    }

    public function monthly_return_bills_report($s, $e, $b, $type = 'inv')
    {
        $this->db->select("invoices.*,wl_doc_codes.prefix,items.itm_code,u1.username as returned,u2.username as created");
        $this->db->select("branches.branch_name,wl_customers.customer_name,wl_customers.customer_prefix");

        $this->db->join("branches", "invoices.branch=branches.id", 'left');
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");

        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("items", "invoice_items.itm_id = items.id", 'left');
        $this->db->join("users as u1", "invoices.last_edit_by = u1.id", 'left');
        $this->db->join("users as u2", "invoices.created_by = u2.id", 'left');

        if ($type == 'inv') {
            $this->db->where('invoices.status', 2);
            $this->db->where('invoices.refund > 0', NULL, FALSE);
            $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='invoice'", 'left');
        }
        if ($type == 'do') {
            $this->db->where('invoices.status', 6);
            $this->db->join("wl_doc_codes", "invoices.branch=wl_doc_codes.branch AND wl_doc_codes.doc='do'", 'left');
        }
        if ($b) {
            $this->db->where_in('invoices.branch', $b);
        }
        if (!empty($s) and !empty($e)) {
            $this->db->where("DATE(invoices.last_edit_at) BETWEEN '$s' AND '$e'", NULL, FALSE);
        } else {
            if (!empty($s)) {
                $this->db->where("DATE(invoices.last_edit_at)", $s);
            }
            if (!empty($e)) {
                $this->db->where("DATE(invoices.last_edit_at)", $e);
            }
        }
        return $this->get_all();
    }

    public function bill_issue_summary_report($sp, $s, $e, $b)
    {
        $this->db->select("invoices.*,dc1.prefix as inv_prefix,dc2.prefix as do_prefix,items.itm_code,invoice_items.rate");
        $this->db->select("u1.username,u2.username as do_issuer,wl_customers.customer_name,wl_customers.customer_prefix");

        $this->db->join("users as u1", "invoices.inv_created_by=u1.id", 'left');
        $this->db->join("users as u2", "invoices.created_by=u2.id", 'left');
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("items", "invoice_items.itm_id = items.id", 'left');


        $this->db->where_in('invoices.status', [4, 5, 1]);
        $this->db->join("wl_doc_codes as dc1", "invoices.branch=dc1.branch AND dc1.doc='invoice'", 'left');
        $this->db->join("wl_doc_codes as dc2", "invoices.branch=dc2.branch AND dc2.doc='do'", 'left');
        if ($sp) {
            $this->db->where('invoices.inv_created_by', $sp);
        }
        if ($b) {
            $this->db->where('invoices.branch', $b);
        }
        if (!empty($s) and !empty($e)) {
            $this->db->where("(invoices.inv_created_on) BETWEEN '$s' AND '$e'", NULL, FALSE);
        } else {
            if (!empty($s)) {
                $this->db->where("(invoices.inv_created_on)", $s);
            }
            if (!empty($e)) {
                $this->db->where("(invoices.inv_created_on)", $e);
            }
        }
        return $this->get_all();
    }

    public function do_summary_report($sp, $s, $e)
    {
        $this->db->select("invoices.*,dc2.prefix as do_prefix,dc1.prefix as inv_prefix,items.itm_code,invoice_items.rate");
        $this->db->select("users.username,wl_customers.customer_name,wl_customers.customer_prefix");
        $this->db->select("wl_customers.nic,wl_customers.tp1,branches.branch_name,devisions.devision");


        $this->db->join("users", "invoices.created_by=users.id", 'left');
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("items", "invoice_items.itm_id = items.id", 'left');
        $this->db->join("branches", "invoices.branch=branches.id", 'left');
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");


        // $this->db->where_not_in('invoices.status', [2,6]);
        $this->db->join("wl_doc_codes as dc1", "invoices.branch=dc1.branch AND dc1.doc='invoice'", 'left');
        $this->db->join("wl_doc_codes as dc2", "invoices.branch=dc2.branch AND dc2.doc='do'", 'left');
        if ($sp) {
            $this->db->where_in('invoices.created_by', $sp);
        }
        if (!empty($s) and !empty($e)) {
            $this->db->where("DATE(invoices.created_at) BETWEEN '$s' AND '$e'", NULL, FALSE);
        } else {
            if (!empty($s)) {
                $this->db->where("DATE(invoices.created_at)", $s);
            }
            if (!empty($e)) {
                $this->db->where("DATE(invoices.created_at)", $e);
            }
        }
        return $this->get_all();
    }

    public function get_approved_invoices($b, $s, $e, $type)
    {
        $this->db->select("invoices.*,dc1.prefix as inv_prefix,dc2.prefix as do_prefix,items.itm_code,invoice_items.rate");
        $this->db->select("u1.username as returned_by,wl_customers.customer_name,wl_customers.customer_prefix");
        $this->db->select("installment_details.next_installment_date,u2.username as approved_user,u3.username as created_user");


        $this->db->join("users as u1", "invoices.last_edit_by=u1.id", 'left');
        $this->db->join("users as u2", "invoices.approved_by=u2.id", 'left');
        $this->db->join("users as u3", "invoices.created_by=u3.id", 'left');
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("invoice_items", "invoices.id = invoice_items.inv_id", 'left');
        $this->db->join("items", "invoice_items.itm_id = items.id", 'left');


        $this->db->where('invoices.status', $type);
        $this->db->where('invoices.cancel_approved', 1);

        $this->db->join("wl_doc_codes as dc1", "invoices.branch=dc1.branch AND dc1.doc='invoice'", 'left');
        $this->db->join("wl_doc_codes as dc2", "invoices.branch=dc2.branch AND dc2.doc='do'", 'left');
        $this->db->join("installment_details", "invoices.id=installment_details.inv_id", 'left');
        if ($b) {
            $this->db->where('invoices.branch', $b);
        }
        if ($type == 1 || $type == 2) {

            if (!empty($s) and !empty($e)) {
                $this->db->where("(invoices.approve_date) BETWEEN '$s' AND '$e'", NULL, FALSE);
            } else {
                if (!empty($s)) {
                    $this->db->where("(invoices.approve_date)", $s);
                }
                if (!empty($e)) {
                    $this->db->where("(invoices.approve_date)", $e);
                }
            }
        } else {
            if (!empty($s) and !empty($e)) {
                $this->db->where("(invoices.approve_date) BETWEEN '$s' AND '$e'", NULL, FALSE);
            } else {
                if (!empty($s)) {
                    $this->db->where("(invoices.approve_date)", $s);
                }
                if (!empty($e)) {
                    $this->db->where("(invoices.approve_date)", $e);
                }
            }
        }
        return $this->get_all();
    }

}
