<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.CARS);

    if (isset($_GET['dc'])) {
        require_permission(DEL.CAR);
        check_result(delete_car((int)$_GET['dc']),  "Автомобіль видалено!", "Помилка бази даних!");
    }

    if (isset($_GET['rc'])) {
        require_permission(DEL.CAR);
        check_result(restore_car((int)$_GET['rc']), "Автомобіль поновлено!", "Помилка бази даних!");
    }

    $type = STATE_ACTUAL;
    if ($_GET['type']) {
        $type = STATE_REMOVED;
        require_permission(DEL.DRIVERS);
    }
?>
<center>
<h2>Машини</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:850px;' class='menu'>
<TR'>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
<?
    echo hasPermission(ADD.CAR) ? "<TD style='width:120px;'><input type='button' id='add-car' value=' Додати машину '/></TD>" : "";
    echo hasPermission(DEL.CARS) ? "<TD style='width:70px;text-align:center;'><input type='button'  id='removed-cars' value=' Видалені '/></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:850px;' id='tbl_cars'>
<?
    $cars = get_cars($type);
    include_once "car-list.php";
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-car").on("click", function() {
        id = $(this).attr('id');
        $("#main_space").load("add-car.php");
    });

    $("#search").click(function() {
        // drivers_tbl
        value = $("#query").val().toLowerCase();

        $("#tbl_cars tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#removed-cars").click(function() {
        $('#main_space').load("cars.php?type=1");
    });
});
</script>