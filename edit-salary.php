<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.SALARY);

    isset($_GET['sid']) or show_error("Не вибрано запис!");
    isset($_GET['editId']) or show_error("Не вибрано дані!");
    $info = get_salary_record((int)$_GET['sid']) or show_error("Такий запис не існує! '{$_GET['sid']}'");
    $sid = $info['s_id'];
    $editId = addslashes($_GET['editId']);

    $op = substr($editId, 0, 3);

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:80px; text-align:center;';

    if ($op == 'esa') {
        if (isset($_GET['set'])) {
            $esa = (float)$_GET['set'];
            $esa >= 0 or show_error("Аванс не може бути від'ємним!");
            $esa <= $info['s_amount'] or show_error("Аванс не може бути більшим ніж нарахована сума!");
            set_salary_advance($sid, $esa) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_advance']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='{$info['s_advance']}' style='$style'>";
        } else {
            echo " &nbsp; {$info['s_advance']} &nbsp; ";
        }
    } else if ($op == 'ess') {
        if (isset($_GET['set'])) {
            $ess = (float)$_GET['set'];
            $ess >= 0 or show_error("Зарплата не може бути від'ємною!");
            $ess <= ($info['s_amount'] - $info['s_advance']) or show_error("Зарплата не може бути більшою ніж нарахована сума мінус аванс!");
            set_salary_salary($sid, $ess) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_salary']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='{$info['s_salary']}' style='$style'>";
        } else {
            echo " &nbsp; {$info['s_salary']} &nbsp; ";
        }
    } else if ($op == 'es3') {
        $rest = $info['s_amount'] - $info['s_advance'] - $info['s_salary'];
        if (isset($_GET['set'])) {
            $es3 = (float)$_GET['set'];
            $es3 >= 0 or show_error("Зарплата (ф3) не може бути від'ємною!");
            $es3 <= ($rest + 0.01) or show_error("Зарплата (ф3) не може бути більшою ніж нарахована сума мінус виплати!");
            set_salary_3rdform($sid, $es3) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_3rdform']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='$rest ?' style='$style'>";
        } else {
            echo " &nbsp; {$info['s_3rdform']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['esa', 'ess', 'es3'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        }).focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id.substr(0, 3)) >= 0) {
                url = "edit-salary.php?sid=" + id.substr(3) + "=&editId=" + id;
                //alert(url);
                $('#' + id).load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id.substr(0, 3)) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = "edit-salary.php?sid=" + id.substr(3) + "=&editId=" + id + "&set=" + val;
                //alert(url);
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>