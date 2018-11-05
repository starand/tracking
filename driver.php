<?
    include_once "common/headers.php";
    $user or die("Not authorized user!");

    isset($_GET['did']) or die("Не вказано водія!");
    $did = (int)$_GET['did'];
    $driver = get_driver($did) or show_error("Водія не знайдено! '$lid'");

    echo "<h2>Водій: {$driver['d_name']}</h2>";
?>
