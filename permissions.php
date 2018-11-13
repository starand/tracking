<?
#---------------------------------------------------------------------------------------------------
## User permissions
/*
The following define describes permission for each positiong in permission string.
*/
## permission types
define('NO_PERM',   0);
define('VIEW',      1);
define('EDIT',      4);
define('ADD',       6);
define('DEL',       9);

/*
The following defines describes order of permission string.
*/
## permission groups
define('GENERAL',   0);
define('DRIVERS',   1);
define('DRIVER',    2);
define('CARS',      3);
define('CAR',       4);
define('POS',       5);
define('PO',        6);
define('ROUTES',    7);
define('ROUTE',     8);
define('LOCATIONS', 9);
define('LOCATION',  10);
define('HIRINGS',   11);
define('HIRING',    12);
define('SALARY',    13);
define('_reservd_2',14);
define('_reservd_3',15);
define('_reservd_4',16);
define('_reservd_5',17);
define('_reservd_6',18);
define('_reservd_7',19);
define('FINANCE',   20);
define('LOGS',     21);

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
##
function require_permission($permission) {
    if (!hasPermission($permission)) {
        show_error("<b>Недостатньо прав!</b>");
    }
}

?>