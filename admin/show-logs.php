<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.LOGS);

    foreach(array_reverse(glob('./logs/*.*')) as $file) {
        $filename = basename($file);
        echo "<a class='file-name' id='$filename'>$filename</a> &nbsp;"; 
    }

    if (isset($_GET['fn'])) {
        $fn = addslashes($_GET['fn']);
        if (strlen($fn) > 14 || strlen($fn) < 12) die();
        echo "<TABLE class='list-content'>";
        $content = file_get_contents('./logs/'.$fn);
        $lines = explode("\n", $content);
        foreach (array_reverse($lines) as $line) {
            $style = strpos($line, ": DELETE ") ? "background:#FFDBDB;" : "";
            $style = strpos($line, ": INSERT ") ? "background:#CDFFD2;" : $style;
            $style = strpos($line, ": UPDATE ") ? "background:#FFFDCD;" : $style;
            echo "<TR class='list-content'><TD class='list-content' style='font-size:12px;text-align:left;$style'>$line</TD></TR>";
        }
        echo "</TABLE>";
    }

?>

<script>
$(document).ready(function() {
    $(".file-name").click(function() {
        id = $(this).attr('id');
        url = 'adm.php?page=sl&fn=' + id;
        $('#main_space').load(url);
    });
});
</script>
