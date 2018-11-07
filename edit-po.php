<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['poid']) or show_error("Не вибрано підприємця!");
    $po = get_po((int)$_GET['poid']) or show_error("Такий підприємець не існує! '{$_GET['poid']}'");
    $poid = $po['po_id'];

    $style='font-family:"Trebuchet MS",Terminal; font-size: 14px;border: 1px solid white; width:245px; text-align:center;';

    if (isset($_GET['name'])) {
        if (isset($_GET['set'])) {
            $name = addslashes($_GET['name']);
            strlen($name) >= 7 or show_error("Надто коротке ім'я. Повинно бути не менше 7 символів.");
            set_po_name($poid, $name) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ename' value='{$po['po_name']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_name']} &nbsp; ";
        }
    } elseif (isset($_GET['phone'])) {
        if (isset($_GET['set'])) {
            $phone = addslashes($_GET['phone']);
            checkPhoneCorrect($phone) or show_error("Не правильний формат номеру!");
            set_po_phone($poid, $phone) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['po_phone']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo " <input type='text' class='edit-item' id='ephone' value='{$po['po_phone']}' style='$style'>";
        } else {
            echo " &nbsp; {$po['po_phone']} &nbsp; ";
        }
    } elseif (isset($_GET['location'])) {
        if (isset($_GET['set'])) {
            $lid = (int)$_GET['location'];
            get_location($lid) or show_error("Така локація не знайдена!");
            set_po_location($poid, $lid) or show_error("Помилка бази даних!");
            $po = get_po($poid);
            echo " &nbsp; {$po['l_name']} &nbsp; ";
        } elseif (isset($_GET['edit'])) {
            echo "<SELECT class='edit-item' id='elocation' style='font-size:14px;' style='$style'>";
            $locations = get_locations();
            foreach ($locations as $loc) {
                echo "<option value='{$loc['l_id']}'>{$loc['l_name']}</option>";
            }
            echo "</SELECT>";         
        } else {
            echo " &nbsp; {$po['l_name']} &nbsp; ";
        }
    }
?>
<script>
$(document).ready(function() {
    var edittables = ['name', 'phone', 'location'];
    $(".edit-item")
        .click(function(event) {
            event.stopImmediatePropagation();
        }).focusout(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                url = 'edit-po.php?' + id + '=&poid=<?=$poid;?>';
                $('#' + id).load(url);
            }
        }).change(function() {
            id = $(this).attr('id').substr(1);
            if (edittables.indexOf(id) >= 0) {
                val = encodeURIComponent($(this).val().trim());
                url = 'edit-po.php?' + id + '=' + val + '&set=&poid=<?=$poid;?>';
                $('#' + id).load(url);
            }
        }).focus().select();
});
</script>