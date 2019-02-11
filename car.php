<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.CAR);

    isset($_GET['cid']) or die("Не вказано машиину!");
    $cid = (int)$_GET['cid'];
    $car = get_car($cid) or show_error("Машину не знайдено!");
?>

<center>
<h2>Машина: <? echo "{$car['c_plate']} - {$car['c_model']}";?></h2>
<?
    $add_car = $car['c_state'] == STATE_ACTUAL
        ? "<input type='button' id='delete-car'  style='color:red;' value=' Видалити автомобіль ' />"
        : "<input type='button' id='restore-car' style='color:red;' value=' Поновити автомобіль ' />";
?>
<TABLE cellspacing='0' cellpadding='2' style='width:500px;' class='menu'>
<TR>
    <TD> </TD>
<?
    echo hasPermission(DEL.CAR) ? "<TD style='width:150px;'><a id='car-info'> $add_car </a></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:500px;'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про машину </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Номер</b>: &nbsp; </TD>
        <TD class='edit-item' id='plate' style='width:350px;'> &nbsp; <?=$car['c_plate'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Модель</b>: &nbsp; </TD>
        <TD class='edit-item' id='model' style='width:350px;'>&nbsp; <?=$car['c_model'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Страхівка</b>: &nbsp; </TD>
        <TD class='edit-item' id='insurance' style='width:350px;'>&nbsp; <?=$car['c_insurance'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>СТО</b>: &nbsp; </TD>
    <TD class='edit-item' id='sto' style='width:350px;'>&nbsp; <?=$car['c_sto'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Місць</b>: &nbsp; </TD>
        <TD class='edit-item' id='places' style='width:350px;'>&nbsp; <?=$car['c_places'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Тип</b>: &nbsp; </TD>
        <TD class='edit-item' id='type' style='width:350px;'>&nbsp; <?=$car['ct_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Власник</b>: &nbsp; </TD>
        <TD class='edit-item' id='owner' style='width:350px;'>&nbsp; <?=$car['c_owner'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Колір</b>: &nbsp; </TD>
        <TD class='edit-item' id='color' style='width:350px;'>&nbsp; <?=$car['c_color'];?> &nbsp; </TD>
    </TR>
</TABLE>
<BR>


<TABLE cellspacing='0' cellpadding='2' style='width:500px;' class='menu'>
<TR>
    <TD> </TD>
<?
    echo hasPermission(ADD.CAR) && hasPermission(VIEW.DRIVER) ? "<TD style='width:100px;'><input type='button' id='add-car-driver' value=' Додати водія '/></TD>" : "";
    echo hasPermission(ADD.CAR) && hasPermission(VIEW.POS) ? "<TD style='width:100px;'><input type='button' id='add-temp-coupon' value=' Додати талон '/></TD>" : "";
?>
</TR>
</TABLE>

<div id='add-car-driver-content'>
<? include_once hasPermission(VIEW.CAR) && hasPermission(VIEW.DRIVER) ? "car-drivers.php" : "no.php"; ?>
</div>
<div id='add-temp-coupon-content'>
<? include_once hasPermission(VIEW.CAR) && hasPermission(VIEW.POS) ? "temp-coupons.php" : "no.php"; ?>
</div>

<?
    $editables = hasPermission(EDIT.CAR)
        ? "'plate', 'model', 'insurance', 'sto', 'places', 'type', 'owner', 'color'"
        : "'nopermission'";
?>

<script>
$(document).ready(function() {
    var editables = [<?=$editables;?>];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (editables.indexOf(id) >= 0) {
            url = "edit-car.php?" + id + "=&cid=<?=$cid;?>&edit=";
            $('#' + id).load(url);
        }
    });

    $("#add-car-driver").on("click", function() {
        $("#add-car-driver-content").load("add-car-driver.php?cid=<?=$cid;?>");
    });

    $("#add-temp-coupon").on("click", function() {
        $("#add-temp-coupon-content").load("add-temp-coupon.php?cid=<?=$cid;?>");
    });

    $("#delete-car").click(function() {
        $("#main_space").load("cars.php?dc=<?=$cid;?>");
    });
    $("#restore-car").click(function() {
        $("#main_space").load("cars.php?rc=<?=$cid;?>");
    });
});
</script>
