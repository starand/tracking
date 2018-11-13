<?
    session_start();

    define('TRACKING_ENGINE', 1);
    define('ROOT_SHIFT', '/tracking');

    $PATH = "";
    if (defined("ROOT_SHIFT")) {
        $PATH = ROOT_SHIFT;
    }

    include_once "common/db.php";
    include_once "common/functions.php";
    include_once "permissions.php";

    $user = getUser();
?>
<HEAD>
    <link rel='icon' href='<?=$PATH;?>/favicon.ico'>
    <LINK href='<?=$PATH;?>/themes/light/main.css' rel=stylesheet type=text/css>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracking System</title>
</HEAD>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<? clear_error(); ?>