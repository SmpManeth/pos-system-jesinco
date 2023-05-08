<?php ?>
<?php echo form_open("#", "id='city_form' class='form-horizontal'") ?>
<?php echo form_hidden("is_ajax_request") ?>
<?php echo form_hidden("id", $city->id) ?>
<div class="form-group">
    <label for="first_name" class="col-lg-3 control-label"></label>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-3 control-label"></label>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-3 control-label">City Name</label>
    <div class="col-sm-4">
        <input type="text" placeholder="Name of the City" id="city" name="city" class="form-control" value="<?php echo $city->city ?>" />
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-3 control-label">City Name Alt 1</label>
    <div class="col-sm-4">
        <input type="text" placeholder="Name of the City Alternative 1" id="city_alt1" name="city_alt1" class="form-control" value="<?php echo $city->city_alt1 ?>" />
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-3 col-sm-4">
        <button type="button" class="btn btn-success" id="save-city"><i class="fa fa-save"></i> <span>Save</span></button>
    </div>
</div>
<?php echo form_close() ?>
<script>
    $(document).ready(function () {
        $("#save-city").click(function (e) {

        });
    });
</script>
