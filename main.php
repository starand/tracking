<?
    if (!defined('TRACKING_ENGINE')) die();
?>
<TABLE cellspacing='0' cellpadding='0' style='width:1200px;'>
<TR>
    <TD><div id='main_error' style='font-weight:bold; width:1100px; text-align:center;'></div></TD>
    <TD><img id='go-prev' class='icon' src='<?=$PATH;?>/themes/light/prev.png' title=' Попередня сторінка '></TD>
    <TD><img id='go-next' class='icon' src='<?=$PATH;?>/themes/light/next.png' title=' Наступна сторінка '></TD>
</TR>
</TABLE>


<TABLE cellspacing='0' cellpadding='0' style='width:1200px;'>
    <TR>
        <? echo hasPermission(VIEW.LOCATIONS) ? "<TD style='vertical-align:top;width:165px;' id='left_menu'>/TD>" : "" ?>
        <TD> &nbsp; </TD><TD id='main_space' style='text-align:center;vertical-align:top;'><div ></div>
    </TD></TR>
</TABLE>

<script>
$(document).ready(function() {
    $("#left_menu").load("locations.php");

    $("#go-prev").on("click", function() {
        goPrev();
    });
    $("#go-next").on("click", function() {
        goNext();
    });
});
</script>