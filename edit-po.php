<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.PO);

    isset($_GET['poid']) or show_error("Не вибрано підприємця!");
    $po = get_po((int)$_GET['poid']) or show_error("Такий підприємець не існує! '{$_GET['poid']}'");
    $poid = $po['po_id'];

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:350px; text-align:center;';

    if (isset($_GET['name'])) {
        if (isset($_GET['set'])) {
            $name = addslashes($_GET['name']);
            strlen($name) >= 7 or show_error("Надто коротке ім'я. Повинно бути не менше 7 символів.");
            set_po_name($poid, $name) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ename' value='{$po['po_name']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_name']} &nbsp; ";
        }
    } elseif (isset($_GET['phone'])) {
        if (isset($_GET['set'])) {
            $phone = addslashes($_GET['phone']);
            checkPhoneCorrect($phone) or show_error("Не правильний формат номеру!");
            set_po_phone($poid, $phone) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_phone']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ephone' value='{$po['po_phone']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_phone']} &nbsp; ";
        }
    } elseif (isset($_GET['location'])) {
        if (isset($_GET['set'])) {
            $lid = (int)$_GET['location'];
            get_location($lid) or show_error("Така локація не знайдена!");
            set_po_location($poid, $lid) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['l_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo "<SELECT class='edit-item' id='elocation' style='$style'>";
                $locations = get_locations();
                foreach ($locations as $loc) {
                    $selected = $loc['l_id'] == $po['po_lid'] ? "selected" : "";
                    echo "<option value='{$loc['l_id']}' $selected>{$loc['l_name']}</option>";
                }
            echo "</SELECT>";         
        } else {
            echo " &nbsp; {$po['l_name']} &nbsp; ";
        }
    } elseif (isset($_GET['address'])) {
        if (isset($_GET['set'])) {
            $address = addslashes($_GET['address']);
            strlen($address) > 0 or show_error("Адреса не може бути пустою!");
            set_po_address($poid, $address) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_address']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eaddress' value='{$po['po_address']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_address']} &nbsp; ";
        }
    } elseif (isset($_GET['license'])) {
        if (isset($_GET['set'])) {
            $license = addslashes($_GET['license']);
            strlen($license) > 0 or show_error("Ліценщія не може бути пустою!");
            set_po_license($poid, $license) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_license']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='elicense' value='{$po['po_license']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_license']} &nbsp; ";
        }
    } elseif (isset($_GET['birthday'])) {
        if (isset($_GET['set'])) {
            $birthday = addslashes($_GET['birthday']); // TODO add check date
            strlen($birthday) == 10 or show_error("Дата повинна містити 10 символів (дд.мм.рррр)!");
            set_po_birthday($poid, $birthday) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_birthday']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ebirthday' value='{$po['po_birthday']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_birthday']} &nbsp; ";
        }
    } elseif (isset($_GET['passport'])) {
        if (isset($_GET['set'])) {
            $passport = addslashes($_GET['passport']);
            strlen($passport) > 0 or show_error("Паспорт не може бути пустим!");
            set_po_passport($poid, $passport) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_passport']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='epassport' value='{$po['po_passport']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_passport']} &nbsp; ";
        }
    } elseif (isset($_GET['idcode'])) {
        if (isset($_GET['set'])) {
            $idcode = addslashes($_GET['idcode']);
            strlen($idcode) > 0 or show_error("Ідентифікаційний код не може бути пустим!");
            set_po_idcode($poid, $idcode) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_idcode']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eidcode' value='{$po['po_idcode']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_idcode']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['name', 'phone', 'location', 'address', 'idcode', 'passport', 'license', 'birthday'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        }).focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                url = 'edit-po.php?' + id + '=&poid=<?=$poid;?>';
                $('#' + id).load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-po.php?' + id + '=' + val + '&set=&poid=<?=$poid;?>';
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>