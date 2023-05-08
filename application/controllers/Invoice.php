<?php

/**
 * Description of Invoice
 *
 * @author DP4
 * Aug 17, 2018 6:30:38 PM
 */
class Invoice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->all();
    }

    public function new_invoice()
    {
        if ($this->ion_auth->logged_in()) {

            $this->data["head"] = "New Invoice";
            $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "New");

            $this->load->model("wl_customer_model");
            $this->load->model("stock_model");
            $items = $this->stock_model->get_stock($this->branch);
            $customers = $this->wl_customer_model->get_all_customers($this->branch);

            $this->data["items"] = $items;
            $this->data["customers"] = $customers;

            $this->load_view(array("invoice/reservation/new"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function all()
    {
        if ($this->ion_auth->logged_in()) {

            $this->data["head"] = "Invoices";
            $this->data["breadcrums"] = array(array("home", "Home"), "Invoce");

            $this->load->model("invoice_model");
            $this->load->model("devision_model");
            $limit = 10;
            $offset = $this->input->get("p");
//            $invoices = $this->invoice_model->get_invoices($this->branch, $limit, $offset);

//            $this->data["invoices"] = $invoices;
            $this->data["devisions"] = $this->devision_model->get_devisions($this->branch);

            $this->load_view(array("invoice/all"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function view($_id = FALSE, $type = FALSE)
    {
        if ($this->ion_auth->logged_in()) {
            if (ctype_digit($_id)) {
                $id = $_id;
            } else {
//                $decorated_id = $this->uri->segment(3);
                $id = undecorate_code($_id);
            }

            if (!empty($id)) {

                $this->data["head"] = $type == "do" ? "DO Number" : "Invoice Number";
                $this->data["type"] = $type;
                $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), array("invoice/reservation", "DO Notes"), "Edit");

                $this->load->model("invoice_model");
                if (ctype_digit($_id)) {
                    $invoice = $this->invoice_model->get_invoice_by_id($id, ($type == "do" ? 6 : 2));
                } else {
                    $invoice = $this->invoice_model->get_invoice_by_invoice_id($this->branch, $id);
                }

                if (!empty($invoice)) {
                    if ($invoice->status == "1" || $invoice->status == "4" || $invoice->status == "5" || $invoice->status == "2" || ctype_digit($_id)) {
                        $this->load->model("wl_customer_model");
                        $this->load->model("invoice_item_model");
                        $this->load->model("repair_model");
                        $this->load->model("invoice_payment_model", "ipm");

                        $invoice_items = $this->invoice_item_model->get_items($invoice->id);
                        $customer = $this->wl_customer_model->get_customer($invoice->customer);
                        $payments = $this->ipm->get_payments($invoice->id);

                        $_paid_amount = 0;
                        foreach ($payments as $payment) {
                            $_paid_amount += doubleval($payment->payment);
                        }

                        if ($type == "do") {
                            $this->data["doc_id"] = decorate_code($invoice->do_id, "do", $this->prefixes);
                        } else {
                            $this->data["doc_id"] = decorate_code($invoice->inv_id, "invoice", $this->prefixes);
                        }
                        $this->data["branch"] = $this->branch;
                        $this->data["customer"] = $customer;
                        $this->data["total_paid_amount"] = $_paid_amount;
                        $this->data["invoice"] = $invoice;
                        $this->data["invoice_items"] = $invoice_items;
                        $this->data["payments"] = $payments;
                        $this->data["repair"] = $this->repair_model->get_by('inv_id', $invoice->id);

                        $this->load_view(array("invoice/view"));
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

    public function due_payments()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Invoices Due Payments";
            $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "Due Payments");

            $this->load->model("invoice_model");
            $due_payments = $this->invoice_model->get_due_payments($this->branch);

            $this->load->model("devision_model");
            $devisions = $this->devision_model->get_devisions($this->branch);
            $this->data["devisions"] = $devisions;

            $this->data["due_payments"] = $due_payments;
            $this->load_view(array("invoice/due_payments"));
        } else {
            redirect("login");
        }
    }

    public function c24()
    {
        if ($this->ion_auth->logged_in()) {

            $this->data["head"] = "C24 List";
            $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "C24 Invoice List");

            $this->load->model("invoice_model");
            $due_payments = $this->invoice_model->get_c24_list($this->branch);


            $this->data["due_payments"] = $due_payments;
            $this->load_view(array("invoice/c24_list"));
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
                $invoice = $this->invoice_model->get_invoice_by_invoice_id($this->branch, $id);
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

                        $this->data["doc_id"] = decorate_code($invoice->inv_id, "invoice", $this->prefixes);
                        $this->data["items"] = $items;
                        $this->data["customers"] = $customers;
                        $this->data["invoice"] = $invoice;
                        $this->data["invoice_items"] = $invoice_items;

                        $this->load_view(array("invoice/reservation/edit"));
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

//    public function view() {
//        if ($this->ion_auth->logged_in()) {
//            $decorated_id = $this->uri->segment(3);
//            $id = undecorate_code($decorated_id);
//
//            if (!empty($id)) {
//
//                $this->data["head"] = "Invoice Payments/Returns";
//                $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "Edit");
//
//                $this->load->model("invoice_model");
//                $invoice = $this->invoice_model->get_invoice_by_invoice_id($this->branch, $id);
//                if (!empty($invoice)) {
//                    if ($invoice->status == "1" || $invoice->status == "2" || $invoice->status == "3") {
//                        $this->load->model("wl_customer_model");
//                        $this->load->model("invoice_item_model");
//                        $this->load->model("invoice_payment_model", "ipm");
//
//                        $invoice_items = $this->invoice_item_model->get_items($invoice->id);
//                        $customer = $this->wl_customer_model->get($invoice->customer);
//                        $payments = $this->ipm->get_payments($invoice->id);
//
//                        $this->data["doc_id"] = decorate_code($invoice->inv_id, "invoice", $this->prefixes);
//                        $this->data["customer"] = $customer;
//                        $this->data["invoice"] = $invoice;
//                        $this->data["invoice_items"] = $invoice_items;
//                        $this->data["payments"] = $payments;
//
//                        $this->load_view(array("invoice/view"));
//                    } else {
//                        redirect(base_url("invoice/edit/" . $decorated_id));
//                    }
//                } else {
//                    $this->load_view(array("nothing"));
//                }
//            } else {
//                redirect(base_url("invoice"));
//            }
//        } else {
//            redirect(base_url("login"));
//        }
//    }

    public function search()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Invoice Search";
            $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "Search");
            $this->load_view(array("invoice/search"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function search_results()
    {

        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Invoice Search Results";
            $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "Search Results");

            $this->load->model("invoice_model");
            $start = $this->input->get("start");
            $to = $this->input->get("to");
            $_inv = $this->input->get("inv");
            $inv = undecorate_code($_inv);
            $invoices = $this->invoice_model->search_invoice($start, $to, $inv, $this->branch);

            $this->data["invoices"] = $invoices;
            $this->load_view(array("invoice/search_result"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function payments()
    {
        if ($this->ion_auth->logged_in()) {
            $decorated_id = $this->uri->segment(3);
            $id = undecorate_code($decorated_id);

            if (!empty($id)) {

                $this->data["head"] = "Invoice Payments/Returns";
                $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "Edit");

                $this->load->model("invoice_model");
                $invoice = $this->invoice_model->get_invoice_by_invoice_id($this->branch, $id);
                if (!empty($invoice)) {
                    if ($invoice->status == "1" || $invoice->status == "4") {
                        $this->load->model("wl_customer_model");
                        $this->load->model("invoice_item_model");
                        $this->load->model("invoice_payment_model", "ipm");

                        $invoice_items = $this->invoice_item_model->get_items($invoice->id);
                        $customer = $this->wl_customer_model->get_customer($invoice->customer);
                        $payments = $this->ipm->get_payments($invoice->id);

                        $this->data["doc_id"] = decorate_code($invoice->inv_id, "invoice", $this->prefixes);
                        $this->data["customer"] = $customer;
                        $this->data["invoice"] = $invoice;
                        $this->data["invoice_items"] = $invoice_items;
                        $this->data["payments"] = $payments;

                        $this->load_view(array("invoice/payments"));
//                    } else if ($invoice->status == "2") {
//                        redirect(base_url("invoice/view/" . $decorated_id));
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

    public function inbound()
    {
        if ($this->ion_auth->logged_in()) {

            $this->data["head"] = "Inbound Invoice";
            $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "Inbound");
            $this->load->model("inbound_item_model", "iim");

            $this->load->model("stock_model");
            $items = $this->stock_model->get_stock($this->branch);
            $this->data["items"] = $items;

            $inbound_items = $this->iim->get_latest_items($this->branch, 250);
            $this->data["inbound_items"] = $inbound_items;
            $this->load_view(array("invoice/inbound"));
        } else {
            redirect(base_url("login"));
        }
    }

    public function print_invoice()
    {
        if ($this->ion_auth->logged_in()) {
            $decorated_id = $this->uri->segment(3);
            $id = undecorate_code($decorated_id);

            if (!empty($id)) {

                $this->data["head"] = "Invoice Payments/Returns";
                $this->data["breadcrums"] = array(array("home", "Home"), array("invoice", "Invoce"), "Edit");

                $this->load->model("invoice_model");
                $invoice = $this->invoice_model->get_invoice_by_invoice_id($this->branch, $id);
                if (!empty($invoice)) {
                    if ($invoice->status == "1" || $invoice->status == "3" || $invoice->status == "4" || $invoice->status == "2") {

                        $this->load->model("wl_customer_model");
                        $this->load->model("invoice_item_model");
                        $this->load->model("invoice_payment_model", "ipm");
                        $this->load->model("installment_detail_model", "idm");
                        $invoice_installment_data = $this->idm->get_invoice_installments($invoice->id);

                        $invoice_items = $this->invoice_item_model->get_items($invoice->id);
                        $customer = $this->wl_customer_model->get($invoice->customer);
                        $payments = $this->ipm->get_payments($invoice->id);

                        $_paid_amount = 0;
                        foreach ($payments as $payment) {
                            $_paid_amount += doubleval($payment->payment);
                        }

                        $this->load->library('F_pdf');
                        $pdf = new My_pdf("P", "mm", array(80, 160));
                        $pdf->set_is_devided(FALSE);
                        $pdf->AcceptPageBreak();
                        $pdf->SetAutoPageBreak(true, 00);
                        $pdf->set_footer(FALSE);

                        $pdf->AddFont('Consolas', '', 'consola.php');
                        $pdf->AddFont('Consolas', 'B', 'consolab.php');

                        $columns = ["Code", "Name", "Qty", "Rate", "Total"];
                        $widths = [15, 15, 12, 15, 22];
                        $text_direction = ["L", "L", "R", "R", "R"];
                        $pdf->lMargin = 0;
                        $pdf->rMargin = 0;
                        $pdf->tMargin = 5;

                        $pdf->AddPage();
                        $pdf->SetFont('Times', 'B', 18);
                        $pdf->Cell(0, 6, $this->branch->branch_name_report, "0", 1, "C");
                        $pdf->SetFont('Times', '', 8);
                        $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
                        if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                            $pdf->Cell(0, 6, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
                        } else {
                            $pdf->Cell(0, 1, "", "B", 1, "C");
                        }

                        if ($invoice->status == "2") {
                            $pdf->SetDash(0.5, 1);
                            $pdf->Cell(0, 3, "", "0", 1, "L");
                            $pdf->SetFont('Times', '', 14);
                            $pdf->Cell(0, 6, "INVOICE CANCELLED", 1, 1, "C");
                        }
                        $pdf->SetFont('Times', '', 8);

                        $pdf->SetFont('Consolas', '', 10);
                        $pdf->Cell(2, 3, "", "0", 1, "L");
                        $pdf->Cell(2, 5, "", "0", 0, "L");
                        $pdf->Cell(38, 5, "Customer : " . ($customer->customer_prefix . " " . $customer->customer_name), "0", 1, "L");
                        $pdf->Cell(2, 5, "", "0", 0, "L");
                        $pdf->Cell(38, 5, "Invoice : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes), "0", 0, "L");
                        $pdf->Cell(38, 5, "Date : " . $invoice->inv_date, "0", 1, "R");

                        $pdf->SetDash(0.5, 1);
                        $pdf->Cell(0, 3, "", 0, 1);
                        $pdf->SetFont('Consolas', '', 9);
                        $pdf->FancyTable_header($columns, $widths, 5, $text_direction, FALSE, FALSE, "BT");

                        $height = 4;
                        foreach ($invoice_items as $inv_item) {
                            $total = doubleval($inv_item->display_qty) * doubleval($inv_item->display_rate);

                            $pdf->Cell(0, 2, "", 0, 1, "R");
                            $pdf->Cell(15, $height, $inv_item->itm_code, 0, 0);
                            $pdf->Cell(65, $height, $inv_item->itm_name, 0, 1);
                            $pdf->Cell(42, $height, intval($inv_item->display_qty) . " , ", 0, 0, "R");
                            $pdf->Cell($widths[3], $height, $inv_item->display_rate, 0, 0, "R");
                            $pdf->Cell($widths[4], $height, is_zero($total), 0, 1, "R");
                        }
                        $pdf->Cell(0, 3, "", "B", 1);

                        $pdf->Cell(0, 2, "", 0, 1, "R");
                        $pdf->Cell(57, 4, "SUBTOTAL", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice->subtotal), 0, 1, "R");
                        $pdf->Cell(57, 4, "DISCOUNT", 0, 0, "R");
                        $pdf->Cell(22, 4, ($invoice->discount) . "%", 0, 1, "R");

                        $pdf->Cell(57, 4, "DOWN PAYMENT", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice->down_payment), 0, 1, "R");

                        $pdf->Cell(57, 4, "SERVICE CHARGE", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice->service_charge), 0, 1, "R");

                        $pdf->Cell(57, 4, "TOTAL", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice->total), 0, 1, "R");

                        $pdf->Cell(57, 4, "PAID AMOUNT", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($_paid_amount), 0, 1, "R");


                        if ($invoice->status == "2") {
                            $pdf->Cell(57, 4, "UNPAID FINES", 0, 0, "R");
                            $pdf->Cell(22, 4, is_zero($invoice->unpaid_fines), 0, 1, "R");

                            $pdf->Cell(57, 4, "DAMAGE DEDUCTION", 0, 0, "R");
                            $pdf->Cell(22, 4, is_zero($invoice->damaged_deduction), 0, 1, "R");

                            $pdf->Cell(57, 4, "REFUND", 0, 0, "R");
                            $pdf->Cell(22, 4, is_zero($invoice->refund), 0, 1, "R");
                        }

                        $pdf->Cell(0, 2, "", 0, 1, "R");
                        if (doubleval($invoice->balance) > 0) {
                            $pdf->Cell(0, 1, "", "T", 1);
                            $pdf->Cell(57, 4, "DUE AMOUNT", 0, 0, "R");
                            $pdf->Cell(22, 4, is_zero($invoice->balance), 0, 1, "R");
                            $pdf->Cell(0, 2, "", 0, 1, "R");
                        }
                        $pdf->Cell(0, 0.5, "", "B", 1);
                        $pdf->Cell(0, 4, "", "0", 1);

                        $pdf->Cell(57, 4, "INSTALLMENT AMOUNT", 0, 0, "R");
                        $pdf->Cell(22, 4, ($invoice_installment_data->installment_amount), 0, 1, "R");

                        $pdf->Cell(57, 4, "INSTALLMENTS", 0, 0, "R");
                        $pdf->Cell(22, 4, ($invoice_installment_data->installment_count), 0, 1, "R");

                        if ($invoice->status !== "1") {
                            $pdf->Cell(57, 4, "NEXT INSTALLMENT", 0, 0, "R");
                            $pdf->Cell(22, 4, ($invoice_installment_data->next_installment_date), 0, 1, "R");
                        }

                        $pdf->Cell(57, 4, "INSTALLMENT DAY", 0, 0, "R");
                        $pdf->Cell(22, 4, ($invoice_installment_data->installment_day), 0, 1, "R");

                        $pdf->Cell(0, 0.5, "", "B", 1);
                        $pdf->Cell(0, 0.5, "", "B", 1);
                        $pdf->Cell(0, 4, "", "0", 1);

                        $pdf->Cell(0, 8, "*** Thank You for your business ***", "B", 1, "C");
                        $pdf->SetFont('Consolas', '', 7);
                        $pdf->Cell(0, 8, date("Y") . " - Powered by The iDea Hub", "0", 1, "C");
                        $pdf->Output("filenme.pdf", "I");
                    } else if ($invoice->status == "2") {
                        redirect(base_url("invoice/view/" . $decorated_id));
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

    public function print_receipt()
    {
        if ($this->ion_auth->logged_in()) {
            $id = $this->uri->segment(3);

            if (!empty($id)) {

                $this->load->model("invoice_model");
                $this->load->model("invoice_payment_model", "ipm");
                $invoice_payment = $this->ipm->get($id);
                $invoice = $this->invoice_model->get($invoice_payment->inv_id);
                if (!empty($invoice)) {
                    if ($invoice->status == "1" || $invoice->status == "3" || $invoice->status == "4") {

                        $this->load->model("wl_customer_model");
                        $this->load->model("invoice_item_model");

                        $this->load->model("installment_detail_model", "idm");
                        $invoice_installment_data = $this->idm->get_invoice_installments($invoice->id);

                        $invoice_items = $this->invoice_item_model->get_items($invoice->id);
                        $customer = $this->wl_customer_model->get($invoice->customer);
                        $payments = $this->ipm->get_payments($invoice->id);

                        $this->load->library('F_pdf');
                        $pdf = new My_pdf("P", "mm", array(80, 160));
                        $pdf->set_is_devided(FALSE);
                        $pdf->AcceptPageBreak();
                        $pdf->SetAutoPageBreak(true, 00);
                        $pdf->set_footer(FALSE);


                        $pdf->AddFont('Consolas', '', 'consola.php');
                        $pdf->AddFont('Consolas', 'B', 'consolab.php');

                        $columns = array("Code", "Name", "Qty", "Rate", "Total");
                        $widths = [15, 15, 12, 15, 22];
                        $text_direction = ["L", "L", "R", "R", "R"];
                        $pdf->lMargin = 0;
                        $pdf->rMargin = 0;
                        $pdf->tMargin = 5;

                        $pdf->AddPage();
                        $pdf->SetFont('Times', 'B', 18);
                        $pdf->Cell(0, 6, $this->branch->branch_name_report, "0", 1, "C");
                        $pdf->SetFont('Times', '', 8);
                        $pdf->Cell(0, 3, $this->branch->address_po_box . "," . $this->branch->address_line1 . "," . $this->branch->address_line2 . "," . $this->branch->address_city, "0", 1, "C");
                        if (!empty($this->branch->tp1) || !empty($this->branch->tp2)) {
                            $pdf->Cell(0, 6, $this->branch->tp1 . " ," . $this->branch->tp2, "B", 1, "C");
                        } else {
                            $pdf->Cell(0, 1, "", "B", 1, "C");
                        }
                        $pdf->SetFont('Consolas', '', 10);
                        $pdf->Cell(2, 3, "", "0", 1, "L");
                        $pdf->Cell(2, 5, "", "0", 0, "L");
                        $pdf->Cell(38, 5, "Customer : " . ($customer->customer_prefix . " " . $customer->customer_name), "0", 1, "L");
                        $pdf->Cell(2, 5, "", "0", 0, "L");
                        $pdf->Cell(38, 5, "Invoice : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes), "0", 0, "L");
                        $pdf->Cell(38, 5, "Date : " . $invoice->inv_date, "0", 1, "R");

                        $pdf->SetDash(0.5, 1);

                        $pdf->Cell(0, 0.5, "", "B", 1);
                        $pdf->Cell(0, 4, "", "0", 1);

                        $pdf->Cell(57, 4, "PAYMENT", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice_payment->payment), 0, 1, "R");

                        $pdf->Cell(57, 4, "PAID DATE", 0, 0, "R");
                        list($paid_date, $paid_time) = explode(" ", $invoice_payment->pay_date);
                        $pdf->Cell(22, 4, ($paid_date), 0, 1, "R");

                        if (doubleval($invoice_payment->fine) > 0) {
                            $pdf->Cell(57, 4, "FINE", 0, 0, "R");
                            $pdf->Cell(22, 4, is_zero($invoice_payment->fine), 0, 1, "R");
                        }

                        $pdf->Cell(57, 4, "DUE DATE", 0, 0, "R");
                        $pdf->Cell(22, 4, ($invoice_payment->due_date), 0, 1, "R");

                        $pdf->Cell(0, 4, "", "0", 1);


                        $pdf->Cell(0, 3, "", 0, 1);
                        $pdf->SetFont('Consolas', '', 9);
                        $pdf->FancyTable_header($columns, $widths, 5, $text_direction, FALSE, FALSE, "BT");

                        $height = 4;
                        foreach ($invoice_items as $inv_item) {
                            $total = doubleval($inv_item->display_qty) * doubleval($inv_item->display_rate);

                            $pdf->Cell(0, 2, "", 0, 1, "R");
                            $pdf->Cell(15, $height, $inv_item->itm_code, 0, 0);
                            $pdf->Cell(65, $height, $inv_item->itm_name, 0, 1);
                            $pdf->Cell(42, $height, intval($inv_item->display_qty) . " , ", 0, 0, "R");
                            $pdf->Cell($widths[3], $height, $inv_item->display_rate, 0, 0, "R");
                            $pdf->Cell($widths[4], $height, is_zero($total), 0, 1, "R");
                        }
                        $pdf->Cell(0, 2, "", "B", 1);

                        $pdf->Cell(0, 2, "", 0, 1, "R");
                        $pdf->Cell(57, 4, "SUBTOTAL", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice->subtotal), 0, 1, "R");
                        $pdf->Cell(57, 4, "DISCOUNT", 0, 0, "R");
                        $pdf->Cell(22, 4, ($invoice->discount) . "%", 0, 1, "R");

                        $pdf->Cell(57, 4, "DOWN PAYMENT", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice->down_payment), 0, 1, "R");

                        $pdf->Cell(57, 4, "SERVICE CHARGE", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice->service_charge), 0, 1, "R");

                        $pdf->Cell(57, 4, "TOTAL", 0, 0, "R");
                        $pdf->Cell(22, 4, is_zero($invoice->total), 0, 1, "R");

                        $pdf->Cell(0, 2, "", 0, 1, "R");
                        if (doubleval($invoice->balance) > 0) {
                            $pdf->Cell(0, 1, "", "T", 1);
                            $pdf->Cell(57, 4, "DUE AMOUNT", 0, 0, "R");
                            $pdf->Cell(22, 4, is_zero($invoice->balance), 0, 1, "R");
                            $pdf->Cell(0, 2, "", 0, 1, "R");
                        }
                        $pdf->Cell(0, 0.5, "", "B", 1);
                        $pdf->Cell(0, 2, "", "0", 1);

                        $pdf->Cell(57, 4, "INSTALLMENT AMOUNT", 0, 0, "R");
                        $pdf->Cell(22, 4, ($invoice_installment_data->installment_amount), 0, 1, "R");

                        if ($invoice->status !== "1") {
                            $pdf->Cell(57, 4, "NEXT INSTALLMENT", 0, 0, "R");
                            $pdf->Cell(22, 4, ($invoice_installment_data->next_installment_date), 0, 1, "R");
                        }


                        $pdf->Cell(0, 0.5, "", "B", 1);
                        $pdf->Cell(0, 0.5, "", "B", 1);
                        $pdf->Cell(0, 4, "", "0", 1);

                        $pdf->Cell(0, 8, "*** Thank You for your business ***", "B", 1, "C");
                        $pdf->SetFont('Consolas', '', 7);
                        $pdf->Cell(0, 8, date("Y") . " - Powered by The iDea Hub", "0", 1, "C");
                        $pdf->Output("filenme.pdf", "I");
                    } else if ($invoice->status == "2") {
                        redirect(base_url("invoice/view/" . $decorated_id));
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

    #+--------------------------------------------------------------------------+
    #                                                                           |
    #                              AJAX Requests                                |
    #                                                                           |
    #+--------------------------------------------------------------------------+

    public function save_invoice()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $this->form_validation->set_rules("customer", "Customer", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("inv_date", "Invoice Date", "trim|required");
                if ($this->form_validation->run()) {
                    $this->form_validation->set_rules("item", "Item", "trim|required");
                    if ($this->form_validation->run()) {
                        $this->form_validation->set_rules("qty", "Quantity", "trim|required|greater_than[0]");
                        if ($this->form_validation->run()) {
                            $this->form_validation->set_rules("rate", "Rate", "trim|required|greater_than[0]");
                            if ($this->form_validation->run()) {
                                $this->load->model("wl_customer_model", 'cm');
                                $customer = $this->cm->get($this->input->post('customer'));
                                if ($customer) {
                                    $location = json_decode($customer->location);
                                    if (!empty($location->long) || !empty($customer->location_img)) {
                                        $inv_id = $this->input->post("id");
                                        $item = $this->input->post("item");
                                        $qty = $this->input->post("qty");
                                        $rate = $this->input->post("rate");
                                        $do_number = $this->input->post("do_number");

                                        $this->load->model("stock_model");
                                        $this->load->model("invoice_model");
                                        $this->load->model("invoice_item_model", "iim");

                                        $item_with_stock = $this->stock_model->get_stock_item($this->branch, $item);
                                        if (!empty($item_with_stock)) {
                                            $stock_qty = doubleval($item_with_stock->qty);
                                            $qty = doubleval($qty);
                                            if ($stock_qty >= $qty) {
                                                $this->db->trans_start();
                                                try {
                                                    $unit_measure = $this->input->post("unit_measure");
                                                    $display_qty = $this->input->post("qty_display");
                                                    $display_rate = $this->input->post("rate_display");

                                                    $json["display_qty"] = $display_qty ? $display_qty : $qty;
                                                    $json["display_rate"] = $display_qty ? $display_rate : $rate;

                                                    $total = doubleval($qty) * doubleval($rate);
                                                    $display_total = doubleval($json["display_qty"]) * doubleval($json["display_rate"]);
                                                    $json["display_total"] = is_zero($display_total);
                                                    $inv_array = $this->invoice_model->save_invoice($inv_id, $do_number, $this->branch, $this->user);

                                                    $inv_item_id = $this->iim->save_items($inv_array[0], $this->branch, $item_with_stock);
                                                    $this->invoice_model->update_total($inv_array[0], $display_total, 1);

                                                    $json["total"] = is_zero($total);
                                                    $json["inv_id_display"] = decorate_code($inv_array[1], "do", $this->prefixes);
                                                    $json["inv_id"] = $inv_array[0];
                                                    $json["do_number"] = $do_number;
                                                    $json["display_name"] = $item_with_stock->itm_name . "" . ($unit_measure ? " (" . strtoupper($unit_measure) . ")" : "");
                                                    $json["inv_item_id"] = $inv_item_id;
                                                    $json["msg_type"] = "OK";
                                                    if (empty($inv_id)) {
                                                        $this->log_login("invoice", "New Delivery Order Created : " . $json["inv_id_display"]);
                                                    }
                                                    $this->db->trans_complete();
                                                } catch (Exception $exc) {
                                                    $this->db->trans_rollback();
                                                    $json["msg_type"] = "ERR";
                                                    $json["msg"] = $exc->getTraceAsString();
                                                }
                                            } else {
                                                $json["msg_type"] = "ERR";
                                                $json["msg"] = "Product Stock not Enough.<br/>" . ($qty - $stock_qty) . "(s) in Short";
                                            }
                                        } else {
                                            $json["msg_type"] = "ERR";
                                            $json["msg"] = "No Stock Found.";
                                        }
                                    } else {
                                        $json["msg_type"] = "ERR";
                                        $json["msg"] = "Route map to client house has not found.";
                                    }
                                } else {
                                    $json["msg_type"] = "ERR";
                                    $json["msg"] = "Customer Not Found.";
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
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function finish()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");

            $this->load->model("invoice_model");
            $this->load->model("invoice_item_model");

            $invoice = $this->invoice_model->get($id);
            if (!empty($invoice)) {
                $inv_items = $this->invoice_item_model->get_items($id);
                $this->load->model("stock_model");
                $b = $this->stock_model->check_for_availablility($this->branch, $inv_items);
                if ($b[0]) {
                    $_is_cash = $this->input->post("is_cash");
                    $_discount = $this->input->post("discount");
                    $is_cash = 0;

                    if (isset($_discount)) {
                        $discount = doubleval($_discount);
                    } else {
                        $discount = 0;
                    }

                    $is_ok = TRUE;
                    $_paymnet = $this->input->post("payment");
                    $payment = isset($_paymnet) ? doubleval($_paymnet) : 0;
                    if ($is_cash == 1) {
                        $payment = $invoice->total;
                    }
                    $balance = $invoice->total;
                    if ($payment >= $balance) {
                        $payment = $balance;
                    }
                    $this->db->trans_start();
                    try {
                        if ($is_ok) {
                            $data = array(
                                "remarks" => $this->input->post("remarks"),
                                "customer" => $this->input->post("customer"),
                                "status" => 1,
                                "is_cash" => $is_cash
                            );
                            $this->invoice_model->update($id, $data);
                            $this->stock_model->update_stock_bulk($this->branch, $inv_items);
                            if ($payment > 0) {
                                $this->load->model("invoice_payment_model", "ipm");
                                $this->ipm->add_payment($id, ($payment), $this->user, $this->branch);

//                                Mark the Location
//                                $long = $this->input->post("long");
//                                $lat = $this->input->post("lat");
//
//                                $data = array(
//                                    "inv_id" => $inv_id,
//                                    "visited_date" => date("Y-m-d H:i:s"),
//                                    "long" => $long,
//                                    "lat" => $lat,
//                                    "status" => 0,
//                                    "user_id" => $this->user->id,
//                                );
//
//                                $this->load->model("payment_visit_model");
//                                $this->payment_visit_model->insert($data);
                            }
                            $json["msg_type"] = "OK";
                            $json["url"] = base_url("invoice/view/" . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                            $json["inv_print"] = base_url("invoice/print-invoice/" . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                            if ($is_cash == 1) {
                                $this->log_login("invoice", "Invoice Finished as Cash : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                            } else {
                                $this->log_login("invoice", "Invoice Finished as Credit with Payment of " . is_zero($payment, "0.00") . " : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                            }
                        }
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
                $json["msg"] = "Invoice Not Found.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function load_cancel_form()
    {
        $id = $this->input->post("inv_id");
        $this->load->model("invoice_model");
        $this->load->model("invoice_payment_model", 'ipm');
        $invoice = $this->invoice_model->get($id);
        $invoice_payments = $this->ipm->get_payments($id);

        $total_paid = 0;
        foreach ($invoice_payments as $ins_data) {
            $total_paid += doubleval($ins_data->payment);
        }
        $total_paid += doubleval($invoice->down_payment);

        $this->load->view('invoice/cancel_form', array("invoice" => $invoice, "total_paid" => $total_paid,'branch'=>$this->branch));
    }

    public function cancel_invoice()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("inv_id");
            $refund_amount = $this->input->post("total_refund");
            $damaged_fines = $this->input->post("damaged_fines");
            $unpaid_fines = $this->input->post("unpaid_fines");
            $_damaged = $this->input->post("damaged");
            $remark = $this->input->post("remark");

            $this->db->trans_start();
            try {
                $this->load->model("invoice_model");
                $invoice = $this->invoice_model->get($id);

                $damaged = isset($_damaged) && $_damaged == "1" ? 1 : 0;
                if (doubleval($refund_amount) <= $invoice->total) {

                    $this->invoice_model->update($id,
                        array(
                            "status" => 2,
                            "refund" => $refund_amount,
                            "unpaid_fines" => $unpaid_fines,
                            "damaged_deduction" => $damaged_fines,
                            "remarks" => $invoice->remarks . PHP_EOL . ($remark ? ("Retun note" . $remark) : ""),
                            "last_edit_by" => $this->user->id,
                            "last_edit_at" => date("Y-m-d H:i:s")
                        ));

                    if ($damaged) {

                        $this->load->model("invoice_item_model");
                        $items = $this->invoice_item_model->get_items($id);
                        $this->load->model("damage_good_model");
                        foreach ($items as $itm) {

                            $this->damage_good_model->insert(array(
                                "item_id" => $itm->itm_id,
                                "branch_id" => $this->branch->id,
                                "status" => 0,
                                "added_by" => $this->user->id,
                                "create_date" => date("Y-m-d"),
                                "remarks" => $remark,
                            ));
                        }
                    }

                    $json["msg_type"] = "OK";
                    $json["url"] = base_url("invoice/view/" . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                    $this->log_login("invoice", "Invoice Cancelled : " . decorate_code($invoice->inv_id . ($damaged ? "(Marked as Damanged)" : ""), "invoice", $this->prefixes));
                } else {

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

    public function get_invoice_list()
    {
        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));
        $search = ($this->input->get("search"));
        $devision_id = ($this->input->get("devision_id"));

        $this->load->model("invoice_model");
        $invoice_count = $this->invoice_model->get_all_invoices($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE, $devision_id, $search);
        $invoices = $this->invoice_model->get_all_invoices($this->branch, $start, $length, $column, $direction, FALSE, $devision_id, $search);

        $dt_array = array();

        foreach ($invoices as $invoice) {
            $_d = array(
                "cusname" => $invoice->customer_prefix . " " . $invoice->customer_name,
                "plain_inv_no" => $invoice->inv_id,
                "balance" => $invoice->balance,
                "inv_id" => decorate_code($invoice->inv_id, "invoice", $this->prefixes),
                "inv_date" => $invoice->inv_date,
                "do_number" => $invoice->do_number,
                "cancel_approved" => intval($invoice->cancel_approved),
                "username" => $invoice->username,
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

    public function remove_item()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");

            $this->load->model("invoice_model");
            $this->load->model("invoice_item_model");

            $invoice_item = $this->invoice_item_model->get($id);
            if (!empty($invoice_item)) {
                $this->db->trans_start();
                try {
                    $qty = $invoice_item->display_qty;
                    $rate = $invoice_item->display_rate;
                    $total = doubleval($qty) * doubleval($rate);

                    $this->invoice_item_model->delete($id);
                    $this->invoice_model->update_total($invoice_item->inv_id, $total, -1);

                    $json["msg_type"] = "OK";

                    $this->db->trans_complete();
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Invoice Item Not Found.<br/> Please Refresh the page.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function add_payment()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("inv_id");
            $_payment = $this->input->post("amount");
            $fine = $this->input->post("fine");
            $with_fine = $this->input->post("with_fine");
            $due_date = $this->input->post("due_date");
            $installment = $this->input->post("installment");
            $this->load->model("invoice_payment_model", "ipm");
            $this->load->model("invoice_model");

            $long = $this->input->post("long");
            $lat = $this->input->post("lat");

            if ($with_fine == "0") {
                $fine = 0;
            }

            $payment = $_payment - $fine;

            $invoice = $this->invoice_model->get($id);
            $balance = doubleval($invoice->balance);

            if ($balance >= doubleval($payment)) {
                $this->db->trans_start();
                try {

                    $this->load->model("installment_detail_model", "idm");
                    $current_payments = $this->ipm->get_payments($id);
                    $current_payment_count = count($current_payments);

                    $installment_details = $this->idm->get_invoice_installments($id);

                    $invoice_installment_count = intval($installment_details->installment_count);

                    $is_last_payment_ok = TRUE;
                    if (($invoice_installment_count - 1) == $current_payment_count) {
                        if ($balance > (doubleval($payment))) {
                            $is_last_payment_ok = FALSE;
                        }
                    }

                    if ($is_last_payment_ok) {

                        $pay_id = $this->ipm->add_payment($id, $payment, $fine, $installment, $due_date, $this->user, $this->branch);

//                        Mark the Location
                        $long = $this->input->post("long");
                        $lat = $this->input->post("lat");

                        $visit_data = array(
                            "inv_id" => $id,
                            "visited_date" => date("Y-m-d H:i:s"),
                            "long" => $long,
                            "payment_id" => $pay_id,
                            "lat" => $lat,
                            "status" => 1,
                            "user_id" => $this->user->id,
                            "due_date" => $due_date,
                        );

                        $this->load->model("payment_visit_model");
                        $this->payment_visit_model->insert($visit_data);


                        $this->load->model("installment_detail_model", "idm");
                        $invoice_installment_data = $this->idm->get_invoice_installments($id);
                        $newdate = strtotime("+1 month", strtotime($due_date));

                        $next_installment_date = $invoice_installment_data->next_installment_date;

                        $_ins_day = $invoice_installment_data->installment_day;
                        $ins_day = str_pad($_ins_day, 2, "0", STR_PAD_LEFT);

                        if (intval(date("m", strtotime($due_date))) == 1) {
                            $days_on_month = cal_days_in_month(CAL_GREGORIAN, 2, date("Y"));
                            if (intval($_ins_day) > intval($days_on_month)) {
                                $pay_date = date("Y-02-$days_on_month", $newdate);
                            } else {
                                $pay_date = date("Y-02-$ins_day", $newdate);
                            }
                        } else {
                            $pay_date = date("Y-m-$ins_day", $newdate);
                        }


                        if ($balance == doubleval($payment)) {
                            $this->idm->update($invoice_installment_data->id, array("next_installment_date" => $pay_date, "all_done" => 1));
                            $this->invoice_model->update($id, array("status" => 1));
                            $json["complete"] = "YES";
                        } else {
                            $this->idm->update($invoice_installment_data->id, array("next_installment_date" => $pay_date));
                            $json["complete"] = "NO";
                        }

                        $json["with_fine"] = $with_fine;
                        $json["msg_type"] = "OK";
                        $json["balance"] = number_format(($balance - $payment), 2);
                        $json["msg"] = "Payment Saved Successfully.";
                        $this->log_login("invoice", is_zero($payment) . " of Payment added to Invoice  : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));

//                    Send SMS To Customer

                        $this->load->model("wl_customer_model", "cm");
                        $customer = $this->cm->get($invoice->customer);
                        if ($customer) {
                            $tp = ($customer->tp1 != "" ? $customer->tp1 : $customer->tp2);
                            if ($tp) {
                                $inv_no = decorate_code($invoice->inv_id, "invoice", $this->prefixes);
                                $message = "Thank you for payment of Rs. " . number_format($payment, 2)
                                    . " ,Received for Invoice : $inv_no.";
                                if ($fine > 0) {
                                    $message .= "There is fine of Rs. $fine because of the late payment.";
                                }
                                if ($json["complete"] == "NO") {
                                    $message .= "Next Due date is $pay_date";
                                } else {
                                    $message .= "Your invoice is now Complete.";
                                }

                                $json["sms"] = $message;
                                $b = $this->send_sms_dialog($tp, $message, $inv_no);
                                if ($b) {
                                    $json["msg"] .= "<br/>SMS Sent to the Customer";
                                } else {
                                    $json["msg"] .= "<br/>SMS Not sent to the Customer";
                                }
                            } else {
                                $json["msg"] .= "<br/>Can't Send SMS,Cuatomer Phone number not found";
                            }
                        }

                        $this->db->trans_complete();
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "This is the Last Payment. Please Enter the Full Remaining Balance";
                    }
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Paying Amount Exceeds the Balance Amount.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function canel_payment()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->load->model("invoice_payment_model", "ipm");
            $this->load->model("invoice_model");

            $payment = $this->ipm->get($id);
            $invoice = $this->invoice_model->get($payment->inv_id);

            $balance = doubleval($invoice->total);
            if ($balance >= $payment->payment) {
                $this->db->trans_start();
                try {
                    $json = $this->ipm->update($id, array("status" => 2));
                    $this->db->set("balance", "(balance + " . ($payment->payment) . ")", FALSE);
                    $this->db->where("id", $payment->inv_id);
                    $this->db->update("invoices");

                    $this->load->model("installment_detail_model", "idm");
                    $invoice_installment_data = $this->idm->get_invoice_installments($invoice->id);
                    $this->idm->update($invoice_installment_data->id, array("next_installment_date" => $payment->due_date));

                    $balance = is_zero(doubleval($invoice->balance) + doubleval($payment->payment));
                    $this->log_login("invoice", is_zero($payment->payment) . " of Payment deleted from Invoice  : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                    $this->db->trans_complete();
                    $json = array(
                        "msg_type" => "OK",
                        "msg" => "Payment Removed.",
                        "balance" => $balance
                    );
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Cannot Cancel the Paymnet.";
                $json["balance"] = $balance;
                $json["payment"] = $payment->payment;
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function return_item_save()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");
            $qty = $this->input->post("qty");
            $displaY_qty = $this->input->post("displaY_qty");
            $remarks = $this->input->post("remarks");
            $_with_refund = $this->input->post("with_refund");
            $with_refund = isset($_with_refund) && $_with_refund == "1" ? 1 : 0;

            $this->form_validation->set_rules("remarks", "Remarks", "trim|required|max_length[200]");
            if ($this->form_validation->run()) {

                $this->load->model("invoice_model");
                $this->load->model("invoice_item_model", "iim");
                $this->load->model("invoice_return_model", "irm");

                $inv_item = $this->iim->get($id);
                $b = TRUE;
                $secondary = FALSE;
                if (doubleval($inv_item->qty) !== doubleval($inv_item->display_qty)) {
                    $secondary = TRUE;
                    $this->form_validation->set_rules("displaY_qty", "Display Quantity", "trim|required");
                    if (!$this->form_validation->run()) {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = validation_errors();
                        $b = FALSE;
                    }
                }
                if ($b) {
                    $qty_ok = FALSE;
                    if ($secondary) {
                        if (doubleval($inv_item->qty) >= doubleval($qty) && doubleval($inv_item->display_qty) >= doubleval($displaY_qty)) {
                            $qty_ok = TRUE;
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = "Actual or Display Quantity Exceeds the Invoice Quantity...";
                        }
                    } else {
                        if (doubleval($inv_item->qty) >= doubleval($qty)) {
                            $qty_ok = TRUE;
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = "Actual Quantity Exceeds the Invoice Quantity...";
                        }
                    }
                    if ($qty_ok) {
                        $this->db->trans_start();
                        try {
                            $this->irm->save_return(array(
                                "branch" => $this->branch->id,
                                "inv_item_id" => $id,
                                "with_refund" => $with_refund,
                                "item_id" => $inv_item->itm_id,
                                "inv_id" => $inv_item->inv_id,
                                "ret_qty" => $qty,
                                "displaY_qty" => (doubleval($displaY_qty)) == 0 ? $qty : $displaY_qty,
                                "ret_by" => $this->user->id,
                                "ret_date" => date("Y-m-d H:i:s"),
                                "remarks" => $remarks
                            ));
                            $invoice = $this->invoice_model->get($inv_item->inv_id);
                            $this->load->model("stock_model");
                            // Update The Stock 
                            $this->stock_model->update_stock($inv_item->itm_id, $this->branch, $qty, 1, TRUE);
                            // Update Invoice Quantity and Total
                            // If the invoice items has altered item
                            $new_inv_qty = doubleval($inv_item->qty) - doubleval($qty);
                            $deduct_amount = FALSE;
                            if ($secondary) {
                                $new_display_inv_qty = doubleval($inv_item->display_qty) - doubleval($displaY_qty);
                                // Update Invoice Items
                                $this->iim->update($id, array(
                                    "qty" => $new_inv_qty,
                                    "display_qty" => $new_display_inv_qty
                                ));
                                $item_rate = doubleval($inv_item->display_rate);
                                $deduct_amount = $displaY_qty * $item_rate;
                            } else {
                                // If the invoice items not has altered item
                                $this->iim->update($id, array(
                                    "qty" => $new_inv_qty,
                                    "display_qty" => $new_inv_qty
                                ));
                                $item_rate = doubleval($inv_item->rate);
                                $deduct_amount = $qty * $item_rate;
                            }

                            // Update Invoice total
                            if ($with_refund == 0) {
                                $this->invoice_model->update_total($inv_item->inv_id, ($deduct_amount), -1);
                                $inv_bal = doubleval($invoice->balance);
                                if ($inv_bal > 0 && ($deduct_amount > $inv_bal)) {
                                    $this->invoice_model->update($inv_item->inv_id, array("balance" => 0));
                                    $json["balance"] = 0;
                                } else {
                                    $this->invoice_model->update($inv_item->inv_id, array("balance" => ($inv_bal - $deduct_amount)));
                                    $json["balance"] = is_zero($inv_bal - $deduct_amount);
                                }
                            } else {
                                $deduct_amount = 0;
                            }
                            $json["msg_type"] = "OK";


                            $sub_total = doubleval($invoice->subtotal) - $deduct_amount;
                            $total = doubleval($invoice->total) - $deduct_amount;
                            $json["subtotal"] = is_zero($sub_total);
                            $json["total"] = is_zero($total);
                            $this->log_login("invoice", $inv_item->itm_name . " was return from Invoice  : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                            $this->db->trans_complete();
                        } catch (Exception $exc) {
                            $this->db->trans_rollback();
                            $json["msg_type"] = "ERR";
                            $json["msg"] = $exc->getTraceAsString();
                        }
                    }
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

    public function cancel_invoice_return()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->db->trans_start();
            try {
                $id = $this->input->post("id");
                $this->load->model("invoice_model");
                $this->load->model("invoice_item_model");
                $this->load->model("stock_model");
                $this->load->model("invoice_return_model", "irm");

                $ir_item = $this->irm->get($id);

                $this->irm->update($id, array("status" => 2));
                $this->stock_model->update_stock($ir_item->item_id, $this->branch, $ir_item->ret_qty, -1, TRUE);
                $json["msg_type"] = "OK";
                $invoice = $this->invoice_model->get($ir_item->inv_id);
                $invoice_item = $this->invoice_item_model->get($ir_item->inv_item_id);

                $returned_qty = $ir_item->ret_qty;
                $returned_displaY_qty = $ir_item->displaY_qty;
                $secondary = FALSE;
                if (doubleval($ir_item->ret_qty) !== doubleval($ir_item->displaY_qty)) {
                    $secondary = TRUE;
                }

//            Update invoice items
                $this->db->set("qty", "qty+" . $returned_qty, FALSE);
                $this->db->set("display_qty", "display_qty+" . $returned_displaY_qty, FALSE);
                $this->db->where("id", $ir_item->inv_item_id);
                $this->db->update("invoice_items");
//            Update Invoice total and Subtotal
                if ($ir_item->with_refund == "0") {
                    $plus_amo = doubleval($returned_displaY_qty) * doubleval($invoice_item->display_rate);
                    $total = doubleval($invoice->total) + $plus_amo;
                    $sub_total = doubleval($invoice->subtotal) + $plus_amo;
                    $this->db->set("total", $total);
                    $this->db->set("subtotal", $sub_total);
                    $this->db->where("id", $invoice->id);
                    $this->db->update("invoices");
                } else {
                    $plus_amo = 0;
                    $total = doubleval($invoice->total) + $plus_amo;
                    $sub_total = doubleval($invoice->subtotal) + $plus_amo;
                }

                $json["total"] = $total;
                $json["subtotal"] = $sub_total;
                $this->log_login("invoice", "Invoice Item Return Cancelled for " . $invoice_item->itm_name . ". Invoice : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
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

    public function get_returns()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_return_model", "irm");
            $inv_id = $this->input->post("id");
            $ret_items = $this->irm->get_inv_return_items($inv_id);
            $this->load->view("invoice/inv_ret_items", array("ret_items" => $ret_items, "inv_id" => $inv_id));
        } else {
            echo "Please Login";
        }
    }

    public function save_discount()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $_discount = $this->input->post("discount");
            $down_payment = $this->input->post("down_payment");
            $inv_id = $this->input->post("id");

            $this->load->model("invoice_model", "im");

            $invoice = $this->im->get($inv_id);
            $__discount = doubleval($invoice->subtotal) * (doubleval($_discount) / 100);
            $discount = ceil($__discount);
            if ($invoice) {
                $this->db->trans_start();
                try {
                    $service_charge = get_option('service-charge',null,$this->branch->id);
                    $data = array(
                        "discount" => $_discount,
                        "down_payment" => $down_payment,
                        "total" => doubleval($invoice->subtotal) - doubleval($discount) - doubleval($service_charge) - doubleval($down_payment),
                        "balance" => doubleval($invoice->subtotal) - doubleval($discount) - doubleval($service_charge) - doubleval($down_payment)
                    );
                    $this->im->update($inv_id, $data);

                    $json["msg_type"] = "OK";
                    if ($_discount > 0) {
                        $this->log_login("do", $discount . " of Discount Saved For DO Note : " . decorate_code($invoice->do_id, "do", $this->prefixes));
                    }
                    if ($down_payment > 0) {
                        $this->log_login("do", $down_payment . " of Down Payment Saved For DO Note : " . decorate_code($invoice->do_id, "do", $this->prefixes));
                    }
                    $this->db->trans_complete();
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            } else {
                $json["msg_type"] = "OK";
                $json["msg"] = "Invoice Not Found...";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function cancel_finished_invoice()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->db->trans_start();
            try {
                $this->load->model("stock_model");
                $this->load->model("invoice_model");
                $this->load->model("invoice_item_model");
                $this->load->model("invoice_return_model");
                $this->load->model("invoice_payment_model", "ipm");

                $inv_items = $this->invoice_item_model->get_items($id);
                $invoice = $this->invoice_model->get($id);

                $this->invoice_model->update($id, array("status" => 2));
                $this->invoice_return_model->cancel_invoice_return($id);

                foreach ($inv_items as $inv_item) {
                    $this->stock_model->update_stock($inv_item->itm_id, $this->branch, $inv_item->qty, 1, TRUE);
                }
                $this->ipm->cancel_payments($id);

                $json["msg_type"] = "OK";
                $json["msg"] = "Invoice Cancelled";
                $json["url"] = base_url("invoice/view/" . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                $this->log_login("invoice", "Invoice Cancelled : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
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

    public function save_inbound_item()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("inv_date", "Date", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("item", "Item", "trim|required");
                if ($this->form_validation->run()) {
                    $this->form_validation->set_rules("qty", "Quantiry", "trim|required|greater_than[0]");
                    if ($this->form_validation->run()) {
                        $this->load->model("inbound_item_model", "iim");
                        $this->load->model("item_model", "im");
                        $itm_id = $this->input->post("item");
                        $item = $this->im->get($itm_id);
                        $qty = $this->input->post("qty");
                        if ($item) {
                            $this->db->trans_start();
                            try {
                                $data = array(
                                    "use_date" => $this->input->post("inv_date"),
                                    "system_date" => date("Y-m-d H:i:s"),
                                    "itm_id" => $itm_id,
                                    "itm_code" => $item->itm_code,
                                    "itm_name" => $item->itm_name,
                                    "qty" => $qty,
                                    "rate" => $item->cost,
                                    "branch" => $this->branch->id,
                                    "user" => $this->user->id
                                );
                                $id = $this->iim->insert($data);
                                $this->load->model("stock_model");
                                $this->stock_model->update_stock($itm_id, $this->branch, $qty, -1, TRUE);

                                $this->log_login("invoice", "Inbound Invoice Issued : " . "#" . $id . " | " . $item->itm_name);
                                $total = doubleval($data['qty']) * doubleval($data['rate']);
                                $json["msg_type"] = "OK";
                                $row = "<tr>
                                    <td>#$id</td>
                                    <td><span>" . $data['use_date'] . "</span><br/>
                                    <small  class='text-primary'>" . $data['system_date'] . "</small>
                                    </td><td><span>" . $data['itm_name'] . "</span><br/>
                                    <strong  class='text-primary'>" . $data['itm_code'] . "</strong></td>
                                    <td>" . $data['qty'] . "</td>
                                    <td>" . is_zero($data['rate']) . "</td>
                                    <td>" . is_zero($total) . "</td>
                                    <td><button type='button' class='btn btn-link btn-xs' onclick='cancel_inbound_item(" . $id . ")'><i class='fa fa-times text-danger'></i></button></td></tr>";

                                $json["msg"] = "Inbound Invoice Item Added Successfully.";
                                $json["row"] = $row;
                                $this->db->trans_complete();
                            } catch (Exception $exc) {
                                $this->db->trans_rollback();
                                $json["msg_type"] = "ERR";
                                $json["msg"] = $exc->getTraceAsString();
                            }
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = "Item Not Found In Server";
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

    public function cancel_inbound_item()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->load->model("inbound_item_model", "iim");
            $this->load->model("stock_model");

            $inb_item = $this->iim->get($id);
            if ($inb_item->status == "1") {
                $this->db->trans_start();
                try {
                    $this->iim->update($id, array("status" => 2));
                    $this->stock_model->update_stock($inb_item->itm_id, $this->branch, $inb_item->qty, 1, TRUE);

                    $this->log_login("invoice", "Inbound Invoice cancelled : " . "#" . $inb_item->id . " | " . $inb_item->itm_name);

                    $json["msg_type"] = "OK";
                    $json["msg"] = "Inbound Invoice Item Cancelled.";
                    $this->db->trans_complete();
                } catch (Exception $exc) {
                    $this->db->trans_rollback();
                    $json["msg_type"] = "ERR";
                    $json["msg"] = $exc->getTraceAsString();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Inbound Invoice Item cannot Cancelled.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function save_remarks()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->db->trans_start();
            try {
                $id = $this->input->post("id");
                $_remarks = $this->input->post("remarks");
                $this->load->model("invoice_model");
                $remarks = strip_tags($_remarks);
                $invoice = $this->invoice_model->get($id);
                $this->invoice_model->update($id, array("remarks" => $remarks, "last_edit_by" => $this->user->id, "last_edit_at" => date("Y-m-d H:i:s")));
                $this->log_login("invoice", "Invoice Remarks Updated : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                $json["remarks"] = $remarks;
                $json["msg_type"] = "OK";
                $json["msg"] = "Remarks Update.";
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

    public function save_inv_date()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->db->trans_start();
            try {
                $id = $this->input->post("id");
                $_inv_date = $this->input->post("inv_date");
                $this->load->model("invoice_model");
                $inv_date = strip_tags($_inv_date);
                $invoice = $this->invoice_model->get($id);
                $this->invoice_model->update($id, array("inv_date" => $inv_date, "last_edit_by" => $this->user->id, "last_edit_at" => date("Y-m-d H:i:s")));
                $this->log_login("invoice", "Invoice Date Updated : " . decorate_code($invoice->inv_id, "invoice", $this->prefixes));
                $json["remarks"] = $inv_date;
                $json["msg_type"] = "OK";
                $json["msg"] = "Invoice Date Updated.";
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

    public function get_installment_data()
    {
        $id = $this->input->post("id");
        $type = $this->input->post("type");
        $this->load->model("invoice_model");
        $this->load->model("installment_detail_model", "idm");
        $invoice = $this->invoice_model->get($id);
        $invoice_installment_data = $this->idm->get_invoice_installments($id);
        $this->load->view('invoice/installment_data', array("invoice" => $invoice, "invoice_installment_data" => $invoice_installment_data, "type" => $type, "user" => $this->user));
    }

    public function get_payment_data()
    {
        $id = $this->input->post("id");
        $type = $this->input->post("type");
        $this->load->model("invoice_model");
        $this->load->model("installment_detail_model", "idm");
        $this->load->model("fine_model");
        $this->load->model("invoice_payment_model", 'ipm');
        $invoice = $this->invoice_model->get($id);
        $invoice_installment_data = $this->idm->get_invoice_installments($id);
        $fines = $this->fine_model->get_all_fines();
        $invoice_payments = $this->ipm->get_payments($id);

        $this->load->view('invoice/invoice_payment_data',
            array(
                "branch" => $this->branch,
                "user" => $this->user,
                "invoice" => $invoice,
                "invoice_installment_data" => $invoice_installment_data,
                "invoice_payments" => $invoice_payments,
                "fines" => $fines,
                "type" => $type
            ));
    }

    public function get_finish_form()
    {
        $id = $this->input->post("id");
        $this->load->model("invoice_model");
        $this->load->model("installment_detail_model", "idm");
        $this->load->model("invoice_payment_model", 'ipm');
        $this->load->model("fine_model");
        $invoice = $this->invoice_model->get($id);
        $payment = $invoice->balance;
        $invoice_installment_data = $this->idm->get_invoice_installments($id);
        $fines = $this->fine_model->get_all_fines();
        $invoice_payments = $this->ipm->get_payments($id);

        $_buffer_days = get_option('fine-buffer-days', 3,$this->branch->id);
        $buffer_days = intval($_buffer_days);
        $next_date = $invoice_installment_data->next_installment_date;

        $is_late = is_date_greater_than_last(date("Y-m-d", strtotime("-" . ($buffer_days - 1) . " days")), date("Y-m-d", strtotime($next_date)));
        $fine = 0;
//        if ($is_late) {
//
//            $dStart = new DateTime($next_date);
//            $dEnd = new DateTime();
//            $dDiff = $dStart->diff($dEnd);
//            $a = $dDiff->format('%r%m');
//
//            $_fine = isset($fines[0]) ? $fines[0]->fine : 0;
//            $fine = (intval($a) + 1) * $_fine;
//
//            $payment += $fine;
//        }
        $is_next_day_late = false;
        $_n_is_late = is_date_greater_eq_than_last(date("Y-m-d", strtotime("-" . ($buffer_days) . " days")), $next_date);

        if ($is_late) {
//                    Added to calculate fine x late months
            $dStart = new DateTime($next_date);
            $dEnd = new DateTime();
            $dDiff = $dStart->diff($dEnd);
            $a = $dDiff->format('%r%m');

            $_fine = 0;
            foreach ($fines as $fn) {
                $_fine = doubleval($fn->fine);
            }

            $fine = (intval($a+1) ) * $_fine;

        } else {
            if ($_n_is_late) {
                $fine = $fines[0]->fine;

            }
        }
        $payment += $fine;
        $this->load->view('invoice/finish_form', array("invoice" => $invoice, 'payment' => $payment,'fine'=>$fine));
    }

    public function load_payment_form()
    {
        $data = $this->input->post(NULL);
        $data['is_admin'] = $this->is_admin();
        $data['user'] = $this->user;
        $this->load->view('invoice/invoice_payment_form', $data);
    }

    public function finish_with_discount()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->db->trans_start();
            try {
                $id = $this->input->post("id");
                $_discount = $this->input->post("discount");
                $this->load->model("invoice_model");
                $invoice = $this->invoice_model->get($id);

                $discount = doubleval($invoice->subtotal) * (doubleval($_discount) / 100);
                $balance = doubleval($invoice->balance) - (round($discount, 2));
                if ($balance > 0) {
                    $data = array(
                        "discount" => round($_discount, 2),
                        "total" => doubleval($invoice->total) - round($discount, 2),
                        "balance" => 0,
                        "discount_close" => $balance,
                        "status" => 1
                    );


                    $this->load->model("invoice_payment_model", "ipm");
                    $this->load->model("installment_detail_model", "idm");
                    $installments = $this->ipm->get_payments($id);
                    $installment_count = count($installments);
                    $today = date("Y-m-d");
                    $invoice_installment_data = $this->idm->get_invoice_installments($id);
                    $pay_id = $this->ipm->add_payment($id, $balance, 0, ($installment_count + 1), $today, $this->user, $this->branch);
                    $this->idm->update($invoice_installment_data->id, array("next_installment_date" => $today, "all_done" => 1));

//                                Mark the Location
                    $long = $this->input->post("long");
                    $lat = $this->input->post("lat");

                    $visit_data = array(
                        "inv_id" => $id,
                        "visited_date" => date("Y-m-d H:i:s"),
                        "long" => $long,
                        "payment_id" => $pay_id,
                        "lat" => $lat,
                        "status" => 1,
                        "user_id" => $this->user->id,
                    );

                    $this->load->model("payment_visit_model");
                    $this->payment_visit_model->insert($visit_data);


                    $this->invoice_model->update($id, $data);
                    $json["discount"] = $discount;
                    $json["balance"] = $balance;
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Invoice payments finished successfully.";
                    $this->db->trans_complete();
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = "Discount Exceeds the Balance";
                }
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

    public function update_payment_day()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $day = $this->input->post("day");

            $this->load->model("installment_detail_model");
            $this->installment_detail_model->update_day($id, $day);
            $json["msg_type"] = "OK";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function mark_as_visited()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $inv_id = $this->input->post("id");
            $long = $this->input->post("long");
            $lat = $this->input->post("lat");

            $this->load->model('installment_detail_model');

            $i_dt = $this->installment_detail_model->get_invoice_installments($inv_id);

            $data = array(
                "inv_id" => $inv_id,
                "visited_date" => date("Y-m-d H:i:s"),
                "long" => $long,
                "lat" => $lat,
                "status" => 0,
                "user_id" => $this->user->id,
                "due_date" => $i_dt->next_installment_date,
            );

            $this->load->model("payment_visit_model");
            $this->payment_visit_model->insert($data);

            $json["msg_type"] = "OK";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function get_visit_history()
    {
        $id = $this->input->post("id");

        $this->load->model("payment_visit_model");

        $history = $this->payment_visit_model->get_history($id);
        $this->load->view('invoice/visit_history', array("history" => $history));
    }

    public function cancelled_invoices()
    {
        $this->load->model("invoice_model");

        $invoices = $this->invoice_model->get_c_c_invoices($this->branch, 2);
        $this->data["invoices"] = $invoices;
        $this->data["type"] = 'invoices';
        $this->load_view(array('invoice/cancelled_invoices'));
    }

    public function completed_invoices()
    {
        $this->load->model("invoice_model");

        $invoices = $this->invoice_model->get_c_c_invoices($this->branch, 1);
        $this->data["invoices"] = $invoices;
        $this->load_view(array('invoice/completed_invoices'));
    }

    function view_cancelled_invoice()
    {
        $id = $this->uri->segment(3);
        $type = $this->uri->segment(4);
        $this->view($id, $type);
    }

    public function approve_cancellation()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $type = $this->input->post("type");

            $this->load->model("invoice_model");
            $this->invoice_model->update($id, array("cancel_approved" => 1, 'approve_date' => date("Y-m-d"), 'approved_by' => $this->user->id));
            $json["msg_type"] = "OK";
            if ($type == "1") {
                $json["msg"] = "Completion Approved Successfully.";
            } else {
                $json["msg"] = "Cancellation Approved Successfully.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

}
