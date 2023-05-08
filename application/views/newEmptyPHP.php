<?php ?>
<div class="container">
    <div class="row">
        <button id="dd" class="btn">rrrr</button>
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery-ui.min.css'); ?>" type="text/css" />
<script src="<?php echo base_url('assets/js/vendor/jquery.form.js'); ?>" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $("#dd").click(function () {
            SLFIND.Input("Please input date", "date", "date", function () {
                alert($("#date").val());
            });
        });
    });
</script>


<?php
if (isset($sub_menus)) {
    foreach ($sub_menus as $sub_menu) {
        $sub_name = $sub_menu["name"];
        $sub_class = $sub_menu["class"];
        $menus = $sub_menu["menus"];
        ?>
        <li class="dropdown more-dropdown-sub">
            <a href="javascript:;">
                <i class="<?php echo $sub_class ?>"></i> <?php echo $sub_name ?>
            </a>
            <ul class="dropdown-menu">
                <?php
                foreach ($menus as $menu) {
                    ?>
                    <li>
                        <a href="<?php echo base_url($menu["url"]) ?>"><i class="<?php echo $menu["icon_class"]; ?>"></i> <?php echo $menu["menu_name"] ?> </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </li>
        <?php
    }
}
?>