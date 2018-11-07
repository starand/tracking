<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['poid']) or show_error("Не вказано підприємця!");
    $poid = (int)$_GET['poid'];
    $po = get_po($poid) or show_error("Підприємець не знайдений!");
?>

<center>
<h2>Підприємець: <? echo "{$po['po_name']}";?></h2>
<TABLE class='list-content'>
    <TR><TD class='list-content-header' colspan='2'> Інформація про підприємця </TD></TR>
    <TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; ПІБ: &nbsp; </TD>
        <TD class='edit-item' id='name' style='width:350px;'> &nbsp; <?=$po['po_name'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Телефон: &nbsp; </TD>
        <TD class='edit-item' id='phone' style='width:350px;'>&nbsp; <?=$po['po_phone'];?> &nbsp; </TD>
    </TR><TR class='list-content' style='height:22px;'>
        <TD class='edit-item'> &nbsp; Локація: &nbsp; </TD>
        <TD class='edit-item' id='location' style='width:250px;'>&nbsp; <?=$po['l_name'];?> &nbsp; </TD>
    </TR>
</TABLE>
<BR>

<script>
$(document).ready(function() {
    var edittables = ['name', 'phone', 'location'];
    $(".edit-item").click(function() {
        id = $(this).attr('id');
        if (edittables.indexOf(id) >= 0) {
            url = "edit-po.php?" + id + "=&poid=<?=$poid;?>&edit=";
            $('#' + id).load(url);
        }
    });
});
</script>