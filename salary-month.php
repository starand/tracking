<?
    include_once "common/headers.php";
    $user or die("Спочатку увійдіть в систему!");
    require_permission(VIEW.SALARY);

    if (isset($_GET['dds'])) {
        require_permission(DEL.SALARY);
        check_result(delete_salary_record((int)$_GET['dds']),  "Дані видалено!", "Помилка бази даних!");
    }

    isset($_GET['month']) or show_error("Відомість не знайдено!");
    $month = addslashes($_GET['month']);
?>
<center>
<h2>Зарплатна відомість за <?=$month;?></h2>
<TABLE class='list-content' style='width:750px;'>
<?
    $infos = get_month_salary($month);
    $stats = get_month_salarн_stats($month);
    $drivers = get_drivers_info();
    $prefix = hasPermission(EDIT.SALARY) ? "es" : "";

    if (!count($infos)) {
        echo "<TR class='list-content'><TD class='list-content'> &nbsp; Даних не знайдено! &nbsp; </TD></TR>";
    } else {
        echo "<TR><TD class='list-content-header'> &nbsp; # &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Водій &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Дата &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Сума &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Аванс &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Зарплата &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; 3тя форма &nbsp; </TD>
                <!--<TD class='list-content-header'> &nbsp; Формула &nbsp; </TD>-->";
        echo hasPermission(DEL.SALARY) ? "<TD class='list-content-header'> &nbsp; X &nbsp; </TD>" : "";
        echo "</TR>";

        $i = 1;
        foreach($infos as $info) {
            $driver = $drivers[$info['s_did']];

            $sum = $info['s_advance'] + $info['s_salary'] + $info['s_3rdform'];
            $style = $sum == $info['s_amount'] ? "background:#E9FFE7;" : "";
            echo "<TR class='list-content' style='$style'>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; {$driver['d_name']} &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; {$info['s_date']} &nbsp; </TD>
                    <TD class='list-content' id='{$info['s_id']}'> &nbsp; {$info['s_amount']} &nbsp; </TD>
                    <!--<TD class='list-content' id='$prefix{$info['s_id']}'> &nbsp; {$info['s_formula']} &nbsp; </TD>-->
                    <TD class='edit-item' id='{$prefix}a{$info['s_id']}' style='width:80px;'> &nbsp; {$info['s_advance']} &nbsp; </TD>
                    <TD class='edit-item' id='{$prefix}s{$info['s_id']}' style='width:80px;'> &nbsp; {$info['s_salary']} &nbsp; </TD>
                    <TD class='edit-item' id='{$prefix}3{$info['s_id']}' style='width:80px;'> &nbsp; {$info['s_3rdform']} &nbsp; </TD>
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
            $('#main_space').load(url);
        }
    });

    $(".icon").click(function() {
        id = $(this).attr('id');
        if (id.substr(0,3) == 'dds') {
            url = "salary-month.php?month=<?=$month;?>&dds=" + id.substr(3);
            $("#main_space").load(url);
        }
    });
});
</script>