<?
    include_once "common/headers.php";
    //$user or die("Not authorized user!");

    echo "<TABLE cellspacing='0' cellpadding='0' style='width:100%;'>
            <TR style='height:50px;'>
                <TD class ='top-title' id='top_logo' style='width:130px;'><div class='top-logo'>&nbsp; Tracking &nbsp;</div></TD>";
                if ($user) {
                    echo "<TD class='top-title' id='drivers' style='width:80px;'> &nbsp; Водії &nbsp; </TD>
                        <TD class='top-title'> &nbsp; </TD>
                        <TD class='top-title' id='logout' style='width:80px;'> &nbsp; Вихід &nbsp; </TD>";
                } else {
                    echo "<TD class='top-title'> &nbsp; </TD>
                        <TD class='top-title' id='login' style='width:80px;'> &nbsp; Вхід &nbsp; </TD>";
                }
        echo "</TR>
        </TABLE>";
?>

<script>
$(document).ready(function() {
    $("#login").on("click", function() {
        $("#content").load("login.php");
    });
    $("#logout").on("click", function() {
        $("#main").load("index.php?logout=");
    });
    $("#locations").on("click", function() {
        $("#left_menu").load("locations.php");
    });
    $("#drivers").on("click", function() {
        $("#main_space").load("drivers.php");
    });
    $("#top_logo").on("click", function() {
        $("#left_menu").load("locations.php");
    });
});
</script>