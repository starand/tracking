<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.CARS);
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
    if (hasPermission(ADD.CAR)) {
        echo "<TD style='width:120px;'><input type='button' id='add-car' value=' Додати машину '/></TD>";
    }
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:850px;' id='tbl_cars'>
<?
    $cars = get_cars();
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
});
</script>