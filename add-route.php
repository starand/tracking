<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    if (isset($_POST['name']) && isset($_POST['desc']) && isset($_POST['lid'])) {
        $name = addslashes($_POST['name']);
        strlen($name) > 2 or show_error("Надто коротке ім'я. Повинно бути не менше 3 символів.");
        $desc = addslashes($_POST['desc']);
        $lid = (int)$_POST['lid'];
        get_location($lid) or show_error("Локація для маршруту не існує!");

        if (add_route($name, $desc, $lid)) {
            show_message("Маршрут додано!");
            load("routes.php?lid=$lid", 'routes');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий маршрут вже існує!");
        }
        die();
    }

    $lid = (int)$_GET['lid'];
?>
<center>
<form action='add-route.php' method='post' target='submit_frame'>
<table cellspacing='5' cellpadding='1' class='form-table'>
    <tr><td colspan='2'><center><h2>Додати маршрут</h2></td></tr>
    <tr><td>Імя: &nbsp;</td><td><input type='text' name='name' style='width:250px;' value='<?=$name;?>'></td></tr>
    <tr><td>Опис: &nbsp;</td><td><input type='text' name='desc' style='width:250px;' value='<?=$name;?>'></td></tr>
    <tr>
        <td colspan='2'><center><input type='submit' value=' Додати '></td>
        <input type='hidden' name='lid' value='<?=$lid;?>'>
    </tr>
</table>
</form>
