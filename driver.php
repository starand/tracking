<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['did']) or die("Не вказано водія!");
    $did = (int)$_GET['did'];
    $driver = get_driver($did) or show_error("Водія не знайдено! '{$_GET['did']}'");
    $hiring = get_driver_hiring($did);
    $po = get_driver_po($did);
?>

<center>
<h2>Водій: <?=$driver['d_name'];?></h2>
<TABLE class='list-content'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про водія </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; ПІБ: &nbsp; </TD>
        <TD class='edit-item' id='name' style='width:350px;'> &nbsp; <?=$driver['d_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Мобільний тел.: &nbsp; </TD>
        <TD class='edit-item' id='phone' style='width:350px;'>&nbsp; <?=$driver['d_phone'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Стаж: &nbsp; </TD>
        <TD class='edit-item' id='stag' style='width:350px;'>&nbsp; <?=$driver['d_stag'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Медична довідка: &nbsp; </TD>
        <TD class='edit-item' id='insurance' style='width:350px;'>&nbsp; <?=$driver['d_insurance'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Підприємець: &nbsp; </TD>
        <TD class='edit-item' id='poid' style='width:350px;'>&nbsp; <? echo "{$po['po_name']} - {$po['po_phone']}"; ?> &nbsp; </TD>
    </TR>

    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; День народження: &nbsp; </TD>
        <TD class='edit-item' id='birthday' style='width:350px;'>&nbsp; <?=$driver['d_birthday'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; День народж.дружини: &nbsp; </TD>
        <TD class='edit-item' id='wbirthday' style='width:350px;'>&nbsp; <?=$driver['d_wife_birthday'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Діти: &nbsp; </TD>
        <TD class='edit-item' id='children' style='width:350px;'>&nbsp; <?=$driver['d_children'];?> &nbsp; </TD>
    </TR>

    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Договір: &nbsp; </TD>
        <TD class='edit-item' id='contract' style='width:350px;'>&nbsp; <?=$hiring['h_contract'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Наказ: &nbsp; </TD>
        <TD class='edit-item' id='order' style='width:350px;'>&nbsp; <?=$hiring['h_order'];?> &nbsp; </TD>
    </TR>

    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Адреса: &nbsp; </TD>
        <TD class='edit-item' id='address' style='width:350px;'>&nbsp; <?=$driver['d_address'];?> &nbsp; </TD>
    </TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Паспорт: &nbsp; </TD>
        <TD class='edit-item' id='passport' style='width:350px;'>&nbsp; <?=$driver['d_passport'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Ідентиф.код: &nbsp; </TD>
        <TD class='edit-item' id='idcode' style='width:350px;'>&nbsp; <?=$driver['d_idcode'];?> &nbsp; </TD>
    </TR>    
</TABLE>
<BR>

<a id='add-driver-route'> Додати маршрут </a>
<div id='add-route-content'>
<? include_once "driver-routes.php"; ?>
</div>

<script>
$(document).ready(function() {
    var edittables = ['name', 'phone', 'stag', 'address', 'passport', 'idcode', 'birthday',
                        'wbirthday', 'children', 'insurance', 'contract', 'order', 'poid'];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id) >= 0) {
            url = "edit-driver.php?" + id + "=&did=<?=$did;?>&edit=";
            $('#' + id).load(url);
        }
    });

    $("#add-driver-route").on("click", function() {
        $("#add-route-content").load("add-driver-route.php?did=<?=$did;?>");
    });
});
</script>