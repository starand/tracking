<?
	include_once "common/config.php";

	# connecting
	$conn = @mysql_pconnect($host, $user, $pswd) or die("Can not connect to database!!");
	mysql_select_db($db) or die("Can not select database!!");
	# set UTF8 as default connection
	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', ".
		"character_set_connection='utf8',character_set_database='utf8',character_set_server='utf8'",
		$conn);

	$tbl_prfx = "tracking";

#---------------------------------------------------------------------------------------------------
# MySQL helper functions
#---------------------------------------------------------------------------------------------------
## send query to db	
function uquery($query) 
{
	$result = @mysql_query($query); // or die("Can not send query to database!! - '$query'");
	return $result;
}

function last_insert_id() {
	global $conn;
	return mysql_insert_id($conn);
}

#---------------------------------------------------------------------------------------------------
## convert mysql result into assosiate array
function res_to_array($res) {
	for($result=array(); $row=mysql_fetch_array($res); $result[]=$row);
	return $result;
}

#---------------------------------------------------------------------------------------------------
## convert one row result to assosiate array
function row_to_array($res) {
	return $res ? mysql_fetch_array($res) : false;
}

#---------------------------------------------------------------------------------------------------
# Users's functions
#---------------------------------------------------------------------------------------------------
## Adds new user
function add_user($name, $pswd) {
	$name = addslashes($name);
	$pswd = md5($pswd);

	$sql = "INSERT INTO tracking_users VALUES(NULL, '$name', '$pswd')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Returns user data by login
function get_user_by_login($login) {
	$login = addslashes($login);

	$sql = "SELECT * FROM tracking_users WHERE u_login='$login' LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Location's functions
#---------------------------------------------------------------------------------------------------
## Adds new location
function add_location($name) {
	$name = addslashes($name);

	$sql = "INSERT INTO tracking_locations VALUES(NULL, '$name')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns locations
function get_locations() {
	$sql = "SELECT * FROM tracking_locations ORDER BY l_id";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns location by id
function get_location($lid) {
	$lid = (int)$lid;

	$sql = "SELECT * FROM tracking_locations WHERE l_id=$lid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns location by name
function get_location_by_name($name) {
	$name = addslashes($name);

	$sql = "SELECT * FROM tracking_locations WHERE l_name='$name' LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Route's functions
#---------------------------------------------------------------------------------------------------
## Adds new route
function add_route($name, $desc, $lid) {
	$name = addslashes($name);
	$nadescme = addslashes($desc);
	$lid = (int)$lid;

	$sql = "INSERT INTO tracking_routes VALUES(NULL, $lid, '$name', '$desc')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns routes by location
function get_routes($lid) {
	$lid = (int)$lid;

	$sql = "SELECT * FROM tracking_routes WHERE r_lid=$lid";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns route by id
function get_route($rid) {
	$rid = (int)$rid;

	$sql = "SELECT * FROM tracking_routes WHERE r_id=$rid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns route by name
function get_route_by_name($name) {
	$name = addslashes($name);

	$sql = "SELECT * FROM tracking_routes WHERE r_name='$name' LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns drivers by route
function get_drivers_by_route($rid) {
	$rid = (int)$rid;

	$sql = "SELECT * FROM tracking_routes, tracking_rates, tracking_drivers 
			WHERE r_id=rate_rid AND r_id=$rid AND d_id=rate_did";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns routes by driver
function get_routes_by_driver($did) {
	$did = (int)$did;

	$sql = "SELECT * FROM tracking_routes, tracking_rates WHERE r_id=rate_rid AND rate_did=$did";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Driver's functions
#---------------------------------------------------------------------------------------------------
## Adds new driver
function add_driver($name, $age, $address, $phone, $idcode, $passport, $stag) {
	$name = addslashes($name);
	$address = addslashes($address);
	$phone = addslashes($phone);
	$idcode = addslashes($idcode);
	$passport = addslashes($passport);
	$stag = addslashes($stag);
	$age = (int)$age;
	
	$sql = "INSERT INTO tracking_drivers 
			VALUES(NULL, '$name', $age, '$address', '$phone', '$idcode', '$passport', '$stag')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns drivers
function get_drivers() {
	$sql = "SELECT * FROM tracking_drivers";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns driver by pib
function get_driver_by_pib($pib) {
	$pib = addslashes($pib);

	$sql = "SELECT * FROM tracking_drivers WHERE d_name='$pib'";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns driver by id
function get_driver($did) {
	$did = (int)$did;

	$sql = "SELECT * FROM tracking_drivers WHERE d_id=$did";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Rate's functions
#---------------------------------------------------------------------------------------------------
## Adds new rate for driver & route
function add_rate($did, $rid, $rate) {
	$did = (int)$did;
	$rid = (int)$rid;
	$rate = (int)$rate;

	$sql = "INSERT INTO tracking_rates VALUES(NULL, $did, $rid, $rate)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------

?>
