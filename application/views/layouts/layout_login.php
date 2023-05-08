<!DOCTYPE html>
<html  style="min-height: 100%; position:relative;">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="SLFIND Inventory Control System " name="description" />
        <meta content="Dilshan Jayasanka" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/font-awesome/css/font-awesome.min.css")?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/simple-line-icons/simple-line-icons.min.css")?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap/css/bootstrap.min.css")?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css")?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo base_url("assets/global/plugins/select2/css/select2.min.css")?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/select2/css/select2-bootstrap.min.css")?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url("assets/global/css/components-md.min.css")?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url("assets/global/css/plugins-md.min.css")?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo base_url("assets/pages/css/login-4.min.css")?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <script src="<?php echo base_url("assets/global/plugins/jquery.min.js")?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/bootstrap/js/bootstrap.min.js")?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/jquery.easing.js")?>" type="text/javascript"></script>
        <link rel="shortcut icon" href="favicon.ico" /> </head>
        
        <script type="text/javascript">
            var csfrData = '<?php echo $this->security->get_csrf_hash(); ?>';
            var base_url = '<?php echo base_url() ?>';</script>

        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body  class=" login">
        <div id="main_content">
            <?php
            if (isset($subviews)) {
                foreach ($subviews as $subview) {
                    $this->load->view($subview);
                }
            }
            ?>
        </div>
        <div class="copyright"> <?php echo date("Y") ?> &copy; Powered by The iDea Hub</div>
        <div class="form">
            <?php
            echo form_open("#", "id='dummy_forum_webl_cm'");
            echo form_close();
            ?>
        </div>
        <script src="<?php echo base_url("assets/global/plugins/js.cookie.min.js")?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js")?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/jquery.blockui.min.js")?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")?>" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?php echo base_url("assets/global/plugins/jquery-validation/js/jquery.validate.min.js")?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/jquery-validation/js/additional-methods.min.js")?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/select2/js/select2.full.min.js")?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/backstretch/jquery.backstretch.min.js")?>" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url("assets/global/scripts/app.min.js")?>" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!--<script src="<?php echo base_url("assets/pages/scripts/login-4.min.js")?>" type="text/javascript"></script>-->
        <script src="<?php echo base_url("assets/js/custom_functions.js")?>" type="text/javascript"></script>
        <script>
            $.ajaxSetup({
                data: {
                    dj_pos: $("#dummy_forum_webl_cm [name='dj_pos']").val(),
                    is_ajax_request: "OK"
                }
            });
            $('[data-toggle="tooltip"]').tooltip();
        </script>
    </body>
</html>