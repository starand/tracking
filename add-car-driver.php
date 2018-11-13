<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
    require_permission(ADD.CAR);
    require_permission(VIEW.DRIVER);

    if (isset($_POST['cid']) && isset($_POST['did'])) {
        $did = (int)$_POST['did'];
        $driver = get_driver($did) or show_error('Такий водій не існує!');
        $cid = (int)$_POST['cid'];
        $car = get_car($cid) or show_error("Така машина не існує! $cid");

        if (add_car_driver($did, $cid)) {
            show_message("Водій <b>{$driver['d_name']}</b> доданий до <b>{$car['c_plate']}</b>!");
            load("car-drivers.php?cid=$cid", 'add-car-driver-content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий водій вже існує для цієї машини!");
        }
        die();
    }

    if (!isset($_GET['cid'])) {
        show_error('Машину не вказано!');
    }

    $cid = (int)$_GET['cid'];
    $drivers = get_all_drivers();
?>
<center>
<form action='add-car-driver.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <tr><td colspan='2'><center><h2>Додати водія</h2></td></tr>
    <tr><td> &nbsp; Водій <?=$cid;?>: &nbsp;</td><td>
        <SELECT name='did' style='width:350px; font-size:14px;'>
        <?
            foreach ($drivers as $driver) {
                echo "<option value='{$driver['d_id']}'>{$driver['d_name']}</option>";
            }
        ?>
        </SELECT>
    </td></tr>
    <tr>
        <td colspan='2'><center><input type='submit' value=' Додати '></td>
        <input type='hidden' name='cid' value='<?=$cid;?>'>
    </tr>
</table>
</form>
