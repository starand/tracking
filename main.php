<?
    if (!defined('TRACKING_ENGINE')) die();
?>
<div id='main_error' style='font-weight:bold; width:1024px; text-align:center;'></div>

<TABLE cellspacing='0' cellpadding='0'>
    <TR>
        <TD style='vertical-align:top;' id='left_menu'>
        </TD>
        <TD> &nbsp; </TD><TD id='main_space'><div ></div>
    </TD></TR>
</TABLE>

<script>
$(document).ready(function() {
    $("#left_menu").load("locations.php");

    //$("#contacts").on("click", function() {
    //    $("#content").load("contacts.php");
    //});
});
</script>