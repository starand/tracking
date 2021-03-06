<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.MECHANIC);

    isset($_GET['did']) or show_error("Не вибрано автослюсаря!");
    $mechanic = get_mechanic((int)$_GET['did']) or show_error("Такий автослюсар не існує!");
    $did = $mechanic['m_id'];

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:340px; text-align:center;';

    if (isset($_GET['name'])) {
        if (isset($_GET['set'])) {
            $name = addslashes($_GET['name']);
            strlen($name) > 6 or show_error("Надто коротке ім'я. Повинно бути не менше 7 символів.");
            set_mechanic_name($did, $name) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            $name = htmlspecialchars($mechanic['m_name'], ENT_QUOTES);
            echo " <input type='text' class='edit-item' id='ename' value='{$name}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_name']} &nbsp; ";
        }
    } elseif (isset($_GET['phone'])) {
        if (isset($_GET['set'])) {
            $phone = addslashes($_GET['phone']);
            checkPhoneCorrect($phone) or show_error("Не правильний формат номеру!");
            set_mechanic_phone($did, $phone) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_phone']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ephone' value='{$mechanic['m_phone']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_phone']} &nbsp; ";
        }
    } elseif (isset($_GET['stag'])) {
        if (isset($_GET['set'])) {
            $stag = addslashes($_GET['stag']);
            strlen($stag) > 0 or show_error("Введіть стаж!");
            set_mechanic_stag($did, $stag) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_stag']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='estag' value='{$mechanic['m_stag']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_stag']} &nbsp; ";
        }
    } elseif (isset($_GET['address'])) {
        if (isset($_GET['set'])) {
            $address = addslashes($_GET['address']);
            strlen($address) > 0 or show_error("Адреса не може бути пустою!");
            set_mechanic_address($did, $address) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_address']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eaddress' value='{$mechanic['m_address']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_address']} &nbsp; ";
        }
    } elseif (isset($_GET['passport'])) {
        if (isset($_GET['set'])) {
            $passport = addslashes($_GET['passport']);
            strlen($passport) > 0 or show_error("Паспорт не може бути пустим!");
            set_mechanic_passport($did, $passport) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_passport']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='epassport' value='{$mechanic['m_passport']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_passport']} &nbsp; ";
        }
    } elseif (isset($_GET['idcode'])) {
        if (isset($_GET['set'])) {
            $idcode = addslashes($_GET['idcode']);
            strlen($idcode) > 0 or show_error("Ідентифікаційний код не може бути пустим!");
            set_mechanic_idcode($did, $idcode) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_idcode']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eidcode' value='{$mechanic['m_idcode']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_idcode']} &nbsp; ";
        }
    } elseif (isset($_GET['birthday'])) {
        if (isset($_GET['set'])) {
            $birthday = addslashes($_GET['birthday']);
            strlen($birthday) == 10 or show_error("Дата повинна містити 10 символів (дд.мм.рррр)!");
            set_mechanic_birthday($did, $birthday) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_birthday']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ebirthday' value='{$mechanic['m_birthday']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_birthday']} &nbsp; ";
        }
    } elseif (isset($_GET['wbirthday'])) {
        if (isset($_GET['set'])) {
            $wbirthday = addslashes($_GET['wbirthday']);
            strlen($wbirthday) == 10 or show_error("Дата повинна містити 10 символів (дд.мм.рррр)!");
            set_mechanic_wbirthday($did, $wbirthday) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_wife_birthday']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ewbirthday' value='{$mechanic['m_wife_birthday']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_wife_birthday']} &nbsp; ";
        }
    } elseif (isset($_GET['children'])) {
        if (isset($_GET['set'])) {
            $children = (int)$_GET['children'];
            set_mechanic_children($did, $children) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_children']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='echildren' value='{$mechanic['m_children']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_children']} &nbsp; ";
        }
    } elseif (isset($_GET['insurance'])) {
        if (isset($_GET['set'])) {
            $insurance = addslashes($_GET['insurance']);
            strlen($insurance) == 10 or show_error("Дата повинна містити 10 символів (дд.мм.рррр)!");
            set_mechanic_insurance($did, $insurance) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_insurance']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='einsurance' value='{$mechanic['m_insurance']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_insurance']} &nbsp; ";
        }
    } elseif (isset($_GET['poid'])) {
        $po = get_mechanic_po($did);
        if (!$po) {
            add_employee_po($did, 22, EMPLOYEE_MECHANIC);
            $po = get_mechanic_po($did);
        }
        if (isset($_GET['set'])) {
            $poid = (int)$_GET['poid'];
            get_po($poid) or show_error("Такий підприємець не існує!");
            set_mechanic_po($did, $poid) or show_error("Помилка бази даних!");
            $po = get_mechanic_po($did);
            echo " &nbsp; {$po['po_name']} - {$po['po_phone']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            $pos = get_pos_wo_locatons();
            echo "<SELECT class='edit-item' id='epoid' style='$style'>";
            foreach ($pos as $po) {
                $selected = $po['po_id']==22 ? 'selected' : '';
                echo "<option value='{$po['po_id']}' $selected>{$po['po_name']}</option>";
            }
            echo "</SELECT>";
        } else {
            echo " &nbsp; {$po['po_name']} - {$po['po_phone']} &nbsp; ";
        }
    } elseif (isset($_GET['education'])) {
        if (isset($_GET['set'])) {
            $education = addslashes($_GET['education']);
            strlen($education) > 0 or show_error("Поле освіта не може бути пустим!");
            set_mechanic_education($did, $education) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($did);
            echo " &nbsp; {$mechanic['m_education']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eeducation' value='{$mechanic['m_education']}' style='$style'>";
        } else {
            echo " &nbsp; {$mechanic['m_education']} &nbsp; ";
        }
    } elseif (isset($_GET['rate'])) {
        $mid = (int)$_GET['did'];
        $mechanic = get_mechanic($mid) or show_message("Автослюсар не знайдений!");
        if (isset($_GET['set'])) {
            $rate = (int)$_GET['rate'] or show_message("Ставка повинна бути більше нуля!");
            set_mechanic_rate($mid, $rate) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($mid);
            echo " &nbsp; {$mechanic['m_rate']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='erate' value='{$mechanic['m_rate']}' style='$style;width:70px;'>";
        } else {
            echo " &nbsp; {$mechanic['m_rate']} &nbsp; ";
        }
    } elseif (isset($_GET['add_coef'])) {
        $mid = (int)$_GET['did'];
        $mechanic = get_mechanic($mid) or show_message("Автослюсар не знайдений!");
        $coef = number_format($mechanic['m_add_coef'], 2);
        if (isset($_GET['set'])) {
            $add_coef = (float)$_GET['add_coef'] or show_message("Ставка повинна бути більше нуля!");
            set_mechanic_coef($mid, $add_coef) or show_error("Помилка бази даних!");
            $mechanic = get_mechanic($mid);
            $coef = number_format($mechanic['m_add_coef'], 2);
            echo " &nbsp; $coef &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eadd_coef' value='$coef' style='$style;width:70px;'>";
        } else {
            echo " &nbsp; $coef &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['name', 'phone', 'stag', 'address', 'passport', 'idcode', 'birthday',
                        'wbirthday', 'children', 'insurance', 'poid', 'education'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        }).focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                url = 'edit-mechanic.php?' + id + '=&did=<?=$did;?>';
                $('#' + id).load(url);
            } else if (id.substr(0, 4) == 'rate') {
                url = 'edit-mechanic.php?rate=&did=<?=$did;?>';
                $('#r<?=$did;?>').load(url);
            } else if (id.substr(0, 8) == 'add_coef') {
                url = 'edit-mechanic.php?add_coef=&did=<?=$did;?>';
                $('#a<?=$did;?>').load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-mechanic.php?' + id + '=' + val + '&set=&did=<?=$did;?>';
                $('#' + id).load(url);
            } else if (id.substr(0, 4) == 'rate') {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-mechanic.php?rate=' + val + '&set=&did=<?=$did;?>';
                $('#r<?=$did;?>').load(url);
            } else if (id.substr(0, 8) == 'add_coef') {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-mechanic.php?add_coef=' + val + '&set=&did=<?=$did;?>';
                $('#a<?=$did;?>').load(url);
            }
        }).focus().select();
});
</script>