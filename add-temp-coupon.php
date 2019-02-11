<?
    include_once "common/headers.php";
    checkAuthorizedUser();
    
    require_permission(ADD.CAR);
    require_permission(VIEW.POS);

    if (isset($_POST['cid']) && isset($_POST['poid']) && isset($_POST['date'])) {
        $poid = (int)$_POST['poid'];
        $po = get_po($poid) or show_error('Такий підприємець не існує!');
        $cid = (int)$_POST['cid'];
        $car = get_car($cid) or show_error("Така машина не існує! $cid");
        $date = addslashes($_POST['date']);

        if (add_temp_coupon($cid, $poid, $date)) {
            show_message("Талон для підприємеця <b>{$po['po_name']}</b> доданий для автомобіля <b>{$car['c_plate']}</b>!");
            load("temp-coupons.php?cid=$cid", 'add-temp-coupon-content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий талон вже існує для цієї машини!");
        }
        die();
    }

    if (!isset($_GET['cid'])) {
        show_error('Машину не вказано!');
    }

    $cid = (int)$_GET['cid'];
    $pos = get_pos();
?>
<center>
<form action='add-temp-coupon.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <tr><td colspan='2'><center><h2>Додати тимчасовий талон</h2></td></tr>
    <tr><td> &nbsp; Підприємець : &nbsp;</td><td>
        <SELECT name='poid' style='width:300px; font-size:14px;'>
        <? foreach ($pos as $po) echo "<option value='{$po['po_id']}'>{$po['po_name']}</option>"; ?>
        </SELECT>
    </td></tr>
    <tr><td> &nbsp; Дійсний до : &nbsp;</td><td><input type='text' name='date' style='width:300px;'></td></tr>
    <tr>
        <td colspan='2'><center><input type='submit' value=' Додати '></td>
        <input type='hidden' name='cid' value='<?=$cid;?>'>
    </tr>
</table>
</form>
