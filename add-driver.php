<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
    require_permission(ADD.DRIVER);

    if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['address']) 
        && isset($_POST['idcode']) && isset($_POST['passport']) && isset($_POST['birthday'])
        && isset($_POST['wbirthday']) && isset($_POST['children']) && isset($_POST['insurance'])) {
        $name = addslashes($_POST['name']);
        strlen($name) > 6 or show_error("Надто коротке ім'я. Повинно бути не менше 7 символів.");

        $phone = addslashes($_POST['phone']);
        $address = addslashes($_POST['address']);
        $idcode = addslashes($_POST['idcode']);
        $passport = addslashes($_POST['passport']);
        $birthday = addslashes($_POST['birthday']);
        $wbirthday = addslashes($_POST['wbirthday']);
        $children = addslashes($_POST['children']);
        $insurance = addslashes($_POST['insurance']);

        if (add_driver($name, $address, $phone, $idcode, $passport, "0",
            $birthday, $wbirthday, $children, $insurance)) {
            show_message("Водій доданий!");
            load('driver.php?did='.last_insert_id(), 'main_space');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий водій вже існує!");
        }
        die();
    }

    $lid = (int)$_GET['lid'];
?>
<center>
<form action='add-driver.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <TR><TD colspan='2'><center><h2>Додати водія</h2></TD></TR>
    <tr><td>ПІБ: &nbsp;</td><td><input type='text' name='name' style='width:250px;'></td></tr>
    <tr><td>Телефон: &nbsp;</td><td><input type='text' name='phone' style='width:250px;'></td></tr>
    <tr><td>День народження: &nbsp;</td><td><input type='text' name='birthday' style='width:250px;'></td></tr>
    <tr><td>День народж.дружини: &nbsp;</td><td><input type='text' name='wbirthday' style='width:250px;'></td></tr>
    <tr><td>Кількість дітей: &nbsp;</td><td><input type='text' name='children' style='width:250px;'></td></tr>
    <tr><td>Страхівка: &nbsp;</td><td><input type='text' name='insurance' style='width:250px;'></td></tr>
    <tr><td>Адреса: &nbsp;</td><td><input type='text' name='address' style='width:250px;'></td></tr>
    <tr><td>Ідент.код: &nbsp;</td><td><input type='text' name='idcode' style='width:250px;'></td></tr>
    <tr><td>Паспорт: &nbsp;</td><td><input type='text' name='passport' style='width:250px;'></td></tr>

    <tr><td colspan='2'><center><input type='submit' value=' Додати '></td></tr>
</table>
</form>
