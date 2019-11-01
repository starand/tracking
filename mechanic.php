<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.MECHANIC);

    isset($_GET['did']) or die("Не вказано автослюсаря!");
    $did = (int)$_GET['did'];
    $mechanic = get_mechanic($did) or show_error("Автослюсаря не знайдено! '{$_GET['did']}'");
    $hiring = get_mechanic_hirings($did);
    $po = get_mechanic_po($did);
?>

<center>
<h2>Автослюсар: <?=$mechanic['m_name'];?></h2>

<?
    $add_drv = $mechanic['m_state'] == STATE_ACTUAL
        ? "<input type='button' id='delete-mechanic'  style='color:red;' value=' Звільнити автослюсаря ' />"
        : "<input type='button' id='restore-mechanic' style='color:red;' value=' Поновити автослюсаря ' />";
?>
<TABLE cellspacing='0' cellpadding='2' style='width:550px;' class='menu'>
<TR>
    <TD> </TD>
<?
    echo hasPermission(DEL.MECHANIC) ? "<TD style='width:110px;'><a id='mechanics-info'> $add_drv </a></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:550px;'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про автослюсаря </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>ПІБ</b>: &nbsp; </TD>
        <TD class='edit-item' id='name' style='width:350px;'> &nbsp; <?=$mechanic['m_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Мобільний тел.</b>: &nbsp; </TD>
        <TD class='edit-item' id='phone' style='width:350px;'>&nbsp; <?=$mechanic['m_phone'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Стаж</b>: &nbsp; </TD>
        <TD class='edit-item' id='stag' style='width:350px;'>&nbsp; <?=$mechanic['m_stag'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Медична довідка</b>: &nbsp; </TD>
        <TD class='edit-item' id='insurance' style='width:350px;'>&nbsp; <?=$mechanic['m_insurance'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Підприємець</b>: &nbsp; </TD>
        <TD class='edit-item' id='poid' style='width:350px;'>&nbsp; <? echo "{$po['po_name']} - {$po['po_phone']}"; ?> &nbsp; </TD>
    </TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>День народження</b>: &nbsp; </TD>
        <TD class='edit-item' id='birthday' style='width:350px;'>&nbsp; <?=$mechanic['m_birthday'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>День народж.дружини</b>: &nbsp; </TD>
        <TD class='edit-item' id='wbirthday' style='width:350px;'>&nbsp; <?=$mechanic['m_wife_birthday'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Діти</b>: &nbsp; </TD>
        <TD class='edit-item' id='children' style='width:350px;'>&nbsp; <?=$mechanic['m_children'];?> &nbsp; </TD>
    </TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Адреса</b>: &nbsp; </TD>
        <TD class='edit-item' id='address' style='width:350px;'>&nbsp; <?=$mechanic['m_address'];?> &nbsp; </TD>
    </TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Паспорт</b>: &nbsp; </TD>
        <TD class='edit-item' id='passport' style='width:350px;'>&nbsp; <?=$mechanic['m_passport'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Ідентиф.код</b>: &nbsp; </TD>
        <TD class='edit-item' id='idcode' style='width:350px;'>&nbsp; <?=$mechanic['m_idcode'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Освіта</b>: &nbsp; </TD>
        <TD class='edit-item' id='education' style='width:350px;'>&nbsp; <?=$mechanic['m_education'];?> &nbsp; </TD>
    </TR>      
</TABLE>
<BR>

<?
    if (hasPermission(VIEW.HIRINGS, false)) {
        echo "<div>";
        include_once "mechanic-hirings.php";
        echo "</div>";
    }

    $editables = hasPermission(EDIT.MECHANIC)
        ? "'name', 'phone', 'stag', 'address', 'passport', 'idcode', 'birthday', 'wbirthday', 'children', 'insurance', 'poid', 'education'"
        : "'nopermission'";
?>

<script>
$(document).ready(function() {
    var editables = [<?=$editables;?>];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (editables.indexOf(id) >= 0) {
            url = "edit-mechanic.php?" + id + "=&did=<?=$did;?>&edit=";
            $('#' + id).load(url);
        }
    });

    $("#delete-mechanic").click(function() {
        $("#main_space").load("mechanics.php?dd=<?=$did;?>");
    });
    $("#restore-mechanic").click(function() {
        $("#main_space").load("mechanics.php?rd=<?=$did;?>");
    });
});
</script>