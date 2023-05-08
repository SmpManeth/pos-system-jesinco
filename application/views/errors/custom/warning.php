<title>Permission Denied</title>
<style>
    .card{background: #fcadad; }
</style>
<div class="five-zero-zero-container">
    <div class="error-code">401 </div>
    <div class="error-message">Unauthorized</div>
    <div class="error-message2"><?php echo $warn?></div>
    <div class="button-place">
        <a href="<?php echo base_url("home")?>" class="btn btn-default btn-lg waves-effect">GO TO HOMEPAGE</a>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("body").addClass("five-zero-zero");
    });
</script>