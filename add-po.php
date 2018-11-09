<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['lid'])) {
        $name = addslashes($_POST['name']);
        strlen($name) >= 7 or show_error("Надто коротке ім'я. Повинно бути не менше 7 символів.");

        $phone = addslashes($_POST['phone']);
        $lid = (int)$_POST['lid'];
        get_location($lid) or show_error("Така локація не існує!");

        if (add_po($name, $phone, $lid)) {
            show_message("ПП доданий!");
            load('pos.php', 'main_space');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий ПП вже існує!");
        }
        die();
    }
?>
<form action='add-po.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <TR><TD colspan='2'><center><h2>Додати підприємця</h2></TD></TR>
    <tr><td>ПІБ: &nbsp;</td><td><input type='text' name='name' style='width:250px;'></td></tr>
    <tr><td>Телефон: &nbsp;</td><td><input type='text' name='phone' style='width:250px;'></td></tr>
    <tr><td>Локація: &nbsp;</td><td>
        <SELECT name='lid' style='width:250px; font-size:14px;'>
        <?
            $locations = get_locations();
            foreach ($locations as $loc) {
                echo "<option value='{$loc['l_id']}'>{$loc['l_name']}</option>";
            }
        ?>
        </SELECT>
    </td></tr>
    <tr><td colspan='2'><center><input type='submit' value=' Додати '></td></tr>
</table>
</form>
