CREATE TABLE tracking_users (
    u_id int unsigned NOT NULL AUTO_INCREMENT,
    u_login varchar(30) NOT NULL,
    u_pswd varchar(32) NOT NULL,
    u_perm int(10) unsigned NOT NULL,
    PRIMARY KEY(u_id)
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
    UNIQUE(d_phone),
    UNIQUE(d_name, d_address),
    PRIMARY KEY(d_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_rates (
    r_id int unsigned NOT NULL AUTO_INCREMENT,
    r_did int unsigned NOT NULL,
    r_rid int unsigned NOT NULL,
    r_rate smallint unsigned not NULL,
    PRIMARY KEY(r_id),
    UNIQUE(r_did, r_rid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_cars (
    c_id int unsigned NOT NULL AUTO_INCREMENT,
    c_plate varchar(10) NOT NULL,
    c_model varchar(32) NOT NULL,
    c_type int unsigned NOT NULL,
    c_places tinyint unsigned NOT NULL,
    c_insurance varchar(32) NOT NULL,
    c_sto varchar(32) NOT NULL,
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
    PRIMARY KEY(h_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_pos (
    po_id int unsigned NOT NULL AUTO_INCREMENT,
    po_name varchar(32) NOT NULL,
    po_phone varchar(16) NOT NULL,
    po_lid int unsigned NOT NULL,
    PRIMARY KEY(po_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE tracking_po_drivers (
    pod_id int unsigned NOT NULL AUTO_INCREMENT,
    pod_poid int unsigned NOT NULL,
    pod_did int unsigned NOT NULL,
    UNIQUE(pod_poid, pod_did),
    PRIMARY KEY(pod_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;