<?
    include_once "common/headers.php";
    $user or die("Спочатку увійдіть в систему!");

    isset($_GET['lid']) or die("Локацію не вказано!");
    $lid = (int)$_GET['lid'];
    get_location($lid) or show_error("Локація не існує! '$lid'");
?>
<center>
<h2>Маршрути</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:700px;'>
<TR'>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
    <TD style='width:130px;'><a id='add-route'> Додати маршрут </a></TD>
</TR>
</TABLE>


<TABLE class='list-content' style='width:700px;' id='tbl_routes'>
    <td class='list-content-header' style=''> &nbsp; # &nbsp; </td>
    <td class='list-content-header' style='width:100px;'> &nbsp; <b>Назва &nbsp;</b> </td>
    <td class='list-content-header' style='width:270px;'> &nbsp; <b>Опис &nbsp; </b></td>
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
            echo "<TR class='list-content'>
                    <TD class='list-content' id='r{$route['r_id']}'> $i </TD>
                    <TD class='list-content' id='r{$route['r_id']}'> &nbsp; {$route['r_name']} &nbsp; </TD>
                    <TD class='list-content' id='r{$route['r_id']}'> &nbsp; {$route['r_desc']} &nbsp; </TD>
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