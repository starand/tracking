<?
    include_once "common/headers.php";
    $user or die("Спочатку увійдіть в систему!");

    isset($_GET['lid']) or die("Локацію не вказано!");
    $lid = (int)$_GET['lid'];
    get_location($lid) or show_error("Локація не існує! '$lid'");
?>
<center>
<h2>Маршрути</h2>
<a id='add-route'> Додати маршрут </a>
<TABLE class='list-content' style='width:700px;'>
    <td class='list-content-header' style='width:100px;'> &nbsp; Назва &nbsp; </td>
    <td class='list-content-header' style='width:270px;'> &nbsp; Опис &nbsp; </td>
    <td class='list-content-header' style='width:300px;'> &nbsp; Водії &nbsp; </td>
<?
    $routes = get_routes($lid);

    if (!count($routes)) {
        echo "<TR class='list-content' style='height: 38px;'>
                <TD class='list-content' colspan='3'> &nbsp; По цій локації маршрутів не знайдено! &nbsp; </TD>
            </TR>";
    } else {
        foreach($routes as $route) {
            $drivers = get_drivers_by_route($route['r_id']);
            $content = "";
            foreach($drivers as $driver) {
                if (strlen($content)) $content .= "<BR>";
                $content .= "{$driver['d_name']}";
            }
            echo "<TR class='list-content' style='height: 38px;'>
                    <TD class='list-content' id='{$route['r_id']}'> &nbsp; <b> {$route['r_name']} </b> &nbsp; </TD>
                    <TD class='list-content' id='{$route['r_id']}'> &nbsp; <b> {$route['r_desc']} </b> &nbsp; </TD>
                    <TD class='list-content' id='{$route['r_id']}'> &nbsp; $content &nbsp; </TD>
                </TR>";
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-route").on("click", function() {
        id = $(this).attr('id');
        $("#main_space").load("add-route.php?lid=<?=$lid;?>");
    });
});
</script>