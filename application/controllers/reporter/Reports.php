<?php

/**
 * Description of Reports
 *
 * @author DP4
 * Aug 30, 2018 9:48:25 AM
 */
class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Reports";
            $this->data["breadcrums"] = array(array("home", "Home"), "Purchasing Reports");
            $this->load_view(array("reports/new-reports"));
        } else {
            redirect(base_url("login"));
        }
    }
    public function sales_reports() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Sales Reports";
            $this->data["breadcrums"] = array(array("home", "Home"), "Sales Reports");
            $this->load_view(array("reports/report-home"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function stock() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Stock Reports";
            $this->data["breadcrums"] = array(array("home", "Home"), "Stock Reports");

            $this->data["report_name"] = $this->uri->segment(4);
            
            if($this->data["report_name"]=="stock-movements"){
                $this->data["breadcrums"] = array(array("home", "Home"), "Stock Adjustments");
                $this->load->model("branch_model");
                $branches = $this->branch_model->get_all();
                $this->data["branches"] = $branches;
            }else{
                $this->load->model("item_category_model", 'icm');
                $categories = $this->icm->get_categories($this->branch);


                $this->load->model("branch_model");
                $branches = $this->branch_model->get_all();
                $this->data["branches"] = $branches;
    
                $this->data["categories"] = $categories;
                $this->load->model("item_model");
                $items = $this->item_model->get_items_by_branch($this->branch);
                $this->data["items"] = $items;
            }

            $this->load_view(array("reports/stock-report"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function purchasing() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Purchasing Reports";
                        
            $this->data["report_name"] = $this->uri->segment(4);
            $this->data["breadcrums"] = array(array("home", "Home"), "Purchasing Reports");
            $this->load->model("wl_supplier_model");
            $suppliers = $this->wl_supplier_model->get_all_names_branch_active($this->branch);

            $this->load->model("item_category_model", 'icm');
            $categories = $this->icm->get_categories($this->branch);


            $this->load->model("branch_model");
            $branches = $this->branch_model->get_all();
            $this->data["branches"] = $branches;

            $this->data["categories"] = $categories;
            $this->data["report_name"] = $this->uri->segment(4);
            $this->data["suppliers"] = $suppliers;
            $this->load_view(array("reports/pruchasing-report"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function sales() {
        if ($this->ion_auth->logged_in()) {
            $this->data["report_name"] = $this->uri->segment(4);
            if($this->data["report_name"]=="customers"){
                $this->load->model("wl_employee_model");
                $sales_persons = $this->wl_employee_model->get_all_emps($this->branch);
                $this->data["sales_persons"] = $sales_persons;
                $this->data["head"] = "Customers Reports";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Customers Reports");
            }else if($this->data["report_name"]=="do-summary"){
                $this->load->model("user_model");
                $this->load->model("branch_model");
                $sales_persons = $this->user_model->get_sales_persons();
                $branches = $this->branch_model->get_all();
                $this->data["sales_persons"] = $sales_persons;
                $this->data["branches"] = $branches;
                $this->data["head"] = "Delivery Notes Summary";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Delivery Notes Summary");
            }else if($this->data["report_name"]=="invoice-summary"){
                $this->load->model("wl_employee_model");
                $this->load->model("branch_model");
                $branches = $this->branch_model->get_all();
                $sales_persons = $this->wl_employee_model->get_all_emps($this->branch);
                $this->data["sales_persons"] = $sales_persons;
                $this->data["branches"] = $branches;
                $this->data["head"] = "Invoice Summary";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Delivery Notes Summary");
            }else if($this->data["report_name"]=="credit-bills"){

                $this->load->model("branch_model");
                $this->load->model("devision_model");
                $this->load->model("user_model");
                $branches = $this->branch_model->get_all();
                $devisions = $this->devision_model->get_all_devisions();

                $sales_persons = $this->user_model->get_marketing_manager();
                $this->data["sales_persons"] = $sales_persons;

                $this->data["branches"] = $branches;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "Invoice Summary";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Credit Bill Summary");
            }else if($this->data["report_name"]=="due-bill-summary"){

                $this->load->model("branch_model");
                $this->load->model("devision_model");
                $branches = $this->branch_model->get_all();
                $devisions = $this->devision_model->get_all_devisions();
                $this->data["branches"] = $branches;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "Due bill summery";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Credit Bill Summary");
            }else if($this->data["report_name"]=="live-due-bill-summary"){

                $this->load->model("branch_model");
                $this->load->model("devision_model");
                $branches = $this->branch_model->get_all();
                $devisions = $this->devision_model->get_all_devisions();
                $this->data["branches"] = $branches;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "Live Due bill summery";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Credit Bill Summary");
            }else if($this->data["report_name"]=="collection-bills"){

                $this->load->model("user_model");
                $sales_persons = $this->user_model->get_marketing_manager();
                $this->data["sales_persons"] = $sales_persons;
                $this->data["head"] = "Collection Bills";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Collection Bills Summary");
            }else if($this->data["report_name"]=="forward-date-collection-bills"){

                $this->load->model("branch_model");
                $this->load->model("devision_model");
                $branches = $this->branch_model->get_all();
                $devisions = $this->devision_model->get_all_devisions();
                $this->data["branches"] = $branches;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "Forward date collection bills";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Forward date collection bills");
            }else if($this->data["report_name"]=="branch-vice-total-collection"){

                $this->load->model("branch_model");
                $this->load->model("devision_model");
                $branches = $this->branch_model->get_all();
                $devisions = $this->devision_model->get_all_devisions();
                $this->data["branches"] = $branches;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "Branch vice Total Collection";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") , "Branch vice Total Collection");
            }else if($this->data["report_name"]=="payment-complete-bills"){

                $this->load->model("branch_model");
                $this->load->model("devision_model");
                $branches = $this->branch_model->get_all();
                $devisions = $this->devision_model->get_all_devisions();
                $this->data["branches"] = $branches;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "Payment Complete Bills";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") , "Payment Complete Bills");
            }else if($this->data["report_name"]=="monthly-return-bills"){

                $this->load->model("branch_model");
                $this->load->model("devision_model");
                $branches = $this->branch_model->get_all();
                $devisions = $this->devision_model->get_all_devisions();
                $this->data["branches"] = $branches;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "Monthly return bills";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") , "Monthly return bills");
            }else if($this->data["report_name"]=="do-report"){

                $this->load->model("user_model");
                $sales_persons = $this->user_model->get_sales_persons();
                $this->data["sales_persons"] = $sales_persons;

                $this->data["head"] = "DO Report";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"DO Report");
            }else if($this->data["report_name"]=="return-do-report"){

                $this->load->model("branch_model");
                $this->load->model("devision_model");
                $branches = $this->branch_model->get_all();
                $devisions = $this->devision_model->get_all_devisions();
                $this->data["branches"] = $branches;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "Returned DO Report";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Returned DO Report");
            }else if($this->data["report_name"]=="bill-issue-summary"){

                $this->load->model("user_model");
                $sales_persons = $this->user_model->get_marketing_manager();
                $branches = $this->branch_model->get_all();
                $this->load->model("branch_model");
                $this->data["branches"] = $branches;
                $this->data["sales_persons"] = $sales_persons;
                $this->data["head"] = "Bill issue summery";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") , "Bill issue summery");
            }else if($this->data["report_name"]=="branch-vice-sold-items"){

                $this->load->model("branch_model");
                $branches = $this->branch_model->get_all();
                $this->data["branches"] = $branches;
                $this->data["head"] = "Branch vice sold items";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") ,"Branch vice sold items");
            }else if($this->data["report_name"]=="c24-report"){

                $this->load->model("branch_model");
                $branches = $this->branch_model->get_all();
                $this->data["branches"] = $branches;
                $this->data["head"] = "C24 Report";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") ,"C24 Report");
            }else if($this->data["report_name"]=="completed-invoices"){

                $this->load->model("branch_model");
                $branches = $this->branch_model->get_all();
                $this->data["branches"] = $branches;
                $this->data["head"] = "Completed Invoices (Approved)";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") ,"Cancelled Invoices (Approved)");
            }else if($this->data["report_name"]=="cancelled-invoices"){

                $this->load->model("branch_model");
                $branches = $this->branch_model->get_all();
                $this->data["branches"] = $branches;
                $this->data["head"] = "Cancelled Invoices (Approved)";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") ,"Cancelled DOs (Approved)");
            }else if($this->data["report_name"]=="cancelled-dos"){

                $this->load->model("branch_model");
                $branches = $this->branch_model->get_all();
                $this->data["branches"] = $branches;
                $this->data["head"] = "Cancelled DOs (Approved)";
                $this->data["breadcrums"] = array(array("home", "Home"),array("reporter/reports/sales-reports", "Sales Reports") ,"Completed Invoices (Approved)");
            }else{
                $this->data["head"] = "Sales Reports";
                $this->data["breadcrums"] = array(array("home", "Home"), array("reporter/reports/sales-reports", "Sales Reports") ,"Sales Reports");
            }
            $this->load_view(array("reports/sales-report"));
        } else {
            redirect(base_url("login"));
        }
    }


    public function do_reports() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "D/O Reports";
            $this->data["breadcrums"] = array(array("home", "Home"), "D/O Reports");

            $this->data["report_name"] = $this->uri->segment(4);
            $this->load_view(array("reports/do_reports"));
        } else {
            redirect(base_url("login"));
        }
    }
    public function live_due_bill_summary() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "D/O Reports";
            $this->data["breadcrums"] = array(array("home", "Home"), "D/O Reports");

            $this->data["report_name"] = $this->uri->segment(4);
            $this->load_view(array("reports/do_reports"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function goods_issue() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Goods Issue Reports";
            $this->data["breadcrums"] = array(array("home", "Home"), "Goods Issue Reports");

            $this->load->model("item_category_model", 'icm');
            $categories = $this->icm->get_categories($this->branch);

            $this->data["categories"] = $categories;
            $this->data["report_name"] = $this->uri->segment(4);
            $this->load_view(array("reports/goods_issue"));
        } else {
            redirect(base_url("login"));
        }
    }

}
