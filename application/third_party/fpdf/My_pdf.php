<?php

/**
 * This PDF Library Created based on FPDF Library.
 * Feel free to Modyfy
 *
 * @author Dilshan Jayasanka
 */
//include_once './tcpdf.php';
require_once APPPATH . 'third_party/fpdf/tfpdf.php';

class My_pdf extends tFPDF {

    protected $col = 0; // Current column
    public $y0;      // Ordinate of column start
    protected $is_devided;      // is devided in to columns
    protected $footer_text;      // footer text with page number
    protected $set_footer = TRUE;      // footer text with page number
    protected $header_data;      // footer text with page number
    protected $header_data_offset;      // footer text with page number
    protected $sub_header_data;      // footer text with page number
    protected $fance_header_data;
    protected $fance_header_data_widths;
    protected $fance_header_data_t_directions;
    protected $fance_header_data_t_height;
    protected $fance_header_data_fill;
    protected $fance_header_data_repeat = FALSE;
    protected $fill_color_red = 220;
    protected $fill_color_green = 220;
    protected $fill_color_blue = 220;
    protected $text_color_red = 0;
    protected $text_color_green = 0;
    protected $text_color_blue = 0;

    public function LoadData($file) {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach ($lines as $line) {
            $data[] = explode(';', chop($line));
        }
        return $data;
    }

    function SetDash($black = null, $white = null) {
        if ($black !== null)
            $s = sprintf('[%.3F %.3F] 0 d', $black * $this->k, $white * $this->k);
        else
            $s = '[] 0 d';
        $this->_out($s);
    }

    // Better table
    function ImprovedTable($header, $data) {
        // Column widths
        $w = array(40, 35, 40, 45);
        // Header
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        $this->Ln();
        // Data
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR');
            $this->Cell($w[1], 6, $row[1], 'LR');
            $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R');
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R');
            $this->Ln();
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }

// Colored table

    function create_table_columns($data, $is_every_page = FALSE, $offset = 0) {
        $this->SetFillColor($this->fill_color_red, $this->fill_color_green, $this->fill_color_blue);
        $this->SetTextColor($this->text_color_red, $this->text_color_green, $this->text_color_blue);
        $this->SetDrawColor(41, 134, 162);
        $this->SetLineWidth(.3);
        for ($i = 0; $i < count($data); $i++) {
            for ($idx_r = 0; $idx_r < count($data[$i]); $idx_r++) {
                if (count($data[$i]) > 1 && $idx_r == count($data[$i]) - 1) {
                    $this->Cell($data[$i][$idx_r][0], $data[$i][$idx_r][1], $data[$i][$idx_r][2], $data[$i][$idx_r][3], 1, $data[$i][$idx_r][5], true);
                    $this->SetX($offset);
                } else {
                    $this->Cell($data[$i][$idx_r][0], $data[$i][$idx_r][1], $data[$i][$idx_r][2], $data[$i][$idx_r][3], 0, $data[$i][$idx_r][5], true);
                }
            }
        }
        if ($is_every_page) {
            $this->header_data = $data;
            $this->header_data_offset = $offset;
        }
    }

    function clear_header() {
        $this->header_data = array();
    }

    function FancyTable_header($header, $widths, $height, $text_direction = FALSE, $fill = FALSE, $all_pages = false, $border = FALSE) {

        // Color and font restoration
        $this->SetFillColor($this->fill_color_red, $this->fill_color_green, $this->fill_color_blue);
        $this->SetTextColor($this->text_color_red, $this->text_color_green, $this->text_color_blue);
//        $this->SetFont('');
        
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($widths[$i], $height, $header[$i], $border, 0, $text_direction ? $text_direction[$i] : "L", $fill);

        $this->Ln($height);
        if ($all_pages) {
            $this->fance_header_data = $header;
            $this->fance_header_data_repeat = TRUE;
            $this->fance_header_data_t_directions = $text_direction;
            $this->fance_header_data_t_height = $height;
            $this->fance_header_data_widths = $widths;
            $this->fance_header_data_fill = $fill;
        }
        // Data
    }

    public function SetMyFillColor($red, $green, $blue) {
        $this->fill_color_red = $red;
        $this->fill_color_green = $green;
        $this->fill_color_blue = $blue;
    }

    public function SetMyTextColor($red, $green, $blue) {
        $this->text_color_red = $red;
        $this->text_color_green = $green;
        $this->text_color_blue = $blue;
    }

    function FancyTable_data($data, $widths, $height, $text_direction = FALSE) {
        $fill = false;
        $this->SetFillColor($this->fill_color_red, $this->fill_color_green, $this->fill_color_blue);
        $this->SetTextColor($this->text_color_red, $this->text_color_green, $this->text_color_blue);
        for ($i = 0; $i < count($data); $i++) {
            $vals = array_values($data[$i]);
            for ($idx = 0; $idx < count($vals); $idx++) {
                $this->Cell($widths[$idx], $height, $vals[$idx], '1', 0, $text_direction ? $text_direction[$i] : "L", $fill);
                $this->Ln();
            }
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    function FancyTable_data_single($data, $widths, $height, $text_direction = FALSE, $fill = FALSE) {
        for ($i = 0; $i < count($data); $i++) {
            $this->Cell($widths[$i], $height, $data[$i], '1', 0, $text_direction ? $text_direction[$i] : "L", $fill);
        }
        $this->Ln(1);
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    public function Header() {
        if ($this->fance_header_data_repeat) {
            if ($this->PageNo() > 1) {
                $this->SetFillColor($this->fill_color_red, $this->fill_color_green, $this->fill_color_blue);
                $this->SetTextColor($this->text_color_red, $this->text_color_green, $this->text_color_blue);
                for ($i = 0; $i < count($this->fance_header_data); $i++) {
                    $this->Cell($this->fance_header_data_widths[$i], $this->fance_header_data_t_height, $this->fance_header_data[$i], 1, 0, $this->fance_header_data_t_directions ? $this->fance_header_data_t_directions[$i] : "L", $this->fance_header_data_fill);
                }
                $this->Cell(array_sum($this->fance_header_data_widths), $this->fance_header_data_t_height, '', 'T', 1);
            }
        } else {
            if ($this->PageNo() > 1) {
                $this->SetFont("", "B", "");
                $this->SetFillColor(224, 235, 255);
                $this->SetTextColor(0);
                $this->SetDrawColor(41, 134, 162);
                $this->SetLineWidth(.3);
                for ($i = 0; $i < count($this->header_data); $i++) {
                    for ($idx_r = 0; $idx_r < count($this->header_data[$i]); $idx_r++) {
                        if (count($this->header_data[$i]) > 1 && $idx_r == count($this->header_data[$i]) - 1) {
                            $this->Cell($this->header_data[$i][$idx_r][0], $this->header_data[$i][$idx_r][1], $this->header_data[$i][$idx_r][2], $this->header_data[$i][$idx_r][3], 1, $this->header_data[$i][$idx_r][5], true);
                            $this->SetX($this->header_data_offset);
                        } else {
                            $this->Cell($this->header_data[$i][$idx_r][0], $this->header_data[$i][$idx_r][1], $this->header_data[$i][$idx_r][2], $this->header_data[$i][$idx_r][3], 0, $this->header_data[$i][$idx_r][5], true);
                        }
                    }
                }
                $this->SetFont("", "", "");
                $this->Ln(5);
            }
        }
    }

    function set_footer($param) {
        $this->set_footer = $param;
    }

    function Footer() {
        if ($this->set_footer) {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 7);
            $this->SetTextColor(100);
            $this->Cell(0, 10, $this->footer_text, 0, 0, 'L');
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'R');
        }
    }

    function SetCol($col) {
        // Set position at a given column
        $this->col = $col;
        $x = 10 + $col * 98;
        $this->SetLeftMargin($x);
        $this->SetX($x);
        $this->is_devided = true;
    }

    function set_is_devided($param) {
        $this->is_devided = $param;
    }

    function set_footer_text($param) {
        $this->footer_text = $param;
    }

    function AcceptPageBreak() {
//        // Method accepting or not automatic page break
//        if ($this->is_devided) {
//            if ($this->col < 1) {
//                // Go to next column
//                $this->SetCol($this->col + 1);
//                // Set ordinate to top
//                $this->SetY($this->y0);
//                // Keep on page
//                return false;
//            } else {
//                // Go back to first column
//                $this->SetCol(0);
//                // Page break
//                return true;
//            }
//        } else {
        return true;
//        }
    }

   function add_signature(){
        $width = $this->GetPageWidth() - 17;

        $this->SetFont('', '', 9);
        $this->SetDash(1,1);
        $this->Ln(10);
        $label_width = 35;
        $gap = 3;
        $sig_with = 54;
        $middle_gap = ($width/2)- ($label_width + $gap + $sig_with);

        $this->Cell($label_width, 10, 'Requester Name', 0, 0, 'L');
        $this->Cell($gap, 10, ':', 0, 0, 'L');
        $this->Cell($sig_with, 8, '', "B", 0, 'L');

        $this->Cell(10, 10, '', 0, 0, 'L');

        $this->Cell($label_width, 10, 'Authorizer Name', 0, 0, 'L');
        $this->Cell($gap, 10, ':', 0, 0, 'L');
        $this->Cell($sig_with, 8, '', "B", 1, 'L');

        $this->Cell($label_width, 10, 'Requester Signature', 0, 0, 'L');
        $this->Cell($gap, 10, ':', 0, 0, 'L');
        $this->Cell($sig_with, 8, '', "B", 0, 'L');

        $this->Cell(10, 10, '', 0, 0, 'L');

        $this->Cell($label_width, 10, 'Authorizer Signature', 0, 0, 'L');
        $this->Cell($gap, 10, ':', 0, 0, 'L');
        $this->Cell($sig_with, 8, '', "B", 1, 'L');

        $this->Cell($label_width, 10, 'Date', 0, 0, 'L');
        $this->Cell($gap, 10, ':', 0, 0, 'L');
        $this->Cell($sig_with, 8, '', "B", 0, 'L');

        $this->Cell(10, 10, '', 0, 0, 'L');

        $this->Cell($label_width, 10, 'Date', 0, 0, 'L');
        $this->Cell($gap, 10, ':', 0, 0, 'L');
        $this->Cell($sig_with, 8, '', "B", 1, 'L');

    }

}

?>
