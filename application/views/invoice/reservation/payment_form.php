<?php ?>
<!--<div class="row">-->
<?php echo form_open("#", "id='city_form' class='form-horizontal'") ?>
<?php echo form_hidden("is_ajax_request") ?>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">First Installment Date</label>
    <div class="col-sm-6">
        <input type="text" class="form-control datepicker" placeholder="Please Select First Intallment date"
               name="first_installment_date" id="first_installment_date" />
        <small>If not Selected This Date will calculate automatically according to the Installment Pay Day</small>
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Installment Pay Date</label>
    <div class="col-sm-6">
        <select name="pay_day" id="pay_day" class="form-control">
            <?php
            for ($i = 1; $i < (31); $i++) {
                ?>
                <option value="<?php echo $i ?>">On Every Month <?php echo $i . get_nth($i) ?></option>
                <?php
            }
            ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Installments</label>
    <div class="col-sm-6">
        <select name="installments" id="installments" class="form-control">
            <?php
            foreach ($installments as $installment) {
                ?>
                <option value="<?php echo $installment->month ?>">By Rs.<?php echo $installment->month ?></option>
                <?php
            }
            ?>
        </select>
    </div>
</div>
<?php
$balance = $invoice->balance;
$_ins_count = doubleval($balance) / 2500;
$ins_count = ceil($_ins_count);
?>

<div class="form-group">

    <div class="col-lg-7 text-right">

        <span class="alert alert-info">Estimated Installments (<span id="ins_amount_span"></span> x <strong id="ins_count_span"><?php echo $ins_count ?></strong>)</span>
    </div>
    <div class="col-lg-3 text-right">
        <button type="button" class="btn btn-success" id="save-reservation-invoice"><i class="fa fa-save"></i> <span>Save</span></button>
    </div>
</div>
</form>
<?php echo form_close() ?>

<script>
    var balance = <?php echo $balance ?>;
    $(document).ready(function () {
        $("#down_payment").number(true, 2);
        $("#first_installment_date").datepicker({format: "yyyy-mm-dd", autoclose: true, startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "+") ?>'});

        $("#save-reservation-invoice").click(function () {
            console.log("save-reservation-invoice");
            DJ.disable_btn_fa('save-reservation-invoice', 'Reserving');
            var id = $("#inv_id").val();

            var installments = $("#installments").val();
            var pay_day = $("#pay_day").val();

            $.ajax({
                url: "<?php echo base_url("reservation/make_invoice") ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    installments: installments,
                    first_installment_date: $("#first_installment_date").val(),
                    pay_day: pay_day
                },
                success: function (data) {
                    DJ.enable_btn_fa('save-reservation-invoice', 'Save');
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        window.location.href = data.url;
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        calculate_ins_count();
        $("#installments").change(function () {
            calculate_ins_count();
        });
    });

    function calculate_ins_count() {

        var amount = $("#installments").val();
        console.log(balance);
        console.log(amount);
        var _count = Number(balance) / Number(amount);
        var count = Math.ceil(_count);

        $("#ins_amount_span").html(DJ.format_number(amount, 2));
        $("#ins_count_span").html(count);

    }
</script>
