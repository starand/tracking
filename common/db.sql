CREATE TABLE tracking_users (
    u_id int unsigned NOT NULL AUTO_INCREMENT,
    u_login varchar(30) NOT NULL,
    u_pswd varchar(32) NOT NULL,
    u_perm int(10) unsigned NOT NULL,
    u_name varchar(64) not null,
    PRIMARY KEY(u_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_permissions (
    p_id int unsigned NOT NULL AUTO_INCREMENT,
    p_desc varchar(32) NOT NULL,
    p_permissions varchar(255) NOT NULL,
    PRIMARY KEY(p_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_locations (
    l_id int unsigned NOT NULL AUTO_INCREMENT,
    l_name varchar(32) NOT NULL,
    UNIQUE(l_name),
    PRIMARY KEY(l_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_routes (
    r_id int unsigned NOT NULL AUTO_INCREMENT,
    r_lid int unsigned NOT NULL,
    r_name varchar(30) NOT NULL,
    r_desc varchar(255) NOT NULL,
    r_state tinyint NOT NULL default 0,
    PRIMARY KEY(r_id),
    UNIQUE(r_lid, r_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_drivers (
    d_id int unsigned NOT NULL AUTO_INCREMENT,
    d_name varchar(32) NOT NULL,
    d_address varchar(255) NOT NULL,
    d_phone varchar(16) NOT NULL,
    d_idcode varchar(16) NOT NULL,
    d_passport varchar(8) NOT NULL,
    d_stag varchar(10) NOT NULL,
    d_birthday varchar(10) NOT NULL,
    d_wife_birthday varchar(10) NOT NULL,
    d_insurance varchar(10) NOT NULL,
    d_children tinyint NOT NULL default 0,
    d_state tinyint NOT NULL default 0,
    UNIQUE(d_phone),
    UNIQUE(d_name, d_address),
    PRIMARY KEY(d_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_rates (
    rate_id int unsigned NOT NULL AUTO_INCREMENT,
    rate_did int unsigned NOT NULL,
    rate_rid int unsigned NOT NULL,
    rate_rate smallint unsigned not NULL,
    PRIMARY KEY(rate_id),
    UNIQUE(rate_did, rate_rid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_cars (
    c_id int unsigned NOT NULL AUTO_INCREMENT,
    c_plate varchar(10) NOT NULL,
    c_model varchar(32) NOT NULL,
    c_type int unsigned NOT NULL,
    c_places tinyint unsigned NOT NULL,
    c_insurance varchar(32) NOT NULL,
    c_sto varchar(32) NOT NULL,
    c_owner varchar(32) NOT NULL,
    c_color varchar(16) NOT NULL,
    c_state tinyint NOT NULL default 0,
    UNIQUE(c_plate),
    PRIMARY KEY(c_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_car_types (
    ct_id int unsigned NOT NULL AUTO_INCREMENT,
    ct_name varchar(32) NOT NULL,
    PRIMARY KEY(ct_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_car_drivers (
    cd_id int unsigned NOT NULL AUTO_INCREMENT,
    cd_cid int unsigned NOT NULL,
    cd_did  int unsigned NOT NULL,
    PRIMARY KEY(cd_id),
    UNIQUE(cd_cid, cd_did)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_hiring (
    h_id int unsigned NOT NULL AUTO_INCREMENT,
    h_did int unsigned NOT NULL,
    h_contract varchar(32) NOT NULL,
    h_order varchar(32) NOT NULL,
    h_firing varchar(32) NOT NULL,
    h_state tinyint NOT NULL default 0,
    h_fire_reason varchar(64) NOT NULL,
    h_hire_date varchar(10) NOT NULL,
    h_fire_date varchar(10) NOT NULL,
    PRIMARY KEY(h_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_pos (
    po_id int unsigned NOT NULL AUTO_INCREMENT,
    po_name varchar(32) NOT NULL,
    po_phone varchar(16) NOT NULL,
    po_lid int unsigned NOT NULL,
    po_address varchar(255) NOT NULL,
    po_idcode varchar(16) NOT NULL,
    po_passport varchar(8) NOT NULL,
    po_license varchar(32) NOT NULL,
    po_birthday varchar(10) NOT NULL,
    po_state tinyint NOT NULL default 0,
    PRIMARY KEY(po_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_po_drivers (
    pod_id int unsigned NOT NULL AUTO_INCREMENT,
    pod_poid int unsigned NOT NULL,
    pod_did int unsigned NOT NULL,
    UNIQUE(pod_poid, pod_did),
    PRIMARY KEY(pod_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_route_data (
    rd_id int unsigned NOT NULL AUTO_INCREMENT,
    rd_rid int unsigned NOT NULL,
    rd_url varchar(255) NOT NULL,
    rd_length smallint NOT NULL,
    rd_cost int NOT NULL,
    rd_name varchar(32) NOT NULL,
    PRIMARY KEY(rd_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_salary (
    s_id int unsigned NOT NULL AUTO_INCREMENT,
    s_did int unsigned NOT NULL,
    s_formula varchar(255) NOT NULL,
    s_amount decimal(7,2) unsigned NOT NULL,
    s_date varchar(10) NOT NULL,
    s_advance decimal(7,2) unsigned NOT NULL,
    s_salary decimal(7,2) unsigned NOT NULL,
    s_3rdform decimal(7,2) unsigned NOT NULL,
    UNIQUE(s_did, s_date),
    PRIMARY KEY(s_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_temp_coupons (
    tc_id int unsigned NOT NULL AUTO_INCREMENT,
    tc_cid int unsigned NOT NULL,
    tc_poid int unsigned NOT NULL,
    tc_date varchar(10) NOT NULL,
    tc_state tinyint NOT NULL default 0,
    PRIMARY KEY(tc_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

