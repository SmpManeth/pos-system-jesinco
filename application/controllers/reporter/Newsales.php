<?php

/**
 * Description of Sales
 *
 * @author DP4
 * Aug 30, 2018 10:15:33 AM
 */
class Newsales extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function forward_date_collection_bills() {
        if ($this->ion_auth->logged_in()) {
            // $this->load->model("invoice_model");
            $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $b = $this->input->get("b");
            $d = $this->input->get("d");

            $payments_list = $this->invoice_payment_model->get_forward_date_collection_bill_report($s, $b, $d);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Bill No.", "Customer Name","Branch","Devision", "Item", "Collected On","Fine", "Payment", "Balance"];
            $widths = [7,25, 70, 48, 20, 20, 25,15, 25, 25];
            $text_direction = ["L", "L", "L", "L", "L", "L", "R", "R", "R", "R"];
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

            $pdf->Cell(45, 6, "Due Date Collected Bill Summary", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . (!empty($s)? (date("M d, Y", strtotime($s))) : " From ". date("M d, Y"))) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $fines = 0;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($payments_list as $invoice) {
                $i++;

                $dec_id = $invoice->prefix . str_pad($invoice->invoice_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix ." ". $invoice->customer_name;
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $customer, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->branch_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->devision, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $invoice->itm_code, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, explode(" ",$invoice->pay_date)[0], 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, is_zero($invoice->fine), 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, is_zero($invoice->payment), 1, 0, $text_direction[8]);
                $pdf->Cell($widths[9], $h, is_zero($invoice->balance), 1, 1, $text_direction[9]);

                $fines +=doubleval($invoice->fine);
                $payments +=doubleval($invoice->payment);
                $balances +=doubleval($invoice->balance);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths)-65, $h, "Totals", 1, 0, "R");
            $pdf->Cell(15, $h, is_zero($fines), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($payments), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($balances), 1, 0, "R");
            $pdf->add_signature();

            $pdf->Output("due-payments.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function collection_bills() {
        if ($this->ion_auth->logged_in()) {
            // $this->load->model("invoice_model");
            $this->load->model("payment_visit_model");
            $s = $this->input->get("s");
            $u = $this->input->get("sp");

            $_payments_list = $this->payment_visit_model->get_collection_bill_report($s, $u);
            $payments_list = array();
            foreach ($_payments_list as $value) {
                $payments_list[$value->invoice_id][] = $value;
            }

            // dump($_payments_list);
            // dump($payments_list);
            // exit;

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Bill No.", "Customer Name","Collector","Devision", "Item", "Collected On","Fine", "Payment", "Balance"];
            $widths = [7,25, 65, 48, 25, 20, 25,15, 25, 25];
            $text_direction = ["L", "L", "L", "L", "L", "L", "R", "R", "R", "R"];
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

            $pdf->Cell(45, 6, "Forward Collection Bills", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . (!empty($s)? (date("M d, Y", strtotime($s))) : " From ". date("M d, Y"))) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $fines = 0;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($payments_list as $invoices) {
                $i++;

                $invoice = end($invoices);

                $dec_id = $invoice->prefix . str_pad($invoice->invoice_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix ." ". $invoice->customer_name;
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $customer, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->username, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->devision, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $invoice->itm_code, 1, 0, $text_direction[5]);
                if($invoice->payment == NULL){
                    $pdf->Cell($widths[6], $h, explode(" ",$invoice->due_date)[0], 1, 0, $text_direction[6]);
                    $pdf->Cell($widths[7], $h, ' - ', 1, 0, $text_direction[7]);
                    $pdf->Cell($widths[8], $h, 'Visit Only', 1, 0, 'L');
                }else{
                    $pdf->Cell($widths[6], $h, explode(" ",$invoice->pay_date)[0], 1, 0, $text_direction[6]);
                    $pdf->Cell($widths[7], $h, is_zero($invoice->fine), 1, 0, $text_direction[7]);
                    $fines +=doubleval($invoice->fine);
                    $payments +=doubleval($invoice->payment);
                    $pdf->Cell($widths[8], $h, is_zero($invoice->payment), 1, 0, $text_direction[8]);

                }
                $pdf->Cell($widths[9], $h, is_zero($invoice->balance), 1, 1, $text_direction[9]);
                $balances +=doubleval($invoice->balance);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths)-65, $h, "Totals", 1, 0, "R");
            $pdf->Cell(15, $h, is_zero($fines), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($payments), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($balances), 1, 0, "R");
            $pdf->add_signature();

            $pdf->Output("due-payments.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function branch_vice_total_collection() {
        if ($this->ion_auth->logged_in()) {
            // $this->load->model("invoice_model");
            $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");
            $d = $this->input->get("d");

            $payments_list = $this->invoice_payment_model->branch_vice_total_collection_report($s,$e, $b, $d,TRUE);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Bill No.", "Customer Name","Branch","Item",'Collected on' ,"Due Date", "Total", "Payment"];
            $widths = [8,25, 80, 48, 20, 25,25, 25, 25];
            $text_direction = ["L", "L", "L", "L", "L", "L", "L", "R", "R"];
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

            $pdf->Cell(45, 6, "Forward Collection Bills", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $date_range = !empty($s)? (date("M d, Y", strtotime($s)) . (!empty($e)? (" - ") . date("M d, Y", strtotime($e)) : "") ) :"";
            $pdf->Cell(148, 6, ($date_range) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($payments_list as $invoice) {
                $i++;

                $dec_id = $invoice->prefix . str_pad($invoice->invoice_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix ." ". $invoice->customer_name;
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $customer, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->branch_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->itm_code, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, date("Y-m-d",strtotime($invoice->pay_date)), 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, $invoice->due_date, 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, is_zero($invoice->total), 1, 0, $text_direction[7]);
                $payment = doubleval($invoice->payment) + doubleval($invoice->fine);
                $pdf->Cell($widths[8], $h, is_zero($payment), 1, 1, $text_direction[8]);

                $totals +=doubleval($invoice->total);
                $payments +=doubleval($payment);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths)-50, $h, "Totals", 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($totals), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($payments), 1, 1, "R");
            $pdf->add_signature();

            $pdf->Output("due-payments.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function payment_complete_bills() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            // $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");

            $payments_list = $this->invoice_model->payment_complete_bills_report($s, $e, $b);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Bill No.", "Customer Name","Issue Date","Complete Date","Item", "User", "SubTotal","Discount"];
            $widths = [8,25, 80, 30, 30,22, 33, 25, 25];
            $text_direction = ["L", "L", "L", "L", "L", "L", "L", "R", "R"];
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

            $pdf->Cell(45, 6, "Payment Complete Bills", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . $s .'-'.$e) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($payments_list as $invoice) {
                $i++;

                $dec_id = $invoice->prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix ." ". $invoice->customer_name;
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $customer, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->inv_created_on, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, date("Y-m-d",strtotime($invoice->pay_date)), 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $invoice->itm_code, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, $invoice->username, 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, is_zero($invoice->subtotal), 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, is_zero($invoice->discount), 1, 1, $text_direction[8]);

            }

            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("payments-complete-bills.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function monthly_return_bills() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            // $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");

            $payments_list = $this->invoice_model->monthly_return_bills_report($s, $e, $b);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Bill No.", "Customer Name",'Create By','Cancel By' ,"SubTotal","Initial pay", "Paid Amount", "Damage Ded","Fines","Refund"];
            $widths = [8,25, 70, 25 ,25, 22, 25, 25, 20, 15, 20];
            $text_direction = ["L", "L", "L", "L", "L", "R", "R", "R", "R", "R", "R"];
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

            $pdf->Cell(45, 6, "Return Bills", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . $s .'-'.$e) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($payments_list as $invoice) {
                $i++;
                // "Bill No.", "Customer Name","Total","Down Payment", "Paid Amount", "Balance","Unpaid Fines","Refund"]
                $dec_id = $invoice->prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix ." ". $invoice->customer_name;
                $paid = doubleval($invoice->total) - doubleval($invoice->balance);
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $customer, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, ($invoice->created), 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, ($invoice->returned), 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, is_zero($invoice->subtotal), 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, is_zero($invoice->down_payment), 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, is_zero($paid), 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, is_zero($invoice->damaged_deduction), 1, 0, $text_direction[8]);
                $pdf->Cell($widths[9], $h, is_zero($invoice->unpaid_fines), 1, 0, $text_direction[9]);
                $pdf->Cell($widths[10], $h, is_zero($invoice->refund), 1, 1, $text_direction[10]);

            }

            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("payments-complete-bills.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function return_do_report() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            // $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");

            $payments_list = $this->invoice_model->monthly_return_bills_report($s, $e, $b,'do');

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"D/O ID.", 'Do Num', "Customer Name","Branch","SubTotal","Created By","Returned Date","Item",'Ret By'];
            $widths = [8,25, 15,65, 35, 25, 35, 25, 17,25];
            $text_direction = ["L", "L", "L", "L", "L", "R", "L", "C", "L", "L"];
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

            $pdf->Cell(45, 6, "Return DO Report", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . $s .'-'.$e) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($payments_list as $invoice) {
                $i++;
                // "Bill No.", "Customer Name","Total","Down Payment", "Paid Amount", "Balance","Unpaid Fines","Refund"]
                $dec_id = $invoice->prefix . str_pad($invoice->do_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix ." ". $invoice->customer_name;
                $paid = doubleval($invoice->total) - doubleval($invoice->balance);
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $invoice->do_number, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $customer, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, str_replace(" BRANCH",'',$invoice->branch_name), 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, is_zero($invoice->subtotal), 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, explode(" ",$invoice->created)[0], 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, explode(" ",$invoice->last_edit_at)[0], 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, ($invoice->itm_code), 1, 0, $text_direction[8]);
                $pdf->Cell($widths[9], $h, ($invoice->returned), 1, 1, $text_direction[9]);

            }

            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("payments-complete-bills.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function bill_issue_summary() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            // $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");
            $sp = $this->input->get("sp");

            $this->load->model("branch_model");
            $branch = $this->branch_model->get($b);

            $payments_list = $this->invoice_model->bill_issue_summary_report($sp, $s, $e,$b);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Issue Date", "DO id",'DO Num',"Do Issuer","Invoice id","Issuer","Itm Code","Price","Down pay","Other Pay:","Due Amount"];
            $widths = [8,25, 22,15,40,25,28,20,22,20,25,25];
            $text_direction = ["L", "L", "L", "L", "L", "L", "L", "R", "R", "R", "R", "L"];
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

            $pdf->Cell(45, 6, "Bill Issue Summery", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . $s .'-'.$e) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");
            $pdf->Cell(20, 6, ": " . strtoupper($this->user->username), 0, 0);
            $pdf->SetFont('', '', 10);
            $pdf->Cell(135, 6, ("Branch : " . $branch->branch_name) , "0", 1, "R");
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $price = 0;
            $down_pay = 0;
            $due = 0;
            $discount_close = 0;
            $i=0;

            foreach ($payments_list as $invoice) {
                $i++;
                // "Issue Date", "DO id","Invoice id","Issuer","Item Code","Price","Service Charge","Down Pay","Due Amount","Customer"
                $inv_id = $invoice->inv_prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);
                $do_id = $invoice->do_prefix . str_pad($invoice->do_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix ." ". $invoice->customer_name;
                $paid = doubleval($invoice->total) - doubleval($invoice->balance);

                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $invoice->inv_created_on, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $do_id, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->do_number, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->do_issuer, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $inv_id, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, $invoice->username, 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, $invoice->itm_code, 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, is_zero($invoice->rate), 1, 0, $text_direction[8]);

                $dp = doubleval($invoice->service_charge) + doubleval($invoice->down_payment);
                $pdf->Cell($widths[9], $h, is_zero($dp), 1, 0, $text_direction[9]);
                $pdf->Cell($widths[10], $h, is_zero($invoice->discount_close), 1, 0, $text_direction[9]);
                $pdf->Cell($widths[11], $h, is_zero($invoice->balance), 1, 1, $text_direction[10]);
//                $pdf->SetFont("", "",'9');
//                $pdf->Cell($widths[11], $h, $customer, 1, 1, $text_direction[11]);
//                $pdf->SetFont("", "",'10');

                $price += doubleval($invoice->rate);
                $discount_close += doubleval($invoice->discount_close);
                $due += doubleval($invoice->balance);
                $down_pay += $dp;
            }

            $offset = array_sum($widths) - ($widths[11] *4);
            $pdf->Cell($offset, $h, 'Total', 1, 0, 'R');
            $pdf->Cell($widths[11], $h, is_zero($price), 1, 0, 'R');
            $pdf->Cell($widths[11], $h, is_zero($down_pay), 1, 0, 'R');
            $pdf->Cell($widths[11], $h, is_zero($discount_close), 1, 0, 'R');
            $pdf->Cell($widths[11], $h, is_zero($due), 1, 1);
            
            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("payments-complete-bills.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function do_report() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            // $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $sp = $this->input->get("sp");

            $payments_list = $this->invoice_model->do_summary_report($sp, $s, $e);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"DO Date","DO ID",'Do Num', "Item Code","Sub Total","Date","Inv No"];
            $widths = [8,25, 25, 25, 20, 25, 25, 25];
            $text_direction = ["L", "L", "L", "L", "L", "R", "L","L"];
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

            $pdf->Cell(45, 6, "Bill Issue Summery", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $date_range = !empty($s)? (date("M d, Y", strtotime($s)) . (!empty($e)? (" - ") . date("M d, Y", strtotime($e)) : "") ) :"";
            $pdf->Cell(148, 6, ($date_range) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($payments_list as $invoice) {
                $i++;
                // "DO Date","DO No", "Item Code","Sub Total","Date","Inv No"]
                $do_id = $invoice->do_prefix . str_pad($invoice->do_id, 5, "0", STR_PAD_LEFT);
                $_customer = $invoice->customer_prefix ." ". $invoice->customer_name;
                $customer = preg_replace('/[\s]+/mu', ' ', $_customer);
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $invoice->inv_date, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $do_id, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->do_number, 1, 0, $text_direction[3]);
                
                $pdf->Cell($widths[4], $h, $invoice->itm_code, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, is_zero($invoice->subtotal), 1, 0, $text_direction[5]);

                if(in_array($invoice->status,[4, 5, 1, 2])){
                    $pdf->Cell($widths[6], $h, $invoice->inv_created_on, 1, 0, $text_direction[6]);
                    $inv_id = $invoice->inv_prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);
                    $pdf->Cell($widths[7], $h, $inv_id, 1, 1, $text_direction[7]);
                }else if($invoice->status=="6"){
                    $pdf->Cell($widths[6], $h, explode(" ",$invoice->last_edit_at)[0], 1, 0, $text_direction[6]);
                    $pdf->Cell($widths[7], $h, "return", 1, 1, $text_direction[7]);
                }else{
                    $pdf->Cell($widths[6], $h, "", 1, 0, $text_direction[6]);
                    $pdf->Cell($widths[7], $h, "", 1, 1, $text_direction[7]);
                }
            }

            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("do-report.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function branch_vice_sold_items() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_item_model");
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $b = $this->input->get("b");

            $sold_list = $this->invoice_item_model->get_branch_vice_sold_items($b, $s, $e);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Item Code", "Item Name","Sold Qty","Total","Payments"];
            $widths = [8,30, 68,22,30,30];
            $text_direction = ["L", "L", "L", "R", "R", "R"];
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

            $pdf->Cell(45, 6, "Branch Vice Sold Items", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . $s .'-'.$e) , "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $qty = 0;
            $i=0;
            foreach ($sold_list as $sold_item) {
                $i++;

                $bal = doubleval($sold_item->tot) - doubleval($sold_item->bal);
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $sold_item->itm_code, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $sold_item->itm_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, is_zero($sold_item->sold), 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, is_zero($sold_item->tot), 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, is_zero($bal), 1, 1, $text_direction[5]);

                $qty += doubleval($sold_item->sold);
                $totals += doubleval($sold_item->tot);
                $payments += doubleval($bal);
            }

            $pdf->Ln(2);

            $pdf->Cell((array_sum($widths)-($widths[3] + $widths[4]+$widths[5])), $h, "Totals", 1, 0, "R");
            $pdf->Cell($widths[3], $h, is_zero($qty), 1, 0, "R");
            $pdf->Cell($widths[4], $h, is_zero($totals), 1, 0, "R");
            $pdf->Cell($widths[5], $h, is_zero($payments), 1, 1, "R");

            $pdf->add_signature();

            $pdf->Output("branch-vice-sold-items.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function c24_report() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $this->load->model("wl_doc_code_model");
            $this->load->model("branch_model");
            
            $b = $this->input->get("b");

            $branch = $this->branch_model->get($b);
            $due_payments = $this->invoice_model->get_c24_list($branch);
            $doc_codes = $this->wl_doc_code_model->get_prefixes($b);


            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);
            

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Invoice ID", "Customer", "Invoice Date", "Due Amount", "Fine", "Due Date", "Payment"];
            $widths = [8,22, 68, 23, 20, 15, 20, 20];
            $text_direction = ["L", "L", "L", "L", "R", "R", "L","R"];
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

            $pdf->Cell(45, 6, "C24 Report", 0, 1, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(25, 6, "Branch ");
            $pdf->Cell(114, 6, ": " . strtoupper($branch->branch_name), 0, 1);
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 9);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $fine_total = 0;
            $payments = 0;
            $balances = 0;
            $this->load->model('fine_model');
            $fines = $this->fine_model->get_all_fines();

            $i=0;
            foreach ($due_payments as $due_pay) {
                $i++;

                $doc_id = $doc_codes['invoice']->prefix. str_pad($due_pay->inv_id, $doc_codes['invoice']->length, "0", STR_PAD_LEFT);

                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $doc_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $due_pay->customer_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $due_pay->inv_date, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, is_zero($due_pay->balance), 1, 0, $text_direction[4]);
                

                $dStart = new DateTime($due_pay->next_installment_date);
                $dEnd  = new DateTime();
                $dDiff = $dStart->diff($dEnd);
                $a = $dDiff->format('%r%m');

                $payable_amo = (intval($a)+1) * $due_pay->installment_amount;
                $fine = 0;
                foreach($fines as $fn){
                    if ($a <= doubleval($fn->day)) {
                        $fine = doubleval($fn->fine);
                        break;
                    }
                }
                $fine = (intval($a)+1) * $fine;
                $pdf->Cell($widths[5], $h, $fine, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, $due_pay->next_installment_date, 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, is_zero($payable_amo), 1, 1, $text_direction[7]);
                
                $fine_total += doubleval($fine);
                $balances += doubleval($due_pay->balance);
                $payments += doubleval($payable_amo);
                // $pdf->Cell($widths[5], $h, is_zero($due_pay->installment_amount), 1, 1, $text_direction[5]);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths)-80, $h, "Totals", 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($balances), 1, 0, "R");
            $pdf->Cell(15, $h, ($fine_total), 1, 0, "R");
            $pdf->Cell(15, $h, "", 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($payments), 1, 1, "R");

            $pdf->add_signature();

            $pdf->Output("c24-list.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function completed_invoices() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $this->load->model("wl_doc_code_model");
            $this->load->model("branch_model");
            
            $b = $this->input->get("b");
            $s = $this->input->get("s");
            $e = $this->input->get("e");

            $branch = $this->branch_model->get($b);
            $invoices = $this->invoice_model->get_approved_invoices($b,$s,$e,1);


            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Invoice ID", "Inv Date","Itm Code","Complete Date","Approved by","Date"];
            $widths = [8,30, 30,22,30,40,30];
            $text_direction = ["L", "L", "L", "L", "L", "L","L"];
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

            $pdf->Cell(45, 6, "Completed Invoice Summary", 0, 1, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(25, 6, "Branch ");
            $pdf->Cell(114, 6, ": " . strtoupper($branch->branch_name), 0, 1);
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 9);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($invoices as $invoice) {
                $i++;

                // Invoice ID", "Inv Date","Item Code","Complete Date","Approved by","Date"
                $doc_id = $invoice->inv_prefix. str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);

                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $doc_id, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[2], $h, date("Y-m-d",strtotime($invoice->inv_created_on)), 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->itm_code, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, ($invoice->next_installment_date), 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $invoice->approved_user, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, ($invoice->approve_date), 1, 1, $text_direction[6]);

            }

            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("completed-invoices.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function cancelled_invoices() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $this->load->model("wl_doc_code_model");
            $this->load->model("branch_model");
            
            $b = $this->input->get("b");
            $s = $this->input->get("s");
            $e = $this->input->get("e");

            $branch = $this->branch_model->get($b);
            $invoices = $this->invoice_model->get_approved_invoices($b,$s,$e,2);


            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"Invoice ID", "Inv Date","Item Code","Return Date","Approved by","Date"];
            $widths = [8,30, 30,22,30,40,30];
            $text_direction = ["L", "L", "L", "L", "L", "L","L"];
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

            $pdf->Cell(45, 6, "Cancelled Invoice Summary", 0, 1, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(25, 6, "Branch ");
            $pdf->Cell(114, 6, ": " . strtoupper($branch->branch_name), 0, 1);
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 9);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($invoices as $invoice) {
                $i++;

                // Invoice ID", "Inv Date","Item Code","Complete Date","Approved by","Date"
                $doc_id = $invoice->inv_prefix. str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);

                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $doc_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, date("Y-m-d",strtotime($invoice->created_at)), 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->itm_code, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, date("Y-m-d",strtotime($invoice->last_edit_at)), 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $invoice->approved_user, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, ($invoice->approve_date), 1, 1, $text_direction[6]);

            }

            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("completed-invoices.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
    public function cancelled_dos() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $this->load->model("branch_model");
            
            $b = $this->input->get("b");
            $s = $this->input->get("s");
            $e = $this->input->get("e");

            $branch = $this->branch_model->get($b);
            $invoices = $this->invoice_model->get_approved_invoices($b,$s,$e,6);


            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#',"DO ID",'DO Num', "DO Date", "Ret. By","Itm Code","Complete Date","Approved by","Ret. Date"];
            $widths = [8,25, 20,25,25,17,25,25,25];
            $text_direction = ["L", "L", "L", "L", "L", "L", "L", "L", "L"];
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

            $pdf->Cell(45, 6, "Cancelled Delivery Note Summary", 0, 1, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(25, 6, "Branch ");
            $pdf->Cell(114, 6, ": " . strtoupper($branch->branch_name), 0, 1);
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 9);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i=0;
            foreach ($invoices as $invoice) {
                $i++;

                // Invoice ID", "Inv Date","Item Code","Complete Date","Approved by","Date"
                $doc_id = $invoice->do_prefix. str_pad($invoice->do_id, 5, "0", STR_PAD_LEFT);

                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $doc_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $invoice->do_number, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, date("Y-m-d",strtotime($invoice->created_at)), 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->returned_by, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $invoice->itm_code, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, date("Y-m-d",strtotime($invoice->last_edit_at)), 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, $invoice->approved_user, 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, ($invoice->approve_date), 1, 1, $text_direction[8]);

            }

            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("completed-invoices.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }
}