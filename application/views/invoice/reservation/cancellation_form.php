<?php 

$total = doubleval($invoice->subtotal);
$discount = doubleval($invoice->discount);
$balance = doubleval($invoice->balance);

$paid_amount = $total - $discount - $balance;

$total_paid = $total_paid;// + doubleval($invoice->service_charge);

?>
<div class="row">
    <div class="col-lg-12">

        <?php echo form_open("#", "id='return-form' class='form-horizontal'") ?>
        <?php echo form_hidden("is_ajax_request") ?>
        <?php echo form_hidden("inv_id", $invoice->id) ?>
        <?php echo form_hidden("type", $type) ?>
            <table class="table table-bordered">
                <caption>If any of item return, then enter return quantity and if the are damaged.<br/>
                Please enter a return quantity for damaged products also.
                </caption>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Return Quantity</th>
                        <th>Damaged</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($items as $item) {
                            ?>
                            <tr>
                                <td><?php echo $item->itm_code?><br/><?php echo $item->itm_name?></td>
                                <td><?php echo $item->display_qty?></td>
                                <td><input type="text" name="ret_qty[<?php echo $item->id?>]" class='form-control input-sm number' style="width:75px;" max="<?php echo $item->display_qty?>"/></td>
                                <td><input type="checkbox" name="damaged[<?php echo $item->id?>]" value="1" class='form-control input-xs' style="width:25px;" /></td>
                            </tr>
                            <?php 
                        }
                        ?>
                </tbody>
            </table>

            <div>
                <div <?php echo $type=="do"?"style='display:none'":''?>>
                    <div class="form-group">
                        <label for="first_name" class="col-lg-4 control-label">1/3 of Paid Amount</label>
                        <div class="col-sm-6">
                            <input type="text" name="refund_amount" id="refund_amount" readonly="true" class="form-control input-sm number-2" value="<?php  echo round(($total_paid / 3),2) > get_option('service-charge',null,$branch->id) ? round(($total_paid / 3),2) : 0 ?>" />
                            <small>(Calculated automatically)</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="col-lg-4 control-label">Unpaid Fines</label>
                        <div class="col-sm-6">
                            <input type="text" name="unpaid_fines" id="unpaid_fines" class="form-control input-sm number-2" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="col-lg-4 control-label">Damage Deductions</label>
                        <div class="col-sm-6">
                            <input type="text" name="damaged_fines" id="damaged_fines" class="form-control form-control-sm number-2" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="col-lg-4 control-label">Refund Amount</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="total_refund_html"></p>
                            <input type="hidden" id="total_refund" name="total_refund" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="first_name" class="col-lg-4 control-label">Remarks</label>
                    <div class="col-sm-6">
                        <textarea name="remark" class="form-control input-sm "></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <?php
         if (user_can_edit()) {
            ?>
            <button class="btn btn-danger btn-sm pull-left" id="just_cancalltion_btn"><i class="fa fa-times"></i> <span>Just Cancel</span></button>
            <?php
        }
        ?>
        <button class="btn btn-primary btn-sm pull-right" id="cancalltion_btn"><i class="fa fa-times"></i> <span>Cancel with Return</span></button>
    </div>
</div>  

<script>
$(document).ready(function(){
    $(".number").number(true,2);

    $("#cancalltion_btn").click(function(){
        var item_data = $("#return-form").serializeArray();
        
        DJ.disable_btn_fa('cancalltion_btn', "Processing");

        $.post("<?php echo base_url("reservation/cancel_reservation_note"); ?>", item_data, function (data) {
            DJ.enable_btn_fa('cancalltion_btn', "Cancel with Return");
            if (data.msg_type == "OK") {
                DJ.Notify(data.msg, "success");
                location.href = data.url;
            } else {
                DJ.Notify(data.msg, "danger");
            }
        }, "JSON");
    });
    $("#just_cancalltion_btn").click(function(){

        DJ.Overlay_confirm({
                title: "Are you want to <strong class='text-danger'>JUST CANCEL</strong> this Reservation Note?",
                button: {
                    yes: {txt: "YES"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {

                        var item_data = $("#return-form").serializeArray();
                        item_data.push({name: "cancel_only", value:"1"});                      
                        
                        DJ.disable_btn_fa('just_cancalltion_btn', "Processing");

                        $.post("<?php echo base_url("reservation/cancel_reservation_note"); ?>", item_data, function (data) {
                            DJ.enable_btn_fa('just_cancalltion_btn', "Just Cancel");
                            if (data.msg_type == "OK") {
                                DJ.Notify(data.msg, "success");
                                location.href = data.url;
                            } else {
                                DJ.Notify(data.msg, "danger");
                            }
                        }, "JSON");

                    }
                }
            });
    });



    $("#unpaid_fines").on("keyup", function () {
        calculate_refund_amount();
    });
    $("#damaged_fines").on("keyup", function () {
        calculate_refund_amount();
    });
    calculate_refund_amount();
});

function calculate_refund_amount() {

var refund_amo1_3 = $("#refund_amount").val();
var unpaid_fines = $("#unpaid_fines").val();
var damaged_fines = $("#damaged_fines").val();

var total_refund_amo = Number(refund_amo1_3) - Number(unpaid_fines) - Number(damaged_fines);

$("#total_refund").val(total_refund_amo);
$("#total_refund_html").html(DJ.format_number(total_refund_amo, 2));
}
</script>