<?php

/**
 * Description of Sales
 *
 * @author DP4
 * Aug 30, 2018 10:15:33 AM
 */
class Sales extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sales_transactions()
    {
        if ($this->ion_auth->logged_in()) {
            error_reporting(-1);
            ini_set('display_errors', 1);
            $this->load->model("wl_customer_model");
            $from = $this->input->get("from");
            $to = $this->input->get("to");
            $sp = $this->input->get("sp");

            $customers = $this->wl_customer_model->get_all_customer_report($this->branch, $sp, $from, $to);
            // dump($customers);
            $this->load->library('Excel');
            $PHPExcel = new PHPExcel();
            PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            $PHPExcel->setActiveSheetIndex(0);
            $sheet = $PHPExcel->getActiveSheet();

            $styleArray = array(
                'font' => array(
                    'bold' => true,
                    // 'size' => 11,
                    // 'name' => 'Verdana'
                ));

            $columns = ["Customer Name", "Address", "Telephone", "E-mail", "Devision", "Approved", "Location"];

            $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
            $sheet->setCellValue("A1", $this->branch->branch_name);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
            $sheet->setCellValue("A2", "Customer Registration");
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
            $from_to = (!empty($from) && !empty($to) ? ("From : " . $from . " To : " . $to) : (!empty($from) ? $from : (!empty($to) ? $to : "")));
            $sheet->setCellValue("A3", $from_to);

            $sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = 5;
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);

            $currencyFormat = '_(* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';

            for ($idx = 1; $idx <= count($columns); $idx++) {
                $sheet->setCellValue($this->get_Letter($idx) . "$row", $columns[$idx - 1]);
            }
            $sheet->getStyle($this->get_Letter(1) . "$row:" . $this->get_Letter(count($columns)) . "$row")->applyFromArray($styleArray);
            $row++;
            foreach ($customers as $customer) {
                $sheet->setCellValue($this->get_Letter(1) . "" . $row, $customer->customer_prefix . " " . $customer->customer_name);
                $address = [$customer->address_po_box, $customer->address_line1, $customer->address_line2, $customer->address_city];
                $sheet->setCellValue($this->get_Letter(2) . "" . $row, implode(",", $address));
                $sheet->setCellValue($this->get_Letter(3) . "" . $row, implode(",", [$customer->tp1, $customer->tp2]));
                $sheet->setCellValue($this->get_Letter(4) . "" . $row, $customer->email);
                $sheet->setCellValue($this->get_Letter(5) . "" . $row, $customer->devision);
                $sheet->setCellValue($this->get_Letter(6) . "" . $row, $customer->approved == "1" ? "Approved" : "Not Approved");
                $sheet->setCellValue($this->get_Letter(7) . "" . $row, $customer->location ? "Yes" : "N/A");

                $row++;
            }
            $row++;

            // header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="customer-list.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } else {
            redirect(base_url("login"));
        }
    }

    public function customer_list()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("wl_customer_model");
            $status = $this->input->get("s");

            $customers = $this->wl_customer_model->get_all_approved_customers($this->branch, $status);
            // dump($customers);
            $this->load->library('Excel');
            $PHPExcel = new PHPExcel();
            PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            $PHPExcel->setActiveSheetIndex(0);
            $sheet = $PHPExcel->getActiveSheet();

            $styleArray = array(
                'font' => array(
                    'bold' => true,
                    // 'size' => 11,
                    // 'name' => 'Verdana'
                ));

            $columns = ["Customer Name", "Address", "Telephone", "E-mail", "Devision", "Approved", "Location"];

            $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
            $sheet->setCellValue("A1", $this->branch->branch_name);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
            $sheet->setCellValue("A2", "Customer List");
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
            $from_to = (!empty($from) && !empty($to) ? ("From : " . $from . " To : " . $to) : (!empty($from) ? $from : (!empty($to) ? $to : "")));
            $sheet->setCellValue("A3", $from_to);

            $sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = 5;
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);

            $currencyFormat = '_(* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';

            for ($idx = 1; $idx <= count($columns); $idx++) {
                $sheet->setCellValue($this->get_Letter($idx) . "$row", $columns[$idx - 1]);
            }
            $sheet->getStyle($this->get_Letter(1) . "$row:" . $this->get_Letter(count($columns)) . "$row")->applyFromArray($styleArray);
            $row++;
            foreach ($customers as $customer) {
                $sheet->setCellValue($this->get_Letter(1) . "" . $row, $customer->customer_prefix . " " . $customer->customer_name);
                $address = [$customer->address_po_box, $customer->address_line1, $customer->address_line2, $customer->address_city];
                $sheet->setCellValue($this->get_Letter(2) . "" . $row, implode(",", $address));
                $sheet->setCellValue($this->get_Letter(3) . "" . $row, implode(",", [$customer->tp1, $customer->tp2]));
                $sheet->setCellValue($this->get_Letter(4) . "" . $row, $customer->email);
                $sheet->setCellValue($this->get_Letter(5) . "" . $row, $customer->devision);
                $sheet->setCellValue($this->get_Letter(6) . "" . $row, $customer->approved == "1" ? "Approved" : "Not Approved");
                $sheet->setCellValue($this->get_Letter(7) . "" . $row, $customer->location ? "Yes" : "N/A");

                $row++;
            }
            $row++;

            // header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="customer-list.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } else {
            redirect(base_url("login"));
        }
    }

    public function do_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            // $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $sp = $this->input->get("sp");


            $payments_list = $this->invoice_model->get_all_dos($s, $e, $sp);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#', "D/O No.", 'DO Num', "Customer Name", "N I C", "Phone", "Branch", "Devision", "Sales Person", "DO Date", "Item", "Price", "Guarantor"];
            $widths = [8, 20, 12, 50, 22, 20, 30, 15, 25, 20, 12, 18, 25];
            $text_direction = ["L", "L", "L", "L", "L", "L", "L", "L", "L", "L", "L", "R", "L"];
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

            $pdf->Cell(45, 6, "DO Summary", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $date_range = !empty($s) ? (date("M d, Y", strtotime($s)) . (!empty($e) ? (" - ") . date("M d, Y", strtotime($e)) : "")) : "";
            $pdf->Cell(148, 6, ("Date : " . $date_range), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 9);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, TRUE, 1);
            $pdf->SetFont("", "", 8);

            $h = 6;
            $totals = 0;
            $payments = 0;
            $balances = 0;
            $i = 0;
//            dump($payments_list,true);
            foreach ($payments_list as $invoice) {

                if (in_array($invoice->status, ['0', '3', '4']) || ($invoice->status == 6 && $invoice->remarks != '')) {
                    $i++;

                    $dec_id = $invoice->prefix . str_pad($invoice->do_id, 5, "0", STR_PAD_LEFT);
                    $_customer = $invoice->customer_name;
                    $customer = preg_replace('/[\s]+/mu', ' ', $_customer);//
                    $salesp = str_replace("SALES AGENT ", " ", $invoice->created);

                    $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                    $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                    $pdf->Cell($widths[2], $h, $invoice->do_number, 1, 0, $text_direction[2]);
                    $pdf->Cell($widths[3], $h, $customer, 1, 0, $text_direction[3]);
                    $pdf->Cell($widths[4], $h, $invoice->nic, 1, 0, $text_direction[4]);
                    $pdf->Cell($widths[5], $h, $invoice->tp1, 1, 0, $text_direction[5]);
                    $pdf->Cell($widths[6], $h, str_replace(" BRANCH", '', $invoice->branch_name), 1, 0, $text_direction[6]);
                    $pdf->Cell($widths[7], $h, $invoice->devision, 1, 0, $text_direction[7]);
                    $pdf->Cell($widths[8], $h, $salesp, 1, 0, $text_direction[8]);
                    $pdf->Cell($widths[9], $h, $invoice->inv_date, 1, 0, $text_direction[9]);
                    $pdf->Cell($widths[10], $h, $invoice->itm_code, 1, 0, $text_direction[10]);
                    $pdf->Cell($widths[11], $h, is_zero($invoice->rate), 1, 0, $text_direction[11]);
                    $pdf->Cell($widths[12], $h, "", 1, 1, $text_direction[12]);

                }
            }

            $pdf->Ln(2);
            $pdf->add_signature();

            $pdf->Output("payments-complete-bills.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function invoice_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $sp = $this->input->get("sp");
            $from = $this->input->get("s");
            $to = $this->input->get("e");

            $invoices = $this->invoice_model->get_all_invoice($sp, $from, $to);
            // dump($customers);
            $this->load->library('Excel');
            $PHPExcel = new PHPExcel();
            PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            $PHPExcel->setActiveSheetIndex(0);
            $sheet = $PHPExcel->getActiveSheet();

            $styleArray = array(
                'font' => array(
                    'bold' => true,
                    // 'size' => 11,
                    // 'name' => 'Verdana'
                ));

            $columns = ["No", "Delivery Date", "DO Number", "Invoice Number", "Issue By", "Customer Name", "Address", "Serice Charge", "Down Payment", "Balance", "Item Code", "Item Name", "Selling Price"];

            $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
            $sheet->setCellValue("A1", $this->branch->branch_name);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
            $sheet->setCellValue("A2", "Invoice List");
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
            $from_to = (!empty($from) && !empty($to) ? ("From : " . $from . " To : " . $to) : (!empty($from) ? $from : (!empty($to) ? $to : "")));
            $sheet->setCellValue("A3", $from_to);

            $sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = 5;

            $currencyFormat = '_(* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';

            for ($idx = 1; $idx <= count($columns); $idx++) {
                $sheet->setCellValue($this->get_Letter($idx) . "$row", $columns[$idx - 1]);
                $sheet->getColumnDimension($this->get_Letter($idx))->setAutoSize(true);
            }
            $sheet->getStyle($this->get_Letter(1) . "$row:" . $this->get_Letter(count($columns)) . "$row")->applyFromArray($styleArray);
            $row++;
            $sheet->freezePane('A' . $row);

            $this->load->model("wl_doc_code_model");
            $_doc_codes = $this->wl_doc_code_model->get_all();
            $doc_codes = [];
            foreach ($_doc_codes as $_doc_code) {
                if ($_doc_code->doc == "do") {
                    $doc_codes["do" . $_doc_code->branch] = [$_doc_code->prefix, $_doc_code->length];
                }
                if ($_doc_code->doc == "invoice") {
                    $doc_codes["inv" . $_doc_code->branch] = [$_doc_code->prefix, $_doc_code->length];
                }
            }
            foreach ($invoices as $invoice) {
                $address = [$invoice->address_po_box, $invoice->address_line1, $invoice->address_line2, $invoice->address_city];
                // "No", "Delivery Date", "DO Number","Invoice Number","Issue By", "Customer Name", "Address", "Serice Charge","Down Payment","Balance","Item Code","Item Name","Selling Price"
                $do_id = $doc_codes["do" . $invoice->branch][0] . (str_pad($invoice->do_id, $doc_codes["do" . $invoice->branch][1], "0", STR_PAD_LEFT));
                $inv_id = $doc_codes["inv" . $invoice->branch][0] . (str_pad($invoice->inv_id, $doc_codes["inv" . $invoice->branch][1], "0", STR_PAD_LEFT));
                $sheet->setCellValue($this->get_Letter(1) . "" . $row, $do_id);
                $sheet->setCellValue($this->get_Letter(2) . "" . $row, $invoice->delivery_date);
                $sheet->setCellValue($this->get_Letter(3) . "" . $row, $invoice->do_number);
                $sheet->setCellValue($this->get_Letter(4) . "" . $row, $inv_id);
                $sheet->setCellValue($this->get_Letter(5) . "" . $row, $invoice->created);
                $sheet->setCellValue($this->get_Letter(6) . "" . $row, $invoice->customer_prefix . " " . $invoice->customer_name);
                $sheet->setCellValue($this->get_Letter(7) . "" . $row, implode(",", $address));
                $sheet->setCellValue($this->get_Letter(8) . "" . $row, number_format($invoice->service_charge, 2));
                $sheet->setCellValue($this->get_Letter(9) . "" . $row, number_format($invoice->down_payment, 2));
                $sheet->setCellValue($this->get_Letter(10) . "" . $row, number_format($invoice->balance, 2));
                $sheet->setCellValue($this->get_Letter(11) . "" . $row, $invoice->itm_code);
                $sheet->setCellValue($this->get_Letter(12) . "" . $row, $invoice->itm_name);
                $sheet->setCellValue($this->get_Letter(13) . "" . $row, number_format($invoice->rate, 2));
                $sheet->getStyle($this->get_Letter(8) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle($this->get_Letter(9) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle($this->get_Letter(10) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle($this->get_Letter(13) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $row++;
            }
            $row++;

            // header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="invoice-list.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } else {
            redirect(base_url("login"));
        }
    }

    public function daily_sales_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $d = $this->input->get("d");
            if (empty($d)) {
                $d = date("Y-m-d");
            }

            $invoices = $this->invoice_model->get_daily_sales($d, $this->branch);
            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#', "Bill No.", "Customer", "Cash", "Credit", "Total"];
            $widths = [8, 25, 75, 25, 25, 25];
            $text_direction = ["L", "L", "L", "L", "R", "R"];
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

            $pdf->Cell(45, 6, "Daily Sales Summary", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, "Date : " . date("M d, Y", strtotime($d)), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $cahs = 0;
            $credit = 0;
            $balance = 0;
            $i = 0;
            foreach ($invoices as $invoice) {
                $i++;

                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, decorate_code($invoice->inv_id, "invoice", $this->prefixes), 1, 0, $text_direction[0]);
                $pdf->Cell($widths[2], $h, $invoice->customer_prefix . " " . $invoice->customer_name, 1, 0, $text_direction[1]);
                if ($invoice->is_cash == "1") {
                    $pdf->Cell($widths[3], $h, is_zero($invoice->total), 1, 0, $text_direction[3]);
                    $pdf->Cell($widths[4], $h, "-", 1, 0, $text_direction[4]);
                    $cahs += doubleval($invoice->total);
                    $pdf->Cell($widths[5], $h, is_zero($invoice->total), 1, 1, $text_direction[5]);
                } else {
                    $pdf->Cell($widths[3], $h, "-", 1, 0, $text_direction[3]);
                    $pdf->Cell($widths[4], $h, is_zero($invoice->total), 1, 0, $text_direction[4]);
                    $credit += doubleval($invoice->total);
                    $pdf->Cell($widths[5], $h, is_zero($invoice->total), 1, 1, $text_direction[5]);
                }
                $balance += doubleval($invoice->balance);
            }

            $pdf->Ln(2);
            $pdf->Cell(110, $h, "Total", 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($cahs), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($credit), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($cahs + $credit), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function daily_invoice_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $d = $this->input->get("d");
            if (empty($d)) {
                $d = date("Y-m-d");
            }

            $invoices = $this->invoice_model->get_daily_sales($d, $this->branch);
            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#', "Date", "Bill No.", "Customer", "Cash", "Credit"];
            $widths = [8, 25, 27, 80, 25, 25];
            $text_direction = ["L", "L", "L", "R", "R"];
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
//            $pdf->SetFillColor(150);
//            $pdf->SetTextColor(255);
            $pdf->Cell(45, 6, "Daily Sales Summary", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, "Date : " . date("M d, Y", strtotime($d)), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $cahs = 0;
            $credit = 0;
            $balance = 0;
            $i = 0;
            foreach ($invoices as $invoice) {
                $i++;
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $invoice->inv_date, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, decorate_code($invoice->inv_id, "invoice", $this->prefixes), 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->customer_prefix . " " . $invoice->customer_name, 1, 0, $text_direction[3]);
                if ($invoice->is_cash == "1") {
                    $pdf->Cell($widths[4], $h, is_zero($invoice->total), 1, 0, $text_direction[4]);
                    $pdf->Cell($widths[5], $h, "-", 1, 1, $text_direction[5]);
                    $cahs += doubleval($invoice->total);
                } else {
                    $_paymnet = doubleval($invoice->total) - doubleval($invoice->balance);
                    $pdf->Cell($widths[4], $h, is_zero($_paymnet), 1, 0, $text_direction[4]);
                    $pdf->Cell($widths[5], $h, is_zero($invoice->balance), 1, 1, $text_direction[5]);
                    $credit += doubleval($invoice->balance);
                    $cahs += $_paymnet;
                }
            }

            $pdf->Ln(2);
            $pdf->Cell(140, $h, "Total", 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($cahs), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($credit), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function monthly_sales_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $type = $this->input->get("t");
            if ($type == "pdf") {
                $this->monthly_sales_summary_pdf();
            } else {
                $this->monthly_sales_summary_excel();
            }
        } else {
            redirect(base_url("login"));
        }
    }

    private function monthly_sales_summary_excel()
    {
        $month = $this->input->get("m");
        $y = $this->input->get("y");

        $this->load->model("invoice_model");
        $invoices = $this->invoice_model->get_monthly_sales($y, $month, $this->branch);

        $data = array();
        foreach ($invoices as $invoice) {
            $data[$invoice->inv_date][] = $invoice;
        }
        $this->load->library('Excel');
        $PHPExcel = new PHPExcel();
        PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        $PHPExcel->setActiveSheetIndex(0);
        $sheet = $PHPExcel->getActiveSheet();

        $styleArray = array(
            'font' => array(
                'bold' => true,
                'size' => 11,
                'name' => 'Verdana'
            ));

        $columns = array_merge(["Date"], ["Cash Sale", "Credit Sale", "Total"]);

        $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
        $sheet->setCellValue("A1", $this->branch->branch_name);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
        $sheet->setCellValue("A2", "Monthly Sale Summary");
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
        $sheet->setCellValue("A3", get_month($month) . " " . $y);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row = 5;
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        $i = 0;
        $total = 0;
        $cash = 0;
        $credit = 0;
        $currencyFormat = '_(* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
//            dump($data);
        foreach ($data as $date => $d) {
            $i++;
            $t = 0;
            $sheet->setCellValue($this->get_Letter(1) . "" . $row, $date);
            if ($d[0]->is_cash == "1") {

                $sheet->setCellValue($this->get_Letter(2) . "" . $row, (($d[0]->tot)));

                $cash += doubleval($d[0]->tot);
                $t += doubleval($d[0]->tot);
                if (isset($d[1])) {

                    $sheet->setCellValue($this->get_Letter(3) . "" . $row, (($d[1]->tot)));

                    $credit += doubleval($d[1]->tot);
                    $t += doubleval($d[1]->tot);
                } else {
                    $sheet->setCellValue($this->get_Letter(4) . "" . $row, "-");
                }
            } else {
                if (isset($d[1])) {
                    $sheet->setCellValue($this->get_Letter(2) . "" . $row, ($d[1]->tot));
                    $cash += doubleval($d[1]->tot);
                    $t += doubleval($d[1]->tot);
                } else {
                    $sheet->setCellValue($this->get_Letter(2) . "" . $row, "-");
                }
                $sheet->setCellValue($this->get_Letter(3) . "" . $row, ($d[0]->tot));
                $credit += doubleval($d[0]->tot);
                $t += doubleval($d[0]->tot);
            }
            $sheet->setCellValue($this->get_Letter(4) . "" . $row, ($t));
            $total += $t;

            $sheet->getStyle($this->get_Letter(2) . "" . $row . ":" . $this->get_Letter(4) . "$row")
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle($this->get_Letter(2) . "" . $row . ":" . $this->get_Letter(4) . "$row")
                ->getNumberFormat()
                ->setFormatCode($currencyFormat);

            $row++;
        }
        $row++;

        $sheet->setCellValue($this->get_Letter(1) . "" . $row, ("Total"));
        $sheet->setCellValue($this->get_Letter(2) . "" . $row, ($cash));
        $sheet->setCellValue($this->get_Letter(3) . "" . $row, ($credit));
        $sheet->setCellValue($this->get_Letter(4) . "" . $row, ($total));

        $sheet->getStyle($this->get_Letter(1) . "" . $row . ":" . $this->get_Letter(4) . "$row")
            ->applyFromArray(array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '99FF99')
                ),
                'borders' => array(
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
                        'color' => array('rgb' => '1006A3')
                    )),
            ));
        $sheet->getStyle($this->get_Letter(2) . "" . $row . ":" . $this->get_Letter(4) . "$row")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle($this->get_Letter(2) . "" . $row . ":" . $this->get_Letter(4) . "$row")
            ->getNumberFormat()
            ->setFormatCode($currencyFormat);

        header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="manthly-sale-summary.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    private function monthly_sales_summary_pdf()
    {
        $month = $this->input->get("m");
        $y = $this->input->get("y");

        $this->load->model("invoice_model");
        $invoices = $this->invoice_model->get_monthly_sales($y, $month, $this->branch);

        $data = array();
        foreach ($invoices as $invoice) {
            $data[$invoice->inv_date][] = $invoice;
        }
        $this->load->library('F_pdf');
        $pdf = new My_pdf("P", "mm", "a4");
        $pdf->set_is_devided(FALSE);
        $pdf->AcceptPageBreak();
        $pdf->SetAutoPageBreak(true, 00);
        $pdf->set_footer(FALSE);

        $pdf->AddFont('Consolas', '', 'consola.php');
        $pdf->AddFont('Consolas', 'B', 'consolab.php');

        $columns = ['#', "Date", "Cash Sale", "Credit Sale", "Total"];
        $widths = [8, 47, 45, 45, 45];
        $text_direction = ["L", "L", "R", "R", "R"];
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
        $pdf->Cell(38, 6, "Monthly Sales Summary");
        $pdf->SetFont('', '', '');
        $pdf->Cell(152, 6, "Month : " . get_month($month) . "," . $y, "0", 1, "R");
        $pdf->SetFont('', '', 10);
        $pdf->Cell(25, 6, "Generate By");

        $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);

        $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
        $pdf->SetFont('', 'B', 10);
        $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
        $pdf->SetFont('', '', 10);

        $i = 0;
        $h = 6;
        $total = 0;
        $cash = 0;
        $credit = 0;
        $i = 0;
//            dump($data);
        foreach ($data as $date => $d) {
            $i++;
            $t = 0;
            $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
            $pdf->Cell($widths[1], $h, $date, 1, 0, $text_direction[1]);
            if ($d[0]->is_cash == "1") {
                $pdf->Cell($widths[2], $h, is_zero($d[0]->tot), 1, 0, $text_direction[2]);
                $cash += doubleval($d[0]->tot);
                $t += doubleval($d[0]->tot);
                if (isset($d[1])) {
                    $pdf->Cell($widths[3], $h, is_zero($d[1]->tot), 1, 0, $text_direction[3]);
                    $credit += doubleval($d[1]->tot);
                    $t += doubleval($d[1]->tot);
                } else {
                    $pdf->Cell($widths[3], $h, "-", 1, 0, $text_direction[3]);
                }
            } else {
                if (isset($d[1])) {
                    $pdf->Cell($widths[2], $h, is_zero($d[1]->tot), 1, 0, $text_direction[2]);
                    $cash += doubleval($d[1]->tot);
                    $t += doubleval($d[1]->tot);
                } else {
                    $pdf->Cell($widths[2], $h, "-", 1, 0, $text_direction[2]);
                }
                $pdf->Cell($widths[3], $h, is_zero($d[0]->tot), 1, 0, $text_direction[3]);
                $credit += doubleval($d[0]->tot);
                $t += doubleval($d[0]->tot);
            }
            $pdf->Cell($widths[4], $h, is_zero($t), 1, 1, $text_direction[4]);
            $total += $t;
        }

        $pdf->Cell(0, 2, "", 0, 1, "R");
        $pdf->Cell($widths[0], $h, "Total", 1, 0, "R");
        $pdf->Cell($widths[1], $h, is_zero($cash), 1, 0, "R");
        $pdf->Cell($widths[2], $h, is_zero($credit), 1, 0, "R");
        $pdf->Cell($widths[3], $h, is_zero($total), 1, 1, "R");

        $pdf->add_signature();
        $pdf->Output("current_stock.pdf", "I");
    }

    public function outstanding_statement_summary()
    {
        if ($this->ion_auth->logged_in()) {

            $this->load->model("invoice_model");
            $invoices = $this->invoice_model->get_outstanding_invoices($this->branch);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');


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
            $pdf->Cell(38, 6, "Outstanding statement summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(152, 6, "Date : " . date("M d, Y"), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);

            $pdf->Cell(00, 4, "", 0, 1);

            $pdf->SetFont('', 'B', 10);

            $columns = ['#', "Date", "Customer", "Bill No.", "Telephone", "Balance", "30", "60", "90"];
            $widths = [8, 25, 62, 20, 25, 24, 8, 8, 8];
            $h = 6;

            $pdf->Cell($widths[0], $h * 2, $columns[0], 1, 0, "C");
            $pdf->Cell($widths[1], $h * 2, $columns[1], 1, 0);
            $pdf->Cell($widths[2], $h * 2, $columns[2], 1, 0, "C");
            $pdf->Cell($widths[3], $h * 2, $columns[3], 1, 0, "C");
            $pdf->Cell($widths[4], $h * 2, $columns[4], 1, 0, "C");
            $pdf->Cell(($widths[7] + $widths[5] + $widths[6]), $h, "Period", 1, 1, "C");

            $pdf->SetX($widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4] + $pdf->lMargin);
            $pdf->Cell($widths[5], $h, $columns[5], 1, 0, "C");
            $pdf->Cell($widths[6], $h, $columns[6], 1, 0, "C");
            $pdf->Cell($widths[7], $h, $columns[7], 1, 1, "C");
            $pdf->Cell($widths[8], $h, $columns[8], 1, 1, "C");
            $pdf->SetFont('', '', 10);

            $i = 0;
            $total = 0;
            $today = date("Y-m-d");
            $i = 0;
            foreach ($invoices as $value) {
                $i++;
                $pdf->SetFont("Consolas");
                $pdf->Cell($widths[0], $h, $i, 1, 0);
                $pdf->Cell($widths[1], $h, $value->inv_date, 1, 0, "C");
                $pdf->Cell($widths[2], $h, (!empty($value->customer_prefix) ? $value->customer_prefix . " " : "") . $value->customer_name, 1, 0);
//                $pdf->Cell($widths[2], $h, is_zero($value->balance), 1, 0, "R");
                $pdf->Cell($widths[3], $h, decorate_code($value->inv_id, "invoice", $this->prefixes), 1, 0, "C");
                $pdf->Cell($widths[4], $h, character_limiter($value->tp1, 10, ""), 1, 0, "C");
                $pdf->Cell($widths[5], $h, $value->balance, 1, 0, "R");

                $total += doubleval($value->balance);
                $days = date_different($value->inv_date, $today);
                $pdf->SetFont("ZapfDingbats");
                if ($days >= 0 && $days < 30) {
                    $pdf->Cell($widths[6], $h, "4", 1, 0, "C");
                    $pdf->Cell($widths[7], $h, "", 1, 0, "C");
                    $pdf->Cell($widths[8], $h, "", 1, 1, "C");
                }
                if ($days >= 30 && $days < 60) {
                    $pdf->Cell($widths[6], $h, "", 1, 0, "C");
                    $pdf->Cell($widths[7], $h, "4", 1, 0, "C");
                    $pdf->Cell($widths[8], $h, "", 1, 1, "C");
                }
                if ($days >= 60) {
                    $pdf->Cell($widths[6], $h, "", 1, 0, "C");
                    $pdf->Cell($widths[7], $h, "", 1, 0, "C");
                    $pdf->Cell($widths[8], $h, "4", 1, 1, "C");
                }
            }
            $pdf->SetFont("Consolas");
            $pdf->Cell(0, 2, "", 0, 1, "R");
            $pdf->Cell($widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[5], $h, is_zero($total), 1, 0, "R");
            $pdf->Cell($widths[6] + $widths[7] + $widths[8], $h, "", 1, 1, "R");

            $pdf->add_signature();
            $pdf->Output("current_stock.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function monthly_sale_summary()
    {
        if ($this->ion_auth->logged_in()) {

            $m = $this->input->get("m");
            $y = $this->input->get("y");
            $this->load->model("invoice_model");
            $_invoices = $this->invoice_model->get_all_branch_sales($y, $m);
            $invoices = array();
            $shops = array();
            foreach ($_invoices as $_invoice) {
                $invoices[$_invoice->inv_date]['br'][$_invoice->branch_name] = $_invoice->tot;
                $shops[] = $_invoice->branch_name;
            }
            $shops = array_unique($shops);

            $this->load->library('EXcel');
            $PHPExcel = new PHPExcel();
            PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            $PHPExcel->setActiveSheetIndex(0);
            $sheet = $PHPExcel->getActiveSheet();

            $styleArray = array(
                'font' => array(
                    'bold' => true,
                    'size' => 11,
                    'name' => 'Verdana'
                ));


            $columns = array_merge(["Date"], $shops, ["  ", "Total"]);

            $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
            $sheet->setCellValue("A1", $this->branch->branch_name);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
            $sheet->setCellValue("A2", "Monthly Sale Summary");
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
            $sheet->setCellValue("A3", get_month($m) . " " . $y);
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = 5;
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);

            for ($idx = 1; $idx <= count($columns); $idx++) {
                $sheet->setCellValue($this->get_Letter($idx) . "$row", $columns[$idx - 1]);
            }

            $sheet->getStyle($this->get_Letter(1) . "$row:" . $this->get_Letter(count($columns)) . "$row")->applyFromArray($styleArray);
            $row++;
            $row++;
            $currencyFormat = '_(* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)';
            $totals = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            $tot_totalals = 0;
            foreach ($invoices as $date => $invoice) {

                $sheet->setCellValue($this->get_Letter(1) . "" . $row, $date);
                $sheet->getStyle($this->get_Letter(1) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $col = 2;
                $tot = 0;

                $_i = 0;
                foreach ($invoice["br"] as $tots) {
                    $tot += doubleval($tots);
                    $sheet->setCellValue($this->get_Letter($col) . "" . $row, ($tots));
                    $sheet->getStyle($this->get_Letter($col) . "" . $row)
                        ->getAlignment()
                        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle($this->get_Letter($col) . "" . $row)
                        ->getNumberFormat()
                        ->setFormatCode($currencyFormat);
                    $col++;
                    $totals[$_i] = $totals[$_i] + doubleval($tots);
                    $_i++;
                }
                $pointer = count($shops) + 3;
                $sheet->setCellValue($this->get_Letter($pointer) . "" . $row, ($tot));
                $tot_totalals += doubleval($tot);
                $sheet->getStyle($this->get_Letter($pointer) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle($this->get_Letter($pointer) . "" . $row)
                    ->getNumberFormat()
                    ->setFormatCode($currencyFormat);

                $row++;
            }
            $row++;
            $letter = $this->get_Letter(1);
            $sheet->setCellValue($letter . "" . $row, "Total");
            for ($i = 2; $i < (count($shops) + 2); $i++) {
                $letter = $this->get_Letter($i);
                $sheet->setCellValue($letter . "" . $row, $totals[$i - 2]);
                $sheet->getStyle($letter . "" . $row)
                    ->getNumberFormat()
                    ->setFormatCode($currencyFormat);
            }

            $letter = $this->get_Letter(count($shops) + 3);
            $sheet->setCellValue($letter . "" . $row, $tot_totalals);
            $sheet->getStyle($letter . "" . $row)
                ->getNumberFormat()
                ->setFormatCode($currencyFormat);


            header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="monthly-sale-summary.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
            $objWriter->save('php://output');
        } else {
            redirect(base_url("login"));
        }
    }

    public function daily_sale_summary()
    {
        if ($this->ion_auth->logged_in()) {

            $start = $this->input->get("s");
            $end = $this->input->get("e");
            $this->load->model("invoice_model");
            $_invoices = $this->invoice_model->get_all_branch_sales_daily($start, $end, $this->branch);
            $invoices = array();
            $_shops = array();
            foreach ($_invoices as $_invoice) {
                if ($_invoice->is_cash == "1") {
                    $invoices[$_invoice->inv_date]['br'][$_invoice->branch_name_report]["cash"] = $_invoice->tot;
                } else {
                    $invoices[$_invoice->inv_date]['br'][$_invoice->branch_name_report]["credit"] = $_invoice->tot;
                }
                $_shops[] = $_invoice->branch_name_report;
            }
            $shops = array_unique($_shops);

            $this->load->library('Excel');
            $PHPExcel = new PHPExcel();
            PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
            $PHPExcel->setActiveSheetIndex(0);
            $sheet = $PHPExcel->getActiveSheet();

            $styleArray = array(
                'font' => array(
                    'bold' => true,
                    'size' => 11,
                    'name' => 'Verdana'
                ));


            $columns = array_merge(["Date"], $shops, ["   ", "Total"]);

            $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
            $sheet->setCellValue("A1", $this->branch->branch_name);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
            $sheet->setCellValue("A2", "Daily Sale Summary");
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
            $sheet->setCellValue("A3", "From " . $start . " to " . $end);
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = 5;
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);

//            for ($idx = 1; $idx <= count($columns); $idx++) {
//                if ($idx > 1 && $idx < (count($columns) - 2)) {
//                    $sheet->setCellValue($this->get_Letter($idx) . "$row", $columns[$idx - 1]);
//                    $sheet->mergeCells('A2:' . $this->get_Letter() . "2");
//                } else {
//                    
//                }
//            }
            $sheet->setCellValue($this->get_Letter(1) . "$row", $columns[0]);
            for ($idx = 2; $idx <= (count($shops) + 1); $idx++) {
                $merg_start = (($idx * 2) - 1);
                $sheet->setCellValue($this->get_Letter($merg_start) . "$row", $columns[$idx - 1]);
                $sheet->getColumnDimension($this->get_Letter($merg_start))->setAutoSize(true);

                $sheet->setCellValue($this->get_Letter($merg_start) . "" . ($row + 1), "Cash");
                $sheet->setCellValue($this->get_Letter($merg_start + 1) . "" . ($row + 1), "Credit");
            }
            $sheet->setCellValue($this->get_Letter((count($shops) * 2) + 3) . "$row", "Total");

            $sheet->getStyle($this->get_Letter(1) . "$row:" . $this->get_Letter(count($columns) + count($shops)) . "$row")->applyFromArray($styleArray);
            $row++;
            $row++;
            $row++;
            $currencyFormat = '_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)';
            $totals = array(array("cash" => 0, "credit" => 0), array("cash" => 0, "credit" => 0), array("cash" => 0, "credit" => 0), array("cash" => 0, "credit" => 0), array("cash" => 0, "credit" => 0));
            $tot_totalals = 0;
//            dump($invoices);
            foreach ($invoices as $date => $invoice) {

                $sheet->setCellValue($this->get_Letter(1) . "" . $row, $date);
                $sheet->getStyle($this->get_Letter(1) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $tot_cash = 0;
                $tot_credit = 0;

                $_i = 0;
//                dump($invoice);
                $col = 2;

                foreach ($invoice as $inv_tots) {
                    foreach ($shops as $shop_name) {
                        if (isset($inv_tots[$shop_name])) {

                            $tots = $inv_tots[$shop_name];

                            $_tot_cash = doubleval(isset($tots["cash"]) ? $tots["cash"] : 0);
                            $_tot_credit = doubleval(isset($tots["credit"]) ? $tots["credit"] : 0);
                            $tot_cash += $_tot_cash;
                            $tot_credit += $_tot_credit;
                            $col_ccredit = (($col * 2));
                            $col_cash = ($col_ccredit - 1);

                            $sheet->setCellValue($this->get_Letter($col_cash) . "" . $row, ($_tot_cash));
                            $sheet->getStyle($this->get_Letter($col_cash) . "" . $row)
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                            $sheet->getStyle($this->get_Letter($col_cash) . "" . $row)
                                ->getNumberFormat()
                                ->setFormatCode($currencyFormat);

                            $sheet->setCellValue($this->get_Letter($col_ccredit) . "" . $row, ($_tot_credit));
                            $sheet->getStyle($this->get_Letter($col_ccredit) . "" . $row)
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                            $sheet->getStyle($this->get_Letter($col_ccredit) . "" . $row)
                                ->getNumberFormat()
                                ->setFormatCode($currencyFormat);
                            $totals[$_i]["cash"] = $totals[$_i]["cash"] + doubleval($_tot_cash);
                            $totals[$_i]["credit"] = $totals[$_i]["credit"] + doubleval($_tot_credit);
                            $_i++;
                        }
                        $col++;
                    }
                }
                $pointer = (count($shops) * 2) + 3;
                $sheet->setCellValue($this->get_Letter($pointer) . "" . $row, ($tot_cash + $tot_credit));
                $tot_totalals += (doubleval($tot_cash));
                $sheet->getStyle($this->get_Letter($pointer) . "" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle($this->get_Letter($pointer) . "" . $row)
                    ->getNumberFormat()
                    ->setFormatCode($currencyFormat);
                $row++;
            }
//            $row++;
//            $letter = $this->get_Letter(1);
//            $sheet->setCellValue($letter . "" . $row, "Total");
//            for ($i = 2; $i < (count($shops) + 2); $i++) {
//                $letter = $this->get_Letter($i);
//                $sheet->setCellValue($letter . "" . $row, $totals[$i - 2]);
//                $sheet->getStyle($letter . "" . $row)
//                        ->getNumberFormat()
//                        ->setFormatCode($currencyFormat);
//            }
//
//            $letter = $this->get_Letter(count($shops) + 3);
//            $sheet->setCellValue($letter . "" . $row, $tot_totalals);
//            $sheet->getStyle($letter . "" . $row)
//                    ->getNumberFormat()
//                    ->setFormatCode($currencyFormat);
            header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="daily-sale-summary.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
            $objWriter->save('php://output');
        } else {
            redirect(base_url("login"));
        }
    }

    public function credit_invoice_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $this->load->model("invoice_payment_model");
            $s = $this->input->get("s");
            $sp = $this->input->get("sp");
            $b = $this->input->get("b");
            $d = $this->input->get("d");


            $payments_list = $this->invoice_payment_model->get_payments_report($s, $b, $d,$sp);


            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Bill No.", "Customer", "Branch", 'Collctor', "Devision", "Due Date", "Total", "Payment", "Balance"];
            $widths = [25, 74, 30, 30, 20, 25, 26, 26, 23];
            $text_direction = ["L", "L", "L", "L", "L", "L", "R", "R", "R"];
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

            $pdf->Cell(45, 6, "Installments collection", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . (!empty($s) ? (" From " . date("M d, Y", strtotime($s))) : date("M d, Y"))), "0", 1, "R");
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
            foreach ($payments_list as $invoice) {

                // "Bill No.", "Customer","Devision", "Date", "Total", "Payment", "Balance"
                $dec_id = $invoice->prefix . str_pad($invoice->invoice_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix . " " . $invoice->customer_name;
                $branch_name = str_replace("BRANCH", "", $invoice->branch_name);
                $pdf->Cell($widths[0], $h, $dec_id, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $customer, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $branch_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->username, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->devision, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, date("Y-m-d", strtotime($invoice->due_date)), 1, 0, $text_direction[5]);
//                $pdf->Cell($widths[6], $h, is_zero($invoice->fine), 1, 0, $text_direction[6]);
                $pdf->Cell($widths[6], $h, is_zero($invoice->total), 1, 0, $text_direction[6]);
                $payment = doubleval($invoice->payment) + doubleval($invoice->fine);
                $pdf->Cell($widths[7], $h, is_zero($payment), 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, is_zero($invoice->balance), 1, 1, $text_direction[8]);

                $fines += doubleval($invoice->fine);
                $totals += doubleval($invoice->total);
                $payments += doubleval($payment);
                $balances += doubleval($invoice->balance);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths) - 75, $h, "Totals", 1, 0, "R");
//            $pdf->Cell(15, $h, is_zero($fines), 1, 0, "R");
            $pdf->Cell(26, $h, is_zero($totals), 1, 0, "R");
            $pdf->Cell(26, $h, is_zero($payments), 1, 0, "R");
            $pdf->Cell(23, $h, is_zero($balances), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("invoice-payments.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function due_bill_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $s = $this->input->get("s");
            $b = $this->input->get("b");
            $d = $this->input->get("d");

            $payments_list = $this->invoice_model->get_due_bill_report($s, $b, $d);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#', "Bill No.", "Branch", "Devision", "Due Date", "Due Amount", "Fine", "Installment", "Due Date", 'First Forward', 'Second Forward'];
            $widths = [8, 24, 50, 20, 23, 22, 20, 24, 25, 35, 30];
            $text_direction = ["L", "L", "L", "L", "L", "R", "R", "R", "R", "L", "L"];
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

            $pdf->Cell(45, 6, "Due Payments", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . (!empty($s) ? (date("M d, Y", strtotime($s))) : " From " . date("M d, Y"))), "0", 1, "R");
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
            $fines = 0;
            $this->load->model('fine_model');
            $db_fines = $this->fine_model->get_all_fines();
            $i = 0;

            $_buffer_days = get_option('fine-buffer-days', 3,$this->branch->id);
            $buffer_days = intval($_buffer_days);
            $_above = array_pop($db_fines);
            $start_2_month = date("Y-m-d", strtotime("-60 days"));
            $start_month = date("Y-m-d", strtotime("-30 days"));
//            $start_month = date("Y-m-d");
            foreach ($payments_list as $invoice) {
                if (intval($invoice->paymetns) > 1 && is_date_greater_eq_than_last($start_2_month,$invoice->next_installment_date)
                    || (intval($invoice->paymetns) == 1 && is_date_greater_eq_than_last($start_month,$invoice->next_installment_date))) {
                }else{
					if($invoice->status == '4' ){
						
						$i++;

						$dec_id = $invoice->prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);
						$customer = $invoice->customer_prefix . " " . $invoice->customer_name;

						$pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
						$pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
						$pdf->Cell($widths[2], $h, $invoice->branch_name, 1, 0, $text_direction[3]);
						$pdf->Cell($widths[3], $h, $invoice->devision, 1, 0, $text_direction[4]);
						$pdf->Cell($widths[4], $h, $invoice->next_installment_date, 1, 0, $text_direction[4]);
						$pdf->Cell($widths[5], $h, is_zero($invoice->balance), 1, 0, $text_direction[6]);
						$fine = 0;


						$is_late = is_date_greater_eq_than_last(date("Y-m-d", strtotime("-" . ($buffer_days - 1) . " days")), $invoice->next_installment_date);
						$date_diff = date_different("today", $invoice->next_installment_date);

						if ($is_late) {
							$fine_reverse = array_reverse($db_fines);
							foreach ($fine_reverse as $fine_rev) {
								if ($date_diff <= intval($fine_rev->day)) {
									$fine = doubleval($fine_rev->fine);
									break;
								}
							}
							if ($fine == 0) {
								if ($date_diff <= doubleval($_above->day)) {
									$fine = doubleval($_above->fine);
								}
							}
						}

						$dStart = new DateTime($invoice->next_installment_date);
						$dEnd = new DateTime('tomorrow');
						$dDiff = $dStart->diff($dEnd);
						$a = $dDiff->format('%r%m');
						$fine = (intval($a)) * $fine;

						$fines += $fine;
						$pdf->Cell($widths[6], $h, is_zero($fine), 1, 0, $text_direction[7]);
						$pdf->Cell($widths[7], $h, is_zero($invoice->installment_amount), 1, 0, $text_direction[8]);
						$pdf->Cell($widths[8], $h, "", 1, 0, $text_direction[9]);
						$pdf->Cell($widths[9], $h, "", 1, 0, $text_direction[9]);
						$pdf->Cell($widths[10], $h, "", 1, 1, $text_direction[9]);

						$totals += doubleval($invoice->total);
						$payments += doubleval($invoice->installment_amount);
						$balances += doubleval($invoice->balance);
					}
                }
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths) - 156, $h, "Totals", 1, 0, "R");
            $pdf->Cell(22, $h, is_zero($balances), 1, 0, "R");
            $pdf->Cell(20, $h, is_zero($fines), 1, 0, "R");
            $pdf->Cell(24, $h, is_zero($payments), 1, 0, "R");
            $pdf->Cell(89, $h, "", 1, 0, "R");

            $pdf->Ln(20);

            $pdf->Cell(149, 10, '', 0, 0, 'L');
            $pdf->Cell(35, 10, 'For Cash Hand Over', 0, 0, 'L');
            $pdf->Cell(3, 10, ':', 0, 0, 'L');

            $pdf->Cell($widths[8], 8, '', "B", 0, 'L');
            $pdf->Cell(2, 10, '', 0, 0, 'L');
            $pdf->Cell($widths[9], 8, '', "B", 0, 'L');
            $pdf->Cell(2, 10, '', 0, 0, 'L');
            $pdf->Cell($widths[10], 8, '', "B", 0, 'L');
            $pdf->Cell(2, 10, '', 0, 1, 'L');

            $pdf->Cell(149, 10, '', 0, 0, 'L');
            $pdf->Cell(35, 10, 'For Cash Hand Over', 0, 0, 'L');
            $pdf->Cell(3, 10, ':', 0, 0, 'L');

            $pdf->Cell($widths[8], 8, '', "B", 0, 'L');
            $pdf->Cell(2, 10, '', 0, 0, 'L');
            $pdf->Cell($widths[9], 8, '', "B", 0, 'L');
            $pdf->Cell(2, 10, '', 0, 0, 'L');
            $pdf->Cell($widths[10], 8, '', "B", 0, 'L');
            $pdf->Cell(2, 10, '', 0, 1, 'L');

            $pdf->add_signature();
            $pdf->Output("due-payments.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function live_due_bill_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $b = $this->input->get("b");
            $d = $this->input->get("d");

            $payments_list = $this->invoice_model->get_live_due_bill_report($b, $d);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["#", "Bill No.", 'Invoice Date', "Customer Name", "Item Code", "Sub Total", "Due Amount", "Due Fines"];
            $widths = [25, 30, 30, 80, 25, 30, 30, 25];
            $text_direction = ["L", "L", "L", "L", "L", "R", "R", "R"];
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

            $pdf->Cell(45, 6, "Live Due Bills", 0, 0, "L");
            $pdf->SetTextColor(0);
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
            $fines = 0;
            $this->load->model('fine_model');
            $db_fines = $this->fine_model->get_all_fines();
            $index = 0;
            foreach ($payments_list as $invoice) {
// "#","Bill No.",'Invoice Date' ,"Customer Name","Item Code","Sub Total", "Due Amount", "Due Fines"
                $index++;
                $dec_id = $invoice->prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix . " " . $invoice->customer_name;
                $pdf->Cell($widths[0], $h, $index, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $invoice->inv_created_on, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $customer, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->itm_code, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, is_zero($invoice->subtotal), 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, is_zero($invoice->balance), 1, 0, $text_direction[6]);
                $fine = 0;

                $dStart = new DateTime($invoice->next_installment_date);
                $dEnd = new DateTime();
                $dDiff = $dStart->diff($dEnd);
                $a = $dDiff->format('%r%m');


                foreach ($db_fines as $fn) {
                    if ($a <= doubleval($fn->day)) {
                        $fine = doubleval($fn->fine);
                        break;
                    }
                }
                $fine = (intval($a)) * $fine;


                $fines += $fine;
                $pdf->Cell($widths[7], $h, is_zero($fine), 1, 1, $text_direction[7]);

                $totals += doubleval($invoice->total);
                $balances += doubleval($invoice->balance);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths) - 85, $h, "Totals", 1, 0, "R");
            $pdf->Cell(30, $h, is_zero($totals), 1, 0, "R");
            $pdf->Cell(30, $h, is_zero($balances), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($fines), 1, 1, "R");


            $pdf->add_signature();
            $pdf->Output("live-due-payments.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function collection_bills()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $s = $this->input->get("s");
            $b = $this->input->get("b");
            $d = $this->input->get("d");

            $payments_list = $this->invoice_model->get_collection_bill_report($s, $b, $d);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#', "Bill No.", "Customer Name", "Branch", "Devision", "Due Date", "Total", "Installment", "Balance"];
            $widths = [8, 25, 60, 60, 30, 25, 24, 24, 24];
            $text_direction = ["L", "L", "L", "L", "L", "L", "R", "R", "R"];
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

            $pdf->Cell(45, 6, "Due Payments", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . (!empty($s) ? (date("M d, Y", strtotime($s))) : " From " . date("M d, Y"))), "0", 1, "R");
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
            $i = 0;
            foreach ($payments_list as $invoice) {
                $i++;

                $dec_id = $invoice->prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix . " " . $invoice->customer_name;
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $customer, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->branch_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->devision, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $invoice->next_installment_date, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, is_zero($invoice->total), 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, is_zero($invoice->installment_amount), 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, is_zero($invoice->balance), 1, 1, $text_direction[8]);

                $totals += doubleval($invoice->total);
                $payments += doubleval($invoice->installment_amount);
                $balances += doubleval($invoice->balance);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths) - 72, $h, "Totals", 1, 0, "R");
            $pdf->Cell(24, $h, is_zero($totals), 1, 0, "R");
            $pdf->Cell(24, $h, is_zero($payments), 1, 0, "R");
            $pdf->Cell(24, $h, is_zero($balances), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("due-payments.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function bill_issue_summary()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("invoice_model");
            $s = $this->input->get("s");
            $b = $this->input->get("b");
            $d = $this->input->get("d");

            $payments_list = $this->invoice_model->get_collection_bill_report($s, $b, $d);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ['#', "Bill No.", "Customer Name", "Branch", "Devision", "Due Date", "Total", "Installment", "Balance"];
            $widths = [8, 25, 62, 55, 30, 25, 25, 25, 25];
            $text_direction = ["L", "L", "L", "L", "L", "L", "R", "R", "R"];
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

            $pdf->Cell(45, 6, "Due Payments", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(148, 6, ("Date : " . (!empty($s) ? (date("M d, Y", strtotime($s))) : " From " . date("M d, Y"))), "0", 1, "R");
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
            $i = 0;
            foreach ($payments_list as $invoice) {
                $i++;

                $dec_id = $invoice->prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT);
                $customer = $invoice->customer_prefix . " " . $invoice->customer_name;
                $pdf->Cell($widths[0], $h, $i, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $dec_id, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $customer, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $invoice->branch_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $invoice->devision, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $invoice->next_installment_date, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, is_zero($invoice->total), 1, 0, $text_direction[6]);
                $pdf->Cell($widths[7], $h, is_zero($invoice->installment_amount), 1, 0, $text_direction[7]);
                $pdf->Cell($widths[8], $h, is_zero($invoice->balance), 1, 1, $text_direction[8]);

                $totals += doubleval($invoice->total);
                $payments += doubleval($invoice->installment_amount);
                $balances += doubleval($invoice->balance);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths) - 75, $h, "Totals", 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($totals), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($payments), 1, 0, "R");
            $pdf->Cell(25, $h, is_zero($balances), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("due-payments.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

}
