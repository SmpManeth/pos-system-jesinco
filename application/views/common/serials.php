<input type="hidden" id="g_id" name="g_id" value="<?php echo $grn_id ?>" />
<input type="hidden" id="gi_id" name="gi_id" value="<?php echo $gi_id ?>" />
<input type="hidden" id="item_id" name="item_id"  value="<?php echo $itm_id ?>"/>
<?php
if ($loc == "edit_grn") {
    ?>
    <div class="row">
        <div class="col-md-12 ">
            <div class="form-body">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="control-label">Item Serial :</label>
                        <input type="text" class="form-control input-sm" name="serial_number" id="serial_number" />
                    </div>
                    <div class="col-md-7">
                        <label class="control-label">&nbsp;</label><br/>
                        <button type="button" class="btn green" id="add_serial_btn"><span>Add Serial</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <?php
}
?>
<div class="row">
    <div class="col-md-12" style="max-height: 250px; overflow-y: scroll;">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Serial</th>
                    <?php
                    if ($loc == "edit_grn") {
                        ?>
                        <th></th>
                        <?php
                    }
                    ?>
                </tr>
            </thead>
            <tbody id="serial_tbody">
                <?php
                if (isset($serials)) {
                    foreach ($serials as $serial) {
                        ?>
                        <tr id="ser_<?php echo $serial->id ?>">
                            <td><?php echo $serial->serial_no ?></td>
                            <?php
                            if ($loc == "edit_grn") {
                                ?>
                                <td><a onclick="remove_serial(<?php echo $serial->id ?>)"><i class="fa fa-remove font-red-thunderbird"></i></a></td>
                                        <?php
                                    }
                                    ?>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#serial_number").keypress(function (e) {
            if (e.which == 13) {
                $("#add_serial_btn").trigger("click");
            }
        });
        $("#add_serial_btn").click(function () {
            DJ.disable_btn("add_serial_btn", "Adding");
            $.ajax({
                url: "<?php echo base_url("grn/add_serial") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    g_id: $("#g_id").val(),
                    item_id: $("#item_id").val(),
                    serial: $("#serial_number").val()
                },
                success: function (data) {
                    DJ.enable_btn("add_serial_btn", "Add Serial");
                    if (data.msg_type == "OK") {
                        var tr = document.createElement("tr");
                        var td1 = document.createElement("td");
                        var td2 = document.createElement("td");

                        var a = document.createElement("a");
                        $(a).html("<i class='fa fa-remove font-red-thunderbird'></i>").attr({href: "javascript:;"}).click(function () {
                            remove_serial(data.id);
                        });
                        $(td1).html($("#serial_number").val());
                        $(td2).html(a);
                        $(tr).append(td1, td2).attr({id: "ser_" + data.id});
                        $("#serial_tbody").prepend(tr);
                        $(tr).addClass("success");

                        var trr = document.createElement("tr");
                        var tdd1 = document.createElement("td");
                        var tdd2 = document.createElement("td");

                        $(tdd1).html($("#serial_model h4").html());
                        $(tdd2).html($(td1).html());
                        $(trr).append(tdd1, tdd2).attr({id: "itm_ser_" + data.id});
                        $("#item_serial_body").append(trr);

                        var rows = $("#serial_tbody").children().size();
                        var gi_id = $("#gi_id").val();
                        var trs = $("#grn_" + gi_id).children();
                        $(trs[1]).html(rows);
                        var rate = $(trs[2]).html();

                        $(trs[3]).html(DJ.format_number(Number(DJ.replace_coma(rate)) * Number(rows)));
                        calculate();

                        setTimeout(function () {
                            $(tr).removeClass("success");
                        }, 1000);
                        $("#serial_number").val();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
    function remove_serial(id) {
        DJ.Confirm("Do You want to Remove this Serial", function () {
            $.ajax({
                url: "<?php echo base_url("grn/remove_serial") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        $("#ser_" + id).hide(500, function () {
                            $("#ser_" + id).remove();
                        });
                        $("#itm_ser_" + id).hide(500, function () {
                            $("#itm_ser_" + id).remove();
                            var rows = $("#serial_tbody").children().size();
                            var gi_id = $("#gi_id").val();
                            var trs = $("#grn_" + gi_id).children();
                            $(trs[1]).html(rows);
                            var rate = $(trs[2]).html();
                            $(trs[3]).html(DJ.format_number(Number(DJ.replace_coma(rate)) * Number(rows)));
                            calculate();
                        });

                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        }, function () {
            $("#ser_" + id).addClass("warning");
        }, function () {
            $("#ser_" + id).removeClass("warning");
        });
    }
</script>