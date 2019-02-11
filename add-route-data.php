<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.ROUTE);

    if (isset($_POST['url']) && isset($_POST['length']) && isset($_POST['cost']) &&
        isset($_POST['rid']) && isset($_POST['name'])) {
        $url = addslashes($_POST['url']);
        $name = addslashes($_POST['name']);
        $len = (int)$_POST['length'];
        $cost = (int)$_POST['cost'];
        $rid = (int)$_POST['rid'];
        $route = get_route($rid) or show_error('Такий маршрут не існує!');

        if (add_route_data($rid, $url, $len, $cost, $name)) {
            show_message("Дані по маршруту <b>{$route['r_name']}</b> додано!");
            load("route-datas.php?rid=$rid", 'route-data-content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий маршрут вже існує для цього водія!");
        }
        die();
    }

    if (!isset($_GET['rid'])) {
        show_error('Водій не вказаний!');
    }

    $rid = (int)$_GET['rid'];
    $route = get_route($rid);

    $style = "width:350px;";
?>
<center>
<form action='add-route-data.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <tr><td colspan='2'><center><h2>Додати геодані</h2></td></tr>
    <tr><td> &nbsp; Маршрут: &nbsp;</td><td><input type='text' name='route' style='<?=$style;?>' value='<?=$route['r_name'];?>' disabled></td></tr>
    <tr><td> &nbsp; Назва: &nbsp;</td><td><input type='text' name='name' style='<?=$style;?>'></td></tr>
    <tr><td> &nbsp; URL: &nbsp;</td><td><input type='text' name='url' style='<?=$style;?>'></td></tr>
    <tr><td> &nbsp; Довжина: &nbsp;</td><td><input type='text' name='length' style='<?=$style;?>'></td></tr>
    <tr>
        <td colspan='2'><center><input type='submit' value=' Додати '></td>
        <input type='hidden' name='cost'/>
        <input type='hidden' name='rid' value='<?=$rid;?>'>
    </tr>
</table>
</form>
