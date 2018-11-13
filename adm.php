<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");
    require_permission(VIEW.LOGS);

    if (isset($_GET['page'])) {
        $page = addslashes($_GET['page']);
        switch ($page) {
            case 'sl':
                include_once 'admin/show-logs.php';
                break;
        }
    } else {
        echo "<a id='show-logs'> Show logs </a>";
    }


?>

<script>
$(document).ready(function() {
    $("#show-logs").click(function() {
        $('#main_space').load('adm.php?page=sl');
    });
});
</script>
