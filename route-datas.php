<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['rid']) or show_error("Не вибрано маршрут!");
    $route = get_route((int)$_GET['rid']) or show_error("Такий маршрут не існує!");
    $rid = $route['r_id'];

    if (isset($_GET['drd'])) {
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
        <TD class='list-content-header'> Довжина </TD>
        <TD class='list-content-header'> Вартість </TD>
        <TD class='list-content-header'> X </TD>
    </TR>
<? // 1rXRdnrQE6XnxS6myjXMMFPvtI4tuWB56
    $i = 1;
    $datas = get_route_datas($rid);
    if (count($datas)) {
        foreach ($datas as $data) {
            echo "<TR class='list-content' style='height:22px;'>
                    <TD class='edit-item'> &nbsp; $i &nbsp; </TD>
                    <TD class='edit-item' style='width:300px;' id='map{$data['rd_url']}'> &nbsp; {$data['rd_name']} &nbsp; </TD>
                    <TD class='edit-item' style='width:100px;'> &nbsp; {$data['rd_length']} &nbsp; </TD>
                    <TD class='edit-item' style='width:100px;'> &nbsp; {$data['rd_cost']} &nbsp; </TD>
                    <TD class='edit-item' style=''> &nbsp; <img id='drd{$data['rd_id']}' class='icon' src='$PATH/themes/light/trash.png' title='Видалити маршрут'> &nbsp; </TD>
                </TR>";
            $i++;
        }
    } else {
        echo "<TR class='list-content' style='height:22px;'>
                <TD class='edit-item' colspan='4'> &nbsp; Геоданих поки що не додано! &nbsp; </TD>
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
        //echo "<iframe src='https://www.google.com/maps/dir/49.8102736,24.0194168/49.8066726,23.9820756/@49.8048825,23.9956127,15.28z/data=!4m9!4m8!1m5!3m4!1m2!1d24.0031472!2d49.8062996!3s0x473ae7bdc49daf3f:0x656400c25b4828fd!1m0!3e0' width='850' height='600'></iframe>";
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
});
</script>

