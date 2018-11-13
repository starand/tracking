<?
    if (!defined('TRACKING_ENGINE')) die();
?>
<div id='main_error' style='font-weight:bold; width:1024px; text-align:center;'></div>

<TABLE cellspacing='0' cellpadding='0' style='width:1024px;'>
    <TR>
        <? echo hasPermission(VIEW.LOCATIONS) ? "<TD style='vertical-align:top;width:165px;' id='left_menu'>/TD>" : "" ?>
        <TD> &nbsp; </TD><TD id='main_space' style='text-align:center'><div ></div>
    </TD></TR>
</TABLE>

<script>
$(document).ready(function() {
    $("#left_menu").load("locations.php");
});
</script>