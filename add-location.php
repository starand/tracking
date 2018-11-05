<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    if (isset($_POST['name'])) {
        $name = addslashes($_POST['name']);
        strlen($name) > 2 or show_error("Надто коротке ім'я. Повинно бути не менше 3 символів.");

        if (add_location($name)) {
            show_message("Локацію додано!");
            load('locations.php', 'content');
        } else {
            show_error("Помилка бази даних. Перевірте чи така локація вже існує!");
        }
        die();
    }
?>
<center>
<form action='add-location.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <tr><td colspan='2'><center><h2>Додати локацію</h2></td></tr>
    <tr><td>Імя: &nbsp;</td><td><input type='text' name='name' style='width:250px;' value='<?=$name;?>'></td></tr>
    <tr><td colspan='2'><center><input type='submit' value=' Додати '></td></tr>
</table>
</form>
