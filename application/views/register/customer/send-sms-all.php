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
?>
<title>Send SMS to Customers</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <div class="" id="toolbar">
        <button type="button" class="btn btn-success btn-sm sbold uppercase" id="selelct-all"><i class="fa fa-check-square-o"></i> <span>Select All</span></button>
        <button type="button" class="btn btn-default btn-sm sbold uppercase" id="selelct-none"><i class="fa fa-square-o"></i> <span>Select NONE</span></button>
    </div>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="cus_table">
        <thead>
            <tr>
                <th></th>
                <th>Customer</th>
                <th>Division</th>
                <th>Address</th>
                <th>Telephone</th>
                <th>E-mail</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($customers as $customer) {
                ?>
                <tr>
                    <td><input type="checkbox" value="<?php echo $customer->id ?>" /></td>
                    <td><?php echo $customer->customer_prefix . " " . $customer->customer_name ?></td>
                    <td><?php echo $customer->devision ?></td>
                    <td><?php echo implode(",<br>", array($customer->address_line1, $customer->address_line2, $customer->address_city)) ?></td>
                    <td><?php echo implode(",<br>", array($customer->tp1, $customer->tp2)) ?></td>
                    <td><?php echo $customer->email ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-lg-6">
        <textarea class="form-control" placeholder="Enter your message" id="message" name="message"></textarea>
        <button type="button" class="btn btn-success" id="send-msg"><i class="fa fa-send"></i> <span>Send Message</span></button>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#selelct-all").click(function (e) {
            $("#cus_table").find("input").each(function () {
                this.checked = true;
            });
        });
        $("#selelct-none").click(function (e) {
            $("#cus_table").find("input").each(function () {
                this.checked = false;
            });
        });

        $("#send-msg").click(function (e) {
            var ids = [];

            $("#cus_table").find("input").each(function () {
                if (this.checked) {
                    ids.push(this.value);
                }
            });
            alert("Function not awailable yet.");
//            $.ajax({
//                url: "<?php echo site_url("") ?>",
//                type: "POST",
//                dataType: "JSON",
//                data: {
//                    ids: ids,
//                    msg: $("#message").val()
//                },
//                success: function (data) {
//                    if (data.msg_type == "OK") {
//                        SLFIND.Notify(data.msg, "success");
//                    } else {
//                        SLFIND.Notify(data.msg, "danger");
//                    }
//                }
//            });
        });
    });
</script>


