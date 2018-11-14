<?
    include_once "common/headers.php";
    $user or die("Спочатку увійдіть в систему!");
    require_permission(VIEW.SALARY);
    require_permission(VIEW.DRIVER);

    isset($_GET['did']) or die("Не вказано водія!");
    $did = (int)$_GET['did'];
    $driver = get_driver($did) or show_error("Водія не знайдено! '{$_GET['did']}'");
    $infos = get_driver_salary($did);
?>
<center>
<h2>Зарплата: <?=$driver['d_name']?></h2>
<TABLE class='list-content' style='width:850px;'>
<?
    if (!count($infos)) {
        echo "<TR class='list-content'><TD class='list-content'> &nbsp; Даних не знайдено! &nbsp; </TD></TR>";
    } else {
        $prefix = hasPermission(EDIT.SALARY) ? "es" : "";

        echo "<TR><TD class='list-content-header'> &nbsp; # &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Дата &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Формула &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Сума &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Аванс &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Зарплата &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; 3тя форма &nbsp; </TD>";
        echo hasPermission(DEL.SALARY) ? "<TD class='list-content-header'> &nbsp; X &nbsp; </TD>" : "";
        echo "</TR>";

        $i = 1;
        foreach($infos as $info) {
            echo "<TR class='list-content'>
                    <TD class='list-content' id='{$driver['d_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='{$driver['d_id']}'> &nbsp; {$info['s_date']} &nbsp; </TD>
                    <TD class='list-content' id='{$info['s_id']}'> &nbsp; {$info['s_formula']} &nbsp; </TD>
                    <TD class='list-content' id='{$driver['d_id']}'> &nbsp; {$info['s_amount']} &nbsp; </TD>
                    <TD class='edit-item' id='{$prefix}a{$info['s_id']}' style='width:80px;'> &nbsp; {$info['s_advance']} &nbsp; </TD>
                    <TD class='edit-item' id='{$prefix}s{$info['s_id']}' style='width:80px;'> &nbsp; {$info['s_salary']} &nbsp; </TD>
                    <TD class='edit-item' id='{$prefix}3{$info['s_id']}' style='width:80px;'> &nbsp; {$info['s_3rdform']} &nbsp; </TD>
                    ";
                    
            echo hasPermission(DEL.SALARY) ? "
                    <TD class='edit-item'> &nbsp; <img id='dds{$info['s_id']}' class='icon' src='$PATH/themes/light/trash.png' title='Видалити водія'> &nbsp; </TD>" : "";
            echo "</TR>";
            $i++;
        }
    }
?>

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

    $(".icon").click(function() {
        id = $(this).attr('id');
        if (id.substr(0,3) == 'dds') {
            url = "salary-month.php?month=<?=$month;?>&dds=" + id.substr(3);
            $("#main_space").load(url);
        }
    });
});
</script>