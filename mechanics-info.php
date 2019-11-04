<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    $children_count = get_mechanic_children_count();
?>
<center>
<h2>Інформація про автослюсарів</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:750px;'>
<TR>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
    <TD style='width:150px;font-size:12px;text-align:right;'>Кількість дітей: <?=$children_count;?></TD>
</TR>
</TABLE>

<TABLE class='list-content' style='width:750px;' id='tbl_mechanics'>
<?
    $mechanics = get_all_mechanics();
    //$hirings = get_hiring_info(EMPLOYEE_DRIVER);
    if (!count($mechanics)) {
        echo "<TR class='list-content'>
                <TD> &nbsp; Водіїв поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TD class='list-content-header'> &nbsp; # &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; ПІБ &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; День народження &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Д.Н. дружини &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Дітей &nbsp; </TD>";
        $i = 1;
        foreach($mechanics as $mechanic) {
            $bstyle = !checkDateDMYFormat($mechanic['m_birthday']) ? "background:#FFDBDB;" : 
                        (checkIsCurrentMonth($mechanic['m_birthday']) ? "background:#C2FC9F;" : "");


            $wstyle = !checkDateDMYFormat($mechanic['m_wife_birthday']) ? "background:#FFDBDB;" : 
                        (checkIsCurrentMonth($mechanic['m_wife_birthday']) ? "background:#C2FC9F;" : "");

            $children = $mechanic['m_children'] > 0 ? $mechanic['m_children'] : "";

            echo "<TR class='list-content'>
                    <TD class='list-content' id='d{$mechanic['m_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='d{$mechanic['m_id']}'> &nbsp; {$mechanic['m_name']} &nbsp; </TD>
                    <TD class='list-content' id='d{$mechanic['m_id']}' style='$bstyle'> &nbsp {$mechanic['m_birthday']} &nbsp </TD>
                    <TD class='list-content' id='d{$mechanic['m_id']}' style='$wstyle'> &nbsp; {$mechanic['m_wife_birthday']} &nbsp; </TD>
                    <TD class='list-content' id='d{$mechanic['m_id']}'> &nbsp; $children &nbsp; </TD>
                </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            load_main_hist("mechanic.php?did=" + id.substr(1));
        }
    });

    $("#search").click(function() {
        value = $("#query").val().toLowerCase();
        $("#tbl_mechanics tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>