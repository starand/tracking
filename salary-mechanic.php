<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.SALARY);
    setEmployeeMode(EMPLOYEE_MECHANIC);

    $months = get_salary_months();
    echo "<b>Місяць: </b> <SELECT name='month' id='month' style='width:150px; font-size:14px;'>";
    foreach ($months as $month) {
        echo "<option value='{$month['month']}'>".getPrevMonthName($month['month'])."</option>";
    }
    echo "</SELECT> <input type='button' id='month-salary' value='Переглянути' id=''/> &nbsp; &nbsp; ";
    show_salary_type_panel(EMPLOYEE_DRIVER);

    $mechanics = get_mechanics_info();
?>
<center>
<h2>Автослюсарі</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:700px;' class='menu'>
<TR><TD> </TD><TD style='width:150px;'></TD></TR>
</TABLE>

<TABLE class='list-content' style='width:700px;' id='tbl_salary'>
    <td class='list-content-header' style='width:30px;'> &nbsp; # &nbsp; </td>
    <td class='list-content-header' style='width:100px;'> &nbsp; ПІБ &nbsp;</b> </td>
    <td class='list-content-header' style='width:70px;'> &nbsp; Стаж &nbsp; </b></td>
    <td class='list-content-header' style='width:70px;'> &nbsp; Ставка &nbsp; </b></td>
    <td class='list-content-header' style='width:70px;font-size:12px;'>Коефіцієнт<BR>надбавки</b></td>
    <td class='list-content-header' style='width:100px;'> &nbsp; Запрплата &nbsp; </b></td>

<?
    if (!count($mechanics)) {
        echo "<TR class='list-content' style='height: 38px;'>
                <TD class='list-content' colspan='3'> &nbsp; Автослюсарів не знайдено! &nbsp; </TD>
            </TR>";
    } else {
        $i = 1;
        foreach($mechanics as $mechanic) {
            if ($mechanic['m_id'] == 1) continue; // skip me
            //$rid = $mechanic['rate_rid'];

            $sstyle = checkDateMYFormat($mechanic['m_stag']) ? "" : "background:#FF9797";
            $add_coef = number_format($mechanic['m_add_coef'], 2);

            echo "<TR class='list-content'>
                    <TD class='list-content' id='d{$mechanic['m_id']}'> $i </TD>
                    <TD class='list-content' id='d{$mechanic['m_id']}'> &nbsp; {$mechanic['m_name']} &nbsp; </TD>
                    <TD class='list-content' id='d{$mechanic['m_id']}' style='$sstyle'> &nbsp; {$mechanic['m_stag']} &nbsp; </TD>
                    <TD class='list-content' id='r{$mechanic['m_id']}'> &nbsp; {$mechanic['m_rate']} &nbsp; </TD>
                    <TD class='list-content' id='a{$mechanic['m_id']}'> &nbsp; $add_coef &nbsp; </TD>                    
                    <TD class='list-content' id='c{$mechanic['m_id']}'> <a>&nbsp; Нарахувати &nbsp;</a> </TD>
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
            load_main_hist("mechanic.php?did=" + id.substr(1));
        } else if (id.substr(0, 1) == 'c') {
            load_main_hist("calc-salary-mechanic.php?did=" + id.substr(1));
        } else if (id.substr(0, 1) == 'r') {
            url = "edit-mechanic.php?rate=&edit=&did=" + id.substr(1);
            $('#' + id).load(url);
        } else if (id.substr(0, 1) == 'a') {
            url = "edit-mechanic.php?add_coef=&edit=&did=" + id.substr(1);
            $('#' + id).load(url);
        }
    });

    $("#month-salary").click(function() {
        month = $("#month").val();
        load_main_hist("salary-month.php?month=" + month);
    });

    $("#show-driver-salary").click(function() {
        load_main_hist("salary-driver.php");
    });
});
</script>