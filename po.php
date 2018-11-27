<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.PO);

    isset($_GET['poid']) or show_error("Не вказано підприємця!");
    $poid = (int)$_GET['poid'];
    $po = get_po($poid) or show_error("Підприємець не знайдений! {$_GET['poid']}");
?>

<center>
<h2>Підприємець: <? echo "{$po['po_name']}";?></h2>
<TABLE class='list-content'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про підприємця </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>ПІБ</b>: &nbsp; </TD>
        <TD class='edit-item' id='name' style='width:350px;'> &nbsp; <?=$po['po_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Телефон</b>: &nbsp; </TD>
        <TD class='edit-item' id='phone' style='width:350px;'>&nbsp; <?=$po['po_phone'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Локація</b>: &nbsp; </TD>
        <TD class='edit-item' id='location' style='width:250px;'>&nbsp; <?=$po['l_name'];?> &nbsp; </TD>
    </TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Адреса</b>: &nbsp; </TD>
        <TD class='edit-item' id='address' style='width:250px;'>&nbsp; <?=$po['po_address'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Ліцензія</b>: &nbsp; </TD>
        <TD class='edit-item' id='license' style='width:250px;'>&nbsp; <?=$po['po_license'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>День народження</b>: &nbsp; </TD>
        <TD class='edit-item' id='birthday' style='width:250px;'>&nbsp; <?=$po['po_birthday'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Паспорт</b>: &nbsp; </TD>
        <TD class='edit-item' id='passport' style='width:250px;'>&nbsp; <?=$po['po_passport'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Ідентиф.код</b>: &nbsp; </TD>
        <TD class='edit-item' id='idcode' style='width:250px;'>&nbsp; <?=$po['po_idcode'];?> &nbsp; </TD>
    </TR>
</TABLE>
<BR>

<?
    $editables = hasPermission(EDIT.DRIVER)
        ? "'name', 'phone', 'location', 'address', 'idcode', 'passport', 'license', 'birthday'"
        : "'nopermission'";
?>
<script>
$(document).ready(function() {
    var edittables = [<?=$editables;?>];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id) >= 0) {
            url = "edit-po.php?" + id + "=&poid=<?=$poid;?>&edit=";
            $('#' + id).load(url);
        }
    });
});
</script>