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
<TABLE class='list-content' style='width:1050px;' id='tbl-month-salary'>
<?
    $infos = get_month_salary($month);
    $stats = get_month_salarн_stats($month);
    $drivers = get_drivers_info();
    $mechanics = get_mechanics_info();
    $prefix = hasPermission(EDIT.SALARY) ? "es" : "";

    if (!count($infos)) {
        echo "<TR class='list-content'><TD class='list-content'> &nbsp; Даних не знайдено! &nbsp; </TD></TR>";
    } else {
        echo "<TR><TH class='list-content-header'>  #  </TD>
                <TH class='list-content-header'>  Водій  </TD>
                <TH class='list-content-header'>  Дата нарах.  </TD>
                <TH class='list-content-header'>  Сума  </TD>
                <TH class='list-content-header'>  Аванс  </TD>
                <TH class='list-content-header'>  Зарплата  </TD>
                <TH class='list-content-header'>  3тя форма  </TD>
                <TH class='list-content-header'> Ф/р 1 </TD>
                <TH class='list-content-header'> Ф/р 2 </TD>
                <TH class='list-content-header'> Ф/р 3 </TD>
                <TH class='list-content-header'> Ф/р 4 </TD>
                <TH class='list-content-header'> Ф/р 5 </TD>
                ";
        echo hasPermission(DEL.SALARY) ? "<TD class='list-content-header'>  X  </TD>" : "";
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

            $fr_style = abs($info['s_3rdform'] - $info['s_fr1'] - $info['s_fr2'] - $info['s_fr3'] - $info['s_fr4'] - $info['s_fr5']) < 0.02 ? "" : "background:#FFEFD9;";

            echo "<TR class='list-content' style='$style'>";
            if ($driver) {
                echo "<TD class='list-content' id='d{$driver['d_id']}'>  $i  </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'>  {$driver['d_name']}  </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'>  {$info['s_date']}  </TD>";
            } elseif ($mechanic) {
                echo "<TD class='list-content' id='m{$mechanic['m_id']}'>  $i  </TD>
                    <TD class='list-content' id='m{$mechanic['m_id']}'>  {$mechanic['m_name']}  </TD>
                    <TD class='list-content' id='m{$mechanic['m_id']}'>  {$info['s_date']}  </TD>";
            }
            echo"   <TD class='list-content' id='{$info['s_id']}' style='width:90px;'>  {$info['s_amount']}  </TD>
                    <TD class='edit-item' id='{$prefix}-advance-{$info['s_id']}' style='width:90px;'>  {$info['s_advance']}  </TD>
                    <TD class='edit-item' id='{$prefix}-salary-{$info['s_id']}' style='width:90px;'>  {$info['s_salary']}  </TD>
                    <TD class='edit-item' id='{$prefix}-3rdform-{$info['s_id']}' style='width:90px;'>  {$info['s_3rdform']}  </TD>
                    <TD class='edit-item' id='{$prefix}-fr1-{$info['s_id']}' style='$fr_style'> {$info['s_fr1']} </TD>
                    <TD class='edit-item' id='{$prefix}-fr2-{$info['s_id']}' style='$fr_style'> {$info['s_fr2']} </TD>
                    <TD class='edit-item' id='{$prefix}-fr3-{$info['s_id']}' style='$fr_style'> {$info['s_fr3']} </TD>
                    <TD class='edit-item' id='{$prefix}-fr4-{$info['s_id']}' style='$fr_style'> {$info['s_fr4']} </TD>
                    <TD class='edit-item' id='{$prefix}-fr5-{$info['s_id']}' style='$fr_style'> {$info['s_fr5']} </TD>
                    ";
                    
            echo hasPermission(DEL.SALARY) ? "
                    <TD class='edit-item'>  <img id='dds{$info['s_id']}' class='icon' src='$PATH/themes/light/trash.png' title='Видалити водія'>  </TD>" : "";
            echo "</TR>";
            $i++;
        }

        echo "<TR><TD class='list-content-header' colspan='3'> &nbsp; Разом &nbsp; </TD>
                <TD class='list-content-header'>  ${stats['amount']}  </TD>
                <TD class='list-content-header'>  ${stats['advance']}  </TD>
                <TD class='list-content-header'>  ${stats['salary']}  </TD>
                <TD class='list-content-header'>  ${stats['3rdform']}  </TD>
                <TD class='list-content-header'> </TD>
                <TD class='list-content-header'> </TD>
                <TD class='list-content-header'> </TD>
                <TD class='list-content-header'> </TD>
                <TD class='list-content-header'> </TD>
                <TD class='list-content-header'> </TD>
                ";
        echo "</TR>";
    }
?>
</TABLE>

<?
    $editables = hasPermission(EDIT.SALARY)
        ? "'advance', 'salary', '3rdform', 'fr1', 'fr2', 'fr3', 'fr4', 'fr5'"
        : "'nopermission'";
?>
<script>
$(document).ready(function() {
    var edittables = [<?=$editables;?>];
    $(".edit-item").click(function() {
        id = $(this).attr('id'); p = id.split('-')
        if (edittables.indexOf(p[1]) >= 0) {
            url = "edit-salary.php?sid=" + p[2] + "=&editId=" + p[1] + "&edit=";
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