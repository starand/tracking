<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.DRIVER);

    isset($_GET['did']) or die("Не вказано водія!");
    $did = (int)$_GET['did'];
    $driver = get_driver($did) or show_error("Водія не знайдено! '{$_GET['did']}'");
    $hiring = get_driver_hirings($did);
    $po = get_driver_po($did);
?>

<center>
<h2>Водій: <?=$driver['d_name'];?></h2>
<?
    $add_drv = $driver['d_state'] == STATE_ACTUAL
        ? "<input type='button' id='delete-driver'  style='color:red;' value=' Звільнити водія ' />"
        : "<input type='button' id='restore-driver' style='color:red;' value=' Поновити водія ' />";
?>
<TABLE cellspacing='0' cellpadding='2' style='width:550px;' class='menu'>
<TR>
    <TD> </TD>
<?
    echo hasPermission(DEL.DRIVER) ? "<TD style='width:110px;'><a id='drivers-info'> $add_drv </a></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:550px;'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про водія </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>ПІБ</b>: &nbsp; </TD>
        <TD class='edit-item' id='name' style='width:350px;'> &nbsp; <?=$driver['d_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Мобільний тел.</b>: &nbsp; </TD>
        <TD class='edit-item' id='phone' style='width:350px;'>&nbsp; <?=$driver['d_phone'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Стаж</b>: &nbsp; </TD>
        <TD class='edit-item' id='stag' style='width:350px;'>&nbsp; <?=$driver['d_stag'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Медична довідка</b>: &nbsp; </TD>
        <TD class='edit-item' id='insurance' style='width:350px;'>&nbsp; <?=$driver['d_insurance'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Підприємець</b>: &nbsp; </TD>
        <TD class='edit-item' id='poid' style='width:350px;'>&nbsp; <? echo "{$po['po_name']} - {$po['po_phone']}"; ?> &nbsp; </TD>
    </TR>

    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>День народження</b>: &nbsp; </TD>
        <TD class='edit-item' id='birthday' style='width:350px;'>&nbsp; <?=$driver['d_birthday'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>День народж.дружини</b>: &nbsp; </TD>
        <TD class='edit-item' id='wbirthday' style='width:350px;'>&nbsp; <?=$driver['d_wife_birthday'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Діти</b>: &nbsp; </TD>
        <TD class='edit-item' id='children' style='width:350px;'>&nbsp; <?=$driver['d_children'];?> &nbsp; </TD>
    </TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Адреса</b>: &nbsp; </TD>
        <TD class='edit-item' id='address' style='width:350px;'>&nbsp; <?=$driver['d_address'];?> &nbsp; </TD>
    </TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Паспорт</b>: &nbsp; </TD>
        <TD class='edit-item' id='passport' style='width:350px;'>&nbsp; <?=$driver['d_passport'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Ідентиф.код</b>: &nbsp; </TD>
        <TD class='edit-item' id='idcode' style='width:350px;'>&nbsp; <?=$driver['d_idcode'];?> &nbsp; </TD>
    </TR>    
</TABLE>
<BR>

<?
    if (hasPermission(EDIT.DRIVER) && hasPermission(VIEW.ROUTE)) {
        echo "<a id='add-driver-route'> Додати маршрут </a>";
    }
    if (hasPermission(VIEW.ROUTES)) {
        echo "<div id='add-route-content'>";
        include_once "driver-routes.php";
        echo "</div><BR>";
    }

    if (hasPermission(VIEW.HIRINGS, false)) {
        echo "<div>";
        include_once "driver-hirings.php";
        echo "</div>";
    }

    $editables = hasPermission(EDIT.DRIVER)
        ? "'name', 'phone', 'stag', 'address', 'passport', 'idcode', 'birthday', 'wbirthday', 'children', 'insurance', 'poid'"
        : "'nopermission'";
?>

<script>
$(document).ready(function() {
    var editables = [<?=$editables;?>];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (editables.indexOf(id) >= 0) {
            url = "edit-driver.php?" + id + "=&did=<?=$did;?>&edit=";
            $('#' + id).load(url);
        }
    });

    $("#add-driver-route").on("click", function() {
        $("#add-route-content").load("add-driver-route.php?did=<?=$did;?>");
    });

    $("#delete-driver").click(function() {
        $("#main_space").load("drivers.php?dd=<?=$did;?>");
    });
    $("#restore-driver").click(function() {
        $("#main_space").load("drivers.php?rd=<?=$did;?>");
    });
});
</script>