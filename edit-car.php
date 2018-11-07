<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

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
    } 
?>
<script>
$(document).ready(function() {
    var edittables = ['plate', 'model', 'insurance', 'sto', 'places', 'type'];
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