<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
    require_permission(EDIT.ROUTE);

    isset($_GET['id']) or show_error("Не вибрано геодані!");
    $data = get_route_data((int)$_GET['id']) or show_error("Такі геодані не існують! '{$_GET['id']}'");
    $id = $data['rd_id'];
    isset($_GET['rdId']) or show_error("Не вибрано геодані!");
    $rdId = addslashes($_GET['rdId']);

    $op = substr($rdId, 0, 3);

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; text-align:center;';

    if ($op == 'rdu') {
        if (isset($_GET['set'])) {
            $url = addslashes($_GET['set']);
            strlen($url) or show_error("Url не повинна бути пустою!");
            set_rodadata_url($id, $url) or show_error("Помилка бази даних!");
            $data = get_route_data($id);
            echo " &nbsp; {$data['rd_url']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$rdId' value='{$data['rd_url']}' style='$style;width:300px;'>";
        } else {
            echo " &nbsp; {$data['rd_url']} &nbsp; ";
        }
    } else if ($op == 'rdn') {
        if (isset($_GET['set'])) {
            $name = addslashes($_GET['set']);
            strlen($name) or show_error("Імя маршруту не повинна бути пустим!");
            set_rodadata_name($id, $name) or show_error("Помилка бази даних!");
            $data = get_route_data($id);
            echo " &nbsp; {$data['rd_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$rdId' value='{$data['rd_name']}' style='$style;width:300px;'>";
        } else {
            echo " &nbsp; {$data['rd_name']} &nbsp; ";
        }
    } else if ($op == 'rdl') {
        if (isset($_GET['set'])) {
            $len = (int)$_GET['set'] or show_error("Довжина маршруту не повинна бути 0!");
            set_rodadata_len($id, $len) or show_error("Помилка бази даних!");
            $data = get_route_data($id);
            echo " &nbsp; {$data['rd_length']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$rdId' value='{$data['rd_length']}' style='$style;width:50px;'>";
        } else {
            echo " &nbsp; {$data['rd_length']} &nbsp; ";
        }
    } else if ($op == 'rdc') {
        require_permission(EDIT.FINANCE);
        if (isset($_GET['set'])) {
            $cost = (int)$_GET['set'] or show_error("Вартість маршруту не повинна бути 0!");
            set_rodadata_cost($id, $cost) or show_error("Помилка бази даних!");
            $data = get_route_data($id);
            echo " &nbsp; {$data['rd_cost']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='e$rdId' value='{$data['rd_cost']}' style='$style;width:50px;'>";
        } else {
            echo " &nbsp; {$data['rd_cost']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['rdn', 'rdu', 'rdl', 'rdc'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        }).change(function() {
            id = $(this).attr('id').substr(1);
            val = encodeURIComponent($(this).val().trim());
            if (edittables.indexOf(id.substr(0, 3)) >= 0) {
                url = 'edit-route-data.php?id=' + id.substr(3) + '&rdId=' + id + '&set='+val;
                $('#' + id).load(url);
            }
        }).focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id.substr(0, 3)) >= 0) {
                url = 'edit-route-data.php?id=' + id.substr(3) + '&rdId=' + id;
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>