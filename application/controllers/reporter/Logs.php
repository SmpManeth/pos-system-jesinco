<?php

/**
 * Description of Goods-issue
 *
 * @author DP4
 * Oct 8, 2018 12:05:10 PM
 */
class Logs extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get_logs(){
        if ($this->ion_auth->logged_in()) {
            $this->load->model("common_model");
            $from = $this->input->get("s");
            $to = $this->input->get("e");

            $logs = $this->common_model->get_Logs_report($this->branch,$from,$to);

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

            $columns = ["Date", "User", "Type/Action","Description"];
            
            $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
            $sheet->setCellValue("A1", $this->branch->branch_name);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
            $sheet->setCellValue("A2", "Logs");
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
            $from_to = (!empty($from) && !empty($to)?("From : ".$from." To : ".$to):(!empty($from)?$from:(!empty($to)?$to:"")));
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
            $sheet->freezePane('A'.$row);

            foreach ($logs as $log) {
                $date = date("M d, Y h:m A",  strtotime($log->at));
                $sheet->setCellValue($this->get_Letter(1) . "" . $row, $date);
                $sheet->setCellValue($this->get_Letter(2) . "" . $row, $log->username);
                $sheet->setCellValue($this->get_Letter(3) . "" . $row, $log->section);
                $sheet->setCellValue($this->get_Letter(4) . "" . $row, $log->action);
                
                $row++;
            }
            $row++;
            
            // header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="logs.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } else {
            redirect(base_url("login"));
        }
    }
    public function get_adjustments(){
        if ($this->ion_auth->logged_in()) {
            $this->load->model("common_model");
            $from = $this->input->get("s");
            $to = $this->input->get("e");
            $item = $this->input->get("i");

            $adjs = $this->common_model->get_adjustments_report($this->branch,$from,$to,$item);

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

            $columns = ["Item Code", "Item Name", "Direction","Quantity","Remarks","Date","User"];
            
            $sheet->mergeCells('A1:' . $this->get_Letter(count($columns)) . "1");
            $sheet->setCellValue("A1", $this->branch->branch_name);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:' . $this->get_Letter(count($columns)) . "2");
            $sheet->setCellValue("A2", "Stock Adjustments");
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A3:' . $this->get_Letter(count($columns)) . "3");
            $from_to = (!empty($from) && !empty($to)?("From : ".$from." To : ".$to):(!empty($from)?$from:(!empty($to)?$to:"")));
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
            $sheet->freezePane('A'.$row);

            foreach ($adjs as $adjust) {
                $date = date("M d ,Y h:i a", strtotime($adjust->adj_time));
                $sheet->setCellValue($this->get_Letter(1) . "" . $row, $adjust->itm_code);
                $sheet->setCellValue($this->get_Letter(2) . "" . $row, $adjust->itm_name);
                $sheet->setCellValue($this->get_Letter(3) . "" . $row, $adjust->direction == "1" ? "Increase ↑" : "Descrease ↓");
                $sheet->setCellValue($this->get_Letter(4) . "" . $row, $adjust->qty);
                $sheet->setCellValue($this->get_Letter(5) . "" . $row, $adjust->remarks);
                $sheet->setCellValue($this->get_Letter(6) . "" . $row, $date);
                $sheet->setCellValue($this->get_Letter(7) . "" . $row, $adjust->username);
                
                $sheet->getStyle($this->get_Letter(1).$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $row++;
            }
            $row++;
            
            // header('Content-Type: application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet');
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="stock_adjustments.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } else {
            redirect(base_url("login"));
        }
    }
}