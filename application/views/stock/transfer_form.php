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
<?php echo form_open("#", "id='transfer_from' class='form-horizontal'") ?>
<?php echo form_hidden("is_ajax_request") ?>
<?php echo form_hidden("itm_id", $item->id) ?>

<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Item</label>
    <div class="col-sm-6">
        <p class="form-control-static"><?php echo $item->itm_name . " [ $item->itm_code ]" ?></p>
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Branch</label>
    <div class="col-sm-6">
        <select class="form-control" name="branch">
            <optgroup>
                <option value="">--SELECT--</option>
            </optgroup>
            <optgroup>
                <?php
                foreach ($branches as $br) {
                    if ($br->id != $branch->id) {
                        ?>
                        <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                        <?php
                    }
                }
                ?>

            </optgroup>
        </select>
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Qty</label>
    <div class="col-sm-6">
        <input type="number" class="form-control" name="qty" value="1" />
    </div>
</div>
<div class="form-group">
    <label for="address_1" class="col-lg-4 control-label"></label>
    <div class="col-lg-6 text-right">
        <button type="button" class="btn btn-success btn-sm" id="modal-proceed-btn"><i class="fa fa-save"></i> <span>Proceed</span></button>
    </div>
</div>
<?php echo form_close() ?>


<script>
    $(document).ready(function () {
        $("#modal-proceed-btn").click(function (e) {
            DJ.disable_btn_fa("modal-proceed-btn","Processing");
            $.ajax({
                url: "<?php echo site_url("stock/transfer") ?>",
                type: "POST",
                dataType: "JSON",
                data: $('#transfer_from').serialize(),
                success: function(data) {
                    DJ.enable_btn_fa("modal-proceed-btn","Proceed");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        DJ.close_model();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
</script>
