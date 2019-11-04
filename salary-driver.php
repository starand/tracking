<?
    include_once "common/headers.php";
    
    checkAuthorizedUser();
    require_permission(VIEW.SALARY);
    setEmployeeMode(EMPLOYEE_DRIVER);

    $location = getActiveLocation();
    if (!$location) $location = get_location(1);
    $lid = $location['l_id'];

    $drivers = get_drivers_by_location($lid);
    $routes = get_routes_info($lid);

    $months = get_salary_months();
    echo "<b>Місяць: </b> <SELECT name='month' id='month' style='width:150px; font-size:14px;'>";
    foreach ($months as $month) {
        echo "<option value='{$month['month']}'>".getPrevMonthName($month['month'])."</option>";
    }
    echo "</SELECT> <input type='button' id='month-salary' value='Переглянути' id=''/> &nbsp; &nbsp; ";
    show_salary_type_panel(EMPLOYEE_MECHANIC);
?>
<center>
<h2>Водії - <?=$location['l_name'];?></h2>

<TABLE cellspacing='0' cellpadding='2' style='width:700px;' class='menu'>
<TR><TD> </TD><TD style='width:150px;'></TD></TR>
</TABLE>

<TABLE class='list-content' style='width:700px;' id='tbl_salary'>
    <td class='list-content-header' style='width:30px;'> &nbsp; # &nbsp; </td>
    <td class='list-content-header' style='width:100px;'> &nbsp; ПІБ &nbsp;</b> </td>
    <td class='list-content-header' style='width:70px;'> &nbsp; Марш. &nbsp; </b></td>
    <td class='list-content-header' style='width:70px;'> &nbsp; Ставка &nbsp; </b></td>
    <td class='list-content-header' style='width:70px;'> &nbsp; Стаж &nbsp; </b></td>
    <td class='list-content-header' style='width:100px;'> &nbsp; Запрплата &nbsp; </b></td>

<?
    if (!count($drivers)) {
        echo "<TR class='list-content' style='height: 38px;'>
                <TD class='list-content' colspan='3'> &nbsp; Водіїв не знайдено! &nbsp; </TD>
            </TR>";
    } else {
        $i = 1;
        foreach($drivers as $driver) {
            $rid = $driver['rate_rid'];
            $rname = $routes[$rid]['r_name'];

            $sstyle = checkDateMYFormat($driver['d_stag']) ? "" : "background:#FF9797";

            echo "<TR class='list-content'>
                    <TD class='list-content' id='d{$driver['d_id']}'> $i </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; {$driver['d_name']} &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; $rname &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; {$driver['rate_rate']} &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}' style='$sstyle'> &nbsp; {$driver['d_stag']} &nbsp; </TD>
                    <TD class='list-content' id='c{$driver['d_id']}'> <a>&nbsp; Нарахувати &nbsp;</a> </TD>
                </TR>";
            $i++;
        }
    }
?>

<script>
$(document).ready(function() {
    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            load_main_hist("driver.php?did=" + id.substr(1));
        } else if (id.substr(0, 1) == 'c') {
            load_main_hist("calc-salary-driver.php?did=" + id.substr(1));
        }
    });

    $("#month-salary").click(function() {
        month = $("#month").val();
        load_main_hist("salary-month.php?month=" + month);
    });

    $("#show-mechanic-salary").click(function() {
        load_main_hist("salary-mechanic.php");
    });
});
</script>