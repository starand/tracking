<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(EDIT.GROUP);

    isset($_GET['gid']) or die("Не вказано групу!");
    $gid = (int)$_GET['gid'];
    $group = get_perm_group($gid) or show_error("Групу не знайдено! '{$_GET['gid']}'");

    isset($_GET['pos']) or die("Не вказано позицію!");
    isset($_GET['val']) or die("Не вказано значення!");
    $pos = (int)$_GET['pos'];
    $val = (int)$_GET['val'];

    $ps = completePermissionString($group['p_permissions']);
    $ps[$pos] = $val;

    check_result(update_perm_string($gid, $ps),  "Дозволи оновлено!", "Помилка бази даних!");
    echo "$ps [".strlen($ps)."]";
?>