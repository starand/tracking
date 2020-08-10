<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.MECHANICS);

    if (isset($_GET['dd'])) {
        require_permission(DEL.MECHANIC);
        check_result(delete_mechanic((int)$_GET['dd']),  "Слюсара звільнено!", "Помилка бази даних!");
    }

    if (isset($_GET['rd'])) {
        require_permission(DEL.MECHANIC);
        check_result(restore_mechanic((int)$_GET['rd']), "Слюсара поновлено!", "Помилка бази даних!");
    }

    $type = STATE_ACTUAL;
    if ($_GET['type']) {
        $type = STATE_REMOVED;
        require_permission(DEL.MECHANICS);
    }
?>
<center>
<h2>Автослюсарі</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:1050px;' class='menu'>
<TR>
    <TD>
        Пошук: <input type='text' id='query' style='width:300px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
<?
    echo hasPermission(ADD.MECHANIC) ? "<TD style='width:100px;'><input type='button' id='add-mechanic' value=' Додати слюсара '/></TD>" : "";
    echo "<TD style='width:100px;'><input type='button' id='mechanics-info' value=' Більше даних '/></TD>";
    echo hasPermission(DEL.MECHANICS) && $type == STATE_ACTUAL ? "<TD style='width:70px;text-align:center;'><input type='button'  id='removed-mechanics' value=' Звільнені '/></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:1050px;' id='tbl_mechanics'>
<?
    $mechanics = get_all_mechanics($type);
    $prefix = hasPermission(VIEW.MECHANIC) ? "d" : "";

    if (!count($mechanics)) {
        echo "<TR class='list-content'>
                <TD style='font-weight:bold; text-align:center;'> &nbsp; Слюсарів поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TD class='list-content-header' style='width:35px;'> &nbsp; # &nbsp; </TD>
                <TD class='list-content-header' style='width:270px;'> &nbsp; ПІБ &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Телефон &nbsp; </TD>
                <TD class='list-content-header' style='width:100px;'> &nbsp; Довідка &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Підприємець &nbsp; </TD>";
        $i = 1;
        foreach($mechanics as $mechanic) {
            $po = get_mechanic_po($mechanic['m_id']);

            $istyle = !checkDateDMYFormat($mechanic['m_insurance']) ? "background:#FDFFC8;" :
                        (checkDMYDateExpired($mechanic['m_insurance']) ? "background:#FF9797;" :
                        (checkDMYDateExpireIn($mechanic['m_insurance']) ? "background:#ffff00;" : ""));
            $pstyle = !checkPhoneCorrect($mechanic['m_phone']) ? "background:#FF9797;" : "";

            $powner = shortenPIB($po['po_name']);
            echo "<TR class='list-content'>
                    <TD class='list-content' id='$prefix{$mechanic['m_id']}'> &nbsp; $i &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$mechanic['m_id']}'> &nbsp; {$mechanic['m_name']} &nbsp; </TD>
                    <TD class='list-content' id='$prefix{$mechanic['m_id']}' style='$pstyle'> {$mechanic['m_phone']} </TD>
                    <TD class='list-content' id='$prefix{$mechanic['m_id']}' style='$istyle'> &nbsp; {$mechanic['m_insurance']} &nbsp; </TD>
                    <TD class='list-content' id='po{$po['po_id']}' style='font-size:12px;'> &nbsp; $powner &nbsp; </TD>
                </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-mechanic").on("click", function() {
        id = $(this).attr('id');
        load_main_hist("add-mechanic.php");
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            load_main_hist("mechanic.php?did=" + id.substr(1));
        } else if (id.substr(0, 2) == 'po') {
            load_main_hist("po.php?poid=" + id.substr(2));
        }  
    });
    $(".car").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'c') {
            load_main_hist("car.php?cid=" + id.substr(1));
        }
    });

    $("#search").click(function() {
        value = $("#query").val().toLowerCase();
        $("#tbl_mechanics tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#mechanics-info").click(function() {
        load_main_hist("mechanics-info.php");
    });

    $("#removed-mechanics").click(function() {
        load_main_hist("mechanics.php?type=1");
    });
});
</script>