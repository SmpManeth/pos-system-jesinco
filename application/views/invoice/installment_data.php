<div class='form'>
    <div class='form-horizontal'>
        <div class="form-body">
            <div class = "form-group">
                <label class="col-md-5 control-label">Installments : </label>
                <div class = "col-md-7">
                    <p class = "form-control-static text-strong "><?php echo $invoice_installment_data->installment_count ?></p>
                </div> 
                <label class="col-md-5 control-label">Installment amount :</label>
                <div class="col-md-7">
                    <p class="form-control-static text-strong ">
                        <span class="pull-left" id="inv_date"><?php echo $invoice_installment_data->installment_amount ?></span>
                    </p>
                </div>
                <label class="col-md-5 control-label">Next Installment Date :</label>
                <div class="col-md-7">
                    <p class="form-control-static text-strong ">
                        <?php
                        if ($invoice->status == "1") {
                            ?>
                            <span class="pull-left" id="inv_date">-</span>
                            <?php
                        } else {
                            ?>
                            <span class="pull-left" id="inv_date"><?php echo $invoice_installment_data->next_installment_date ?></span>

                            <?php
                        }
                        ?>
                    </p>
                </div>
                <label class="col-md-5 control-label">Installment Day :</label>
                <div class="col-md-7">
                    <p class="form-control-static text-strong ">
                        <span class="pull-left" id="inv_date"><?php echo $invoice_installment_data->installment_day ?><sup><?php echo get_nth($invoice_installment_data->installment_day) ?></sup></span>
                        &nbsp;&nbsp;&nbsp;
                        <?php
                        if (user_can($user, CAN_EDIT_INVOICE_INSTALLMENTS) && (!isset($type) || $type!=="no-edit")) {
                            ?>
                            <a href="#" id="open_update_form"><i class="fa fa-edit"></i></a>
                                <?php
                            }
                            ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#open_update_form").click(function (e) {
            e.preventDefault();
            var id = $("#inv_id").val();
            DJ.Overlay_input({
                title: "Enter Payment Day?",
                type: "select",
                options: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
                no_empty: false,
                greater_than: 0,
                button: {
                    yes: {txt: "OK"},
                    no: {txt: "CANCEL"}
                },
                button: {
                    yes: {txt: "PROCEED"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {
                        $.post("<?php echo base_url("invoice/update_payment_day"); ?>", {day: v, id: id},
                                function (data) {
                                    if (data.msg_type == "OK") {
                                        DJ.Notify(data.msg, "success");
                                        load_installment_data();
                                    } else {
                                        DJ.Notify(data.msg, "danger");
                                    }

                                }, "JSON");
                    }
                }
            });
        });
    });
</script>

