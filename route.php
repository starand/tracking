<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.ROUTE);

    isset($_GET['rid']) or die("Не вказано маршрут!");
    $rid = (int)$_GET['rid'];
    $route = get_route($rid) or show_error("Маршрут не знайдено!");
?>

<center>
<h2>Маршрут: <? echo "{$route['r_name']} - {$route['r_desc']}";?></h2>
<?
    $add_route = $route['r_state'] == STATE_ACTUAL
        ? "<a id='delete-route' style='color:red;'>Видалити маршрут</a>"
        : "<a id='restore-route' style='color:red;'>Поновити маршрут</a>";
?>
<TABLE cellspacing='0' cellpadding='2' style='width:500px;' class='menu'>
<TR>
    <TD> </TD>
<?
    echo hasPermission(DEL.ROUTE) ? "<TD style='width:150px;'><a id='po-info'> $add_route </a></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:550px;'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про маршрут </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Ім'я</b>: &nbsp; </TD>
        <TD class='edit-item' id='name' style='width:400px;'> &nbsp; <?=$route['r_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Опис</b>: &nbsp; </TD>
        <TD class='edit-item' id='desc'>&nbsp; <?=$route['r_desc'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Локація</b>: &nbsp; </TD>
        <TD class='edit-item' id='location'>&nbsp; <?=$route['l_name'];?> &nbsp; </TD>
    </TR>
</TABLE>
<BR>

<? echo hasPermission(EDIT.ROUTE) ? "<a id='add-route-data'> Додати геодані </a>" : ""; ?>
<div id='route-data-content'>
<? include_once "route-datas.php"; ?>
</div>

<script>
$(document).ready(function() {
    var edittables = ['name', 'desc', 'location'];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id) >= 0) {
            url = "edit-route.php?" + id + "=&rid=<?=$rid;?>&edit=";
            $('#' + id).load(url);
        }
    });

    $("#add-route-data").on("click", function() {
        $("#route-data-content").load("add-route-data.php?rid=<?=$rid;?>");
    });

    $("#delete-route").click(function() {
        $("#main_space").load("routes.php?dr=<?=$rid;?>");
    });
    $("#restore-route").click(function() {
        $("#main_space").load("routes.php?rr=<?=$rid;?>");
    });
});
</script>