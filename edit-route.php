<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.ROUTE);

    isset($_GET['rid']) or show_error("Не вибрано маршрут!");
    $route = get_route((int)$_GET['rid']) or show_error("Такий маршрут не існує!");
    $rid = $route['r_id'];

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:340px; text-align:center;';

    if (isset($_GET['name'])) {
        if (isset($_GET['set'])) {
            $name = addslashes($_GET['name']);
            strlen($name) >= 3 or show_error("Надто коротке ім'я. Повинно бути не менше 3 символів.");
            set_route_name($rid, $name) or show_error("Помилка бази даних!");
            $route = get_route($rid);
            echo " &nbsp; {$route['r_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ename' value='{$route['r_name']}' style='$style'>";
        } else {
            echo " &nbsp; {$route['r_name']} &nbsp; ";
        }
    } elseif (isset($_GET['desc'])) {
        if (isset($_GET['set'])) {
            $desc = addslashes($_GET['desc']);
            strlen($desc) >= 5 or show_error("Надто коротке ім'я. Повинно бути не менше 5 символів.");
            set_route_desc($rid, $desc) or show_error("Помилка бази даних!");
            $route = get_route($rid);
            echo " &nbsp; {$route['r_desc']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='edesc' value='{$route['r_desc']}' style='$style'>";
        } else {
            echo " &nbsp; {$route['r_desc']} &nbsp; ";
        }
    } elseif (isset($_GET['location'])) {
        if (isset($_GET['set'])) {
            $lid = (int)$_GET['location'];
            get_location($lid) or show_error("Таку локацію не знайдено!");
            set_route_location($rid, $lid) or show_error("Помилка бази даних!");
            $route = get_route($rid);
            echo " &nbsp; {$route['l_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            $locations = get_locations();
            echo "<SELECT class='edit-item' id='elocation' style='$style'>";
            foreach ($locations as $loc) {
                $selected = $loc['l_id']==$route['l_id'] ? 'selected' : '';
                echo "<option value='{$loc['l_id']}' $selected>{$loc['l_name']}</option>";
            }
            echo "</SELECT>";
        } else {
            echo " &nbsp; {$route['l_name']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['name', 'desc', 'location'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        })
        .focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                url = 'edit-route.php?' + id + '=&rid=<?=$rid;?>';
                $('#' + id).load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-route.php?' + id + '=' + val + '&set=&rid=<?=$rid;?>';
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>