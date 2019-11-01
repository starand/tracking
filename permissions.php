<?
#---------------------------------------------------------------------------------------------------
## User permissions
/*
The following define describes permission for each positiong in permission string.
*/
## permission types
$VNAMES = array();
define('NO_PERM',   0);     $VNAMES[NO_PERM] = "Немає дозволу";
define('VIEW',      1);     $VNAMES[VIEW] = "Перегляд";
define('EDIT',      4);     $VNAMES[EDIT] = "Редагування";
define('ADD',       6);     $VNAMES[ADD] = "Додавання";
define('DEL',       9);     $VNAMES[DEL] = "Видалення";

/*
The following defines describes order of permission string.
*/
## permission groups
define('RESERVED', "[зарезервовано]");
$PNAMES = array();

define('GENERAL',   0);     $PNAMES[GENERAL] = "Діючий користувач";
define('DRIVERS',   1);     $PNAMES[DRIVERS] = "Список водіїв";
define('DRIVER',    2);     $PNAMES[DRIVER] = "Водій";
define('CARS',      3);     $PNAMES[CARS] = "Список автомобілів";
define('CAR',       4);     $PNAMES[CAR] = "Автомобіль";
define('POS',       5);     $PNAMES[POS] = "Список підприємців";
define('PO',        6);     $PNAMES[PO] = "Підприємець";
define('ROUTES',    7);     $PNAMES[ROUTES] = "Список маршрутів";
define('ROUTE',     8);     $PNAMES[ROUTE] = "Маршрут";
define('LOCATIONS', 9);     $PNAMES[LOCATIONS] = "Список локацій";
define('LOCATION',  10);    $PNAMES[LOCATION] = "Локація";
define('HIRINGS',   11);    $PNAMES[HIRINGS] = "Список прийомів на роботу";
define('HIRING',    12);    $PNAMES[HIRING] = "Прийому на роботу";
define('SALARY',    13);    $PNAMES[SALARY] = "Заробітна плата";
define('MECHANICS', 14);    $PNAMES[MECHANICS] = "Список автослюсарів";
define('MECHANIC',  15);    $PNAMES[MECHANIC] = "Автослюсар";
define('_reservd_4',16);    $PNAMES[_reservd_4] = RESERVED;
define('_reservd_5',17);    $PNAMES[_reservd_5] = RESERVED;
define('_reservd_6',18);    $PNAMES[_reservd_6] = RESERVED;
define('_reservd_7',19);    $PNAMES[_reservd_7] = RESERVED;
define('FINANCE',   20);    $PNAMES[FINANCE] = "Фінанси";
define('LOGS',      21);    $PNAMES[LOGS] = "Логи";
define('USERS',     22);    $PNAMES[USERS] = "Список користувачів";
define('USER',      23);    $PNAMES[USER] = "Користувач";
define('GROUPS',    24);    $PNAMES[GROUPS] = "Список груп";
define('GROUP',     25);    $PNAMES[GROUP] = "Група";
define('MAX_PERM',  25);

#---------------------------------------------------------------------------------------------------
## Checks whether user has specific permission
function hasPermission($permission, $debug = false) {
    global $user;
    if (strlen($permission) < 2) return false;
    if (!is_numeric($permission[0])) return false;
    $perm = $permission[0];
    $pos = substr($permission, 1);
    if (!is_numeric($pos)) return false;

    $ps = $user['p_permissions'];
    if (strlen($ps) < $pos) return false;

    $ret = $ps[$pos] >= $perm;
    echo $debug == true ? "$permission --- $ps - $pos - $perm = ".($ret ? "YES" : "NO")."<BR>" : "";
    return $ret;
}

#---------------------------------------------------------------------------------------------------
## Check whether user has permission. Returns if no.
function require_permission($permission) {
    if (!hasPermission($permission)) {
        show_error("<b>Недостатньо прав!</b>");
    }
}

#---------------------------------------------------------------------------------------------------
## Completes permission string till end
function completePermissionString($ps) {
    $len = strlen($ps);
    for ($idx = $len; $idx <= MAX_PERM; ++$idx) {
        $ps .= "0";
    }

    return $ps;
}

#---------------------------------------------------------------------------------------------------

?>