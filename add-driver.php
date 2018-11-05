<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['address']) 
        && isset($_POST['age']) && isset($_POST['idcode']) && isset($_POST['passport'])) {
        $name = addslashes($_POST['name']);
        strlen($name) > 2 or show_error("Надто коротке ім'я. Повинно бути не менше 7 символів.");

        $phone = addslashes($_POST['phone']);
        $address = addslashes($_POST['address']);
        $age = (int)$_POST['age'];
        $idcode = addslashes($_POST['idcode']);
        $passport = addslashes($_POST['passport']);

        if (add_driver($name, $age, $address, $phone, $idcode, $passport, "0")) {
            show_message("Водій доданий!");
            load('drivers.php', 'content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий водій вже існує!");
        }
        die();
    }

    $lid = (int)$_GET['lid'];
?>
<form action='add-driver.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <TR><TD colspan='2'><center><h2>Додати водія</h2></TD></TR>
    <tr><td>ПІБ: &nbsp;</td><td><input type='text' name='name' style='width:250px;' value='<??>'></td></tr>
    <tr><td>Телефон: &nbsp;</td><td><input type='text' name='phone' style='width:250px;' value='<??>'></td></tr>
    <tr><td>Адреса: &nbsp;</td><td><input type='text' name='address' style='width:250px;' value='<??>'></td></tr>
    <tr><td>Вік: &nbsp;</td><td><input type='text' name='age' style='width:250px;' value='<??>'></td></tr>
    <tr><td>Ідент.код: &nbsp;</td><td><input type='text' name='idcode' style='width:250px;' value='<??>'></td></tr>
    <tr><td>Паспорт: &nbsp;</td><td><input type='text' name='passport' style='width:250px;' value='<??>'></td></tr>

    <tr><td colspan='2'><center><input type='submit' value=' Додати '></td></tr>
</table>
</form>
