<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

?>
<center>
<h2>Водії</h2>
<a id='add-driver'> Додати водія </a>

<TABLE class='list-content' style='width:650px;'>
<?
    $drivers = get_drivers($lid);

    if (!count($drivers)) {
        echo "<TR class='list-content'>
                <TD> &nbsp; Водіїв поки що не додано! &nbsp; </TD>
            </TR>";
    } else {
        echo "<TD class='list-content-header'> &nbsp; ПІБ &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Телефон &nbsp; </TD>
                <TD class='list-content-header'> &nbsp; Маршрути &nbsp; </TD>";
        foreach($drivers as $driver) {
            $content = "";
            $routes = get_routes_by_driver($driver['d_id']);
            foreach($routes as $route) {
                if (strlen($content)) $content .= ", ";
                $content .= "{$route['r_name']}";
            }

            echo "<TR class='list-content'>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; <b> {$driver['d_name']} </b> &nbsp; </TD>
                    <TD class='list-content' id='d{$driver['d_id']}'> &nbsp; <b> {$driver['d_phone']} </b> &nbsp; </TD>
                    <TD class='list-content' id='{$driver['d_id']}'> &nbsp; $content &nbsp; </TD>
                </TR>";
        }
    }
?>
</TABLE>

<script>
$(document).ready(function() {
    $("#add-driver").on("click", function() {
        id = $(this).attr('id');
        $("#main_space").load("add-driver.php");
    });

    $(".list-content").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'd') {
            $('#main_space').load("driver.php?did=" + id.substr(1));
        }  
    });
});
</script>