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

	$sql = "SELECT * FROM tracking_users, tracking_permissions 
			WHERE u_login='$login' AND u_perm=p_id LIMIT 1";
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
define('STATE_ACTUAL', 0);
define('STATE_REMOVED', 1);
function get_all_drivers($type = STATE_ACTUAL) {
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
					'$birthday', '$wbirthday', '$insurance', $children, 0)";
	uquery($sql);

	# add new hiring record as it's next hiring time
	return add_hiring_record(last_insert_id(), '', '');
}

#---------------------------------------------------------------------------------------------------
# Deletes driver by id
function delete_driver($did) {
	$did = (int)$did;

	# remove driver from route
	uquery("DELETE FROM tracking_rates WHERE rate_did=$did LIMIT 1");
	# remove cars from driver
	uquery("DELETE FROM tracking_car_drivers WHERE cd_did=$did LIMIT 1");
	# remove driver's po
	# uquery("DELETE FROM tracking_po_drivers WHERE pod_did=$did LIMIT 1");
	# hide driver's hiring indo
	uquery("UPDATE tracking_hiring SET h_state=".STATE_REMOVED." WHERE h_did=$did");
	# hide driver from lists
	return uquery("UPDATE tracking_drivers SET d_state=".STATE_REMOVED." WHERE d_id=$did LIMIT 1");
}

#---------------------------------------------------------------------------------------------------
# Restores driver by id
function restore_driver($did) {
	$did = (int)$did;

	# add new hiring record as it's next hiring time
	add_hiring_record($did, '', '');
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
## Updated route rate
function set_route_rate($did, $rid, $rate) {
	$did = (int)$did;
	$rid = (int)$rid;
	$rate = (int)$rate;

	$sql = "UPDATE tracking_rates SET rate_rate=$rate WHERE rate_id=$rid AND rate_did=$did";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Car's functions
#---------------------------------------------------------------------------------------------------
## Adds new car
function add_car($plate, $model, $type, $places, $insurance, $sto, $owner, $color) {
	$plate = addslashes($plate);
	$model = addslashes($model);
	$type = (int)$type;
	$places = (int)$places;
	$insurance = addslashes($insurance);
	$sto = addslashes($sto);
	$owner = addslashes($owner);
	$color = addslashes($color);

	$sql = "INSERT INTO tracking_cars 
			VALUES(NULL, '$plate', '$model', $type, $places, '$insurance', '$sto', '$owner', 
						 '$color')";
	echo $sql;
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
function add_hiring_record($did, $contract, $order) {
	$did = (int)$did;
	$contract = addslashes($contract);
	$order = addslashes($order);

	$sql = "INSERT INTO tracking_hiring 
			VALUES(NULL, $did, '$contract', '$order', '', 0, '', '', '')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# Returns all hiring info with driver id as a key
function get_hiring_info() {
	$sql = "SELECT * FROM tracking_hiring ORDER BY h_id";
	$res = uquery($sql);
	
	for($result=array(); $row=mysql_fetch_array($res); $result[$row['h_did']]=$row);
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

	$sql = "SELECT * FROM tracking_hiring WHERE h_did=$did";
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
# Salary functions
#---------------------------------------------------------------------------------------------------
## Adds new calculation record
function add_salary_record($did, $formula, $amount) {
	$did = (int)$did;
	$amount = (float)$amount;
	$formula = addslashes($formula);
	$date = date('j.n.Y');

	$sql = "INSERT INTO tracking_salary VALUES(NULL, $did, '$formula', $amount, '$date', 0, 0, 0)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Returns salary periods
function get_salary_months() {
	$sql = "SELECT SUBSTR(s_date, 4) as month FROM tracking_salary GROUP BY month";
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
	$sql = "SELECT * FROM tracking_salary WHERE s_did=$did ORDER BY s_id DESC";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
##

?>
