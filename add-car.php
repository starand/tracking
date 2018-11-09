<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    if (isset($_POST['plate']) && isset($_POST['model']) && isset($_POST['type']) &&
        isset($_POST['insurance']) && isset($_POST['sto']) && isset($_POST['places']) &&
        isset($_POST['owner']) && isset($_POST['color'])) {
        $plate = addslashes($_POST['plate']);
        strlen($plate) >= 7 or show_error("Надто короткий номер. Повинно бути не менше 7 символів.");
        $model = addslashes($_POST['model']);
        strlen($model) >= 7 or show_error("Надто коротка модель. Повинно бути не менше 3 символів.");
        $type = (int)$_POST['type'];
        get_car_type($type) or show_error("Такий тип машини не існує! $type");
        $insurance = addslashes($_POST['insurance']);
        $sto = addslashes($_POST['sto']);
        $places = (int)$_POST['places'];

        $owner = addslashes($_POST['owner']);
        $color = addslashes($_POST['color']);

        if (add_car($plate, $model, $type, $places, $insurance, $sto, $owner, $color)) {
            show_message("Машина додана!");
            load('car.php?cid='.last_insert_id(), 'main_space');
        } else {
            show_error("Помилка бази даних! Перевірте чи така машина вже існує!");
        }
        die();
    }
?>
<form action='add-car.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <TR><TD colspan='2'><center><h2>Додати машину</h2></TD></TR>
    <tr><td>Номер: &nbsp;</td><td><input type='text' name='plate' style='width:250px;'></td></tr>
    <tr><td>Модель: &nbsp;</td><td><input type='text' name='model' style='width:250px;'></td></tr>
    <tr><td>Тип: &nbsp;</td><td>
        <SELECT name='type' style='width:250px; font-size:14px;'>
            <?
                $types = get_car_types();
                foreach ($types as $type) {
                    echo "<option value='{$type['ct_id']}'>{$type['ct_name']}</option>";
                }
            ?>
        </SELECT>
    </td></tr>
    <tr><td>Страхівка: &nbsp;</td><td><input type='text' name='insurance' style='width:250px;'></td></tr>
    <tr><td>Тех.огляд: &nbsp;</td><td><input type='text' name='sto' style='width:250px;'></td></tr>
    <tr><td>Місць: &nbsp;</td><td><input type='text' name='places' style='width:250px;'></td></tr>
    <tr><td>Власник: &nbsp;</td><td><input type='text' name='owner' style='width:250px;'></td></tr>
    <tr><td>Колір: &nbsp;</td><td><input type='text' name='color' style='width:250px;'></td></tr>
    <tr><td colspan='2'><center><input type='submit' value=' Додати '></td></tr>
</table>
</form>
