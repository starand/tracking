<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['cid']) or die("Не вказано машиину!");
    $cid = (int)$_GET['cid'];
    $car = get_car($cid) or show_error("Машину не знайдено!");
?>

<center>
<h2>Машина: <? echo "{$car['c_plate']} - {$car['c_model']}";?></h2>
<TABLE class='list-content'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про машину </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Номер: &nbsp; </TD>
        <TD class='edit-item' id='plate' style='width:350px;'> &nbsp; <?=$car['c_plate'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Модель: &nbsp; </TD>
        <TD class='edit-item' id='model' style='width:350px;'>&nbsp; <?=$car['c_model'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Страхівка: &nbsp; </TD>
        <TD class='edit-item' id='insurance' style='width:350px;'>&nbsp; <?=$car['c_insurance'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; СТО: &nbsp; </TD>
        <TD class='edit-item' id='sto' style='width:350px;'>&nbsp; <?=$car['c_sto'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Місць: &nbsp; </TD>
        <TD class='edit-item' id='places' style='width:350px;'>&nbsp; <?=$car['c_places'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Тип: &nbsp; </TD>
        <TD class='edit-item' id='type' style='width:350px;'>&nbsp; <?=$car['ct_name'];?> &nbsp; </TD>
    </TR>
</TABLE>
<BR>

<a id='add-car-driver'> Додати водія </a>
<div id='add-car-driver-content'>
<? include_once "car-drivers.php"; ?>
</div>

<script>
$(document).ready(function() {
    var edittables = ['plate', 'model', 'insurance', 'sto', 'places', 'type'];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id) >= 0) {
            url = "edit-car.php?" + id + "=&cid=<?=$cid;?>&edit=";
            $('#' + id).load(url);
        }
    });

    $("#add-car-driver").on("click", function() {
        $("#add-car-driver-content").load("add-car-driver.php?cid=<?=$cid;?>");
    });
});
</script>