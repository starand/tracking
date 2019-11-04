<?
    include_once "common/headers.php";
    checkAuthorizedUser();

    if (!hasPermission(VIEW.LOCATIONS)) die();
?>
<center>
<TABLE class='list-content' style='width:150px;'>
    <td class='list-content-header'> &nbsp; Локація &nbsp; </td>
<?
    $locations = get_locations();
    $prefix = hasPermission(VIEW.ROUTES) ? "l" : "";
    if (!count($locations)) {
        echo "<TR class='list-content' style='height: 38px;'>
                <TD> &nbsp; Локацій поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        foreach($locations as $loc) {
            echo "<TR class='list-content' style='height: 38px;'>
                    <TD class='list-content' id='$prefix{$loc['l_id']}'> &nbsp; <b> {$loc['l_name']} </b> &nbsp; </TD>
                </TR>";
        }
    }
?>
</TABLE>
<BR>
<? echo hasPermission(ADD.LOCATION) ? "<a id='add-location'> Додати локацію </a>" : ""; ?>

<script>
$(document).ready(function() {
    $("#add-location").on("click", function() {
        load_main_hist("add-location.php");
    });
    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'l') {
            load_main_hist("routes.php?lid=" + id.substr(1));
        }  
    });
});
</script>

<?
    if ($user['u_login'] != 'starand') die();

	$sql = "SELECT * FROM tracking_salary";
    $recs = res_to_array(uquery($sql));
    foreach ($recs as $rec) {
        $driver = get_driver($rec['s_eid']);
        if ($driver) continue;

        $mechanic = get_mechanic($rec['s_eid']);
        //echo "{$mechanic['m_name']} <BR>";

        $sql = "update tracking_salary set s_emp_type=1 where s_id={$rec['s_id']}";
        //uquery($sql);
    }
?>