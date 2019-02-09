<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.GROUPS);

    $groups = get_perm_groups();
?>
<center>
<h2>Користувачі системи</h2>

<TABLE class='list-content' style='width:550px;' id='tbl_groups'>
<?
    if (!count($groups)) {
        echo "<TR class='list-content'>
                <TD> &nbsp; Користувачів поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TR><TD class='list-content-header' style='width:30px;'> &nbsp; # &nbsp; </TD>
            <TD class='list-content-header'> &nbsp; Назва &nbsp; </TD>
            <TD class='list-content-header'> &nbsp; Права &nbsp; </TD>
            <TD class='list-content-header'> &nbsp; Користувачі &nbsp; </TD>
        </TR>";

        $i = 1;
        foreach ($groups as $group) {
            $prefix = "";
            $users = get_users_by_group($group['p_id']);
            $user_list = "";
            foreach ($users as $user) {
                if ($user['u_id'] == 1) continue;
                $user_list .= (strlen($user_list) > 0 ? "<BR>" : "")."{$user['u_name']}";
            }

            echo "<TR class='list-content'>
                <TD class='list-content' id='$prefix{$group['p_id']}'> $i </TD>
                <TD class='list-content' id='$prefix{$group['p_id']}'> &nbsp; {$group['p_desc']} &nbsp; </TD>
                <TD class='list-content' id='$prefix{$group['p_id']}'> ".completePermissionString($group['p_permissions'])." </TD>
                <TD class='list-content' id='$prefix{$group['p_id']}'> $user_list </TD>
            </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("td.list-content").on("click", function() {
        id = $(this).attr('id');
        $('#main_space').load("group.php?gid=" + id);
    });
});
</script>
