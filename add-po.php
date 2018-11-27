<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(ADD.PO);

    if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['lid']) && isset($_POST['address'])
        && isset($_POST['idcode']) && isset($_POST['passport']) && isset($_POST['license']) && isset($_POST['birthday'])) {
        $name = addslashes($_POST['name']);
        strlen($name) >= 7 or show_error("Надто коротке ім'я. Повинно бути не менше 7 символів.");

        $phone = addslashes($_POST['phone']);
        $lid = (int)$_POST['lid'];
        get_location($lid) or show_error("Така локація не існує!");
        $address = addslashes($_POST['address']);
        $idcode = addslashes($_POST['idcode']);
        $passport = addslashes($_POST['passport']);
        $license = addslashes($_POST['license']);
        $birthday = addslashes($_POST['birthday']);

        if (add_po($name, $phone, $lid, $address, $idcode, $passport, $license, $birthday)) {
            show_message("ПП доданий!");
            load('pos.php', 'main_space');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий ПП вже існує!");
        }
        die();
    }
?>
<center>
<form action='add-po.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <TR><TD colspan='2'><center><h2>Додати підприємця</h2></TD></TR>
    <tr><td>ПІБ: &nbsp;</td><td><input type='text' name='name' style='width:350px;'></td></tr>
    <tr><td>Телефон: &nbsp;</td><td><input type='text' name='phone' style='width:350px;'></td></tr>
    <tr><td>Локація: &nbsp;</td><td>
        <SELECT name='lid' style='width:350px; font-size:14px;'>
        <?
            $locations = get_locations();
            foreach ($locations as $loc) {
                echo "<option value='{$loc['l_id']}'>{$loc['l_name']}</option>";
            }
        ?>
        </SELECT>
    </td></tr>
    <tr><td>Адреса: &nbsp;</td><td><input type='text' name='address' style='width:350px;'></td></tr>
    <tr><td>Ідент.код: &nbsp;</td><td><input type='text' name='idcode' style='width:350px;'></td></tr>
    <tr><td>Паспорт: &nbsp;</td><td><input type='text' name='passport' style='width:350px;'></td></tr>
    <tr><td>Ліцензія: &nbsp;</td><td><input type='text' name='license' style='width:350px;'></td></tr>
    <tr><td>День народж.: &nbsp;</td><td><input type='text' name='birthday' style='width:350px;'></td></tr>
    <tr><td colspan='2'><center><input type='submit' value=' Додати '></td></tr>
</table>
</form>
