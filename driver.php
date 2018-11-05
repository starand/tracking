<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['did']) or die("Не вказано водія!");
    $did = (int)$_GET['did'];
    $driver = get_driver($did) or show_error("Водія не знайдено! '$lid'");
?>

<center>
<h2>Водій: <?=$driver['d_name'];?></h2>
<TABLE class='list-content'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про водія </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-person'> &nbsp; ПІБ &nbsp; </TD>
        <TD class='edit-person' id='name' style='width:350px;'> &nbsp; <?=$driver['d_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-person'> &nbsp; Мобільний тел.: &nbsp; </TD>
        <TD class='edit-person' id='phone' style='width:350px;'>&nbsp; <?=$driver['d_phone'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-person'> &nbsp; Стаж: &nbsp; </TD>
        <TD class='edit-person' id='stag' style='width:350px;'>&nbsp; <?=$driver['d_stag'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-person'> &nbsp; Адреса &nbsp; </TD>
        <TD class='edit-person' id='address' style='width:350px;'>&nbsp; <?=$driver['d_address'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-person'> &nbsp; Вік &nbsp; </TD>
        <TD class='edit-person' id='age' style='width:350px;'>&nbsp; <?=$driver['d_age'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-person'> &nbsp; Паспорт &nbsp; </TD>
        <TD class='edit-person' id='passport' style='width:350px;'>&nbsp; <?=$driver['d_passport'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-person'> &nbsp; Ідентиф.код &nbsp; </TD>
        <TD class='edit-person' id='idcode' style='width:350px;'>&nbsp; <?=$driver['d_idcode'];?> &nbsp; </TD>
    </TR>
</TABLE>
<BR>

<a id='add-driver-route'> Додати маршрут </a>
<div id='add-route-content'>
<? include_once "driver-routes.php"; ?>
</div>

<script>
$(document).ready(function() {
    var edittables = ['name', 'phone', 'stag', 'address', 'age', 'passport', 'idcode'];
    $(".edit-person").click(function() {
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