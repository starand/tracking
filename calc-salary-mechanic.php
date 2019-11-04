<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(ADD.SALARY);
    
    if (isset($_POST['did']) && isset($_POST['amount']) && isset($_POST['formula'])) {
        $did = (int)$_POST['did'];
        get_mechanic($did) or show_error("Такий автослюсар не існує!");
        $formula = addslashes($_POST['formula']);
        strlen($formula) > 0 or show_error("Формула не може бути пустою!");
        $amount = (float)$_POST['amount'];
        $amount > 0 or show_error("Сума не може бути 0!");

        if (add_salary_record($did, $formula, $amount, EMPLOYEE_MECHANIC)) {
            show_message("Зарплата нарохавана!");
            load('salary-mechanic.php', 'main_space');
        } else {
            show_error("Помилка бази даних!");
        }
        die();
    }

    isset($_GET['did']) or die("Не вказано автослюсаря!");
    $did = (int)$_GET['did'];
    $mechanic = get_mechanic($did) or show_error("Автослюсаря не знайдено! '{$_GET['did']}'");

    $coef = number_format($mechanic['m_add_coef'], 2);
    $h_rate = number_format($mechanic['m_rate'] / 8, 2);
    $style1 = "width:50px; text-align:center;";
?>
<center>
<h2 id='view-employee'> <?=$mechanic['m_name'];?> </h2>
<span>Розрахунок заробітної плати автослюсаря</span>
<form action='calc-salary-mechanic.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table' style='width:630px;'>
    <tr>
        <td style='text-align:center;'>&nbsp; <b>Днів:</b> <input type='text' class='calc-sal' id='days' style='<?=$style1;?>'></td>
        <td style='text-align:center;'>&nbsp; <b>Годин:</b> <input type='text' class='calc-sal' id='hours' style='<?=$style1;?>' value='0'></td>
        <td style='text-align:center;'>&nbsp; <b>Годин (надбав)&rsquo;:</b> <input type='text' class='calc-sal' id='add_hours' style='<?=$style1;?>' value='0'></td>
        <td style='text-align:center;'>&nbsp; <b>Коефіцієнт: </b>
            <input type='text' id='add_coef' disabled value='<?=$coef;?>' style='<?=$style1;?>'></td>
    </tr>
<?
    $srate = 8.34;
    $mcount = getMonthCount($mechanic['m_stag']);
    $samount = $mcount * $srate;
?>
    <TR></TR><TR></TR>
    <tr>
        <td colspan='2'> &nbsp; <b>Стаж (з <?=$mechanic['m_stag'];?>)</b>: <input type='text' class='calc-sal' id='stag' style='width:40px;text-align:center;' disabled value='<?=$mcount;?>'> = <?=$samount;?></td>
        <td style='text-align:center;'>&nbsp;<b style='font-size:13px;'>Ставка/год (<?=$mechanic['m_rate'];?>/8):</b> <input type='text' id='rate' style='<?=$style1;?>' value='<?=$h_rate;?>' disabled> </td>
        <td style='text-align:center;'>&nbsp; <b>Премія</b> &nbsp; <input type='text' class='calc-sal' id='premium' style='<?=$style1;?>'> </td>
    </tr>
    <TR></TR>
    <tr><td> &nbsp; <b>Формула:</b> &nbsp; </td><td colspan='3' id='fstr' style='font-size:13px;'> </td></tr>
    <tr>
        <td></td><td style='text-align:right;'> &nbsp; <b>Сума:</b> &nbsp; </td>
        <td><input type='text' id='amount' name='amount' style='width:100px;' readonly/></td>
        <td><input type='submit' value=' Нарахувати '></td>
        <input type='hidden' name='did' value='<?=$mechanic['m_id'];?>'/>
        <input type='hidden' name='formula' id='formula'/>
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

            if (id == 'days' && $.isNumeric($('#days').val()) && $.isNumeric($('#hours').val())) {
                if (formula.length > 0) formula += ' + ';

                var days = parseFloat($('#days').val());
                var hours = parseFloat($('#hours').val());
                var add_hours = parseFloat($('#add_hours').val());
                var rate = parseFloat($('#rate').val());

                formula += '(' + days + 'd * 8 + ' + hours +'h - ' + add_hours + '’) * ' + rate;
                sum += (days * 8 + hours - add_hours) * rate;
            }

            if (id == 'stag') {
                if (formula.length > 0) formula += ' + ';
                formula += $('#stag').val() + ' * ' + srate;
                sum += $('#stag').val() * srate;
            }

            if (id == 'add_hours' && $('#add_hours').val().length > 0 && $.isNumeric($('#add_hours').val())) {
                if (formula.length > 0) formula += ' + ';

                var add_hours = parseFloat($('#add_hours').val());
                var add_coef = parseFloat($('#add_coef').val());

                formula += add_hours + '’ * ' + add_coef + ' * ' + rate;
                sum += add_hours * add_coef * rate;
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

    $("#view-employee").click(function(event) {
        load_main_hist("mechanic.php?did=<?=$did;?>");
    });
    
});
</script>