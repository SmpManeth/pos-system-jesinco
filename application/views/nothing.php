<?php ?>
<title>404: Nothings Found</title>
<center>
    <h1>Nothing Found !!!</h1>
    <?php
    if (isset($message)) {
        ?>
        <div class="alert alert-warning">
            <h3><?php echo $message; ?></h3>
        </div>
        <?php
    }
    ?>
    <?php
    $back_link = base_url();
    if (isset($link)) {
        $back_link = $link;
    }
    ?>
    <a href="<?php echo $back_link ?>" class="btn btn-info">Back to Home</a>
</center>