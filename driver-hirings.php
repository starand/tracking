<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
    require_permission(VIEW.ROUTE);
    require_permission(VIEW.HIRINGS);

    isset($_GET['did']) or show_error("Не вибрано водія!");
    $driver = get_driver((int)$_GET['did']) or show_error("Такий водій не існує!");
    $did = $driver['d_id'];

    $hirings = get_driver_hirings($did);
?>
<TABLE class='list-content'>
<?
    if (!count($hirings)) {
        echo "<TR class='list-content'>
                <TD style='font-weight:bold; text-align:center;'> &nbsp; Дані поки що не додано! &nbsp; </TD>
            </TR>";
        add_hiring_record($did, '', '');
    } else {
        echo "<TD class='list-content-header' style='width:30px;'> &nbsp; # &nbsp; </TD>
                <TD class='list-content-header' style='width:135px;'> &nbsp; Дата прийому &nbsp; </TD>
                <TD class='list-content-header' style='width:135px;'> &nbsp; Договір &nbsp; </TD>
                <TD class='list-content-header' style='width:135px;'> &nbsp; Наказ &nbsp; </TD>
                <TD class='list-content-header' style='width:135px;'> &nbsp; Дата звільнення &nbsp; </TD>
                <TD class='list-content-header' style='width:135px;'> &nbsp; Причина &nbsp; </TD>";
        $i = 1;
        foreach($hirings as $info) {
            $trstyle = $info['h_state'] == STATE_ACTUAL ? "" : "background:#F9ECEC;";
            echo "<TR class='list-content' style='$trstyle'>
                    <TD class='edit-item' id='{$info['h_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='edit-item' id='hhd{$info['h_id']}'> &nbsp; {$info['h_hire_date']} &nbsp; </TD>
                    <TD class='edit-item' id='hhc{$info['h_id']}'> &nbsp; {$info['h_contract']} &nbsp; </TD>
                    <TD class='edit-item' id='hho{$info['h_id']}'> &nbsp; {$info['h_order']} &nbsp; </TD>
                    <TD class='edit-item' id='hfd{$info['h_id']}'> &nbsp; {$info['h_fire_date']} &nbsp; </TD>
                    <TD class='edit-item' id='hfr{$info['h_id']}'> &nbsp; {$info['h_fire_reason']} &nbsp; </TD>
                </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    var edittables = ['hhd', 'hhc', 'hho', 'hfd', 'hfr'];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id.substr(0,3)) >= 0) {
            url = "edit-hiring.php?hid=" + id.substr(3) + "&editId=" + id + "&edit=";
            $('#' + id).load(url);
        }
    });
});
</script>