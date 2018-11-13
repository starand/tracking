<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
    require_permission(VIEW.CAR);
    require_permission(VIEW.DRIVER);

    isset($_GET['cid']) or show_error("Не вибрано машину!");
    $car = get_car((int)$_GET['cid']) or show_error("Така машина не існує");
    $cid = $car['c_id'];

    if (isset($_GET['dcd'])) {
        require_permission(DEL.CAR);
        $cd_id = (int)$_GET['dcd'];
        get_car_driver($cd_id) or show_error("Такий зв'язок не знайдено! $cd_id");
        if (delete_car_driver($cd_id)) {
            show_message("Водія <b>{$driver['d_name']}</b> видалено!");
            load("car-drivers.php?cid=$cid", 'add-car-driver-content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий зв'язок існує!");
        }
    }
?>
<TABLE class='list-content' style='width:450px;'>
    <TR>
        <TD class='list-content-header' style='width:30px;'> # </TD>
        <TD class='list-content-header'> Водій </TD>
        <? echo hasPermission(DEL.CAR) ? "<TD class='list-content-header'> X </TD>" : ""; ?>
    </TR>
<?
    $i = 1;
    $drivers = get_drivers_by_car($cid);

    if (count($drivers)) {
        foreach ($drivers as $driver) {
            echo "<TR class='list-content' style='height:22px;'>
                    <TD class='edit-item'> &nbsp; $i &nbsp; </TD>
                    <TD class='edit-item' style='width:300px;' id='dd{$driver['d_id']}'> &nbsp; {$driver['d_name']} &nbsp; </TD>";
            echo hasPermission(DEL.CAR) ? "
                    <TD class='edit-item'> &nbsp; <img id='dcd{$driver['cd_id']}' class='icon' src='$PATH/themes/light/trash.png' title='Видалити водія'> &nbsp; </TD>" : "";
            echo "</TR>";
            $i++;
        }
    } else {
        echo "<TR class='list-content' style='height:22px;'>
                <TD class='edit-item' colspan='4'> &nbsp; Водіїв поки що не додано! &nbsp; </TD>
            </TR>";
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $(".icon").click(function() {
        id = $(this).attr('id');
        if (id.substr(0,3) == 'dcd') {
            url = "car-drivers.php?cid=<?=$cid;?>&dcd=" + id.substr(3);
            $("#add-car-driver-content").load(url);
        }
    });
});
</script>

