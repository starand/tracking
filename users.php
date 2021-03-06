<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.USERS);

    $users = get_users();
?>
<center>
<h2>Користувачі системи</h2>

<TABLE cellspacing='0' cellpadding='2' style='width:550px;' class='menu'>
<TR>
    <TD>
        Пошук: <input type='text' id='query' style='width:230px;'/>
        <img id='search' style='height:18px;' src='<?=$PATH;?>/themes/light/search.png' title='Шукати'>
    </TD>
    <TD> </TD>
<?
    echo hasPermission(ADD.USER) ? "<TD style='width:100px;'><input type='button' id='add-user' value=' Додати користувача '/></TD>" : "";
    echo hasPermission(VIEW.GROUPS) ? "<TD style='width:50px;'><input type='button' id='groups' value=' Групи '/></TD>" : "";
?>
</TR>
</TABLE>

<TABLE class='list-content' style='width:550px;' id='tbl_users'>
<?
    if (!count($users)) {
        echo "<TR class='list-content'>
                <TD> &nbsp; Користувачів поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TR><TD class='list-content-header' style='width:30px;'> &nbsp; # &nbsp; </TD>
            <TD class='list-content-header'> &nbsp; ПІБ &nbsp; </TD>
            <TD class='list-content-header'> &nbsp; Логін &nbsp; </TD>
            <TD class='list-content-header'> &nbsp; Група &nbsp; </TD>
        </TR>";

        $i = 1;
        foreach ($users as $user) {
            echo "<TR class='list-content'>
                <TD class='list-content' id='{$user['u_id']}'> $i </TD>
                <TD class='list-content' id='{$user['u_id']}'> &nbsp; {$user['u_name']} &nbsp; </TD>
                <TD class='list-content' id='{$user['u_id']}'> {$user['u_login']} </TD>
                <TD class='list-content' id='g{$user['p_id']}'> &nbsp; {$user['p_desc']} &nbsp; </TD>
            </TR>";
            $i++;
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-user").on("click", function() {
        load_main_hist("add-user.php");
    });
    $("#groups").on("click", function() {
        load_main_hist("groups.php");
    });

    $("#search").click(function() {
        value = $("#query").val().toLowerCase();

        $("#tbl_users tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'g') {
            load_main_hist("group.php?gid=" + id.substr(1));
        } else if (id.substr(0, 1) == 'u') {
            //load_main_hist("po.php?poid=" + id.substr(2));
        }
    });
});
</script>
