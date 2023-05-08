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
 * Description of Repairs
 *
 * @author Dilshan  Jayasnka
 */
class Repairs extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo 'none of your business';
    }

    public function repair_list() {
        if ($this->ion_auth->logged_in()) {

            $this->data["head"] = "Repair Items";
            $this->data["breadcrums"] = array(array("home", "Home"), array("repair", "Invoce"), "Item Repairs");
            $this->load_view(array("invoice/repair_list/list"));
        } else {
            redirect("login");
        }
    }

    public function print_sendtocompany() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("repair_model");
            $_ids = $this->input->get("ids");
            $ids = explode(",", $_ids);


            $repairs = $this->repair_model->get_selected($ids);
            $this->load->library('F_pdf');
            $pdf = new My_pdf("P", "mm", "a5");
            $pdf->set_is_devided(FALSE);
            $pdf->AcceptPageBreak();
            $pdf->SetAutoPageBreak(true, 00);
            $pdf->set_footer(FALSE);

            $pdf->AddFont('Consolas', '', 'consola.php');
            $pdf->AddFont('Consolas', 'B', 'consolab.php');

            $columns = ["INV NO", "Item Code", "Item Name", "Receive Date"];
            $widths = [25, 20, 60, 25];
            $text_direction = ["L", "L", "L", "L"];
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

            $pdf->Cell(45, 6, "Repair Item Delivery Note", 0, 0, "L");
            $pdf->SetTextColor(0);
            $pdf->SetFont('', '', '');
            $pdf->Cell(85, 6, "Date : " . date("M d, Y"), "0", 1, "R");
            $pdf->SetFont('', '', 10);
            $pdf->Cell(25, 6, "Generate By");

            $pdf->Cell(114, 6, ": " . strtoupper($this->user->username), 0, 1);
            $pdf->SetFont('', '', 10);

            $pdf->Cell(0, 4, "", 0, 1);

//                $pdf->SetDash(01, 1.2);
            $pdf->SetFont("", "B");
            $pdf->FancyTable_header($columns, $widths, 6, $text_direction, FALSE, FALSE, 1);
            $pdf->SetFont("", "");

            $h = 6;
            $cahs = 0;
            $credit = 0;
            $balance = 0;
            foreach ($repairs as $repair) {
                $pdf->Cell($widths[0], $h, decorate_code($repair->inv_id, "invoice", $this->prefixes), 1, 0, $text_direction[0]);
                $pdf->Cell($widths[1], $h, $repair->itm_code, 1, 0, $text_direction[1]);
                $pdf->Cell($widths[2], $h, $repair->itm_name, 1, 0, $text_direction[2]);
                $pdf->Cell($widths[3], $h, $repair->created_date, 1, 1, $text_direction[3]);
            }

            $pdf->Ln(2);
            $pdf->Cell(105, $h, "Total Items", 1, 0, "R");
            $pdf->Cell(25, $h, count($repairs), 1, 0, "R");

            $pdf->Cell(0, 20, "", 0, 1, 0);
            $pdf->Cell(95, 4, "", 0, 0, 0);
            $pdf->Cell(35, 4, "....................", 0, 1, "C");
            $pdf->Cell(95, 4, "", 0, 0, 0);
            $pdf->Cell(35, 4, "Manager", 0, 1, "C");

            $pdf->Output("delevery_note_" . date("Y_m_d") . ".pdf", "I");
        } else {
            redirect(base_url("login"));
        }
    }

    /*
      +--------------------------------------------------------------------------+
      |                                                                          |
      |                             AJAX Requests                                |
      |                                                                          |
      +--------------------------------------------------------------------------+
     */

    public function get_repair_list() {
        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));

        $this->load->model("repair_model");
        $repair_count = $this->repair_model->get_all_repairs($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE);
        $repairs = $this->repair_model->get_all_repairs($this->branch, $start, $length, $column, $direction);

        $dt_array = array();

        foreach ($repairs as $repair) {
            $_d = array(
                "id" => intval($repair->id),
                "cusname" => $repair->customer_prefix . " " . $repair->customer_name,
                "plain_inv_no" => $repair->inv_id,
                "item_name" => $repair->itm_name,
                "inv_id" => decorate_code($repair->invid, "invoice", $this->prefixes),
                "created_date" => $repair->created_date,
                "username" => $repair->username,
                "status" => ($repair->status),
                "sent_date" => $repair->sent_date,
                "returned_date" => $repair->returned_date,
                "handover_date" => $repair->handover_date,
            );
            $dt_array[] = $_d;
        }
        $output = array(
            "total" => $repair_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

    public function change_status() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $status = $this->input->post("status");

            $this->load->model("repair_model");

            $this->repair_model->update($id, array("status" => $status));

            $msgs = array(
                "Product mark as pending",
                "Product sent to company for repair.",
                "Product mark as repairing",
                "Product mark as Repair Done",
                "Product mark as Send to Branch",
                "Product mark as Handover to the Customer"
            );

            $json["msg_type"] = "OK";
            $json["msg"] = $msgs[$status];
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function get_one_row() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model("repair_model");
            $id = $this->input->post("id");
            $repair = $this->repair_model->get_one_row($id);
            $_d = array(
                "id" => intval($repair->id),
                "cusname" => $repair->customer_prefix . " " . $repair->customer_name,
                "plain_inv_no" => $repair->inv_id,
                "item_name" => $repair->itm_name,
                "inv_id" => decorate_code($repair->inv_id, "invoice", $this->prefixes),
                "created_date" => $repair->created_date,
                "username" => $repair->username,
                "status" => ($repair->status),
                "sent_date" => $repair->sent_date,
                "returned_date" => $repair->returned_date,
                "handover_date" => $repair->handover_date,
            );
            $json["msg_type"] = "OK";
            $json["data"] = $_d;
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

}
