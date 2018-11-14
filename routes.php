<?
    include_once "common/headers.php";
    $user or die("Спочатку увійдіть в систему!");
    require_permission(VIEW.ROUTES);

    isset($_GET['lid']) or die("Локацію не вказано!");
    $lid = (int)$_GET['lid'];
    $location = get_location($lid) or show_error("Локація не існує! '$lid'");

    setActiveLocation($location);
?>
<center>
<h2>Маршрути</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:850px;' class='menu'>
<TR'>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
    <? echo hasPermission(ADD.ROUTE) ? "<TD style='width:130px;'><input type='button' id='add-route' value=' Додати маршрут '/></TD>" : ""; ?>
</TR>
</TABLE>

<TABLE class='list-content' style='' id='tbl_routes' style='width:850px;'>
    <td class='list-content-header' style='width:30px;'> &nbsp; # &nbsp; </td>
    <td class='list-content-header' style='width:100px;'> &nbsp; <b>Назва &nbsp;</b> </td>
    <td class='list-content-header' style='width:380px;'> &nbsp; <b>Опис &nbsp; </b></td>
    <td class='list-content-header' style='width:75px;'> Довжина </b></td>
    <td class='list-content-header' style='width:300px;'> &nbsp; <b>Водії &nbsp;</b> </td>
<?
    $routes = get_routes($lid);

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
                $content .= "<a class='driver' id='d{$driver['d_id']}'>{$driver['d_name']}</a>";
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
        $("#main_space").load("add-route.php?lid=<?=$lid;?>");
    });

    $(".driver").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            $('#main_space').load("driver.php?did=" + id.substr(1));
        }
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'r') {
            $('#main_space').load("route.php?rid=" + id.substr(1));
        }
    });

    $("#search").click(function() {
        value = $("#query").val().toLowerCase();
        $("#tbl_routes tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>