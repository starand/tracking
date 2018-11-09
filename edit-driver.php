<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['did']) or show_error("Не вибрано водія!");
    $driver = get_driver((int)$_GET['did']) or show_error("Такий водій не існує!");
    $did = $driver['d_id'];

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:340px; text-align:center;';

    if (isset($_GET['name'])) {
        if (isset($_GET['set'])) {
            $name = addslashes($_GET['name']);
            strlen($name) > 6 or show_error("Надто коротке ім'я. Повинно бути не менше 7 символів.");
            set_driver_name($did, $name) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ename' value='{$driver['d_name']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_name']} &nbsp; ";
        }
    } elseif (isset($_GET['phone'])) {
        if (isset($_GET['set'])) {
            $phone = addslashes($_GET['phone']);
            checkPhoneCorrect($phone) or show_error("Не правильний формат номеру!");
            set_driver_phone($did, $phone) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_phone']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ephone' value='{$driver['d_phone']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_phone']} &nbsp; ";
        }
    } elseif (isset($_GET['stag'])) {
        if (isset($_GET['set'])) {
            $stag = addslashes($_GET['stag']);
            strlen($stag) > 0 or show_error("Введіть стаж!");
            set_driver_stag($did, $stag) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_stag']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='estag' value='{$driver['d_stag']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_stag']} &nbsp; ";
        }
    } elseif (isset($_GET['address'])) {
        if (isset($_GET['set'])) {
            $address = addslashes($_GET['address']);
            strlen($address) > 0 or show_error("Адреса не може бути пустою!");
            set_driver_address($did, $address) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_address']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eaddress' value='{$driver['d_address']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_address']} &nbsp; ";
        }
    } elseif (isset($_GET['passport'])) {
        if (isset($_GET['set'])) {
            $passport = addslashes($_GET['passport']);
            strlen($passport) > 0 or show_error("Паспорт не може бути пустим!");
            set_driver_passport($did, $passport) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_passport']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='epassport' value='{$driver['d_passport']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_passport']} &nbsp; ";
        }
    } elseif (isset($_GET['idcode'])) {
        if (isset($_GET['set'])) {
            $idcode = addslashes($_GET['idcode']);
            strlen($idcode) > 0 or show_error("Ідентифікаційний код не може бути пустим!");
            set_driver_idcode($did, $idcode) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_idcode']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eidcode' value='{$driver['d_idcode']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_idcode']} &nbsp; ";
        }
    } elseif (isset($_GET['birthday'])) {
        if (isset($_GET['set'])) {
            $birthday = addslashes($_GET['birthday']);
            strlen($birthday) == 10 or show_error("Дата повинна містити 10 символів (дд.мм.рррр)!");
            set_driver_birthday($did, $birthday) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_birthday']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ebirthday' value='{$driver['d_birthday']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_birthday']} &nbsp; ";
        }
    } elseif (isset($_GET['wbirthday'])) {
        if (isset($_GET['set'])) {
            $wbirthday = addslashes($_GET['wbirthday']);
            strlen($wbirthday) == 10 or show_error("Дата повинна містити 10 символів (дд.мм.рррр)!");
            set_driver_wbirthday($did, $wbirthday) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_wife_birthday']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ewbirthday' value='{$driver['d_wife_birthday']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_wife_birthday']} &nbsp; ";
        }
    } elseif (isset($_GET['children'])) {
        if (isset($_GET['set'])) {
            $children = (int)$_GET['children'];
            set_driver_children($did, $children) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_children']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='echildren' value='{$driver['d_children']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_children']} &nbsp; ";
        }
    } elseif (isset($_GET['insurance'])) {
        if (isset($_GET['set'])) {
            $insurance = addslashes($_GET['insurance']);
            strlen($insurance) == 10 or show_error("Дата повинна містити 10 символів (дд.мм.рррр)!");
            set_driver_insurance($did, $insurance) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_insurance']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='einsurance' value='{$driver['d_insurance']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_insurance']} &nbsp; ";
        }
    } elseif (isset($_GET['poid'])) {
        $po = get_driver_po($did);
        if (!$po) {
            add_driver_po($did, 22);
            $po = get_driver_po($did);
        }
        if (isset($_GET['set'])) {
            $poid = (int)$_GET['poid'];
            get_po($poid) or show_error("Такий підприємець не існує!");
            set_driver_po($did, $poid) or show_error("Помилка бази даних!");
            $po = get_driver_po($did);
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
    } elseif (isset($_GET['rate'])) {
        $rateId = addslashes($_GET['rid']);
        $rid = (int)substr($rateId, 4);
        get_rate($rid) or show_error("Така ставка не знайдена");
        if (isset($_GET['set'])) {
            $rate = (int)$_GET['rate'] or show_message("Ставка повинна бути більше нуля!");
            set_route_rate($did, $rid, $rate) or show_error("Помилка бази даних!");
            $rate = get_rate($rid);
            echo " &nbsp; {$rate['rate_rate']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$rateId' value='{$rate['rate_rate']}' style='$style;width:100px;' name='$rid'>";
        } else {
            echo " &nbsp; {$rate['rate_rate']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['name', 'phone', 'stag', 'address', 'passport', 'idcode', 'birthday',
                        'wbirthday', 'children', 'insurance', 'poid'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        }).focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                url = 'edit-driver.php?' + id + '=&did=<?=$did;?>';
                $('#' + id).load(url);
            } else if (id.substr(0, 4) == 'rate') {
                url = 'edit-driver.php?rate=&did=<?=$did;?>&rid=<?=$rateId;?>';
                $('#' + id).load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-driver.php?' + id + '=' + val + '&set=&did=<?=$did;?>';
                $('#' + id).load(url);
            } else if (id.substr(0, 4) == 'rate') {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-driver.php?rate=' + val + '&rid=<?=$rateId;?>&set=&did=<?=$did;?>';
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>