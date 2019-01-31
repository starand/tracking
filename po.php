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
<?
    $add_po = $po['po_state'] == STATE_ACTUAL
        ? "<a id='delete-po' style='color:red;'>Звільнити підприємця</a>"
        : "<a id='restore-po' style='color:red;'>Поновити підприємця</a>";
?>
<TABLE cellspacing='0' cellpadding='2' style='width:500px;' class='menu'>
<TR>
    <TD> </TD>
<?
    echo hasPermission(DEL.PO) ? "<TD style='width:150px;'><a id='po-info'> $add_po </a></TD>" : "";
?>
</TR>
</TABLE>

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

<TABLE class='list-content'>
    <TR>
        <TD class='list-content-header'> # </TD>
        <TD class='list-content-header'> Працівник </TD>
        <TD class='list-content-header'> Телефон </TD>
    </TR>
<?
    $empoyees = get_po_employees($poid);
    $prefix = hasPermission(VIEW.DRIVER) ? "d" : "";

    if (count($empoyees) == 0) {
        echo "<TR><TD colspan='3'>За цим підприємцем працівників не закріплено!</TD></TR>";
    } else {
        $i = 1;
        foreach ($empoyees as $employee) {
            echo "<TR>
                    <TD class='list-content'> $i </TD>
                    <TD class='list-content' id='$prefix{$employee['d_id']}'> &nbsp; {$employee['d_name']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$employee['d_id']}'> &nbsp; {$employee['d_phone']} &nbsp; </TD>
                </TR>";
            ++$i;
        }
    }
?>
</TABLE>

<BR>
<b>Власник наступних автомобілів:</b>
<TABLE class='list-content'>
<?
    $cars = get_cars_by_owner($po['po_name']);
    include_once "car-list.php";
?>
</TABLE>

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

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            $('#main_space').load("driver.php?did=" + id.substr(1));
        } else if (id.substr(0, 2) == 'po') {
            $('#main_space').load("po.php?poid=" + id.substr(2));
        }
    });

    $("#delete-po").click(function() {
        $("#main_space").load("pos.php?dpo=<?=$poid;?>");
    });
    $("#restore-po").click(function() {
        $("#main_space").load("pos.php?rpo=<?=$poid;?>");
    });
});
</script>