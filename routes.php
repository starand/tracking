<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.ROUTES);

    if (isset($_GET['dr'])) {
        require_permission(DEL.ROUTE);
        check_result(delete_route((int)$_GET['dr']),  "Маршурут видалено!", "Помилка бази даних!");
    }

    if (isset($_GET['rr'])) {
        require_permission(DEL.ROUTE);
        check_result(restore_route((int)$_GET['rr']), "Маршурут поновлено!", "Помилка бази даних!");
    }

    $type = STATE_ACTUAL;
    if ($_GET['type']) {
        $type = STATE_REMOVED;
        require_permission(DEL.ROUTES);
    }

    isset($_GET['lid']) or die("Локацію не вказано!");
    $lid = (int)$_GET['lid'];
    $location = get_location($lid) or show_error("Локація не існує! '$lid'");
    $name = $location['l_name'];

    setActiveLocation($location);
?>
<center>
<h2>Маршрути - <?=$name;?></h2>

<TABLE cellspacing='0' cellpadding='2' style='width:850px;' class='menu'>
<TR>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
    <?
        echo hasPermission(ADD.ROUTE) ? "<TD style='width:130px;'><input type='button' id='add-route' value=' Додати маршрут '/></TD>" : "";
        echo hasPermission(DEL.ROUTE) && $type == STATE_ACTUAL ? "<TD style='width:70px;text-align:center;'><input type='button'  id='removed-routes' value=' Видалені '/></TD>" : "";
    ?>
</TR>
</TABLE>

<TABLE class='list-content' style='' id='tbl_routes' style='width:850px;'>
    <td class='list-content-header' style='width:30px;'> &nbsp; # &nbsp; </td>
    <td class='list-content-header' style='width:100px;'> &nbsp; <b>Назва &nbsp;</b> </td>
    <td class='list-content-header' style='width:380px;'> &nbsp; <b>Опис &nbsp; </b></td>
    <td class='list-content-header' style='width:75px;'> Довжина </b></td>
    <td class='list-content-header' style='width:300px;'> &nbsp; <b>Водії &nbsp;</b> </td>
<?
    $routes = get_routes($lid, $type);

    if (!count($routes)) {
        echo "<TR class='list-content' style='height: 38px;'>
                <TD class='list-content' colspan='3'> &nbsp; По цій локації маршрутів не знайдено! &nbsp; </TD>
            </TR>";
    } else {
        $i = 1;
        foreach($routes as $route) {
            $drivers = get_drivers_by_route($route['r_id']);
            $content = "";
            foreach($drivers as $driver) {
                if (strlen($content)) $content .= "<BR>";
                $content .= "<a class='driver' id='d{$driver['d_id']}'>".shortenPIB($driver['d_name'])." - {$driver['d_phone']}</a>";
            }
            $datas = get_route_datas($route['r_id']);
            $dcontent = "";
            if (count($datas)) $dcontent .= $datas[0]['rd_length'];

            echo "<TR class='list-content'>
                    <TD class='list-content' id='r{$route['r_id']}'> $i </TD>
                    <TD class='list-content' id='r{$route['r_id']}'> &nbsp; {$route['r_name']} &nbsp; </TD>
                    <TD class='list-content' id='r{$route['r_id']}'> &nbsp; {$route['r_desc']} &nbsp; </TD>
                    <TD class='list-content' id='r{$route['r_id']}'> &nbsp; $dcontent &nbsp; </TD>
                    <TD class='list-content' id='{$route['r_id']}'> &nbsp; $content &nbsp; </TD>
                </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-route").on("click", function() {
        id = $(this).attr('id');
        load_main_hist("add-route.php?lid=<?=$lid;?>");
    });

    $(".driver").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            load_main_hist("driver.php?did=" + id.substr(1));
        }
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'r') {
            load_main_hist("route.php?rid=" + id.substr(1));
        }
    });

    $("#search").click(function() {
        value = $("#query").val().toLowerCase();
        $("#tbl_routes tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#removed-routes").click(function() {
        load_main_hist("routes.php?type=1&lid=<?=$lid;?>");
    });
});
</script>
