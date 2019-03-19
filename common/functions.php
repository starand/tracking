<?

#---------------------------------------------------------------------------------------------------
function mobileDevice() {
    global $_SERVER;
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
}

#---------------------------------------------------------------------------------------------------
## show error
function show_error($msg, $target = 'main_error') {
    //echo $msg;
    $msg = addslashes("<span class='error-msg'>$msg</span>");
    echo "<script>parent.document.getElementById('$target').innerHTML = '$msg'</script>";
    die();
}

#---------------------------------------------------------------------------------------------------
## clear error
function clear_error($target = 'main_error') {
    echo "<script>parent.document.getElementById('$target').innerHTML = ' &nbsp; '</script>";
}

#---------------------------------------------------------------------------------------------------
## show message

function show_message($msg, $target = 'main_error') {
    $msg = addslashes("<span class='green-msg'>$msg</span>");
    echo "<script>parent.document.getElementById('$target').innerHTML = '$msg'</script>";
    //echo $msg;
}

#---------------------------------------------------------------------------------------------------
## load page
function load_page($page, $target='content') {
    echo "<script>parent.load_page('$page', '$target');</script>";
}

#---------------------------------------------------------------------------------------------------

function load($page, $target) {
    echo "<script>$('#$target', parent.document).load('$page');</script>";
}

#---------------------------------------------------------------------------------------------------
## USERS
#---------------------------------------------------------------------------------------------------
# Define permissions
# define('PERM_ADD_CATEGORY',		0b00000001);
# Checks whether users is logged in
function getUser() {
    global $_SESSION;
    if (!isset($_SESSION['tracking_user'])) return false;
    $_SESSION['tracking_user'] = get_user_by_login($_SESSION['tracking_user']['u_login']);
    return $_SESSION['tracking_user'];
}

#---------------------------------------------------------------------------------------------------
# clears session data
function clearSession() {
	global $_SESSION;
	unset($_SESSION['tracking_user']);
}

#---------------------------------------------------------------------------------------------------
## sets user 
function setUser($user) {
    global $_SESSION;
    $_SESSION['tracking_user'] = $user;
}

#---------------------------------------------------------------------------------------------------
# checks whether user has permission
function userHasPermission($permission) {
    global $_SESSION;
    return isset($_SESSION['tracking_user']) 
                ? $_SESSION['tracking_user']['u_access'] & $permission : false;
}

#---------------------------------------------------------------------------------------------------
# Sets active location
function setActiveLocation($loc) {
    global $_SESSION;
    $_SESSION['location'] = $loc;
}

#---------------------------------------------------------------------------------------------------
# Returns active location
function getActiveLocation() {
    return $_SESSION['location'];
}

#---------------------------------------------------------------------------------------------------
# checks whether date format is valid
function checkDateDMYFormat($date) {
    $parts = explode(".", $date);

    if (count($parts) != 3) return false;
    if (!is_numeric($parts[0])) return false;
    if (!is_numeric($parts[1])) return false;
    if (!is_numeric($parts[2])) return false;

    $day = (int)$parts[0];
    $month = (int)$parts[1];
    $year = (int)$parts[2];

    if ($day < 1 || $day > 31) return false;
    if ($month < 1 || $month > 12) return false;
    if ($year < 1950 || $year > 2030) return false;

    return true;
}

#---------------------------------------------------------------------------------------------------
# checks whether date format is valid
function checkDateMYFormat($date) {
    $parts = explode(".", $date);

    if (count($parts) != 2) return false;
    if (!is_numeric($parts[0])) return false;
    if (!is_numeric($parts[1])) return false;

    $month = (int)$parts[0];
    $year = (int)$parts[1];

    if ($month < 1 || $month > 12) return false;
    if ($year < 1950 || $year > 2030) return false;

    return true;
}

#---------------------------------------------------------------------------------------------------
# checks whether date format is valid
function checkPhoneCorrect($phone) {
    if (strlen($phone) < 10) return false;
    $phone = trim($phone);
    if ($phone[0] === '+') $phone = substr($phone, 1);

    if (!ctype_digit($phone)) return false;
    if ($phone[0] == '3' && ($phone[1] != '8' || $phone[2] != '0')) return false;

    return true;
}

#---------------------------------------------------------------------------------------------------
## Checks whether date is expired
function checkDMYDateExpired($date) {
    return strtotime(date('d-m-Y')) >= strtotime($date);
}

#---------------------------------------------------------------------------------------------------
## Checks whether date is about 2 weeks to epiration
function checkDMYDateExpireIn($date, $days = 14) {
    $curSeconds = strtotime(date('d-m-Y'));
    $dateSeconds = strtotime($date);

    return $curSeconds >= $dateSeconds ? true
                : ($dateSeconds - $curSeconds) <= 3600 * 24 * $days;
}

#---------------------------------------------------------------------------------------------------
##
function checkIsCurrentMonth($date) {
    $parts = explode(".", $date);
    if (count($parts) != 3) return false;
    //echo date('m')." - ".$parts[1]."<BR>";
    return date('m') == $parts[1];
}

#---------------------------------------------------------------------------------------------------
##
function debug($msg) {
    echo "<script>alert(".addslashes($msg).");</script>";
}

#---------------------------------------------------------------------------------------------------
##
function check_result($res, $success, $error) {
    if ($res) {
        show_message($success);
    } else {
        show_error($error);
    }
}

#---------------------------------------------------------------------------------------------------
function getMonthCount($date) {
    $d1 = strtotime(date('j.n.Y'));
    $d2 = strtotime("01.".$date);
    $min_date = min($d1, $d2);
    $max_date = max($d1, $d2);
    $i = 0;

    while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
        $i++;
    }

    return $i;
}

#---------------------------------------------------------------------------------------------------

$MONTHS_UA = array("", "Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", 
                    "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень");

function getUaMonthName($m) {
    switch($m) {
        case 1: return "Січень";
        case 2: return "Лютий";
        case 3: return "Березень";
        case 4: return "Квітень";
        case 5: return "Травень";
        case 6: return "Червень";
        case 7: return "Липень";
        case 8: return "Серпень";
        case 9: return "Вересень";
        case 10: return "Жовтень";
        case 11: return "Листопад";
        case 12: return "Грудень";
        default: return "ERROR";
    }
}

#---------------------------------------------------------------------------------------------------
# Converst 'mm.yyyy' -> into 'Month yyyyy'
function getPrevMonthName($date) {
    $parts = explode(".", $date);
    if (count($parts) != 2) return "<ERROR>";
    $month = strlen($parts[0]) == 1 ? "0".$parts[0] : $parts[0];
    $date = "01.".$month.".".$parts[1];

    $time = strtotime("-1 MONTH", strtotime($date));
    $m = (int)date("m", $time);
    $year = date("Y", $time);
    return getUaMonthName($m)." ".$year;
}

#---------------------------------------------------------------------------------------------------
#
function shortenPIB($pib) {
    $parts = explode(" ", trim($pib));
    if ($parts <= 1) return $pib;

    $result = $parts[0]." ";
    $count = count($parts);
    for ($i = 1; $i < $count; ++$i) {
        $part = $parts[$i];
        if (strlen($part) == 0) continue;
        $result .= mb_substr($part, 0, 1).".";
    }

    return $result;
}
#---------------------------------------------------------------------------------------------------

?>
