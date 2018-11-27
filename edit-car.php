<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.CAR);

    isset($_GET['cid']) or show_error("Не вибрано машину!");
    $car = get_car((int)$_GET['cid']) or show_error("Така машина не існує!");
    $cid = $car['c_id'];

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:340px; text-align:center;';

    if (isset($_GET['plate'])) {
        if (isset($_GET['set'])) {
            $plate = addslashes($_GET['plate']);
            strlen($plate) > 5 or show_error("Надто короткий номер. Номер повинен бути не менше 6 символів.");
            set_car_plate($cid, $plate) or show_error("Помилка бази даних!");
            $car = get_car($cid);
            echo " &nbsp; {$car['c_plate']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eplate' value='{$car['c_plate']}' style='$style'>";
        } else {
            echo " &nbsp; {$car['c_plate']} &nbsp; ";
        }
    } elseif (isset($_GET['model'])) {
        if (isset($_GET['set'])) {
            $model = addslashes($_GET['model']);
            strlen($model) >= 4 or show_error("Модель повинна бути не коротше 4 символів.");
            set_car_model($cid, $model) or show_error("Помилка бази даних!");
            $car = get_car($cid);
            echo " &nbsp; {$car['c_model']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='emodel' value='{$car['c_model']}' style='$style'>";
        } else {
            echo " &nbsp; {$car['c_model']} &nbsp; ";
        }
    } elseif (isset($_GET['insurance'])) {
        if (isset($_GET['set'])) {
            $insurance = addslashes($_GET['insurance']);
            strlen($insurance) >= 8 or show_error("Дата страхівки повинна бути не коротше 8 символів.");
            set_car_insurance($cid, $insurance) or show_error("Помилка бази даних!");
            $car = get_car($cid);
            echo " &nbsp; {$car['c_insurance']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='einsurance' value='{$car['c_insurance']}' style='$style'>";
        } else {
            echo " &nbsp; {$car['c_insurance']} &nbsp; ";
        }
    } elseif (isset($_GET['sto'])) {
        if (isset($_GET['set'])) {
            $sto = addslashes($_GET['sto']);
            strlen($sto) >= 8 or show_error("Дата тех.огляду повинна бути не коротше 8 символів.");
            set_car_sto($cid, $sto) or show_error("Помилка бази даних!");
            $car = get_car($cid);
            echo " &nbsp; {$car['c_sto']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='esto' value='{$car['c_sto']}' style='$style'>";
        } else {
            echo " &nbsp; {$car['c_sto']} &nbsp; ";
        }
    }  elseif (isset($_GET['places'])) {
        if (isset($_GET['set'])) {
            $places = (int)$_GET['places'];
            $places > 0 or show_error("Введіть правильну кількість місць!");
            set_car_places($cid, $places) or show_error("Помилка бази даних!");
            $car = get_car($cid);
            echo " &nbsp; {$car['c_places']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eplaces' value='{$car['c_places']}' style='$style'>";
        } else {
            echo " &nbsp; {$car['c_places']} &nbsp; ";
        }
    } elseif (isset($_GET['owner'])) {
        if (isset($_GET['set'])) {
            $owner = addslashes($_GET['owner']);
            strlen($owner) >= 8 or show_error("Ім'я власника повинне бути не коротше 8 символів.");
            set_car_owner($cid, $owner) or show_error("Помилка бази даних!");
            $car = get_car($cid);
            echo " &nbsp; {$car['c_owner']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='eowner' value='{$car['c_owner']}' style='$style'>";
        } else {
            echo " &nbsp; {$car['c_owner']} &nbsp; ";
        }
    } elseif (isset($_GET['color'])) {
        if (isset($_GET['set'])) {
            $color = addslashes($_GET['color']);
            set_car_color($cid, $color) or show_error("Помилка бази даних!");
            $car = get_car($cid);
            echo " &nbsp; {$car['c_color']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ecolor' value='{$car['c_color']}' style='$style'>";
        } else {
            echo " &nbsp; {$car['c_color']} &nbsp; ";
        }
    } elseif (isset($_GET['type'])) {
        if (isset($_GET['set'])) {
            $type = (int)$_GET['type'];
            get_car_type($type) or show_error("Введіть правильний тип машини!");
            set_car_type($cid, $type) or show_error("Помилка бази даних!");
            $car = get_car($cid);
            echo " &nbsp; {$car['ct_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo "<SELECT class='edit-item' id='etype' style='$style'>";
            $types = get_car_types();
            foreach ($types as $type) {
                $selected = $type['ct_id'] == $car['c_type'] ? "selected" : "";
                echo "<option value='{$type['ct_id']}' $selected>{$type['ct_name']}</option>";
            }
            echo "</SELECT>";
        } else {
            echo " &nbsp; {$car['ct_name']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['plate', 'model', 'insurance', 'sto', 'places', 'type', 'owner', 'color'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        })
        .focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                url = 'edit-car.php?' + id + '=&cid=<?=$cid;?>';
                $('#' + id).load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-car.php?' + id + '=' + val + '&set=&cid=<?=$cid;?>';
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>