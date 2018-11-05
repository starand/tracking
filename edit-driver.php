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
            echo " <input type='text' class='edit-person' id='ename' value='{$driver['d_name']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_name']} &nbsp; ";
        }
    } elseif (isset($_GET['phone'])) {
        if (isset($_GET['set'])) {
            $phone = addslashes($_GET['phone']);
            strlen($phone) > 0 or show_error("Не правильний формат номеру!");
            set_driver_phone($did, $phone) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_phone']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-person' id='ephone' value='{$driver['d_phone']}' style='$style'>";
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
            echo " <input type='text' class='edit-person' id='estag' value='{$driver['d_stag']}' style='$style'>";
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
            echo " <input type='text' class='edit-person' id='eaddress' value='{$driver['d_address']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_address']} &nbsp; ";
        }
    } elseif (isset($_GET['age'])) {
        if (isset($_GET['set'])) {
            $age = (int)$_GET['age'];
            $age >= 18 or show_error("Введіть вік більше 18 років!");
            set_driver_age($did, $age) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_age']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-person' id='eage' value='{$driver['d_age']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_age']} &nbsp; ";
        }
    } elseif (isset($_GET['passport'])) {
        if (isset($_GET['set'])) {
            $passport = addslashes($_GET['passport']);
            strlen($passport) > 0 or show_error("Паспорт не може бути пустим!");
            set_driver_passport($did, $passport) or show_error("Помилка бази даних!");
            $driver = get_driver($did);
            echo " &nbsp; {$driver['d_passport']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-person' id='epassport' value='{$driver['d_passport']}' style='$style'>";
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
            echo " <input type='text' class='edit-person' id='eidcode' value='{$driver['d_idcode']}' style='$style'>";
        } else {
            echo " &nbsp; {$driver['d_idcode']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['name', 'phone', 'stag', 'address', 'age', 'passport', 'idcode'];
    $(".edit-person")
        .click(function(event) {
            event.stopImmediatePropagation();
        })
        .focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                url = 'edit-driver.php?' + id + '=&did=<?=$did;?>';
                $('#' + id).load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-driver.php?' + id + '=' + val + '&set=&did=<?=$did;?>';
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>