<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
?>
<center>
<h2>Водії</h2>


<TABLE cellspacing='0' cellpadding='2' style='width:900px;'>
<TR'>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
    <TD style='width:110px;'><a id='add-driver'> Додати водія </a></TD>
</TR>
</TABLE>

<TABLE class='list-content' style='width:900px;' id='drivers_tbl'>
<?


    $drivers = get_all_drivers();
    //$hirings = get_hiring_info();

    if (!count($drivers)) {
        echo "<TR class='list-content'>
                <TD> &nbsp; Водіїв поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TD class='list-content-header'> &nbsp; # &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; ПІБ &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Телефон &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Довідка &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Маршрути &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Машини &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Підприємець &nbsp; </TD>";
        $i = 1;
        foreach($drivers as $driver) {
            $content = "";
            $routes = get_routes_by_driver($driver['d_id']);
            foreach($routes as $route) {
                if (strlen($content)) $content .= ", ";
                $content .= "{$route['r_name']}";
            }

            $carcont = "";
            $cars = get_cars_by_driver($driver['d_id']);
            foreach ($cars as $car) {
                if (strlen($carcont)) $carcont .= " &nbsp; <BR> &nbsp; ";
                $carcont .= "<a class='car' id='c{$car['c_id']}'>{$car['c_plate']}</a>";
            }

            $po = get_driver_po($driver['d_id']);

            $istyle = !checkDateDMYFormat($driver['d_insurance']) ? "background:#FDFFC8;" :
                        (checkDMYDateExpired($driver['d_insurance']) ? "background:#FF9797;" : "");

            $pstyle = !checkPhoneCorrect($driver['d_phone']) ? "background:#FF9797;" : "";
            echo "<TR class='list-content'>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; {$driver['d_name']} &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}' style='$pstyle'> {$driver['d_phone']} </TD>
                    <TD class='list-content' id='d{$driver['d_id']}' style='$istyle'> &nbsp; {$driver['d_insurance']} &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; $content &nbsp; </TD>
                    <TD class='list-content' id='{$driver['d_id']}'> &nbsp; $carcont &nbsp; </TD>
                    <TD class='list-content' id='po{$driver['d_id']}' style='font-size:12px;'> &nbsp; {$po['po_name']} &nbsp; </TD>
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
        $("#main_space").load("add-driver.php");
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            $('#main_space').load("driver.php?did=" + id.substr(1));
        } else if (id.substr(0, 2) == 'po') {
            $('#main_space').load("po.php?poid=" + id.substr(2));
        }  
    });
    $(".car").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'c') {
            $('#main_space').load("car.php?cid=" + id.substr(1));
        }
    });

    $("#search").click(function() {
        value = $("#query").val().toLowerCase();
        $("#tbl_drivers tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>