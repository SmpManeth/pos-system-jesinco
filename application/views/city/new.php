<?php ?>
<?php echo form_open("#", "id='city_form'") ?>
<?php echo form_hidden("is_ajax_request") ?>

<div class="form-group">
    <label for="first_name" class="col-lg-2 control-label">City Name</label>
    <div class="col-sm-4">
        <input type="text" placeholder="Name of the City" id="city" name="city" class="form-control">
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-2 control-label">City Name Alt 1</label>
    <div class="col-sm-4">
        <input type="text" placeholder="Name of the City Alternative 1" id="city_alt1" name="city_alt1" class="form-control">
    </div>
</div>
<div class="form-group">
    <label for="address_1" class="col-lg-2 control-label">Amount</label>
    <div class="col-sm-4">
        <<button type="button" class="btn btn-success" id="save-city"><i class="fa fa-save"></i> <span>Save</span></button>
    </div>
</div>
</form>
<?php echo form_close() ?>