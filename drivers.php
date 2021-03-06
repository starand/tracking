<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.DRIVERS);

    if (isset($_GET['dd'])) {
        require_permission(DEL.DRIVER);
        check_result(delete_driver((int)$_GET['dd']),  "Водія звільнено!", "Помилка бази даних!");
    }

    if (isset($_GET['rd'])) {
        require_permission(DEL.DRIVER);
        check_result(restore_driver((int)$_GET['rd']), "Водія поновлено!", "Помилка бази даних!");
    }

    $type = STATE_ACTUAL;
    if ($_GET['type']) {
        $type = STATE_REMOVED;
        require_permission(DEL.DRIVERS);
    }
?>
<center>
<h2>Водії</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:1050px;' class='menu'>
<TR>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
<?
    echo hasPermission(ADD.DRIVER) ? "<TD style='width:100px;'><input type='button' id='add-driver' value=' Додати водія '/></TD>" : "";
    echo "<TD style='width:100px;'><input type='button' id='drivers-info' value=' Більше даних '/></TD>";
    echo hasPermission(DEL.DRIVERS) && $type == STATE_ACTUAL ? "<TD style='width:70px;text-align:center;'><input type='button'  id='removed-drivers' value=' Звільнені '/></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:1050px;' id='tbl_drivers'>
<?
    $drivers = get_all_drivers($type);
    $prefix = hasPermission(VIEW.DRIVER) ? "d" : "";

    if (!count($drivers)) {
        echo "<TR class='list-content'>
                <TD style='font-weight:bold; text-align:center;'> &nbsp; Водіїв поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TD class='list-content-header' style='width:35px;'>  #  </TD>
                <TD class='list-content-header' style='width:270px;'>  ПІБ  </TD>
                <TD class='list-content-header'>  Телефон  </TD>
                <TD class='list-content-header' style='width:100px;'> Довідка </TD>
                <TD class='list-content-header' style='width:100px;'> Страхівка водія </TD>
                <TD class='list-content-header'> Маршрути </TD>
                <TD class='list-content-header' style='width:90px;'>  Машини  </TD>
                <TD class='list-content-header'>  Підприємець  </TD>";
        $i = 1;
        foreach($drivers as $driver) {
            $content = "";
            $routes = get_routes_by_driver($driver['d_id']);
            foreach($routes as $route) {
                if (strlen($content)) $content .= ", <BR>";
                $content .= "{$route['r_name']}";
            }

            $carcont = "";
            $cars = get_cars_by_driver($driver['d_id']);
            foreach ($cars as $car) {
                if (strlen($carcont)) $carcont .= ",<BR>";
                $carcont .= "<a class='car' id='c{$car['c_id']}'>{$car['c_plate']}</a>";
            }

            $po = get_driver_po($driver['d_id']);

            $istyle = !checkDateDMYFormat($driver['d_insurance']) ? "background:#FDFFC8;" :
                        (checkDMYDateExpired($driver['d_insurance']) ? "background:#FF9797;" :
                        (checkDMYDateExpireIn($driver['d_insurance']) ? "background:#ffff00;" : ""));
            $drvstyle = !checkDateDMYFormat($driver['d_drv_insur']) ? "background:#FDFFC8;" :
                    (checkDMYDateExpired($driver['d_drv_insur']) ? "background:#FF9797;" :
                    (checkDMYDateExpireIn($driver['d_drv_insur']) ? "background:#ffff00;" : ""));
            $pstyle = !checkPhoneCorrect($driver['d_phone']) ? "background:#FF9797;" : "";

            $powner = shortenPIB($po['po_name']);
            echo "<TR class='list-content'>
                    <TD class='list-content' id='$prefix{$driver['d_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$driver['d_id']}'> &nbsp; {$driver['d_name']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$driver['d_id']}' style='$pstyle'> {$driver['d_phone']} </TD>
                    <TD class='list-content' id='$prefix{$driver['d_id']}' style='$istyle'> &nbsp; {$driver['d_insurance']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$driver['d_id']}' style='$drvstyle'> &nbsp; {$driver['d_drv_insur']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$driver['d_id']}'> $content </TD>
                    <TD class='list-content' id='{$driver['d_id']}'> $carcont </TD>
                    <TD class='list-content' id='po{$po['po_id']}' style='font-size:12px;'> &nbsp; $powner &nbsp; </TD>
                </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-driver").on("click", function() {
        id = $(this).attr('id');
        load_main_hist("add-driver.php");
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            load_main_hist("driver.php?did=" + id.substr(1));
        } else if (id.substr(0, 2) == 'po') {
            load_main_hist("po.php?poid=" + id.substr(2));
        }  
    });
    $(".car").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'c') {
            load_main_hist("car.php?cid=" + id.substr(1));
        }
    });

    $("#search").click(function() {
        value = $("#query").val().toLowerCase();
        $("#tbl_drivers tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#drivers-info").click(function() {
        load_main_hist("drivers-info.php");
    });

    $("#removed-drivers").click(function() {
        load_main_hist("drivers.php?type=1");
    });
});
</script>