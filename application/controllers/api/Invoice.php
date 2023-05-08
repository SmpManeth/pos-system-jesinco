<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Invoice extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get($id = FALSE)
	{
        // if(!empty($id)){
        //     $data = $this->db->get_where("items", ['id' => $id])->row_array();
        // }else{
        //     $data = $this->db->get("items")->result();
        // }
        $data = array();
        if($id){
            $this->load->model("invoice_model");
            $this->load->model("invoice_item_model","iim");
            $this->load->model("wl_doc_code_model");
            $this->load->model("branch_model");
            $this->load->model("company_model");
            $this->load->model("invoice_payment_model", "ipm");
            $this->load->model("installment_detail_model", "idm");
            
            
            $invoice = $this->invoice_model->get_invoice($id);
            $branch = $this->branch_model->get($invoice->branch);
            $company = $this->company_model->get($branch->company_id);
            $prefix = $this->wl_doc_code_model->get_prefixes($branch->id);
            
            $branch->company_name = $company->company_name;
            $items = $this->iim->get_invoice_items($id);

            $payments = $this->ipm->get_payments($invoice->id);
            $_paid_amount = 0;
            foreach ($payments as $payment) {
                $_paid_amount += doubleval($payment->payment);
            }

            $invoice_installment_data = $this->idm->get_invoice_installments($invoice->id);

            $invoice->down_payment = number_format($invoice->down_payment,2);
            $invoice->total = number_format($invoice->total,2);
            $invoice->subtotal = number_format($invoice->subtotal,2);
            $invoice->service_charge = number_format($invoice->service_charge,2);
            $invoice->unpaid_fines = number_format($invoice->unpaid_fines,2);
            $invoice->damaged_deduction = number_format($invoice->damaged_deduction,2);
            $invoice->refund = number_format($invoice->refund,2);
            $invoice->balance_string = number_format($invoice->balance,2);
            $invoice->customer = $invoice->customer_prefix . " ".$invoice->customer_name;
            $invoice->do_id = decorate_code($invoice->do_id,"do",$prefix);
            $invoice->inv_id = decorate_code($invoice->inv_id,"invoice",$prefix);
            $invoice->paid_amount = number_format($_paid_amount,2);
            $invoice->inv_date = $invoice->inv_created_on;
            
            $invoice->installment_amount = number_format($invoice_installment_data->installment_amount,2);
            $invoice->installment_count = ($invoice_installment_data->installment_count);
            $invoice->next_installment_date = $invoice_installment_data->next_installment_date;
            

            unset($invoice->created_by);
            unset($invoice->last_edit_by);
            unset($invoice->is_cash);
            unset($invoice->remarks);
            unset($invoice->customer_prefix);
            unset($invoice->customer_name);
            unset($invoice->username);
            unset($invoice->last_edit_at);
            unset($invoice->c24_remarks);
            unset($invoice->c24_date);
            
            unset($branch->status);
            unset($branch->bank_name);
            unset($branch->bank_branch);
            unset($branch->bank_acc_no);
            unset($branch->bank_acc_name);
            unset($branch->e_by);
            unset($branch->e_at);
            unset($branch->main_branch);

            $data["invoice"]= $invoice;
            $data["items"]= $items;
            $data["branch"]= $branch;
        }else{
            $data["invoice"]= "no invoice";
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
	}
    	
}