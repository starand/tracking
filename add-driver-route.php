<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    require_permission(ADD.DRIVER);

    if (isset($_POST['did']) && isset($_POST['rid']) && isset($_POST['rate'])) {
        $did = (int)$_POST['did'];
        $driver = get_driver($did) or show_error('Такий водій не існує!');
        $rid = (int)$_POST['rid'];
        $route = get_route($rid) or show_error('Такий маршрут не існує!');
        $rate = (int)$_POST['rate'];
        $rate > 0 or show_error("Не коректна ставка!");

        if (add_rate($did, $rid, $rate)) {
            show_message("Маршрут <b>{$route['r_name']}</b> для водія <b>{$driver['d_name']}</b> додано!");
            load("driver-routes.php?did=$did", 'add-route-content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий маршрут вже існує для цього водія!");
        }
        die();
    }

    if (!isset($_GET['did'])) {
        show_error('Водій не вказаний!');
    }

    $did = (int)$_GET['did'];
    $routes = get_all_routes();
?>
<center>
<form action='add-driver-route.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <tr><td colspan='2'><center><h2>Додати маршрут</h2></td></tr>
    <tr><td> &nbsp; Маршрут: &nbsp;</td><td>
        <SELECT name='rid' style='width:150px; font-size:14px;'>
        <?
            foreach ($routes as $route) {
                echo "<option value='{$route['r_id']}'>{$route['r_name']}</option>";
            }
        ?>
        </SELECT>
    </td></tr>
    <tr><td> &nbsp; Ставка: &nbsp;</td><td><input type='text' name='rate' style='width:150px;'></td></tr>
    <tr>
        <td colspan='2'><center><input type='submit' value=' Додати '></td>
        <input type='hidden' name='did' value='<?=$did;?>'>
    </tr>
</table>
</form>
