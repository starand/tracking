<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.PO);

    isset($_GET['poid']) or show_error("Не вказано підприємця!");
    $poid = (int)$_GET['poid'];
    $po = get_po($poid) or show_error("Підприємець не знайдений! {$_GET['poid']}");
?>

<center>
<h2>Підприємець: <? echo "{$po['po_name']}";?></h2>
<?
    $add_po = $po['po_state'] == STATE_ACTUAL
        ? "<input type='button' id='delete-po'  style='color:red;' value=' Звільнити підприємця ' />"
        : "<input type='button' id='restore-po' style='color:red;' value=' Поновити підприємця ' />";
?>
<TABLE cellspacing='0' cellpadding='2' style='width:500px;' class='menu'>
<TR>
    <TD> </TD>
<?
    echo hasPermission(DEL.PO) ? "<TD style='width:150px;'><a id='po-info'> $add_po </a></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про підприємця </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>ПІБ</b>: &nbsp; </TD>
        <TD class='edit-item' id='name' style='width:350px;'> &nbsp; <?=$po['po_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Телефон</b>: &nbsp; </TD>
        <TD class='edit-item' id='phone' style='width:350px;'>&nbsp; <?=$po['po_phone'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Локація</b>: &nbsp; </TD>
        <TD class='edit-item' id='location' style='width:250px;'>&nbsp; <?=$po['l_name'];?> &nbsp; </TD>
    </TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Адреса</b>: &nbsp; </TD>
        <TD class='edit-item' id='address' style='width:250px;'>&nbsp; <?=$po['po_address'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Ліцензія</b>: &nbsp; </TD>
        <TD class='edit-item' id='license' style='width:250px;'>&nbsp; <?=$po['po_license'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>День народження</b>: &nbsp; </TD>
        <TD class='edit-item' id='birthday' style='width:250px;'>&nbsp; <?=$po['po_birthday'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Паспорт</b>: &nbsp; </TD>
        <TD class='edit-item' id='passport' style='width:250px;'>&nbsp; <?=$po['po_passport'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; <b>Ідентиф.код</b>: &nbsp; </TD>
        <TD class='edit-item' id='idcode' style='width:250px;'>&nbsp; <?=$po['po_idcode'];?> &nbsp; </TD>
    </TR>
</TABLE>

<BR>

<TABLE>
<TR><TD style='vertical-align:top;'>
    <b>Працівники:</b>
    <TABLE class='list-content'>
        <TR>
            <TD class='list-content-header'> # </TD>
            <TD class='list-content-header'> Працівник </TD>
            <TD class='list-content-header'> Телефон </TD>
            <TD class='list-content-header'> Посада </TD>
        </TR>
    <?
        $drivers = get_po_drivers($poid);
        $mechanics = get_po_mechanics($poid);
        $prefix = hasPermission(VIEW.DRIVER) ? "d" : "";
        $i = 1;
        if (count($drivers) == 0 && count($mechanics) == 0) {
            echo "<TR><TD colspan='3' class='list-content' style='text-align:center;'>За цим підприємцем працівників не закріплено!</TD></TR>";
        } else {
            foreach ($drivers as $employee) {
                echo "<TR>
                        <TD class='list-content'> $i </TD>
                        <TD class='list-content' id='$prefix{$employee['d_id']}'> &nbsp; {$employee['d_name']} &nbsp; </TD>
                        <TD class='list-content' id='$prefix{$employee['d_id']}'> &nbsp; {$employee['d_phone']} &nbsp; </TD>
                        <TD class='list-content' id='$prefix{$employee['d_id']}'> &nbsp; Водій &nbsp; </TD>
                    </TR>";
                ++$i;
            }
        }
        $mechanics = get_po_mechanics($poid);
        $prefix = hasPermission(VIEW.MECHANIC) ? "m" : "";
        if (count($mechanics) != 0) {
            foreach ($mechanics as $employee) {
                echo "<TR>
                        <TD class='list-content'> $i </TD>
                        <TD class='list-content' id='$prefix{$employee['m_id']}'> &nbsp; {$employee['m_name']} &nbsp; </TD>
                        <TD class='list-content' id='$prefix{$employee['m_id']}'> &nbsp; {$employee['m_phone']} &nbsp; </TD>
                        <TD class='list-content' id='$prefix{$employee['m_id']}'> &nbsp; Автослюсар &nbsp; </TD>
                    </TR>";
                ++$i;
            }
        }
    ?>
    </TABLE>
</TD><TD style='vertical-align:top;'>
    <b>Талони:</b>
    <TABLE class='list-content'>
        <TR>
            <TD class='list-content-header'> # </TD>
            <TD class='list-content-header'> Автомобіль </TD>
            <TD class='list-content-header'> Дійсний до </TD>
        </TR>
    <?
        $coupons = get_po_temp_coupons($poid);
        $prefix = hasPermission(VIEW.CARS) ? "c" : "";

        if (count($coupons) == 0) {
            echo "<TR><TD colspan='3' class='list-content' style='text-align:center;'>Талонів не знайдено!</TD></TR>";
        } else {
            $i = 1;
            foreach ($coupons as $coupon) {
                $dstyle = checkDMYDateExpired($coupon['tc_date']) ? "background:#FF9797;" : "";
                echo "<TR style='$dstyle'>
                        <TD class='list-content'> $i </TD>
                        <TD class='list-content' id='$prefix{$coupon['c_id']}'> &nbsp; {$coupon['c_plate']} &nbsp; </TD>
                        <TD class='list-content' id='{$coupon['tc_id']}'> &nbsp; {$coupon['tc_date']} &nbsp; </TD>
                    </TR>";
                ++$i;
            }
        }
    ?>
    </TABLE>
</TD></TR>
</TABLE>

<BR>
<b>Власник наступних автомобілів:</b>
<TABLE class='list-content'>
<?
    $cars = get_cars_by_owner($po['po_name']);
    include_once "car-list.php";
?>
</TABLE>

<?
    $editables = hasPermission(EDIT.DRIVER)
        ? "'name', 'phone', 'location', 'address', 'idcode', 'passport', 'license', 'birthday'"
        : "'nopermission'";
?>
<script>
$(document).ready(function() {
    var edittables = [<?=$editables;?>];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id) >= 0) {
            url = "edit-po.php?" + id + "=&poid=<?=$poid;?>&edit=";
            $('#' + id).load(url);
        }
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            load_main_hist("driver.php?did=" + id.substr(1));
        } else if (id.substr(0, 2) == 'po') {
            load_main_hist("po.php?poid=" + id.substr(2));
        } else if (id.substr(0, 1) == 'c') {
            load_main_hist("car.php?cid=" + id.substr(1));
        } else if (id.substr(0, 1) == 'm') {
            load_main_hist("mechanic.php?did=" + id.substr(1));
        }
    });

    $("#delete-po").click(function() {
        $("#main_space").load("pos.php?dpo=<?=$poid;?>");
    });
    $("#restore-po").click(function() {
        $("#main_space").load("pos.php?rpo=<?=$poid;?>");
    });
});
</script>