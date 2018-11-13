<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    require_permission(VIEW.LOGS);

    foreach(glob('./logs/*.*') as $file) {
        $filename = basename($file);
        echo "<a class='file-name' id='$filename'>$filename</a> &nbsp;"; 
    }

    if (isset($_GET['fn'])) {
        $fn = addslashes($_GET['fn']);
        if (strlen($fn) != 14) die();
        echo "<TABLE class='list-content'>";
        $content = file_get_contents('./logs/'.$fn);
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            echo "<TR class='list-content'><TD class='list-content' style='font-size:12px;text-align: left;'>$line</TD></TR>";
        }
        echo "</TABLE>";
    }

?>

<script>
$(document).ready(function() {
    $(".file-name").click(function() {
        id = $(this).attr('id');
        $('#main_space').load('adm.php?page=sl&fn='+id);
    });
});
</script>
