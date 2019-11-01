<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(ADD.SALARY);
    
    if (isset($_POST['did']) && isset($_POST['amount']) && isset($_POST['formula'])) {
        $did = (int)$_POST['did'];
        get_driver($did) or show_error("Такий водій не існує!");
        $formula = addslashes($_POST['formula']);
        strlen($formula) > 0 or show_error("Формула не може бути пустою!");
        $amount = (float)$_POST['amount'];
        $amount > 0 or show_error("Сума не може бути 0!");

        if (add_salary_record($did, $formula, $amount, EMPLOYEE_DRIVER)) {
            show_message("Зарплата нарохавана!");
            load('salary-driver.php', 'main_space');
        } else {
            show_error("Помилка бази даних!");
        }
        die();
    }

    isset($_GET['did']) or die("Не вказано водія!");
    $did = (int)$_GET['did'];
    $driver = get_driver($did) or show_error("Водія не знайдено! '{$_GET['did']}'");
    $routes = get_routes_by_driver($did);
?>
<center>
<h2> <?=$driver['d_name'];?> </h2>
<span>Розрахунок заробітної плати</span>
<form action='calc-salary-driver.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table' style='width:600px;'>
    <tr><td colspan='4' style='text-align:center;'>&nbsp; <b>Маршрути</b> &nbsp;</td></tr>
    <tr>
        <td style='text-align:center;'>&nbsp; <b>Назва</b> &nbsp;</td>
        <td style='text-align:center;'>&nbsp; <b>Опис</b> &nbsp;</td>
        <td style='text-align:center;'>&nbsp; <b>Ставка</b> &nbsp;</td>
        <td style='text-align:center;'>&nbsp; <b>Рейсів</b> &nbsp;</td>
    </tr>
<?
    foreach($routes as $route) {
        echo "<tr>
                <td style='text-align:center;'> &nbsp; <b>{$route['r_name']}</b> &nbsp; </td>
                <td style='text-align:center;'>{$route['r_desc']} &nbsp; </td>
                <td style='text-align:center;'`>
                    <input type='text' class='calc-sal' id='rate{$route['rate_rid']}' style='width:100px;' value='{$route['rate_rate']}' disabled></td>
                <td style='text-align:center;'><input type='text' id='count{$route['rate_rid']}' class='calc-sal' style='width:100px;'></td>
            </tr>";
    }

    $srate = 8.34;
    $mcount = getMonthCount($driver['d_stag']);
    $samount = $mcount * $srate;
?>
    <TR></TR><TR></TR>
    <tr>
        <td> &nbsp; <b>Стаж</b>: &nbsp;</td>
        <td> &nbsp; <?=$driver['d_stag'];?> &nbsp; <input type='text' id='stag' class='calc-sal' style='width:40px;' disabled value='<?=$mcount;?>'> = <?=$samount;?></td>
        <td style='text-align:center;'>&nbsp; <b>Надбавка</b> &nbsp;</td>
        <td style='text-align:center;'><input type='text' class='calc-sal' id='additional' style='width:100px;'> </td>
    </tr>
    <tr>
        <td> &nbsp; </td>
        <td> &nbsp; </td>
        <td style='text-align:center;'>&nbsp; <b>Премія</b> &nbsp;</td>
        <td style='text-align:center;'><input type='text' class='calc-sal' id='premium' style='width:100px;'> </td>
    </tr>

    <tr><td> &nbsp; <b>Формула:</b> &nbsp; </td><td colspan='3' id='fstr'> </td></tr>
    <tr>
        <td></td><td style='text-align:right;'> &nbsp; <b>Сума:</b> &nbsp; </td>
        <td><input type='text' id='amount' name='amount' style='width:100px;'/></td>
        <td><input type='submit' value=' Нарахувати '></td>
        <input type='hidden' name='did' value='<?=$driver['d_id'];?>'/>
        <input type='hidden' name='formula' id='formula' />
    </tr>
</table>
</form>

<script>
$(document).ready(function() {
    $(".calc-sal").change(function() {
        inputs = $(".calc-sal");
        srate = <?=$srate;?>;

        var sum = 0.0;
        var formula = '';
        for(var i = 0; i < inputs.length; i++) {
            var id = $(inputs[i]).attr('id');
            if (id.substr(0, 4) == 'rate') {
                countId = 'count' + id.substr(4);
                countVal = $('#'+countId).val();

                if ($.isNumeric(countVal)) {
                    if (formula.length > 0) formula += ' + ';
                    formula += $('#'+id).val() + ' * ' + countVal;
                    sum += $('#'+id).val() * countVal;
                }
            }

            if (id == 'stag') {
                if (formula.length > 0) formula += ' + ';
                formula += $('#stag').val() + ' * ' + srate;
                sum += $('#stag').val() * srate;
            }

            if (id == 'additional' && $('#additional').val().length > 0 && $.isNumeric($('#additional').val())) {
                if (formula.length > 0) formula += ' + ';
                formula += $('#additional').val();
                sum += $('#additional').val() * 1;
            }

            if (id == 'premium' && $('#premium').val().length > 0 && $.isNumeric($('#premium').val())) {
                if (formula.length > 0) formula += ' + ';
                formula += $('#premium').val() + '!';
                sum += $('#premium').val() * 1;
            }
        }

        $('#formula').val(formula);
        formula += ' = ' + sum;
        $('#fstr').html('<span style=\'color:red;\'>' + formula + '</span>');
        $('#amount').val(sum);
    });
});
</script>