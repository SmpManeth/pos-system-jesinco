<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>        
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <link rel="icon" href="<?php echo base_url("assets/images/favicon.gif") ?>" sizes="32x32">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?php echo base_url("assets/layouts/layout/css/fonts.css") ?>" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url("assets/global/plugins/font-awesome/css/font-awesome.min.css") ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/simple-line-icons/simple-line-icons.min.css") ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap/css/bootstrap.min.css") ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css") ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url("assets/global/css/components.min.css") ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url("assets/global/css/plugins.min.css") ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url("assets/layouts/layout/css/layout.min.css") ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/layouts/layout/css/themes/blue.min.css") ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/layouts/layout/css/custom.min.css") ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/css/my-styles.css") ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <script>
            function full_screen2() {
                if ($("#header_menu").is(":visible")) {
                    $(".page-content").css({"padding-top": "10px"});
                    $("#header_menu").addClass("hidden");
                    document.cookie = "sidebar=min";
                    console.log("min");
                } else {
                    console.log("full");
                    $(".page-content").css({"padding-top": "85px"});
                    $("#header_menu").removeClass("hidden");
                    document.cookie = "sidebar=full";
                }
            }
        </script>
        <script src="<?php echo base_url("assets/global/plugins/jquery.min.js") ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/bootstrap/js/bootstrap.min.js") ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/js.cookie.min.js") ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/jquery.easing.js") ?>" type="text/javascript"></script>
        <script type="text/javascript">
            var csfrData = '<?php echo $this->security->get_csrf_hash(); ?>';
            var base_url = '<?php echo base_url() ?>';
        </script>
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]--> 
        <style>
            .upper-link{
                text-decoration: none;
                color:#fff;
            }
            .upper-link:hover{
                text-decoration: none;
                color:#fff;
                border-bottom: 1px dashed #fff;
            }
        </style>
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <div class="menu-toggler sidebar-toggler">
                            <span></span>
                        </div>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu pull-left">
                        <ul class="nav navbar-nav pull-left">
                            <!-- BEGIN NOTIFICATION DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after "dropdown-extended" to change the dropdown styte -->
                            <!-- DOC: Apply "dropdown-hoverable" class after below "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
                            <!-- DOC: Remove "dropdown-hoverable" and add data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to the below A element with dropdown-toggle class -->
                            <!-- END NOTIFICATION DROPDOWN -->
                            <!-- BEGIN INBOX DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="">
                                <span style="font-size: 18px;color: #fff;line-height: 48px;">
                                    <a class="upper-link" href="<?php echo base_url("home") ?>"><?php echo $company->company_name ?></a> &rAarr;<small style="color: #ccc">&nbsp;<a class="upper-link" href="<?php echo base_url("change-company") ?>"><?php echo $branch->branch_name_report ?></a></small>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <?php
                                    $im_url = $user->avatar;
                                    ?>
                                    <span class="username username-hide-on-mobile"> <?php echo $user->first_name ?> </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default" role="menu">
                                    <li>
                                        <a href="<?php echo base_url("my-profile") ?>">
                                            <i class="icon-user"></i> My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="app_calendar.html">
                                            <i class="icon-calendar"></i> My Calendar </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url("my-company") ?>">
                                            <i class="icon-briefcase"></i>&nbsp;Company Details
                                        </a>
                                    </li>
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="<?php echo base_url("change-company") ?>">
                                            <i class="icon-lock"></i> Change Company </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url("users/logout") ?>">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <!-- BEGIN SIDEBAR MENU -->
                        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->

                            <?php
                            $segment = $this->uri->segment(1);
                            if (isset($directories) && FALSE) {
                                foreach ($directories as $directory) {
                                    $fa_class = $directory["class"];
                                    $menu_name = $directory["name"];
                                    $sub_menus = $directory["subs"];
                                    ?>
                                    <li class="nav-item 
                                    <?php
                                    if (($segment == "register" || $segment == "home" || $segment == "site") && strtolower($menu_name) == "home") {
                                        echo ' active open';
                                    }
                                    if ($segment == "wl-admin" && strtolower($menu_name) == "admin") {
                                        echo ' active open';
                                    }
                                    if (($segment == "po" || $segment == "grn") && strtolower($menu_name) == "stock") {
                                        echo ' active open';
                                    }
                                    if ($segment == "invoice" && strtolower($menu_name) == "invoice") {
                                        echo ' active open';
                                    }
                                    ?> ">
                                        <a href="javascript:;" class="text-uppercase nav-link nav-toggle font-white">
                                            <i class="<?php echo $fa_class; ?>"></i>
                                            <span class="title"><?php echo $menu_name ?></span>
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu">
                                            <?php
                                            if (isset($sub_menus)) {
                                                foreach ($sub_menus as $sub_menu) {
                                                    $sub_name = $sub_menu["name"];
                                                    $sub_class = $sub_menu["class"];
                                                    $menus = $sub_menu["menus"];
                                                    if (!empty($sub_name)) {
                                                        ?>
                                                        <li class="nav-item start">
                                                            <a  class="text-uppercase text-strong nav-link nav-toggle" href="#">

                                                                <span><?php echo $sub_name ?></span>
                                                                <span class="arrow"></span>
                                                            </a>
                                                            <ul class="sub-menu">
                                                                <?php
                                                                foreach ($menus as $menu) {
                                                                    ?>
                                                                    <li>
                                                                        <a class="title font-white" href="<?php echo base_url($menu->url) ?>"><i class="<?php echo $menu->icon_class; ?>"></i> <?php echo $menu->menu_name ?></a>
                                                                    </li>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </li>
                                                        <?php
                                                    } else {
                                                        foreach ($menus as $menu) {
                                                            ?>
                                                            <li class="text-uppercase nav-link nav-toggle">
                                                                <a class="title font-white" href="<?php echo base_url($menu->url) ?>"><i class="<?php echo $menu->icon_class; ?>"></i> 
                                                                    <span><?php echo $menu->menu_name ?></span>
                                                                </a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                            <?php
                            if (isset($directories)) {
                                foreach ($directories as $directory) {
                                    $fa_class = $directory["class"];
                                    $menu_name = $directory["name"];
                                    $sub_menus = $directory["subs"];

                                    if (isset($sub_menus)) {
                                        foreach ($sub_menus as $sub_menu) {
                                            $sub_name = $sub_menu["name"];
                                            $sub_class = $sub_menu["class"];
                                            $menus = $sub_menu["menus"];
                                            if (!empty($sub_name)) {
                                                ?>
                                                <li class="nav-item start <?php
                                                if (($segment == "grn") && strtolower($sub_name) == "goods receive note") {
                                                    echo ' active open';
                                                }
                                                if (($segment == "invoice") && strtolower($sub_name) == "invoice") {
                                                    echo ' active open';
                                                }
                                                if (($segment == "po") && strtolower($sub_name) == "purchasing order") {
                                                    echo ' active open';
                                                }
                                                if (($segment == "stock") && strtolower($sub_name) == "stock") {
                                                    echo ' active open';
                                                }
                                                if (($segment == "register") && strtolower($sub_name) == "registration") {
                                                    echo ' active open';
                                                }
                                                if (($segment == "goods-issue") && strtolower($sub_name) == "goods issue note") {
                                                    echo ' active open';
                                                }
                                                if (($segment == "reporter") && strtolower($sub_name) == "reports") {
                                                    echo ' active open';
                                                }
                                                if (($segment == "wl-admin") && strtolower($sub_name) == "company") {
                                                    echo ' active open';
                                                }
                                                if (($segment == "admin") && strtolower($sub_name) == "settings") {
                                                    echo ' active open';
                                                }
                                                ?>">
                                                    <a  class="text-uppercase nav-link nav-toggle" href="#">
                                                        <?php
                                                        if (isset($sub_class)) {
                                                            ?>
                                                            <i class="<?php echo $sub_class ?>"></i>
                                                            <?php
                                                        }
                                                        ?>
                                                        <span><?php echo $sub_name ?></span>
                                                        <span class="arrow"></span>
                                                    </a>
                                                    <ul class="sub-menu">
                                                        <?php
                                                        foreach ($menus as $menu) {
                                                            ?>
                                                            <li class="text-uppercase nav-link nav-toggle">
                                                                <a class="title font-white" href="<?php echo base_url($menu->url) ?>"><i class="<?php echo $menu->icon_class; ?>"></i> <?php echo $menu->menu_name ?></a>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                                <?php
                                            } else {
                                                foreach ($menus as $menu) {
                                                    ?>
                                                    <li class="nav-item start">
                                                        <a class="title text-uppercase nav-link nav-toggle" href="<?php echo base_url($menu->url) ?>"><i class="<?php echo $menu->icon_class; ?>"></i> <span><?php echo $menu->menu_name ?></span></a>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- END THEME PANEL -->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <h1 class="page-title pull-left">
                                <?php echo isset($head) && $head ? $head : "Dashboard" ?> &nbsp;&nbsp;
                                <span class="text-info" id="doc_info" style="<?php echo isset($doc_id) ? "" : "display: none"; ?>"><span></span> : <b><?php echo isset($doc_id) ? $doc_id : "" ?></b></span>
                            </h1>
                            <ul class="page-breadcrumb pull-right text-uppercase">

                                <?php
                                if (isset($breadcrums)) {
                                    $count = count($breadcrums);
                                    $i = 0;
                                    foreach ($breadcrums as $breadcrum) {
                                        $i++;
                                        if ($i != $count) {
                                            ?>
                                            <li>
                                                <a href="<?php echo base_url($breadcrum[0]) ?>"><?php echo $breadcrum[1] ?></a>
                                                <i class="fa fa-circle"></i>
                                            </li>
                                            <?php
                                        } else {
                                            ?>
                                            <li class="active"><?php echo $breadcrum ?></li>
                                            <i class="fa fa-circle"></i>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <div>
                            <?php
                            if (isset($subviews)) {
                                foreach ($subviews as $subview) {
                                    $this->load->view($subview);
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner">
                    <?php echo date("Y") ?> &copy; Powered by The iDea Hub  [<small>Page Processed in <strong>{elapsed_time}</strong> seconds (v<?php echo CI_VERSION; ?>)</small>]
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        <div class="quick-nav-overlay"></div>

        <div class="form">
            <?php
            echo form_open("#", "id='dummy_forum_'");
            echo form_close();
            ?>
        </div>
        <div class="modal fade" id="login_model">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Login Session Expired. Please Login to Continue</h4>
                    </div>
                    <div class="modal-body text-center">
                        <h2>Loading... <img src="<?php echo base_url("assets/images/377.gif") ?>" width="50"/></h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- END QUICK NAV -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<script src="../assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?php echo base_url("assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js") ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/jquery.blockui.min.js") ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js") ?>" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url("assets/global/scripts/app.min.js") ?>" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?php echo base_url("assets/layouts/layout/scripts/layout.min.js") ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/js/vendor/jquery.number.min.js") ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("assets/js/custom_functions.js?v=1.0") ?>" type="text/javascript"></script>
        <script>
            $.ajaxSetup({
                data: {
                    dj_pos: $("#dummy_forum_ [name='dj_pos']").val(),
                    is_ajax_request: "ok"
                }
            });

            $('[data-toggle="tooltip"]').tooltip();
            $(document).ajaxSuccess(function (event, xhr, settings) {
                if (settings.dataType === "JSON") {
                    var data = JSON.parse(xhr.responseText);
                    if (data.msg_type === "LOG") {
                        $("#login_model").modal("show");
                        $.ajax({
                            url: "<?php echo base_url("users/fetch_login") ?>",
                            type: "POST",
                            dataType: "JSON",
                            data: {},
                            success: function (data) {
                                $("#login_model .modal-body").html(data.html);
                            }
                        });
                    }
                }
            });
            $(document).ready(function () {
                var coo_sitebar = DJ.getCookie("sidebar_closed");
                var body = $('body');
                var sidebar = $('.page-sidebar');
                var sidebarMenu = $('.page-sidebar-menu');
                $(".sidebar-search", sidebar).removeClass("open");

                if (coo_sitebar == "0") {
                    body.removeClass("page-sidebar-closed");
                    sidebarMenu.removeClass("page-sidebar-menu-closed");
                } else {
                    body.addClass("page-sidebar-closed");
                    sidebarMenu.addClass("page-sidebar-menu-closed");
                    if (body.hasClass("page-sidebar-fixed")) {
                        sidebarMenu.trigger("mouseleave");
                    }
                }
                $(".number-2").number(true,2);
            });
        </script>
    </body>

</html>