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
        $("#main_space").load("add-location.php");
    });
    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'l') {
            $('#main_space').load("routes.php?lid=" + id.substr(1));
        }  
    });
});
</script>

<?
    if ($user['u_login'] != 'starand') die();

    //echo hasPermission(VIEW.DRIVERS) ? "HAS" : "NO";
    //add_permission_string("Оператор", "9999");
    $data = "Федик Зіновій Іванович
    Куциняк Роман Іванович
    Дмитраш Роман Богданович";

    $lines = explode("\n", $data);
    $i = 69;
    foreach($lines as $line) {
        $parts = explode(",", $line);
        $pib = trim($parts[0]);
        $driver = get_driver_like_pib($pib);
        $did = $driver ? $driver['d_id'] : "NOT_FOUND";
        //$loc = trim($parts[1]);
        
        //echo "$did <BR>";
        //add_driver_po($did, 20); set_driver_po($did, 20);
        //add_hiring_record($drv_id, $contract, $order);
        //add_po($pib, '', $lid);
    }
?>