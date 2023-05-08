<?php // dump($fines);                                       ?>
<table class="table table-striped">
    <thead>
        <tr>
            <td></td>
            <td>Due Date</td>
            <td>Payment</td>
            <td>Fine</td>
            <td></td>
        </tr>                
    </thead>
    <tbody id="tbody_payments">
        <?php
        if ($invoice_installment_data) {
            $i = 0;
            $total_collected = 0;
            $total_collected_with_fines = 0;
            $total = 0;
            $show_check = FALSE;
            $_above = array_pop($fines);
            $invoice_total = doubleval($invoice->total);
            $balance = 0;
            for ($index = 0; $index < $invoice_installment_data->installment_count; $index++) {
                $i++;

                $due_date = $invoice_installment_data->next_installment_date;

                if (isset($invoice_payments[$index])) {
                    $ipm = $invoice_payments[$index];
                    $total_collected += doubleval($ipm->payment);
                    $balance += doubleval($ipm->payment);
                    $total += doubleval($ipm->payment);
                    $total_collected_with_fines += (doubleval($ipm->payment) + doubleval($ipm->fine));
                    ?>
                    <tr class="success">
                        <td><?php echo $i ?></td>
                        <td><?php
                            echo $ipm->due_date;
                            if (!empty($ipm->pay_date)) {
                                echo "<br>" . $ipm->pay_date;
                            }
                            ?></td>
                        <td>
                            <?php
                            echo number_format($ipm->payment, 2);
                            ?>
                        </td>
                        <td><?php echo number_format($ipm->fine, 2); ?></td>
                        <td>
                            <?php
                            if (user_can_edit() && $invoice->status !== "1") {
                                if (count($invoice_payments) == ($index + 1)) {
                                    ?>
                                    <button type="button" class="btn btn-danger btn-xs cancel-btn" data-id="<?php echo $ipm->id ?>"><i class="fa fa-times"></i></button>
                                    <?php
                                }
                            }
                            ?>
                            <a href="<?php echo base_url("invoice/print-receipt/" . $ipm->id); ?>" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-print"></i></a>
                        </td>
                    </tr>
                    <?php
                } else {
                    $pay_date = $due_date;
                    $pay_amount = $invoice_installment_data->installment_amount;

                    if ($i > 1) {
                        $date = date("Y-m-01", strtotime($due_date));
                        $newdate = strtotime("+" . ($index - (count($invoice_payments))) . " month", strtotime($date));
                        $_ins_day = $invoice_installment_data->installment_day;
                        $ins_day = str_pad($_ins_day, 2, "0", STR_PAD_LEFT);
                        $pay_date = date("Y-m-$ins_day", $newdate);
                    }
                    $_buffer_days = get_option('fine-buffer-days', 3,$branch->id);
                    $buffer_days = intval($_buffer_days);
                    $is_late = is_date_greater_than_last(date("Y-m-d", strtotime("-" . ($buffer_days - 1) . " days")), $pay_date);
                    $date_diff = date_different("today", $pay_date);
                    $fine = 0;

                    $above = $_above->fine;
                    if ($is_late) {
                        $fine_reverse = array_reverse($fines);
                        foreach ($fines as $fine_rev) {
                            if ($date_diff <= intval($fine_rev->day)) {
                                $fine = doubleval($fine_rev->fine);
                                break;
                            }
                        }
//                        $fine = $above;
                    }

                    $_balance_pay = $invoice_total - $balance;
                    $dt = 0;
                    if (($_balance_pay) == 0) {
                        break 1;
                    }
                    if ($_balance_pay > 0) {
                        if ($_balance_pay > $pay_amount) {
                            $dt = $pay_amount;
                        } else {
                            $dt = $_balance_pay;
                        }
                    }
                    $balance += $dt;
                    ?>
                    <tr class="<?php echo $is_late ? "danger" : "" ?>">
                        <td><?php echo $i ?></td>
                        <td>
                            <?php echo $pay_date;
                            ?>
                        </td>
                        <td>
                            <?php
                            echo number_format($dt, 2);
                            ?>
                        </td>
                        <td><?php echo $fine > 0 ? $fine : "-" ?></td>
                        <td>
                            <?php
                            if (!$show_check) {
                                $show_check = TRUE;
                                ?>
                                <button type="button" data-due-date="<?php echo $pay_date ?>" data-amount="<?php echo $pay_amount ?>" data-fine="<?php echo $fine ?>" class="btn btn-success btn-xs ins_pay_btn"><i class="fa fa-check"></i></button>

                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                <?php
            }
        }
        ?>
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $(".ins_pay_btn").click(function (e) {
            var amount = $(this).data("amount");
            var inv_id = <?php echo $invoice->id ?>;
            var installment = <?php echo $invoice->id ?>;
            var fine = $(this).data("fine");
            var due_date = $(this).data("due-date");

            var ele = this;
            DJ.Overlay_input({
                title: "Are you want to complete this payment?",
                value: amount,
                type: "number",
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
                        DJ.disable_ele_fa(ele, "");
                        $.post("<?php echo base_url("invoice/add_payment"); ?>", {
                            amount: v,
                            inv_id: inv_id,
                            installment: installment,
                            fine: fine,
                            due_date: due_date
                        }, function (data) {
                            DJ.enable_ele_fa(ele, "");
                            if (data.msg_type == "OK") {
                                DJ.Notify(data.msg, "success");
                                if (data.complete == "YES") {
                                    window.location.reload();
                                } else {
                                    load_installment_data();
                                }
//                    window.open(data.url, '_blank');
                            } else {
                                DJ.Notify(data.msg, "danger");
                            }

                        }, "JSON");
                    }
                }
            });

        });
        $(".cancel-btn").click(function (e) {
            var id = $(this).data('id');
            var ele = this;
            DJ.Overlay_confirm({
                title: "Are you want to <strong class='text-danger'>delete</strong> this payment?",
                button: {
                    yes: {txt: "YES"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {
                        DJ.disable_ele_fa(ele, "");
                        $.post("<?php echo base_url("invoice/canel_payment"); ?>", {id: id}, function (data) {
                            DJ.enable_ele_fa(ele, "");
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
