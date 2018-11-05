<?
    if (!defined('TRACKING_ENGINE')) die();
?>
<div id='main_error'></div>

<div id='content'>
    <TABLE cellspacing='0' cellpadding='0'>
        <TR>
            <TD style='vertical-align:top;' id='left_menu'>
            </TD>
            <TD> &nbsp; </TD><TD id='main_space'><div ></div>
        </TD></TR>
    </TABLE>
</div>

<script>
$(document).ready(function() {
    $("#left_menu").load("locations.php");

    //$("#contacts").on("click", function() {
    //    $("#content").load("contacts.php");
    //});
});
</script>