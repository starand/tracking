<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.CARS);

    if (!isset($cars)) die("Internal error!");

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
                <TD class='list-content-header'> &nbsp; Власник &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Водії &nbsp; </TD>
                </TR>";
        $i = 1;
        foreach($cars as $car) {
            $content = $car['c_driver'];
            $drivers = get_drivers_by_car($car['c_id']);
            foreach($drivers as $driver) {
                if (strlen($content)) $content .= ", ";
                $content .= "<a class='driver' id='d{$driver['d_id']}'>".shortenPIB($driver['d_name'])."</a>";
            }

            $istyle = !checkDateDMYFormat($car['c_insurance']) ? "background:#FDFFC8;" :
                        (checkDMYDateExpired($car['c_insurance']) ? "background:#FF9797;" :
                        (checkDMYDateExpireIn($car['c_insurance']) ? "background:#ffff00;" : ""));
            $to_style = !checkDateDMYFormat($car['c_sto']) ? "background:#FDFFC8;" :
                        (checkDMYDateExpired($car['c_sto']) ? "background:#FF9797;" :
                        (checkDMYDateExpireIn($car['c_sto']) ? "background:#ffff00;" : ""));

            $owner = shortenPIB($car['c_owner']);

            echo "<TR class='list-content'>
                    <TD class='list-content' id='$prefix{$car['c_id']}'> $i </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}'> &nbsp; {$car['c_plate']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}'> {$car['c_model']} </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}' style='$istyle'> {$car['c_insurance']} </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}' style='$to_style'> &nbsp; {$car['c_sto']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$car['c_id']}'> &nbsp; {$car['c_places']} &nbsp; </TD>
                    <TD class='list-content' style='font-size:14px;'> &nbsp; $owner &nbsp; </TD>
                    <TD class='list-content' style='font-size:14px;'> &nbsp; $content &nbsp; </TD>
                </TR>";
            $i++;
        }
    }
?>


<script>
$(document).ready(function() {
    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'c') {
            load_main_hist("car.php?cid=" + id.substr(1));
        }  
    });

    $(".driver").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            load_main_hist("driver.php?did=" + id.substr(1));
        }  
    });
});
</script>