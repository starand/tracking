<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
?>
<center>
<TABLE class='list-content' style='width:150px;'>
    <td class='list-content-header'> &nbsp; Локація &nbsp; </td>
<?
    $locations = get_locations();
    if (!count($locations)) {
        echo "<TR class='list-content' style='height: 38px;'>
                <TD> &nbsp; Локацій поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        foreach($locations as $loc) {
            echo "<TR class='list-content' style='height: 38px;'>
                    <TD class='list-content' id='l{$loc['l_id']}'> &nbsp; <b> {$loc['l_name']} </b> &nbsp; </TD>
                </TR>";
        }
    }
?>
</TABLE>
<BR>
<a id='add-location'> Додати локацію </a>

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
    $data = "Сидор Петро Володимирович,Ч01,700/500
    Денис Ярослав станіславович,Ч02,0
    Івануса Ілля Петрович,Ч03,0";

    $lines = explode("\n", $data);
    $i = 69;
    foreach($lines as $line) {
        $parts = explode(",", $line);
        $pib = trim($parts[0]);
        $office = str_replace(' ', '', trim($parts[1]));
        $route = get_route_by_name($office);
        $rid = $route ? $route['r_id'] : "NOT_FOUND";

        $rate = (int)trim($parts[2]);

        $driver = get_driver_by_pib($pib);
        $did = $driver ? $driver['d_id'] : "NOT_FOUND";
        //echo "$did - $rid - $rate <BR>";
        //add_rate($did, $rid, $rate);
    }
?>