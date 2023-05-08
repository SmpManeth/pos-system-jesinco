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
<?php echo form_open("#", "id='c24_remark_form' class='form-horizontal'") ?>
<?php echo form_hidden("is_ajax_request") ?>
<?php echo form_hidden("inv_id", $invoice->id) ?>

<?php
if ($this->branch->main_branch == "1") {
    ?>
    <div class="form-group">
        <label for="first_name" class="col-lg-4 control-label">Remarks</label>
        <div class="col-sm-8">
            <textarea name="c24_remarks" class="form-control" rows="6"><?php echo $invoice->c24_remarks ?></textarea>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="form-group">
        <label for="first_name" class="col-lg-4 control-label">Remarks</label>
        <div class="col-sm-8">
            <p class="form-control-static"><?php echo nl2br($invoice->c24_remarks) ?></p>
        </div>
    </div>
    <div class="form-group">
        <label for="first_name" class="col-lg-4 control-label">Added Date</label>
        <div class="col-sm-8">
            <p class="form-control-static"><?php echo date("M d, Y h:i a", strtotime($invoice->c24_date)) ?></p>
        </div>
    </div>

    <?php
}
?>
<?php
if ($this->branch->main_branch == "1") {
    ?>
    <div class="form-group">
        <label for="address_1" class="col-lg-4 control-label"></label>
        <div class="col-lg-8 text-right">
            <button type="button" class="btn btn-success" id="modal-add-c24-remark"><i class="fa fa-save"></i> <span>Proceed</span></button>
        </div>
    </div>
    <?php
}
?>
</form>
<?php echo form_close() ?>

<script>
    $(document).ready(function () {
        $(".number-2").number(true, 2);
        $("#modal-add-c24-remark").click(function () {
            DJ.disable_btn_fa('modal-add-c24-remark', 'Processing');
            $.ajax({
                url: "<?php echo base_url("invoice_c/add_c24_remark") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#c24_remark_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa('modal-add-c24-remark', 'Proceed');
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        DJ.close_model();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }
    );
</script>