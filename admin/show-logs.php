<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.LOGS);

    $logFiles = array();
    foreach(array_reverse(glob('./logs/*.*')) as $file) {
        $filename = basename($file);
        $parts = explode('.', $filename);
        $year = $parts[2].".".$parts[1];
        $day = (int)$parts[0];

        if (!isset($logFiles[$year])) $logFiles[$year] = array();

        $logFiles[$year][$day] = $filename;
    }

    echo "<center><h2>DataBase Logs</h2>";
    echo "<TABLE class='list-content'>";

    function cmp($a, $b) {
        $date_a = explode(".", $a);
        $date_b = explode(".", $b);

        $year_a = (int)$date_a[0];
        $year_b = (int)$date_b[0];
        $month_a = (int)$date_a[1];
        $month_b = (int)$date_b[1];

        //echo "$year_a $year_b $month_a $month_b <BR>";

        if ($year_a == $year_b)  {
            if ($month_a == $month_b) return 0;
            return $month_a < $month_b ? 1 : -1;
        } else {
            return $year_a < $year_b ? 1 : -1;
        }
    }
    uksort($logFiles, 'cmp');

    foreach ($logFiles as $year => $files) {
        ksort($files);
        echo "<TR class='list-content'>
                    <TD class='edit-item'><b>$year</b></TD>
                <TD class='edit-item'>";
        foreach (array_reverse($files) as $file) {
            echo "<a class='file-name' id='$file'>$file</a> &nbsp;";
        }

        echo "</TD></TR>";
    }
    echo "</TABLE><BR>";

    if (isset($_GET['fn'])) {
        $fn = addslashes($_GET['fn']);
        if (strlen($fn) > 14 || strlen($fn) < 12) die();
        echo "<h2>$fn</h2><TABLE class='list-content'>";
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
        load_main_hist(url);
    });
});
</script>
