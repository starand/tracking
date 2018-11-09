<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
?>
<center>
<h2>Приватні підприємці</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:500px;' class='menu'>
<TR'>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
    <TD style='width:70px;'><a id='add-po'> Додати </a></TD>
</TR>
</TABLE>


<TABLE class='list-content' style='width:500px;' id='tbl_pos'>
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

    $("#search").click(function() {
        // drivers_tbl
        value = $("#query").val().toLowerCase();

        $("#tbl_pos tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>