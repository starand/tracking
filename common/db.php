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

	define('STATE_ACTUAL', 		0);
	define('STATE_REMOVED', 	1);
	define('STATE_DISABLED', 	2);

#---------------------------------------------------------------------------------------------------
# MySQL helper functions
#---------------------------------------------------------------------------------------------------
## send query to db	
function uquery($query) {
	if (substr($query, 0, 3) != "SEL") log_msg($query);

	$result = @mysql_query($query); // or die("Can not send query to database!! - '$query'");
	return $result;
}

#---------------------------------------------------------------------------------------------------
## returns last inserted id
function last_insert_id() {
	global $conn;
	return mysql_insert_id($conn);
}

#---------------------------------------------------------------------------------------------------
## logs SQL into log file
function log_msg($data) {
	global $user;
	$login = $user['u_login'];
	$time = date('G:i:s');
	$date = date("j.n.Y");
	file_put_contents("./logs/$date.log", "[$time] $login: $data\n", FILE_APPEND);
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
function add_user($name, $pswd, $pib="", $perm=1) {
	$name = addslashes($name);
	$pswd = md5($pswd);
	$pib = addslashes($pib);
	$perm = (int)$perm;

	$sql = "INSERT INTO tracking_users VALUES(NULL, '$name', '$pswd', $perm, '$pib')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Returns user data by login
function get_user_by_login($login) {
	$login = addslashes($login);

	$sql = "SELECT * FROM tracking_users, tracking_permissions 
			WHERE u_login='$login' AND u_perm=p_id LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns users list
function get_users() {
	$sql = "SELECT * FROM tracking_users, tracking_permissions WHERE u_perm=p_id AND u_id<>1";
	return res_to_array(uquery($sql));
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
# Returns drivers by tracking location
function get_drivers_by_location($lid) {
	$lid = (int)$lid;

	$sql = "SELECT * FROM tracking_drivers, tracking_rates 
			WHERE d_id=rate_did AND rate_rid IN (SELECT r_id FROM tracking_routes WHERE r_lid=$lid) 
			GROUP BY d_id ORDER BY d_name";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Route's functions
#---------------------------------------------------------------------------------------------------
## Adds new route
function add_route($name, $desc, $lid) {
	$name = addslashes($name);
	$nadescme = addslashes($desc);
	$lid = (int)$lid;

	$sql = "INSERT INTO tracking_routes VALUES(NULL, $lid, '$name', '$desc', ".STATE_ACTUAL.")";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Deletes route by id
function delete_route($rid) {
	$rid = (int)$rid;

	# hide route from lists
	return uquery("UPDATE tracking_routes SET r_state=".STATE_REMOVED." WHERE r_id=$rid LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Restores route by id
function restore_route($rid) {
	$rid = (int)$rid;

	# hide route from lists
	return uquery("UPDATE tracking_routes SET r_state=".STATE_ACTUAL." WHERE r_id=$rid LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Returns routes by location
function get_routes($lid, $type = STATE_ACTUAL) {
	$lid = (int)$lid;

	$sql = "SELECT * FROM tracking_routes WHERE r_lid=$lid AND r_state=$type";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns routes by location
function get_routes_info($lid) {
	$lid = (int)$lid;

	$sql = "SELECT * FROM tracking_routes WHERE r_lid=$lid";
	$res = uquery($sql);

	for($result=array(); $row=mysql_fetch_array($res); $result[$row['r_id']]=$row);
	return $result;
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

	$sql = "SELECT * FROM tracking_routes, tracking_locations 
			WHERE r_id=$rid AND l_id=r_lid LIMIT 1";
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
function get_all_drivers($type = STATE_ACTUAL) {
	$type = (int)$type;

	$sql = "SELECT * FROM tracking_drivers WHERE d_state=$type ORDER BY d_name ";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns drivers by route
function get_drivers_by_route($rid) {
	$rid = (int)$rid;

	$sql = "SELECT * FROM tracking_routes, tracking_rates, tracking_drivers 
			WHERE r_id=rate_rid AND r_id=$rid AND d_id=rate_did AND d_state=".STATE_ACTUAL;
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns drivers info with key d_id
function get_drivers_info() {
	$sql = "SELECT * FROM tracking_drivers";
	$res = uquery($sql);

	for($result=array(); $row=mysql_fetch_array($res); $result[$row['d_id']]=$row);
	return $result;
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
# Updates route name
function set_route_name($rid, $name) {
	$rid = (int)$rid;
	$name = addslashes($name);

	$sql = "UPDATE tracking_routes SET r_name='$name' WHERE r_id=$rid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Updates route description
function set_route_desc($rid, $desc) {
	$rid = (int)$rid;
	$desc = addslashes($desc);

	$sql = "UPDATE tracking_routes SET r_desc='$desc' WHERE r_id=$rid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Updates route location
function set_route_location($rid, $lid) {
	$rid = (int)$rid;
	$lid = (int)$lid;

	$sql = "UPDATE tracking_routes SET r_lid='$lid' WHERE r_id=$rid";
	return uquery($sql);
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
					'$birthday', '$wbirthday', '$insurance', $children, ".STATE_ACTUAL.")";
	uquery($sql);

	# add new hiring record as it's next hiring time
	return add_hiring_record(last_insert_id(), '', '', EMPLOYEE_DRIVER);
}

#---------------------------------------------------------------------------------------------------
# Deletes driver by id
function delete_driver($did) {
	$did = (int)$did;

	# remove driver from route
	uquery("DELETE FROM tracking_rates WHERE rate_did=$did LIMIT 1");
	# remove cars from driver
	uquery("DELETE FROM tracking_car_drivers WHERE cd_did=$did LIMIT 1");
	# hide driver's hiring indo
	uquery("UPDATE tracking_hiring SET h_state=".STATE_REMOVED." 
			WHERE h_eid=$did AND h_emp_type=".EMPLOYEE_DRIVER);
	# hide driver from lists
	return uquery("UPDATE tracking_drivers SET d_state=".STATE_REMOVED." WHERE d_id=$did LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Restores driver by id
function restore_driver($did) {
	$did = (int)$did;

	# add new hiring record as it's next hiring time
	add_hiring_record($did, '', '', EMPLOYEE_DRIVER);
	return uquery("UPDATE tracking_drivers SET d_state=".STATE_ACTUAL." WHERE d_id=$did LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Returns driver by pib
function get_driver_by_pib($pib) {
	$pib = addslashes($pib);

	$sql = "SELECT * FROM tracking_drivers 
			WHERE d_name='$pib' AND d_state=".STATE_ACTUAL." LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns driver by pib
function get_driver_like_pib($pib) {
	$pib = addslashes($pib);

	$sql = "SELECT * FROM tracking_drivers 
			WHERE d_name LIKE '%$pib%' AND d_state=".STATE_ACTUAL." LIMIT 1";
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

	$sql = "UPDATE tracking_drivers SET d_name='$name' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver phone
function set_driver_phone($did, $phone) {
	$did = (int)$did;
	$phone = addslashes($phone);

	$sql = "UPDATE tracking_drivers SET d_phone='$phone' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver stag
function set_driver_stag($did, $stag) {
	$did = (int)$did;
	$stag = addslashes($stag);

	$sql = "UPDATE tracking_drivers SET d_stag='$stag' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver address
function set_driver_address($did, $address) {
	$did = (int)$did;
	$address = addslashes($address);

	$sql = "UPDATE tracking_drivers SET d_address='$address' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver passport
function set_driver_passport($did, $passport) {
	$did = (int)$did;
	$passport = addslashes($passport);

	$sql = "UPDATE tracking_drivers SET d_passport='$passport' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver idcode
function set_driver_idcode($did, $idcode) {
	$did = (int)$did;
	$idcode = addslashes($idcode);

	$sql = "UPDATE tracking_drivers SET d_idcode='$idcode' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver birthday
function set_driver_birthday($did, $birthday) {
	$did = (int)$did;
	$birthday = addslashes($birthday);

	$sql = "UPDATE tracking_drivers SET d_birthday='$birthday' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver's wife birthday
function set_driver_wbirthday($did, $birthday) {
	$did = (int)$did;
	$birthday = addslashes($birthday);

	$sql = "UPDATE tracking_drivers SET d_wife_birthday='$birthday' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver birthday
function set_driver_children($did, $children) {
	$did = (int)$did;
	$children = (int)$children;

	$sql = "UPDATE tracking_drivers SET d_children=$children WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver's insurance
function set_driver_insurance($did, $insurance) {
	$did = (int)$did;
	$insurance = addslashes($insurance);

	$sql = "UPDATE tracking_drivers SET d_insurance='$insurance' WHERE d_id=$did LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets driver's insurance
function get_children_count() {
	$sql = "SELECT sum(d_children) FROM tracking_drivers WHERE d_state=".STATE_ACTUAL;
	$res = uquery($sql);
	return $res ? mysql_result($res, 0, 0) : 0;
}

#---------------------------------------------------------------------------------------------------
# Rate's functions
#---------------------------------------------------------------------------------------------------
## Adds new rate for driver & route
function add_rate($did, $rid, $rate) {
	$did = (int)$did;
	$rid = (int)$rid;
	$rate = (int)$rate;

	$sql = "INSERT INTO tracking_rates VALUES(NULL, $did, $rid, $rate, '')";
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
## Updated route rate
function set_route_rate($did, $rid, $rate) {
	$did = (int)$did;
	$rid = (int)$rid;
	$rate = (int)$rate;
	$date = date('d.m.Y');

	$sql = "UPDATE tracking_rates SET rate_rate=$rate, rate_update='$date' 
			WHERE rate_id=$rid AND rate_did=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Updated route rate last increase date
function set_route_rate_update($did, $rid, $date) {
	$did = (int)$did;
	$rid = (int)$rid;
	$date = addslashes($date);

	$sql = "UPDATE tracking_rates SET rate_update='$date' WHERE rate_id=$rid AND rate_did=$did";
	return uquery($sql);
}


#---------------------------------------------------------------------------------------------------
# Car's functions
#---------------------------------------------------------------------------------------------------
## Adds new car
function add_car($plate, $model, $type, $places, $insurance, $sto, $owner, $color, $driver="") {
	$plate = addslashes($plate);
	$model = addslashes($model);
	$type = (int)$type;
	$places = (int)$places;
	$insurance = addslashes($insurance);
	$sto = addslashes($sto);
	$owner = addslashes($owner);
	$color = addslashes($color);
	$driver = addslashes($driver);

	$sql = "INSERT INTO tracking_cars 
			VALUES(NULL, '$plate', '$model', $type, $places, '$insurance', '$sto', '$owner', 
						 '$color', ".STATE_ACTUAL.", '$driver')";
	echo $sql;
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Deletes car by id
function delete_car($cid) {
	$cid = (int)$cid;

	# hide car from lists
	return uquery("UPDATE tracking_cars SET c_state=".STATE_REMOVED." WHERE c_id=$cid LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Restores car by id
function restore_car($cid) {
	$cid = (int)$cid;

	# hide car from lists
	return uquery("UPDATE tracking_cars SET c_state=".STATE_ACTUAL." WHERE c_id=$cid LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Returns cars
function get_cars($type = STATE_ACTUAL) {
	$type = (int)$type;

	$sql = "SELECT * FROM tracking_cars WHERE c_state=$type ORDER BY c_plate";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns cars by owner
function get_cars_by_owner($owner) {
	$owner = addslashes($owner);

	$sql = "SELECT * FROM tracking_cars WHERE c_owner='$owner' ORDER BY c_plate";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns car by id
function get_car($cid) {
	$cid = (int)$cid;

	$sql = "SELECT * FROM tracking_cars, tracking_car_types 
			WHERE c_type=ct_id AND c_id=$cid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns all car types
function get_car_types() {
	$sql = "SELECT * FROM tracking_car_types ORDER BY ct_name";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns car type by id
function get_car_type($ctid) {
	$ctid = (int)$ctid;

	$sql = "SELECT * FROM tracking_car_types WHERE ct_id=$ctid LIMIT 1";
	return res_to_array(uquery($sql));
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
# Sets car places
function set_car_places($cid, $places) {
	$cid = (int)$cid;
	$places = (int)$places;

	$sql = "UPDATE tracking_cars SET c_places=$places WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets car owner
function set_car_owner($cid, $owner) {
	$cid = (int)$cid;
	$owner = addslashes($owner);

	$sql = "UPDATE tracking_cars SET c_owner='$owner' WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets car driver
function set_car_driver($cid, $driver) {
	$cid = (int)$cid;
	$driver = addslashes($driver);

	$sql = "UPDATE tracking_cars SET c_driver='$driver' WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets car color
function set_car_color($cid, $color) {
	$cid = (int)$cid;
	$color = addslashes($color);

	$sql = "UPDATE tracking_cars SET c_color='$color' WHERE c_id=$cid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets car type
function set_car_type($cid, $type) {
	$cid = (int)$cid;
	$type = (int)$type;

	$sql = "UPDATE tracking_cars SET c_type=$type WHERE c_id=$cid";
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
			WHERE cd_cid=$cid AND d_id=cd_did AND d_state=".STATE_ACTUAL." ORDER BY d_name";
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
define('EMPLOYEE_DRIVER',		0);
define('EMPLOYEE_MECHANIC',		1);
function add_hiring_record($eid, $contract, $order, $emp_type) {
	$eid = (int)$eid;
	$contract = addslashes($contract);
	$order = addslashes($order);
	$emp_type = (int)$emp_type;

	$sql = "INSERT INTO tracking_hiring 
			VALUES(NULL, $eid, '$contract', '$order', '', 0, '', '', '', $emp_type)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns all hiring info with driver id as a key
function get_hiring_info($emp_type) {
	$emp_type = (int)$emp_type;

	$sql = "SELECT * FROM tracking_hiring WHERE h_emp_type=$emp_type ORDER BY h_id";
	$res = uquery($sql);
	
	for($result=array(); $row=mysql_fetch_array($res); $result[$row['h_eid']]=$row);
	return $result;
}

#---------------------------------------------------------------------------------------------------
# Returns hiring info by id
function get_hiring($hid) {
	$hid = (int)$hid;

	$sql = "SELECT * FROM tracking_hiring WHERE h_id=$hid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns all hiring info with driver id as a key
function get_driver_hirings($did) {
	$did = (int)$did;

	$sql = "SELECT * FROM tracking_hiring WHERE h_eid=$did AND h_emp_type=".EMPLOYEE_DRIVER;
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns all hiring info with mecahnic id as a key
function get_mechanic_hirings($mid) {
	$mid = (int)$mid;

	$sql = "SELECT * FROM tracking_hiring WHERE h_eid=$mid AND h_emp_type=".EMPLOYEE_MECHANIC;
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Updates hiring date
function set_hire_date($hid, $date) {
	$hid = (int)$hid;
	$date = addslashes($date);

	$sql = "UPDATE tracking_hiring SET h_hire_date='$date' WHERE h_id=$hid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Updates fire date
function set_fire_date($hid, $date) {
	$hid = (int)$hid;
	$date = addslashes($date);

	$sql = "UPDATE tracking_hiring SET h_fire_date='$date' WHERE h_id=$hid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Updates hiring contract
function set_hire_contract($hid, $contract) {
	$hid = (int)$hid;
	$contract = addslashes($contract);

	$sql = "UPDATE tracking_hiring SET h_contract='$contract' WHERE h_id=$hid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Updates hiring order
function set_hire_order($hid, $order) {
	$hid = (int)$hid;
	$order = addslashes($order);

	$sql = "UPDATE tracking_hiring SET h_order='$order' WHERE h_id=$hid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Updates hiring order
function set_fire_reason($hid, $reason) {
	$hid = (int)$hid;
	$reason = addslashes($reason);

	$sql = "UPDATE tracking_hiring SET h_fire_reason='$reason' WHERE h_id=$hid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Private Enterprenier's functions
#---------------------------------------------------------------------------------------------------
## Adds new po
function add_po($name, $phone, $lid, $address, $idcode, $passport, $license, $birthday) {
	$name = addslashes($name);
	$phone = addslashes($phone);
	$lid = (int)$lid;
	$address = addslashes($address);
	$idcode = addslashes($idcode);
	$passport = addslashes($passport);
	$license = addslashes($license);
	$birthday = addslashes($birthday);

	$sql = "INSERT INTO tracking_pos 
			VALUES(NULL, '$name', '$phone', $lid, '$address', '$idcode', '$passport',
				   '$license', '$birthday', ".STATE_ACTUAL.")";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Deletes po by id
function delete_po($poid) {
	$poid = (int)$poid;

	# hide po from lists
	return uquery("UPDATE tracking_pos SET po_state=".STATE_REMOVED." WHERE po_id=$poid LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Restores po by id
function restore_po($poid) {
	$poid = (int)$poid;

	# hide po from lists
	return uquery("UPDATE tracking_pos SET po_state=".STATE_ACTUAL." WHERE po_id=$poid LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Returns all pos
function get_pos($type = STATE_ACTUAL) {
	$type = (int)$type;

	$sql = "SELECT * FROM tracking_pos, tracking_locations 
			WHERE po_lid=l_id AND po_state=$type ORDER BY po_name";
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

	$sql = "UPDATE tracking_pos SET po_name='$name' WHERE po_id=$poid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po phone
function set_po_phone($poid, $phone) {
	$poid = (int)$poid;
	$phone = addslashes($phone);

	$sql = "UPDATE tracking_pos SET po_phone='$phone' WHERE po_id=$poid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po phone
function set_po_location($poid, $lid) {
	$poid = (int)$poid;
	$lid = (int)$lid;

	$sql = "UPDATE tracking_pos SET po_lid=$lid WHERE po_id=$poid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po address
function set_po_address($poid, $address) {
	$poid = (int)$poid;
	$address = addslashes($address);

	$sql = "UPDATE tracking_pos SET po_address='$address' WHERE po_id=$poid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po idcode
function set_po_idcode($poid, $idcode) {
	$poid = (int)$poid;
	$idcode = addslashes($idcode);

	$sql = "UPDATE tracking_pos SET po_idcode='$idcode' WHERE po_id=$poid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po passport
function set_po_passport($poid, $passport) {
	$poid = (int)$poid;
	$passport = addslashes($passport);

	$sql = "UPDATE tracking_pos SET po_passport='$passport' WHERE po_id=$poid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po license
function set_po_license($poid, $license) {
	$poid = (int)$poid;
	$license = addslashes($license);

	$sql = "UPDATE tracking_pos SET po_license='$license' WHERE po_id=$poid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets po birthday
function set_po_birthday($poid, $birthday) {
	$poid = (int)$poid;
	$birthday = addslashes($birthday);

	$sql = "UPDATE tracking_pos SET po_birthday='$birthday' WHERE po_id=$poid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# PO-employee relations functions
#---------------------------------------------------------------------------------------------------
## Adds new po
# look at EMPLOYEE_DRIVER
function add_employee_po($did, $poid, $emp_type) {
	$poid = (int)$poid;
	$did = (int)$did;
	$emp_type = (int)$emp_type;

	$sql = "INSERT INTO tracking_po_employees VALUES(NULL, $poid, $did, $emp_type)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns driver po
function get_driver_po($did) {
	$did = (int)$did;

	$sql = "SELECT * FROM tracking_po_employees, tracking_pos 
			WHERE pod_did=$did AND po_id=pod_poid AND pod_emp_type=".EMPLOYEE_DRIVER." LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns mechanic po
function get_mechanic_po($mid) {
	$mid = (int)$mid;

	$sql = "SELECT * FROM tracking_po_employees, tracking_pos 
			WHERE pod_did=$mid AND po_id=pod_poid AND pod_emp_type=".EMPLOYEE_MECHANIC." LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Sets driver po
function set_driver_po($did, $poid) {
	$poid = (int)$poid;
	$did = (int)$did;

	$sql = "UPDATE tracking_po_employees SET pod_poid=$poid 
			WHERE pod_did=$did AND pod_emp_type=".EMPLOYEE_DRIVER;
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic po
function set_mechanic_po($mid, $poid) {
	$poid = (int)$poid;
	$mid = (int)$mid;

	$sql = "UPDATE tracking_po_employees SET pod_poid=$poid 
			WHERE pod_did=$mid AND pod_emp_type=".EMPLOYEE_MECHANIC;
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns all pos
function get_po_drivers($poid) {
	$poid = (int)$poid;

	$sql = "SELECT * FROM tracking_po_employees, tracking_drivers
			WHERE pod_poid=$poid AND pod_did=d_id AND pod_emp_type=".EMPLOYEE_DRIVER."
					AND d_state=".STATE_ACTUAL." ORDER BY d_name";
	return res_to_array(uquery($sql));
}


#---------------------------------------------------------------------------------------------------
# Returns all pos
function get_po_mechanics($poid) {
	$poid = (int)$poid;

	$sql = "SELECT * FROM tracking_po_employees, tracking_mechanics
			WHERE pod_poid=$poid AND pod_did=m_id AND pod_emp_type=".EMPLOYEE_MECHANIC." 
					AND m_state=".STATE_ACTUAL." ORDER BY m_name";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Route data functions
#---------------------------------------------------------------------------------------------------
## Adds new route data
function add_route_data($rid, $url, $len, $cost, $name) {
	$rid = (int)$rid;
	$url = addslashes($url);
	$name = addslashes($name);
	$len = (int)$len;
	$cost = (int)$cost;

	$sql = "INSERT INTO tracking_route_data VALUES(NULL, $rid, '$url', $len, $cost, '$name')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Returns route data by  id
function get_route_data($rdid) {
	$rdid = (int)$rdid;

	$sql = "SELECT * FROM tracking_route_data WHERE rd_id=$rdid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns route datas by route id
function get_route_datas($rid) {
	$rid = (int)$rid;

	$sql = "SELECT * FROM tracking_route_data WHERE rd_rid=$rid";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Deletes route data by id
function delete_route_data($rdid) {
	$rdid = (int)$rdid;

	$sql = "DELETE FROM tracking_route_data WHERE rd_id=$rdid";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Updates route data url
function set_rodadata_url($rdid, $url) {
	$rdid = (int)$rdid;
	$url = addslashes($url);

	$sql = "UPDATE tracking_route_data SET rd_url='$url' WHERE rd_id=$rdid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Updates route data url
function set_rodadata_len($rdid, $len) {
	$rdid = (int)$rdid;
	$len = (int)$len;

	$sql = "UPDATE tracking_route_data SET rd_length=$len WHERE rd_id=$rdid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Updates route data url
function set_rodadata_cost($rdid, $cost) {
	$rdid = (int)$rdid;
	$cost = (int)$cost;

	$sql = "UPDATE tracking_route_data SET rd_cost=$cost WHERE rd_id=$rdid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Updates route data name
function set_rodadata_name($rdid, $name) {
	$rdid = (int)$rdid;
	$name = addslashes($name);

	$sql = "UPDATE tracking_route_data SET rd_name='$name' WHERE rd_id=$rdid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Permission functions
#---------------------------------------------------------------------------------------------------
## Adds new permission string
function add_permission_string($desc, $permissions) {
	$desc = addslashes($desc);
	$permissions = addslashes($permissions);

	$sql = "INSERT INTO tracking_permissions VALUES(NULL, '$desc', '$permissions')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Returns groups list
function get_perm_groups() {
	$sql = "SELECT * FROM tracking_permissions";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns group by id
function get_perm_group($gid) {
	$gid = (int)$gid;

	$sql = "SELECT * FROM tracking_permissions WHERE p_id=$gid";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns users in group list
function get_users_by_group($gid) {
	$gid = (int)$gid;

	$sql = "SELECT * FROM tracking_users WHERE u_perm=$gid";
	return res_to_array(uquery($sql));
}

function update_perm_string($gid, $value) {
	$gid = (int)$gid;
	$value = addslashes($value);

	$sql = "UPDATE tracking_permissions SET p_permissions='$value' WHERE p_id=$gid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Salary functions
#---------------------------------------------------------------------------------------------------
## Adds new calculation record
function add_salary_record($did, $formula, $amount, $emp_type) {
	$did = (int)$did;
	$amount = (float)$amount;
	$formula = addslashes($formula);
	$date = date('j.n.Y');
	$emp_type = (int)$emp_type;

	$sql = "INSERT INTO tracking_salary 
			VALUES(NULL, $did, '$formula', $amount, '$date', 0, 0, 0, $emp_type)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Returns salary periods
function get_salary_months() {
	$sql = "SELECT s_date, SUBSTRING(s_date, POSITION('.' IN s_date) + 1) as month 
			FROM tracking_salary GROUP BY month ORDER BY s_date DESC";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns salary record
function get_salary_record($sid) {
	$sid = (int)$sid;

	$sql = "SELECT * FROM tracking_salary WHERE s_id=$sid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns all salary records per month
function get_month_salary($month) {
	$month = addslashes($month);
	$sql = "SELECT * FROM tracking_salary 
			WHERE s_date LIKE '%.$month' ORDER BY s_id";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns all salary records per month
function get_month_salarн_stats($month) {
	$month = addslashes($month);
	$sql = "SELECT sum(s_amount) as amount, sum(s_advance) as advance, sum(s_salary) as salary, 
				   sum(s_3rdform) as 3rdform FROM tracking_salary 
			WHERE s_date LIKE '%.$month'";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Removed one row from salary report
function delete_salary_record($sid) {
	$sid = (int)$sid;

	$sql = "DELETE FROM tracking_salary WHERE s_id=$sid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Updates salary advance
function set_salary_advance($sid, $advance) {
	$sid = (int)$sid;
	$advance = (float)$advance;

	$sql = "UPDATE tracking_salary SET s_advance=$advance WHERE s_id=$sid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Updates salary salary
function set_salary_salary($sid, $salary) {
	$sid = (int)$sid;
	$salary = (float)$salary;

	$sql = "UPDATE tracking_salary SET s_salary=$salary WHERE s_id=$sid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Updates salary 3rdform
function set_salary_3rdform($sid, $trdform) {
	$sid = (int)$sid;
	$trdform = (float)$trdform;

	$sql = "UPDATE tracking_salary SET s_3rdform=$trdform WHERE s_id=$sid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Returns all salary records per month
function get_driver_salary($did) {
	$did = (int)$did;
	$sql = "SELECT * FROM tracking_salary 
			WHERE s_eid=$did AND s_emp_type=".EMPLOYEE_DRIVER." ORDER BY s_id DESC";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns all salary records per month
function get_mechanic_salary($did) {
	$did = (int)$did;
	$sql = "SELECT * FROM tracking_salary 
			WHERE s_eid=$did AND s_emp_type=".EMPLOYEE_MECHANIC." ORDER BY s_id DESC";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Salary functions
#---------------------------------------------------------------------------------------------------
## Adds new temporary coupon
function add_temp_coupon($cid, $poid, $date, $state = STATE_ACTUAL) {
	$cid = (int)$cid;
	$poid = (int)$poid;
	$state  = (int)$state;
	$date = addslashes($date);

	$sql = "INSERT INTO tracking_temp_coupons VALUES(NULL, $cid, $poid, '$date', $state)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Removes temp coupon
function remove_temp_coupon($tcid) {
	$tcid = (int)$tcid;

	$sql = "DELETE FROM tracking_temp_coupons WHERE tc_id=$tcid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Returns temp coupon
function get_temp_coupon($tcid) {
	$tcid = (int)$tcid;

	$sql = "SELECT * FROM tracking_temp_coupons WHERE tc_id=$tcid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns temporary coupons by car id
function get_car_temp_coupons($cid) {
	$cid = (int)$cid;

	$sql = "SELECT * FROM tracking_temp_coupons, tracking_pos  WHERE tc_cid=$cid AND tc_poid=po_id";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Returns temporary coupons by po id
function get_po_temp_coupons($poid) {
	$poid = (int)$poid;

	$sql = "SELECT * FROM tracking_temp_coupons, tracking_cars WHERE tc_poid=$poid AND tc_cid=c_id";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## Updates temp coupon state
function update_temp_coupon_state($tcid, $state) {
	$tcid = (int)$tcid;
	$state = (int)$state;

	$sql = "UPDATE tracking_temp_coupons SET tc_state=$state WHERE tc_id=$tcid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Mechanic's functions
#---------------------------------------------------------------------------------------------------
## Adds new mechanic
function add_mechanic($name, $address, $phone, $idcode, $passport, 
					$stag, $birthday, $wbirthday, $children, $insurance, $education) {
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
    $education = addslashes($education);

	$sql = "INSERT INTO tracking_mechanics 
			VALUES(NULL, '$name', '$address', '$phone', '$idcode', '$passport', '$stag', '$birthday',
					'$wbirthday', '$insurance', $children, ".STATE_ACTUAL.", '$education', 0, 1.0)";
	uquery($sql);

	# add new hiring record as it's next hiring time
	return add_hiring_record(last_insert_id(), '', '', EMPLOYEE_MECHANIC);
}

#---------------------------------------------------------------------------------------------------
# Returns all mechanics
function get_all_mechanics($type = STATE_ACTUAL) {
	$type = (int)$type;

	$sql = "SELECT * FROM tracking_mechanics WHERE m_state=$type ORDER BY m_name ";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns mechanics info with key m_id
function get_mechanics_info() {
	$sql = "SELECT * FROM tracking_mechanics";
	$res = uquery($sql);

	for($result=array(); $row=mysql_fetch_array($res); $result[$row['m_id']]=$row);
	return $result;
}

#---------------------------------------------------------------------------------------------------
# Deletes mechanic by id
function delete_mechanic($mid) {
	$mid = (int)$mid;

	# hide mechanic's hiring info
	uquery("UPDATE tracking_hiring SET h_state=".STATE_REMOVED." 
			WHERE h_eid=$mid AND h_emp_type=".EMPLOYEE_MECHANIC);
	# hide mechanic from lists
	return uquery("UPDATE tracking_mechanics SET m_state=".STATE_REMOVED." WHERE m_id=$mid LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Restores mechanic by id
function restore_mechanic($mid) {
	$mid = (int)$mid;

	# add new hiring record as it's next hiring time
	add_hiring_record($mid, '', '', EMPLOYEE_MECHANIC);
	return uquery("UPDATE tracking_mechanics SET m_state=".STATE_ACTUAL." WHERE m_id=$mid LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Returns mechanic by pib
function get_mechanic_by_pib($pib) {
	$pib = addslashes($pib);

	$sql = "SELECT * FROM tracking_mechanics 
			WHERE m_name='$pib' AND m_state=".STATE_ACTUAL." LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns mechanic by pib
function get_mechanic_like_pib($pib) {
	$pib = addslashes($pib);

	$sql = "SELECT * FROM tracking_mechanics 
			WHERE m_name LIKE '%$pib%' AND m_state=".STATE_ACTUAL." LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Returns mechanic by id
function get_mechanic($mid) {
	$mid = (int)$mid;

	$sql = "SELECT * FROM tracking_mechanics WHERE m_id=$mid LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic name
function set_mechanic_name($mid, $name) {
	$mid = (int)$mid;
	$name = addslashes($name);

	$sql = "UPDATE tracking_mechanics SET m_name='$name' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic phone
function set_mechanic_phone($mid, $phone) {
	$mid = (int)$mid;
	$phone = addslashes($phone);

	$sql = "UPDATE tracking_mechanics SET m_phone='$phone' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic stag
function set_mechanic_stag($mid, $stag) {
	$mid = (int)$mid;
	$stag = addslashes($stag);

	$sql = "UPDATE tracking_mechanics SET m_stag='$stag' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic address
function set_mechanic_address($mid, $address) {
	$mid = (int)$mid;
	$address = addslashes($address);

	$sql = "UPDATE tracking_mechanics SET m_address='$address' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic passport
function set_mechanic_passport($mid, $passport) {
	$mid = (int)$mid;
	$passport = addslashes($passport);

	$sql = "UPDATE tracking_mechanics SET m_passport='$passport' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic idcode
function set_mechanic_idcode($mid, $idcode) {
	$mid = (int)$mid;
	$idcode = addslashes($idcode);

	$sql = "UPDATE tracking_mechanics SET m_idcode='$idcode' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic birthday
function set_mechanic_birthday($mid, $birthday) {
	$mid = (int)$mid;
	$birthday = addslashes($birthday);

	$sql = "UPDATE tracking_mechanics SET m_birthday='$birthday' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic's wife birthday
function set_mechanic_wbirthday($mid, $birthday) {
	$mid = (int)$mid;
	$birthday = addslashes($birthday);

	$sql = "UPDATE tracking_mechanics SET m_wife_birthday='$birthday' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic birthday
function set_mechanic_children($mid, $children) {
	$mid = (int)$mid;
	$children = (int)$children;

	$sql = "UPDATE tracking_mechanics SET m_children=$children WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic's insurance
function set_mechanic_insurance($mid, $insurance) {
	$mid = (int)$mid;
	$insurance = addslashes($insurance);

	$sql = "UPDATE tracking_mechanics SET m_insurance='$insurance' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns children count
function get_mechanic_children_count() {
	$sql = "SELECT sum(m_children) FROM tracking_mechanics WHERE m_state=".STATE_ACTUAL;
	$res = uquery($sql);
	return $res ? mysql_result($res, 0, 0) : 0;
}

#---------------------------------------------------------------------------------------------------
# Sets mechanic's education
function set_mechanic_education($mid, $education) {
	$mid = (int)$mid;
	$education = addslashes($education);

	$sql = "UPDATE tracking_mechanics SET m_education='$education' WHERE m_id=$mid LIMIT 1";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------


#---------------------------------------------------------------------------------------------------
##

#---------------------------------------------------------------------------------------------------
##

?>
