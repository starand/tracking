<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.LOGS);

    $_GET['page'] = 'sl'; //TODO remove if there are more pages
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
        load_main_hist('adm.php?page=sl');
    });
});
</script>
