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
    $prefix = hasPermission(VIEW.CAR) ? "c" : "";

    if (!count($cars)) {
        echo "<TR class='list-content'>
                <TD> &nbsp; Машини поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TR><TD class='list-content-header' style='width:30px;'> &nbsp; # &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Номер &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Модель &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Страхівка &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Тех.огляд &nbsp; </TD>
                <TD class='list-content-header'>  Місць  </TD>
                <TD class='list-content-header'> &nbsp; Водії &nbsp; </TD>
                </TR>";
        $i = 1;
        foreach($cars as $car) {
            $content = "";
            $drivers = get_drivers_by_car($car['c_id']);
            foreach($drivers as $driver) {
                if (strlen($content)) $content .= ", ";
                $content .= "<a class='driver' id='d{$driver['d_id']}'>{$driver['d_name']}</a>";
            }

            $istyle = !checkDateDMYFormat($car['c_insurance']) ? "background:#FDFFC8;" :
                        (checkDMYDateExpired($car['c_insurance']) ? "background:#FF9797;" : "");
            $to_style = !checkDateDMYFormat($car['c_sto']) ? "background:#FDFFC8;" :
                        (checkDMYDateExpired($car['c_sto']) ? "background:#FF9797;" : "");

            echo "<TR class='list-content'>
                    <TD class='list-content' id='$prefix{$car['c_id']}'> $i </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}'> &nbsp; {$car['c_plate']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}'> {$car['c_model']} </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}' style='$istyle'> {$car['c_insurance']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}' style='$to_style'> &nbsp; {$car['c_sto']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}'> &nbsp; {$car['c_places']} &nbsp; </TD>
                    <TD class='list-content'> &nbsp; $content &nbsp; </TD>                    
                </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-car").on("click", function() {
        id = $(this).attr('id');
        $("#main_space").load("add-car.php");
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'c') {
            $('#main_space').load("car.php?cid=" + id.substr(1));
        }  
    });

    $(".driver").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            $('#main_space').load("driver.php?did=" + id.substr(1));
        }  
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