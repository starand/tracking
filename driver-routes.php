<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['did']) or show_error("Не вибрано водія!");
    $driver = get_driver((int)$_GET['did']) or show_error("Такий водій не існує!");
    $did = $driver['d_id'];

    if (isset($_GET['ddr'])) {
        $rate_id = (int)$_GET['ddr'];
        $rate = get_rate($rate_id) or show_error("Такий маршрут не знайдено!");
        if (delete_rate($rate_id)) {
            show_message("Маршрут водія <b>{$driver['d_name']}</b> видалено!");
            load("driver-routes.php?did=$did", 'add-route-content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий маршрут існує для водія!");
        }
    }
?>
<TABLE class='list-content'>
    <TR>
        <TD class='list-content-header'> # </TD>
        <TD class='list-content-header'> Маршрут </TD>
        <TD class='list-content-header'> Ставка </TD>
        <TD class='list-content-header'> X </TD>
    </TR>
<?
    $i = 1;
    $routes = get_routes_by_driver($did);
    if (count($routes)) {
        foreach ($routes as $route) {
            echo "<TR class='list-content' style='height:22px;'>
                    <TD class='edit-item'> &nbsp; $i &nbsp; </TD>
                    <TD class='edit-item' style='width:300px;'> &nbsp; {$route['r_name']} - {$route['r_desc']} &nbsp; </TD>
                    <TD class='edit-item' style='width:100px;'> &nbsp; {$route['rate_rate']} &nbsp; </TD>
                    <TD class='edit-item' style=''> &nbsp; <img id='ddr{$route['rate_id']}' class='icon' src='$PATH/themes/light/trash.png' title='Видалити маршрут'> &nbsp; </TD>
                </TR>";
            $i++;
        }
    } else {
        echo "<TR class='list-content' style='height:22px;'>
                <TD class='edit-item' colspan='4'> &nbsp; Маршрутів поки що не додано! &nbsp; </TD>
            </TR>";
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $(".icon").click(function() {
        id = $(this).attr('id');
        if (id.substr(0,3) == 'ddr') {
            url = "driver-routes.php?did=<?=$did;?>&ddr=" + id.substr(3);
            $("#add-route-content").load(url);
        }
    });
});
</script>

