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
# Returns routes by location
function get_all_routes() {
	$sql = "SELECT * FROM tracking_routes";
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
# Returns all drivers
function get_all_drivers() {
	$sql = "SELECT * FROM tracking_drivers ORDER BY d_name";
	return res_to_array(uquery($sql));
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

	$sql = "SELECT * FROM tracking_routes, tracking_rates 
			WHERE r_id=rate_rid AND rate_did=$did ORDER BY rate_id";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Driver's functions
#---------------------------------------------------------------------------------------------------
## Adds new driver
function add_driver($name, $address, $phone, $idcode, $passport, 
					$stag, $birthday, $wbirthday, $children, $insurance) {
	$name = addslashes($name);
	$address = addslashes($address);
	$phone = addslashes($phone);
	$idcode = addslashes($idcode);
	$passport = addslashes($passport);
	$stag = addslashes($stag);
	$children = (int)$children;
	$birthday = addslashes($birthday);
	$wbirthday = addslashes($wbirthday);
	$insurance = addslashes($insurance);
	
	$sql = "INSERT INTO tracking_drivers 
			VALUES(NULL, '$name', '$address', '$phone', '$idcode', '$passport', '$stag', 
					'$birthday', '$wbirthday', '$insurance', $children)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns driver by pib
function get_driver_by_pib($pib) {
	$pib = addslashes($pib);

	$sql = "SELECT * FROM tracking_drivers WHERE d_name='$pib' LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns driver by pib
function get_driver_like_pib($pib) {
	$pib = addslashes($pib);

	$sql = "SELECT * FROM tracking_drivers WHERE d_name LIKE '%$pib%' LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns driver by id
function get_driver($did) {
	$did = (int)$did;

	$sql = "SELECT * FROM tracking_drivers WHERE d_id=$did LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Sets driver name
function set_driver_name($did, $name) {
	$did = (int)$did;
	$name = addslashes($name);

	$sql = "UPDATE tracking_drivers SET d_name='$name' WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver phone
function set_driver_phone($did, $phone) {
	$did = (int)$did;
	$phone = addslashes($phone);

	$sql = "UPDATE tracking_drivers SET d_phone='$phone' WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver stag
function set_driver_stag($did, $stag) {
	$did = (int)$did;
	$stag = addslashes($stag);

	$sql = "UPDATE tracking_drivers SET d_stag='$stag' WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver address
function set_driver_address($did, $address) {
	$did = (int)$did;
	$address = addslashes($address);

	$sql = "UPDATE tracking_drivers SET d_address='$address' WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver passport
function set_driver_passport($did, $passport) {
	$did = (int)$did;
	$passport = addslashes($passport);

	$sql = "UPDATE tracking_drivers SET d_passport='$passport' WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver idcode
function set_driver_idcode($did, $idcode) {
	$did = (int)$did;
	$idcode = addslashes($idcode);

	$sql = "UPDATE tracking_drivers SET d_idcode='$idcode' WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver birthday
function set_driver_birthday($did, $birthday) {
	$did = (int)$did;
	$birthday = addslashes($birthday);

	$sql = "UPDATE tracking_drivers SET d_birthday='$birthday' WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver's wife birthday
function set_driver_wbirthday($did, $birthday) {
	$did = (int)$did;
	$birthday = addslashes($birthday);

	$sql = "UPDATE tracking_drivers SET d_wife_birthday='$birthday' WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver birthday
function set_driver_children($did, $children) {
	$did = (int)$did;
	$children = (int)$children;

	$sql = "UPDATE tracking_drivers SET d_children=$children WHERE d_id=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver's insurance
function set_driver_insurance($did, $insurance) {
	$did = (int)$did;
	$insurance = addslashes($insurance);

	$sql = "UPDATE tracking_drivers SET d_insurance='$insurance' WHERE d_id=$did";
	return uquery($sql);
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
## Returns rate info
function get_rate($rid) {
	$rid = (int)$rid;

	$sql = "SELECT * FROM tracking_rates WHERE rate_id=$rid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Deletes rate
function delete_rate($rid) {
	$rid = (int)$rid;

	$sql = "DELETE FROM tracking_rates WHERE rate_id=$rid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Car's functions
#---------------------------------------------------------------------------------------------------
## Adds new car
function add_car($plate, $model, $type, $places, $insurance, $sto) {
	$plate = addslashes($plate);
	$model = addslashes($model);
	$type = (int)$type;
	$places = (int)$places;
	$insurance = addslashes($insurance);
	$sto = addslashes($sto);

	$sql = "INSERT INTO tracking_cars 
			VALUES(NULL, '$plate', '$model', $type, $places, '$insurance', '$sto')";
	//echo $sql;
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns cars
function get_cars() {
	$sql = "SELECT * FROM tracking_cars ORDER BY c_plate"; // , tracking_car_types WHERE c_type=ct_id
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns car by id
function get_car($cid) {
	$cid = (int)$cid;

	$sql = "SELECT * FROM tracking_cars , tracking_car_types 
			WHERE c_type=ct_id AND c_id=$cid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns car by id
function get_car_by_plate($plate) {
	$plate = addslashes($plate);

	$sql = "SELECT * FROM tracking_cars WHERE c_plate='$plate' LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Sets car plate
function set_car_plate($cid, $plate) {
	$cid = (int)$cid;
	$plate = addslashes($plate);

	$sql = "UPDATE tracking_cars SET c_plate='$plate' WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets car model
function set_car_model($cid, $model) {
	$cid = (int)$cid;
	$model = addslashes($model);

	$sql = "UPDATE tracking_cars SET c_model='$model' WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets car insurance
function set_car_insurance($cid, $insurance) {
	$cid = (int)$cid;
	$insurance = addslashes($insurance);

	$sql = "UPDATE tracking_cars SET c_insurance='$insurance' WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets car sto
function set_car_sto($cid, $sto) {
	$cid = (int)$cid;
	$sto = addslashes($sto);

	$sql = "UPDATE tracking_cars SET c_sto='$sto' WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets car sto
function set_car_places($cid, $places) {
	$cid = (int)$cid;
	$places = (int)$places;

	$sql = "UPDATE tracking_cars SET c_places=$places WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Car drvier's functions
#---------------------------------------------------------------------------------------------------
## Adds new driver for car
function add_car_driver($did, $cid) {
	$did = (int)$did;
	$cid = (int)$cid;

	$sql = "INSERT INTO tracking_car_drivers VALUES(NULL, $cid, $did)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns all drivers related to car
function get_drivers_by_car($cid) {
	$cid = (int)$cid;

	$sql = "SELECT * FROM tracking_car_drivers, tracking_drivers 
			WHERE cd_cid=$cid AND d_id=cd_did ORDER BY d_name";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns all drivers related to car
function get_cars_by_driver($did) {
	$did = (int)$did;

	$sql = "SELECT * FROM tracking_car_drivers, tracking_cars WHERE cd_did=$did AND c_id=cd_cid";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns car-driver id
function get_car_driver($cdid) {
	$cdid = (int)$cdid;

	$sql = "SELECT * FROM tracking_car_drivers WHERE cd_id=$cdid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Deletes car-driver
function delete_car_driver($cdid) {
	$cdid = (int)$cdid;

	$sql = "DELETE FROM tracking_car_drivers WHERE cd_id=$cdid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Hiring functions
#---------------------------------------------------------------------------------------------------
## Adds new hiring record
function add_hiring_record($did, $contract, $order) {
	$did = (int)$did;
	$contract = addslashes($contract);
	$order = addslashes($order);

	$sql = "INSERT INTO tracking_hiring VALUES(NULL, $did, '$contract', '$order', '')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns all hiring info with driver id as a key
function get_hiring_info() {
	$sql = "SELECT * FROM tracking_hiring";
	$res = uquery($sql);
	
	for($result=array(); $row=mysql_fetch_array($res); $result[$row['h_did']]=$row);
	return $result;
}

#---------------------------------------------------------------------------------------------------
# Returns all hiring info with driver id as a key
function get_driver_hiring($did) {
	$did = (int)$did;

	$sql = "SELECT * FROM tracking_hiring WHERE h_did=$did LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Updates driver conract
function set_driver_contract($did, $contract) {
	$did = (int)$did;
	$contract = addslashes($contract);

	$sql = "UPDATE tracking_hiring SET h_contract='$contract' WHERE h_did=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Updates driver conract
function set_driver_order($did, $order) {
	$did = (int)$did;
	$order = addslashes($order);

	$sql = "UPDATE tracking_hiring SET h_order='$order' WHERE h_did=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Private Enterprenier's functions
#---------------------------------------------------------------------------------------------------
## Adds new po
function add_po($name, $phone, $lid) {
	$name = addslashes($name);
	$phone = addslashes($phone);
	$lid = (int)$lid;

	$sql = "INSERT INTO tracking_pos VALUES(NULL, '$name', '$phone', $lid)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns all pos
function get_pos() {
	$sql = "SELECT * FROM tracking_pos, tracking_locations WHERE po_lid=l_id ORDER BY po_name";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns all pos
function get_pos_wo_locatons() {
	$sql = "SELECT * FROM tracking_pos ORDER BY po_name";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns po by id
function get_po($poid) {
	$poid = (int)$poid;

	$sql = "SELECT * FROM tracking_pos, tracking_locations 
			WHERE po_id=$poid AND po_lid=l_id LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Sets po name
function set_po_name($poid, $name) {
	$poid = (int)$poid;
	$name = addslashes($name);

	$sql = "UPDATE tracking_pos SET po_name='$name' WHERE po_id=$poid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po phone
function set_po_phone($poid, $phone) {
	$poid = (int)$poid;
	$phone = addslashes($phone);

	$sql = "UPDATE tracking_pos SET po_phone='$phone' WHERE po_id=$poid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po phone
function set_po_location($poid, $lid) {
	$poid = (int)$poid;
	$lid = (int)$lid;

	$sql = "UPDATE tracking_pos SET po_lid=$lid WHERE po_id=$poid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# PO-drivers relations functions
#---------------------------------------------------------------------------------------------------
## Adds new po
function add_driver_po($did, $poid) {
	$poid = (int)$poid;
	$did = (int)$did;

	$sql = "INSERT INTO tracking_po_drivers VALUES(NULL, $poid, $did)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns all pos
function get_driver_po($did) {
	$did = (int)$did;

	$sql = "SELECT * FROM tracking_po_drivers, tracking_pos 
			WHERE pod_did=$did AND po_id=pod_poid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns all pos
function set_driver_po($did, $poid) {
	$poid = (int)$poid;
	$did = (int)$did;

	$sql = "UPDATE tracking_po_drivers SET pod_poid=$poid WHERE pod_did=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------


?>
