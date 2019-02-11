<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.CAR);
    require_permission(VIEW.POS);

    isset($_GET['cid']) or show_error("Не вибрано машину!");
    $car = get_car((int)$_GET['cid']) or show_error("Така машина не існує");
    $cid = $car['c_id'];

    if (isset($_GET['dtc'])) {
        require_permission(DEL.CAR);
        $tc_id = (int)$_GET['dtc'];
        get_temp_coupon($tc_id) or show_error("Такий зв'язок не знайдено! $tc_id");
        if (remove_temp_coupon($tc_id)) {
            show_message("Талон видалено!");
            load("temp-coupons.php?cid=$cid", 'add-temp-coupon-content');
        } else {
            show_error("Помилка бази даних. Перевірте чи такий зв'язок існує!");
        }
    }
?>
<BR>
<TABLE class='list-content' style='width:500px;'>
    <TR>
        <TD class='list-content-header' style='width:30px;'> # </TD>
        <TD class='list-content-header'> Підприємець </TD>
        <TD class='list-content-header'> Дійсний до </TD>
        <? echo hasPermission(DEL.CAR) ? "<TD class='list-content-header'> X </TD>" : ""; ?>
    </TR>
<?
    $i = 1;
    $coupons = get_car_temp_coupons($cid);

    if (count($coupons)) {
        foreach ($coupons as $coupon) {
            $dstyle = checkDMYDateExpired($coupon['tc_date']) ? "background:#FF9797;" : "";

            echo "<TR class='list-content' style='height:22px;'>
                    <TD class='edit-item'> &nbsp; $i &nbsp; </TD>
                    <TD class='edit-item' style='width:300px;' id='dd{$coupon['tc_id']}'> &nbsp; {$coupon['po_name']} &nbsp; </TD>
                    <TD class='edit-item' style='width:100px;$dstyle' id='{$coupon['tc_id']}'> &nbsp; {$coupon['tc_date']} &nbsp; </TD>";
            echo hasPermission(DEL.CAR) ? "
                    <TD class='edit-item'> &nbsp; <img id='dtc{$coupon['tc_id']}' class='icon' src='$PATH/themes/light/trash.png' title='Видалити талон'> &nbsp; </TD>" : "";
            echo "</TR>";
            $i++;
        }
    } else {
        echo "<TR class='list-content' style='height:22px;'>
                <TD class='list-content' colspan='4'> &nbsp; Талони відсутні! &nbsp; </TD>
            </TR>";
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $(".icon").click(function() {
        id = $(this).attr('id');
        if (id.substr(0,3) == 'dtc') {
            url = "temp-coupons.php?cid=<?=$cid;?>&dtc=" + id.substr(3);
            $("#add-temp-coupon-content").load(url);
        }
    });
});
</script>

