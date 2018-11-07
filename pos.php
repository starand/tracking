<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
?>
<center>
<h2>Приватні підприємці</h2>
<a id='add-po'> Додати ПП </a>

<TABLE class='list-content' style='width:450px;'>
<?
    $pos = get_pos();

    if (!count($pos)) {
        echo "<TR class='list-content'>
                <TD> &nbsp; Підприємці поки що не додані! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TD class='list-content-header'> &nbsp; # &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; ПІБ &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Телефон &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Локація &nbsp; </TD>
                ";
        $i = 1;
        foreach($pos as $po) {
            $pstyle = !checkPhoneCorrect($po['po_phone']) ? "background:#FF9797;" : "";

            echo "<TR class='list-content'>
                    <TD class='list-content' id='c{$po['po_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='c{$po['po_id']}'> &nbsp; {$po['po_name']} &nbsp; </TD>
                    <TD class='list-content' id='c{$po['po_id']}' style='$pstyle'> &nbsp; {$po['po_phone']} &nbsp; </TD>
                    <TD class='list-content' id='{$po['po_id']}'> &nbsp; {$po['l_name']} &nbsp; </TD>
                </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-po").on("click", function() {
        id = $(this).attr('id');
        $("#main_space").load("add-po.php");
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'c') {
            $('#main_space').load("po.php?poid=" + id.substr(1));
        }  
    });

});
</script>