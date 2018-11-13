<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
    require_permission(VIEW.ROUTE);

    isset($_GET['rid']) or show_error("Не вибрано маршрут!");
    $route = get_route((int)$_GET['rid']) or show_error("Такий маршрут не існує!");
    $rid = $route['r_id'];

    if (isset($_GET['drd'])) {
        require_permission(DEL.ROUTE);
        $rdata_id = (int)$_GET['drd'];
        get_route_data($rdata_id) or show_error("Такі дані не знайдено!");
        if (delete_route_data($rdata_id)) {
            show_message("Дані видалено!");
            load("route-datas.php?rid=$rid", 'route-data-content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий маршрут існує для водія!");
        }
    }
?>
<TABLE class='list-content'>
    <TR>
        <TD class='list-content-header'> # </TD>
        <TD class='list-content-header'> Маршрут </TD>
        <TD class='list-content-header'> URL </TD>
        <TD class='list-content-header'> Довж. </TD>
        <?
            echo hasPermission(VIEW.FINANCE) ? "<TD class='list-content-header'> Варт. </TD>" : "";
            echo hasPermission(DEL.ROUTE) ? "<TD class='list-content-header'> X </TD>" : "";
        ?>
    </TR>
<? // 1rXRdnrQE6XnxS6myjXMMFPvtI4tuWB56
    $i = 1;
    $datas = get_route_datas($rid);
    $prefix = hasPermission(EDIT.ROUTE) ? "r" : "";
    $dprefix = hasPermission(DEL.ROUTE) ? "r" : "";
    if (count($datas)) {
        foreach ($datas as $data) {
            echo "<TR class='list-content' style='height:22px;'>
                    <TD class='edit-item'> &nbsp; $i &nbsp; </TD>
                    <TD class='edit-item' style='width:300px;' id='{$prefix}dn{$data['rd_id']}'> &nbsp; {$data['rd_name']} &nbsp; </TD>
                    <TD class='edit-item' style='width:300px;' id='{$prefix}du{$data['rd_id']}'> &nbsp; {$data['rd_url']} &nbsp; </TD>
                    <TD class='edit-item' style='width:50px;' id='{$prefix}dl{$data['rd_id']}'> &nbsp; {$data['rd_length']} &nbsp; </TD>";
                    echo hasPermission(VIEW.FINANCE) ? "<TD class='edit-item' style='width:60px;' id='rdc{$data['rd_id']}'> &nbsp; {$data['rd_cost']} &nbsp; </TD>" : "";
                    echo hasPermission(DEL.ROUTE) ? "<TD class='edit-item' style='' id='rd{$data['rd_id']}'> &nbsp; 
                        <img id='{$dprefix}rd{$data['rd_id']}' class='icon' src='$PATH/themes/light/trash.png' title='Видалити маршрут'> &nbsp; </TD>" : "";
                echo "</TR>";
            $i++;
        }
    } else {
        echo "<TR class='list-content' style='height:22px;'>
                <TD class='edit-item' colspan='6'> &nbsp; Геоданих поки що не додано! &nbsp; </TD>
            </TR>";
    }

    $iframe_begin = "<iframe src='https://www.google.com/maps/d/embed?mid=";
    $iframe_end = "' width='850' height='600'></iframe>";
?>
</TABLE>
<BR>
<div id='map-data' style='width:850px;'>
<?
    if (count($datas)) {
        echo "$iframe_begin{$datas[0]['rd_url']}$iframe_end";
    }
?>
</div>

<script>
$(document).ready(function() {
    $(".icon").click(function() {
        id = $(this).attr('id');
        if (id.substr(0,3) == 'drd') {
            url = "route-datas.php?rid=<?=$rid;?>&drd=" + id.substr(3);
            $("#route-data-content").load(url);
        }
    });

    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (id.substr(0,3) == 'map') {
            content = "<?=$iframe_begin;?>" + id.substr(3) + "<?=$iframe_end;?>";
            $("#map-data").html(content);
        }
    });

    var edittables = ['rdn', 'rdu', 'rdl', 'rdc'];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id.substr(0, 3)) >= 0) {
            url = "edit-route-data.php?id=" + id.substr(3) + "&rdId=" + id + "&edit=";
            $('#' + id).load(url);
        }
    });
});
</script>

