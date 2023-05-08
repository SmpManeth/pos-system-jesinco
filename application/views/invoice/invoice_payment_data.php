<?php //dump($fines);                                                  ?>
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
        $user_can_cancel_payment = user_can($user, CAN_CANCEL_PAYMENT);
        $i = 0;
        $total_collected = 0;
        $total_collected_with_fines = 0;
        $total = 0;
        $show_check = FALSE;
        $_above = array_pop($fines);
        $invoice_total = doubleval($invoice->total);
        $balance = 0;
        $first_pending_pay = TRUE;
        $inv_payment_count = count($invoice_payments);

        $pay_amount = $invoice_installment_data->installment_amount;
        $total_should_paid = count($invoice_payments) * doubleval($pay_amount);

        $feb_forverded = FALSE;
        $_payble_data = [];
        $total_fine = 0;
        for ($index = 0; $index < $invoice_installment_data->installment_count; $index++) {
            $i++;
            $last_payment = (intval($invoice_installment_data->installment_count)) == $i;

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
                        // if (user_can_edit() && $invoice->status !== "1") {
                        if ($inv_payment_count == ($index + 1) && $user_can_cancel_payment && (!isset($type) || $type !== "no-edit")) {
                            ?>
                            <button type="button" class="btn btn-danger btn-xs cancel-btn"
                                    data-id="<?php echo $ipm->id ?>"><i class="fa fa-times"></i></button>
                            <?php
                        }
                        // }
                        $diff = date_different($ipm->pay_date, date('Y-m-d H:i:s'));
                        if ($diff <= 2 || user_can($user, CAN_PRINT_RECEIPT)) {
                            ?>
                            <!-- <a href="<?php echo base_url("invoice/print-receipt/" . $ipm->id); ?>" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-print"></i></a> -->
                            <a href="jesinco.print://receipt/<?php echo $ipm->id; ?>" target="_blank"
                               class="btn btn-primary btn-xs"><i class="fa fa-print"></i></a>
                            <?php
                        }
                        ?>

                        <?php
                        if (isset($ipm->long) && !empty($ipm->long)) {
                            ?>
                            <a target="_blank"
                               href="http://maps.google.com/?q=<?php echo $ipm->lat ?>,<?php echo $ipm->long ?>"
                               class="btn btn-success btn-xs"><i class="fa fa-map-marker"></i></a>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            } else {
                $pay_date = $due_date;
                $date = date("Y-m-01", strtotime($due_date));
                $month_inc = (($index) - $inv_payment_count);

                $_ins_day = $invoice_installment_data->installment_day;
                $ins_day = str_pad($_ins_day, 2, "0", STR_PAD_LEFT);

                $newdate = strtotime("+" . $month_inc . " month", strtotime($date));
                // $pay_date = date("Y-m-$ins_day", $newdate);
                $_next_date = strtotime("+" . ($month_inc + 1) . " month", strtotime($date));

                if ($index > 0) {
                    if (intval(date("m", $newdate)) == 2) {
                        $days_on_month = cal_days_in_month(CAL_GREGORIAN, 2, date("Y"));
                        if (intval($_ins_day) > intval($days_on_month)) {
                            $pay_date = date("Y-m-t", $newdate);
                            $feb_forverded = TRUE;
                        } else {
                            $pay_date = date("Y-02-$ins_day", $newdate);
                        }
                    } else {
                        $pay_date = date("Y-m-$ins_day", $newdate);
                    }
                }

                $_buffer_days = get_option('fine-buffer-days', 3,$branch->id);
                $buffer_days = intval($_buffer_days);
                $is_late = is_date_greater_eq_than_last(date("Y-m-d", strtotime("-" . ($buffer_days - 1) . " days")), date("Y-m-d", $_next_date));
//                    $date_diff = date_different("today", $pay_date);
                $fine = 0;

                $is_next_day_late = false;
                $_n_is_late = is_date_greater_eq_than_last(date("Y-m-d", strtotime("-" . ($buffer_days) . " days")), $pay_date);

                $above = $_above->fine;

                if ($is_late) {

//                    Added to calculate fine x late months
                    $dStart = new DateTime($pay_date);
                    $dEnd = new DateTime();
                    $dDiff = $dStart->diff($dEnd);
                    $a = $dDiff->format('%r%m');

                    $_fine = 0;
                    foreach ($fines as $fn) {
                        $_fine = doubleval($fn->fine);
                    }
                    $fine = (intval($a) + 1) * $_fine;
                    $total_fine = $fine;
                    if (!$last_payment) {
                        $fine = $_fine;
                    }
                } else {
                    if ($_n_is_late) {
                        $fine = $fines[0]->fine;
                    }
                }

                if ($invoice->status == "2") {
                    $fine = 0;
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
                if ($first_pending_pay) {
//                        $payment_count = count($invoice_payments);
//                        $last_pay = array_pop($invoice_payments);
                    $balance_should_paid = $total_should_paid - $total_collected;
                    if ($balance_should_paid > 0) {
                        $dt += $balance_should_paid;
                    }

                    $first_pending_pay = FALSE;
                }
                if ($i == $invoice_installment_data->installment_count) {
                    $dt = doubleval($invoice->total) - $balance;
                }
                $balance += $dt;

                $__data = [
                    'i' => $i,
                    'pay_date' => $pay_date,
                    'dt' => $dt,
                    'is_late' => ($is_late || $_n_is_late) && $fine > 0,
                    'fine' => $fine,
                    'show_check' => !$show_check && (!isset($type) || $type !== "no-edit"),
                ];
                if (!$show_check && (!isset($type) || $type !== "no-edit")) {
                    $show_check = TRUE;
                }
                $_payble_data[] = $__data;
                ?>
                <?php
            }
            ?>

            <?php
        }

        $_i = 0;
        foreach ($_payble_data as $_pay_data) {
            $_i++;
            ?>
            <tr class="<?php echo ($_pay_data['is_late']) ? "danger" : "" ?>">
                <td><?php echo $_pay_data['i'] ?></td>
                <td>
                    <?php echo $_pay_data['pay_date'];
                    ?>
                </td>
                <td>
                    <?php
                    echo number_format($_pay_data['dt'], 2);
                    ?>
                </td>
                <td><?php echo $_pay_data['is_late'] ? number_format(($_i == count($_payble_data)) ? $total_fine : $_pay_data['fine'], 2) : "-" ?></td>
                <td>
                    <?php
                    if ($_pay_data['show_check']) {
                        ?>
                        <button type="button" data-due-date="<?php echo $_pay_data['pay_date'] ?>"
                                data-amount="<?php echo $_pay_data['dt'] ?>"
                                data-fine="<?php echo $_pay_data['fine'] ?>"
                                class="btn btn-success btn-xs ins_pay_btn"><i class="fa fa-check"></i></button>

                        <?php
                    }
                    ?>
                </td>
            </tr>
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

            DJ.load_to_model({
                title: "Make Payment",
                url: "<?php echo base_url("invoice/load_payment_form"); ?>",
                type: "",
                data: {
                    amount: amount,
                    inv_id: inv_id,
                    installment: installment,
                    fine: fine,
                    due_date: due_date
                },
                fade: "fade",
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
