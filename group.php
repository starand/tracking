<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.GROUP);

    isset($_GET['gid']) or die("Не вказано групу!");
    $gid = (int)$_GET['gid'];
    $group = get_perm_group($gid) or show_error("Групу не знайдено! '{$_GET['gid']}'");
?>
<center>
<h2>Група - <?=$group['p_desc'];?></h2>

<TABLE class='list-content' style='width:550px;' id='tbl_groups'>
<?
    $ps = completePermissionString($group['p_permissions']);
    $perms = str_split($ps);

    echo "<TR>
        <TD class='list-content-header'> &nbsp; # &nbsp; </TD>
            <TD class='list-content-header'> &nbsp; Назва &nbsp; </TD>
            <TD class='list-content-header'> &nbsp; Значення &nbsp; </TD>
        </TR>";

    $idx = 0;
    foreach ($perms as $perm) {
        $name = $PNAMES[$idx];
        if ($name === RESERVED) { ++$idx; continue; }

        $disabled = hasPermission(EDIT.GROUP) ? "" : "disabled";
        $select = "<SELECT class='permission' id='$idx' $disabled style='width:150px; font-size:14px;'>";
        foreach ($VNAMES as $pid => $pname) {
            $select .= "<option value='$pid' ".((int)$perm == $pid ? "selected" : "").">$pname</option>";
        }
        $select .= "</SELECT>";


        echo "<TR class='list-content'>
            <TD class='list-content' id=''> ".($idx + 1)." </TD>
                <TD class='list-content' id=''> &nbsp; $name &nbsp; </TD>
                <TD class='list-content' id=''> &nbsp; $select &nbsp; </TD>
            </TR>";

        ++$idx;
    }
?>
</TABLE>
<BR>
<TABLE class='list-content' style='width:550px;' id='tbl_groups'>
<TR><TD class='list-content'>Дозволи: </TD><TD class='list-content' id='ps-value'><?=$ps;?></TD></TR>
<TR><TD class='list-content'></TD><TD class='list-content'></TD></TR>
</TABLE>

<script>
$(document).ready(function() {
    $(".permission").change(function() {
        id = $(this).attr('id');
        val = $(this).val();

        url = 'update-perm.php?gid=<?=$gid;?>&pos=' + id + '&val=' + val;
        $('#ps-value').load(url);
    });
});
</script>
