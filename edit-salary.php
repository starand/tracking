<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.SALARY);

    isset($_GET['sid']) or show_error("Не вибрано запис!");
    isset($_GET['editId']) or show_error("Не вибрано дані!");
    $info = get_salary_record((int)$_GET['sid']) or show_error("Такий запис не існує! '{$_GET['sid']}'");
    $sid = $info['s_id'];
    $editId = addslashes($_GET['editId']);

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:80px; text-align:center;';

    if ($editId == 'advance') {
        if (isset($_GET['set'])) {
            $esa = (float)$_GET['set'];
            $esa >= 0 or show_error("Аванс не може бути від'ємним!");
            $esa <= $info['s_amount'] or show_error("Аванс не може бути більшим ніж нарахована сума!");
            set_salary_advance($sid, $esa) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_advance']} &nbsp; ";
            //die();
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='{$info['s_advance']}' style='$style'>";
        } else {
            echo " &nbsp; {$info['s_advance']} &nbsp; ";
        }
    } else if ($editId == 'salary') {
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
    } else if ($editId == '3rdform') {
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
    } else if ($editId == 'fr1') {
        $rest = $info['s_3rdform'] - $info['s_fr2'] - $info['s_fr3'] - $info['s_fr4'] - $info['s_fr5'];
        if (isset($_GET['set'])) {
            $fr1 = (float)$_GET['set'];
            $fr1 >= 0 or show_error("Сума не може бути від'ємною!");
            $fr1 <= ($rest + 0.01) or show_error("Сума не може бути більшою ніж 3тя форма мінус сума ф.р.!");
            set_salary_fr($sid, 'fr1', $fr1) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_fr1']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='$rest' style='$style; width:60px;'>";
        } else {
            echo " &nbsp; {$info['s_fr1']} &nbsp; ";
        }
    } else if ($editId == 'fr2') {
        $rest = $info['s_3rdform'] - $info['s_fr1'] - $info['s_fr3'] - $info['s_fr4'] - $info['s_fr5'];
        if (isset($_GET['set'])) {
            $fr2 = (float)$_GET['set'];
            $fr2 >= 0 or show_error("Сума не може бути від'ємною!");
            $fr2 <= ($rest + 0.01) or show_error("Сума не може бути більшою ніж 3тя форма мінус сума ф.р.!");
            set_salary_fr($sid, 'fr2', $fr2) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_fr2']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='$rest' style='$style; width:40px;'>";
        } else {
            echo " &nbsp; {$info['s_fr2']} &nbsp; ";
        }
    } else if ($editId == 'fr3') {
        $rest = $info['s_3rdform'] - $info['s_fr1'] - $info['s_fr2'] - $info['s_fr4'] - $info['s_fr5'];
        if (isset($_GET['set'])) {
            $fr3 = (float)$_GET['set'];
            $fr3 >= 0 or show_error("Сума не може бути від'ємною!");
            $fr3 <= ($rest + 0.01) or show_error("Сума не може бути більшою ніж 3тя форма мінус сума ф.р.!");
            set_salary_fr($sid, 'fr3', $fr3) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_fr3']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='$rest' style='$style; width:40px;'>";
        } else {
            echo " &nbsp; {$info['s_fr3']} &nbsp; ";
        }
    } else if ($editId == 'fr4') {
        $rest = $info['s_3rdform'] - $info['s_fr1'] - $info['s_fr2'] - $info['s_fr3'] - $info['s_fr5'];
        if (isset($_GET['set'])) {
            $fr4 = (float)$_GET['set'];
            $fr4 >= 0 or show_error("Сума не може бути від'ємною!");
            $fr4 <= ($rest + 0.01) or show_error("Сума не може бути більшою ніж 3тя форма мінус сума ф.р.!");
            set_salary_fr($sid, 'fr4', $fr4) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_fr4']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='$rest' style='$style; width:40px;'>";
        } else {
            echo " &nbsp; {$info['s_fr4']} &nbsp; ";
        }
    } else if ($editId == 'fr5') {
        $rest = $info['s_3rdform'] - $info['s_fr1'] - $info['s_fr2'] - $info['s_fr3'] - $info['s_fr4'];
        if (isset($_GET['set'])) {
            $fr5 = (float)$_GET['set'];
            $fr5 >= 0 or show_error("Сума не може бути від'ємною!");
            $fr5 <= ($rest + 0.01) or show_error("Сума не може бути більшою ніж 3тя форма мінус сума ф.р.!");
            set_salary_fr($sid, 'fr5', $fr5) or show_error("Помилка бази даних!");
            $info = get_salary_record($sid);
            echo " &nbsp; {$info['s_fr5']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='$rest' style='$style; width:40px;'>";
        } else {
            echo " &nbsp; {$info['s_fr5']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['advance', 'salary', '3rdform', 'fr1', 'fr2', 'fr3', 'fr4', 'fr5'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        }).focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                url = "edit-salary.php?sid=<?=$sid;?>&editId=" + id;
                //alert(url);
                $('#es-'+id+'-<?=$sid;?>').load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = "edit-salary.php?sid=<?=$sid;?>&editId=" + id + "&set=" + val;
                //alert(url);
                $('#es-'+id+'-<?=$sid;?>').load(url);     
            }
        }).on('keypress',function(e) {
            if(e.which == 13) {
                id = $(this).attr('id').substr(1);
                if (edittables.indexOf(id) >= 0) {
                    val = encodeURIComponent($(this).val().trim());
                    url = "edit-salary.php?sid=<?=$sid;?>&editId=" + id + "&set=" + val;
                    //alert(url);
                    $('#es-'+id+'-<?=$sid;?>').load(url);     
                }
            }
        }).focus().select();
});
</script>