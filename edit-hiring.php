<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.HIRING);

    isset($_GET['hid']) or show_error("Не вибрано дані прийняття водія!");
    $hiring = get_hiring((int)$_GET['hid']) or show_error("Такі дані не існують!");
    $hid = $hiring['h_id'];
    isset($_GET['editId']) or show_error("Не вибрано поле для редагування");
    $editId = addslashes($_GET['editId']);

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:135px; text-align:center;';

    $op = substr($editId, 0, 3);
    if ($op === 'hhd') {
        if (isset($_GET['set'])) {
            $hhd = addslashes($_GET['set']);
            strlen($hhd) >= 10 or show_error("Дата прийому повинна бути не менше 10 символів!");
            set_hire_date($hid, $hhd) or show_error("Помилка бази даних!");
            $hiring = get_hiring($hid);
            echo " &nbsp; {$hiring['h_hire_date']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='{$hiring['h_hire_date']}' style='$style'>";
        } else {
            echo " &nbsp; {$hiring['h_hire_date']} &nbsp; ";
        }
    } else if ($op === 'hhc') {
        if (isset($_GET['set'])) {
            $hhc = addslashes($_GET['set']);
            set_hire_contract($hid, $hhc) or show_error("Помилка бази даних!");
            $hiring = get_hiring($hid);
            echo " &nbsp; {$hiring['h_contract']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='{$hiring['h_contract']}' style='$style'>";
        } else {
            echo " &nbsp; {$hiring['h_contract']} &nbsp; ";
        }
    } else if ($op === 'hho') {
        if (isset($_GET['set'])) {
            $hho = addslashes($_GET['set']);
            set_hire_order($hid, $hho) or show_error("Помилка бази даних!");
            $hiring = get_hiring($hid);
            echo " &nbsp; {$hiring['h_order']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='{$hiring['h_order']}' style='$style'>";
        } else {
            echo " &nbsp; {$hiring['h_order']} &nbsp; ";
        }
    } else if ($op === 'hfd') {
        if (isset($_GET['set'])) {
            $hfd = addslashes($_GET['set']);
            strlen($hfd) >= 10 or show_error("Дата звільнення повинна бути не менше 10 символів!");
            set_fire_date($hid, $hfd) or show_error("Помилка бази даних!");
            $hiring = get_hiring($hid);
            echo " &nbsp; {$hiring['h_fire_date']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='{$hiring['h_fire_date']}' style='$style'>";
        } else {
            echo " &nbsp; {$hiring['h_fire_date']} &nbsp; ";
        }
    } else if ($op === 'hfr') {
        if (isset($_GET['set'])) {
            $hfr = addslashes($_GET['set']);
            set_fire_reason($hid, $hfr) or show_error("Помилка бази даних!");
            $hiring = get_hiring($hid);
            echo " &nbsp; {$hiring['h_fire_reason']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$editId' value='{$hiring['h_fire_reason']}' style='$style'>";
        } else {
            echo " &nbsp; {$hiring['h_fire_reason']} &nbsp; ";
        }
    }
        
?>
<script>
$(document).ready(function() {
    var edittables = ['hhd', 'hhc', 'hho', 'hfd', 'hfr'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        }).focusout(function() {
            id = $(this).attr('id');
            if (edittables.indexOf(id.substr(0, 3)) >= 0) {
                url = "edit-hiring.php?hid=" + id.substr(3) + "&editId=" + id;
                $('#' + id).load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            val = encodeURIComponent($(this).val().trim());
            if (edittables.indexOf(id.substr(0, 3)) >= 0) {
                url = "edit-hiring.php?hid=" + id.substr(3) + "&editId=" + id + "&set=" + val;
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>