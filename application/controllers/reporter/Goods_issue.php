<?php

/**
 * Description of Goods-issue
 *
 * @author DP4
 * Oct 8, 2018 12:05:10 PM
 */
class Goods_issue extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function summary() {
        if ($this->ion_auth->logged_in()) {

            $s = $this->input->get("s");
            $e = $this->input->get("e");

            $this->load->model("gi_item_model");

            $issued_items = $this->gi_item_model->get_issued_items($this->branch, $s, $e);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Date", "Note No.", "Shop Name", "Item Name", "Qty", "Rate", "Amount"];
            $widths = [25, 30, 75, 70, 20, 30, 30];
            $text_direction = ["L", "L", "L", "L", "R", "R", 'R'];
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
            $pdf->Cell(40, 6, "Goods Issue Summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(240, 6, ("Date : " . date("M d, Y ", strtotime($s)) . " - " . date("M d, Y ", strtotime($e))), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);

            $i = 0;
            $h = 6;
            $total = 0;
            $pdf->Ln(2);
            foreach ($issued_items as $i_item) {
                $qty = doubleval($i_item->qty);
                $rate = doubleval($i_item->rate);
                $_total = $qty * $rate;
                $pdf->Cell($widths[0], $h, $i_item->issue_date, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, decorate_code($i_item->gi_id, "gi", $this->prefixes), 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $i_item->branch_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $i_item->itm_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $qty, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $rate, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, is_zero($_total), 1, 1, $text_direction[6]);
                $total += ($_total);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths) - $widths[6], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[6], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("goods-issue-summary.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    public function category_vice_summary() {
        if ($this->ion_auth->logged_in()) {
            $s = $this->input->get("s");
            $e = $this->input->get("e");
            $c = $this->input->get("c");

            $this->load->model("gi_item_model");
            $this->load->model("item_category_model");
            $category = $this->item_category_model->get($c);

            $issued_items = $this->gi_item_model->get_issued_items_category($this->branch, $s, $e, $c);

            $this->load->library('F_pdf');
            $pdf = new My_pdf("L", "mm", "a4");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["Date", "Note No.", "Shop Name", "Item Name", "Qty", "Rate", "Amount"];
            $widths = [25, 30, 75, 70, 20, 30, 30];
            $text_direction = ["L", "L", "L", "L", "R", "R", 'R'];
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
            $pdf->Cell(40, 6, "Goods Issue Summary");
            $pdf->SetFont('', '', '');
            $pdf->Cell(240, 6, ("Date : " . date("M d, Y ", strtotime($s)) . " - " . date("M d, Y ", strtotime($e))), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");


            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 0);
            if (isset($category)) {
                $pdf->Cell(141, 6, " Filter By : " . $category->cat_name, 0, 1, "R");
            } else {
                $pdf->Cell(141, 6, "", 0, 1);
            }
            $pdf->SetFont('', '', 10);

            $pdf->Cell(00, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);

            $i = 0;
            $h = 6;
            $total = 0;
            $pdf->Ln(2);
            foreach ($issued_items as $i_item) {
                $qty = doubleval($i_item->qty);
                $rate = doubleval($i_item->rate);
                $_total = $qty * $rate;
                $pdf->Cell($widths[0], $h, $i_item->issue_date, 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, decorate_code($i_item->gi_id, "gi", $this->prefixes), 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $i_item->branch_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $i_item->itm_name, 1, 0, $text_direction[3]);
                $pdf->Cell($widths[4], $h, $qty, 1, 0, $text_direction[4]);
                $pdf->Cell($widths[5], $h, $rate, 1, 0, $text_direction[5]);
                $pdf->Cell($widths[6], $h, is_zero($_total), 1, 1, $text_direction[6]);
                $total += ($_total);
            }

            $pdf->Ln(2);
            $pdf->Cell(array_sum($widths) - $widths[6], $h, "Total", 1, 0, "R");
            $pdf->Cell($widths[6], $h, is_zero($total), 1, 0, "R");

            $pdf->add_signature();
            $pdf->Output("goods-issue-summary.pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

}
