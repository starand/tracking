<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.SALARY);

    if (isset($_GET['dds'])) {
        require_permission(DEL.SALARY);
        check_result(delete_salary_record((int)$_GET['dds']),  "Дані видалено!", "Помилка бази даних!");
    }

    isset($_GET['month']) or show_error("Відомість не знайдено!");
    $month = addslashes($_GET['month']);
?>
<center>
<h2>Зарплатна відомість за <?=getPrevMonthName($month);?></h2>
<TABLE class='list-content' style='width:850px;' id='tbl-month-salary'>
<?
    $infos = get_month_salary($month);
    $stats = get_month_salarн_stats($month);
    $drivers = get_drivers_info();
    $mechanics = get_mechanics_info();
    $prefix = hasPermission(EDIT.SALARY) ? "es" : "";

    if (!count($infos)) {
        echo "<TR class='list-content'><TD class='list-content'> &nbsp; Даних не знайдено! &nbsp; </TD></TR>";
    } else {
        echo "<TR><TH class='list-content-header'> &nbsp; # &nbsp; </TD>
                <TH class='list-content-header'> &nbsp; Водій &nbsp; </TD>
                <TH class='list-content-header'> &nbsp; Дата нарах. &nbsp; </TD>
                <TH class='list-content-header'> &nbsp; Сума &nbsp; </TD>
                <TH class='list-content-header'> &nbsp; Аванс &nbsp; </TD>
                <TH class='list-content-header'> &nbsp; Зарплата &nbsp; </TD>
                <TH class='list-content-header'> &nbsp; 3тя форма &nbsp; </TD>";
        echo hasPermission(DEL.SALARY) ? "<TD class='list-content-header'> &nbsp; X &nbsp; </TD>" : "";
        echo "</TR>";

        $i = 1;
        foreach($infos as $info) {
            unset($driver); unset($mechanic);
            if ($info['s_emp_type'] == EMPLOYEE_DRIVER) {
                $driver = $drivers[$info['s_eid']];
            } elseif ($info['s_emp_type'] == EMPLOYEE_MECHANIC) {
                $mechanic = $mechanics[$info['s_eid']];
            }

            $sum = $info['s_advance'] + $info['s_salary'] + $info['s_3rdform'];
            $style = abs($sum - $info['s_amount']) < 0.01 ? "background:#E9FFE7;" : "";
            echo "<TR class='list-content' style='$style'>";
            if ($driver) {
                echo "<TD class='list-content' id='d{$driver['d_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; {$driver['d_name']} &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; {$info['s_date']} &nbsp; </TD>";
            } elseif ($mechanic) {
                echo "<TD class='list-content' id='m{$mechanic['m_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='m{$mechanic['m_id']}'> &nbsp; {$mechanic['m_name']} &nbsp; </TD>
                    <TD class='list-content' id='m{$mechanic['m_id']}'> &nbsp; {$info['s_date']} &nbsp; </TD>";
            }
            echo"   <TD class='list-content' id='{$info['s_id']}' style='width:90px;'> &nbsp; {$info['s_amount']} &nbsp; </TD>
                    <TD class='edit-item' id='{$prefix}a{$info['s_id']}' style='width:90px;'> &nbsp; {$info['s_advance']} &nbsp; </TD>
                    <TD class='edit-item' id='{$prefix}s{$info['s_id']}' style='width:90px;'> &nbsp; {$info['s_salary']} &nbsp; </TD>
                    <TD class='edit-item' id='{$prefix}3{$info['s_id']}' style='width:90px;'> &nbsp; {$info['s_3rdform']} &nbsp; </TD>
                    ";
                    
            echo hasPermission(DEL.SALARY) ? "
                    <TD class='edit-item'> &nbsp; <img id='dds{$info['s_id']}' class='icon' src='$PATH/themes/light/trash.png' title='Видалити водія'> &nbsp; </TD>" : "";
            echo "</TR>";
            $i++;
        }

        echo "<TR><TD class='list-content-header' colspan='3'> &nbsp; Разом &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; ${stats['amount']} &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; ${stats['advance']} &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; ${stats['salary']} &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; ${stats['3rdform']} &nbsp; </TD>
                <TD class='list-content-header'> </TD>";
        echo "</TR>";
    }
?>
</TABLE>

<?
    $editables = hasPermission(EDIT.SALARY)
        ? "'esa', 'ess', 'es3'"
        : "'nopermission'";
?>
<script>
$(document).ready(function() {
    var edittables = [<?=$editables;?>];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id.substr(0, 3)) >= 0) {
            url = "edit-salary.php?sid=" + id.substr(3) + "=&editId=" + id + "&edit=";
            $('#' + id).load(url);
        }
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            url = "driver-salary.php?did=" + id.substr(1);
            load_main_hist(url);
        } else if (id.substr(0, 1) == 'm') {
            url = "driver-salary.php?mid=" + id.substr(1);
            load_main_hist(url);
        }
    });

    $(".icon").click(function() {
        id = $(this).attr('id');
        if (id.substr(0,3) == 'dds') {
            url = "salary-month.php?month=<?=$month;?>&dds=" + id.substr(3);
            $("#main_space").load(url);
        }
    });

    var table = $('table');

    $('.list-content-header')
        .wrapInner('<span title="sort this column"/>')
        .each(function(){
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            th.click(function() {
                table.find('td').filter(function() {
                    return $(this).index() === thIndex;
                }).sortElements(function(a, b) {
                    if( $.text([a]) == $.text([b]) ) { return 0; }
                    return $.text([a]) > $.text([b]) ? (inverse ? -1 : 1) : (inverse ? 1 : -1);
                }, function() {
                    // parentNode is the element we want to move
                    return this.parentNode; 
                });
                inverse = !inverse;
                alert(1);
            });
        });
});
</script>