/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */
;
/*!40101 SET NAMES utf8 */
;
/*!50503 SET NAMES utf8mb4 */
;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */
;
/*!40103 SET TIME_ZONE = '+00:00' */
;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */
;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */
;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */
;
CREATE DATABASE IF NOT EXISTS `ncparks`
    /*!40100 DEFAULT CHARACTER SET utf8mb4 */
;
USE `ncparks`;

CREATE TABLE IF NOT EXISTS `county`
(
    `cty_id`   int(10) unsigned NOT NULL AUTO_INCREMENT,
    `cty_name` varchar(100)     NOT NULL,
    PRIMARY KEY (`cty_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `county`;
INSERT INTO `county` (`cty_id`, `cty_name`)
VALUES (1, 'Alamance'),
       (2, 'Alexander'),
       (3, 'Alleghany'),
       (4, 'Anson'),
       (5, 'Ashe'),
       (6, 'Avery'),
       (7, 'Beaufort'),
       (8, 'Bertie'),
       (9, 'Bladen'),
       (10, 'Brunswich'),
       (11, 'Buncombe'),
       (12, 'Burke'),
       (13, 'Cabarrus'),
       (14, 'Caldwell'),
       (15, 'Camden'),
       (16, 'Carteret'),
       (17, 'Caswell'),
       (18, 'Catawba'),
       (19, 'Chatham'),
       (20, 'Cherokee'),
       (21, 'Chowan'),
       (22, 'Clay'),
       (23, 'Cleveland'),
       (24, 'Columbus'),
       (25, 'Craven'),
       (26, 'Cumberland'),
       (27, 'Currituck'),
       (28, 'Dare'),
       (29, 'Davidson'),
       (30, 'Davie'),
       (31, 'Duplin'),
       (32, 'Durham'),
       (33, 'Edgecombe'),
       (34, 'Forsyth'),
       (35, 'Franklin'),
       (36, 'Gaston'),
       (37, 'Gates'),
       (38, 'Graham'),
       (39, 'Granville'),
       (40, 'Greene'),
       (41, 'Guilfird'),
       (42, 'Halifaz'),
       (43, 'Harnett'),
       (44, 'Haywood'),
       (45, 'Henderson'),
       (46, 'Hertford'),
       (47, 'Hoke'),
       (48, 'Hyde'),
       (49, 'Iredell'),
       (50, 'Jackson'),
       (51, 'Johnson'),
       (52, 'Jones'),
       (53, 'Lee'),
       (54, 'Lenoir'),
       (55, 'Lincoln'),
       (56, 'Macon'),
       (57, 'Madison'),
       (58, 'Martin'),
       (59, 'McDowell'),
       (60, 'Mecklenburg'),
       (61, 'Mitchell'),
       (62, 'Montgomery'),
       (63, 'Moore'),
       (64, 'Nash'),
       (65, 'New Hanover'),
       (66, 'Northampton'),
       (67, 'Onslow'),
       (68, 'Orange'),
       (69, 'Pamilico'),
       (70, 'Pasquotank'),
       (71, 'Pender'),
       (72, 'Perquimans'),
       (73, 'Person'),
       (74, 'Pitt'),
       (75, 'Polk'),
       (76, 'Randolph'),
       (77, 'Richmond'),
       (78, 'Rithersford'),
       (79, 'Robeson'),
       (80, 'Rockingham'),
       (81, 'Rowan'),
       (82, 'Rutherford'),
       (83, 'Sampson'),
       (84, 'Scotland'),
       (85, 'Stanly'),
       (86, 'Stokes'),
       (87, 'Surry'),
       (88, 'Swain'),
       (89, 'Transylvania'),
       (90, 'Tyrrell'),
       (91, 'Union'),
       (92, 'Vance'),
       (93, 'Wake'),
       (94, 'Warren'),
       (95, 'Washington'),
       (96, 'Watauga'),
       (97, 'Wayne'),
       (98, 'Wikes'),
       (99, 'Wilson'),
       (100, 'Yadkin'),
       (101, 'Yancey');

CREATE TABLE IF NOT EXISTS `district`
(
    `dst_id`   int(10) unsigned NOT NULL AUTO_INCREMENT,
    `dst_code` varchar(5)       NOT NULL,
    PRIMARY KEY (`dst_id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `district`;

CREATE TABLE IF NOT EXISTS `park`
(
    `par_id`       int(10) unsigned NOT NULL AUTO_INCREMENT,
    `par_code`     varchar(5)       NOT NULL,
    `par_reg_id`   int(10) unsigned DEFAULT NULL,
    `par_admin_by` int(10) unsigned DEFAULT NULL,
    `par_name`     varchar(255)     NOT NULL,
    `par_lat`      decimal(10, 8)   DEFAULT NULL,
    `par_lon`      decimal(10, 8)   DEFAULT NULL,
    PRIMARY KEY (`par_id`),
    UNIQUE KEY `idx_par_code` (`par_code`),
    KEY `FK_park_admin_by` (`par_admin_by`),
    CONSTRAINT `FK_park_admin_by` FOREIGN KEY (`par_admin_by`) REFERENCES `park` (`par_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `park`;

INSERT INTO `park` (`par_id`, `par_code`, `par_reg_id`, `par_admin_by`, `par_name`, `par_lat`, `par_lon`)
VALUES (198, 'ARCH', NULL, NULL, 'At Raleigh Central Headquarters', 35.78690000, -78.63870000),
       (199, 'BAIS', NULL, 220, 'Bald Head Island State Natural Area', 33.86160000, -77.96330000),
       (200, 'BALA', NULL, 233, 'Bakers Lake State Natural Area', 34.80960900, -78.76468300),
       (201, 'BATR', NULL, 233, 'Bay Tree Lake State Natural Area', 34.67846400, -78.43053800),
       (202, 'BECR', NULL, 226, 'Beech Creek Bog State Natural Area', 36.19440000, -81.85000000),
       (203, 'BEPA', NULL, 226, 'Bear Paw State Natural Area', 36.16075000, -81.82027000),
       (204, 'BOCR', NULL, 274, 'Bobs Creek State Natural Area', 35.63465700, -81.93469000),
       (205, 'BULA', NULL, 233, 'Bushy Lake State Natural Area', 34.87020000, -78.68350000),
       (206, 'BUMO', NULL, NULL, 'Bullhead Mountain State Natural Area', 36.44914500, -81.06021900),
       (207, 'CABE', NULL, NULL, 'Carolina Beach State Park', 34.04710000, -77.90720000),
       (208, 'CACR', NULL, NULL, 'Carvers Creek State Park', 35.21105250, -78.97747190),
       (209, 'CHRO', NULL, NULL, 'Chimney Rock State Park', 35.43279900, -82.25026000),
       (210, 'CHSW', NULL, 246, 'Chowan Swamp State Natural Area', 36.40194200, -76.90498400),
       (211, 'CLNE', NULL, NULL, 'Cliffs of the Neuse State Park', 35.23540000, -77.89320000),
       (212, 'CORE', NULL, NULL, 'Coastal Regional Office', NULL, NULL),
       (213, 'CRMO', NULL, NULL, 'Crowders Mountain State Park', 35.21331600, -81.29355500),
       (214, 'DERI', NULL, 234, 'Deep River State Trail', 35.58741500, -79.18052700),
       (215, 'DISW', NULL, NULL, 'Dismal Swamp State Park', 36.50570000, -76.35510000),
       (216, 'EADI', NULL, NULL, 'East District Office', NULL, NULL),
       (217, 'ELKN', NULL, NULL, 'Elk Knob State Park', 36.33258600, -81.69064000),
       (218, 'ENRI', NULL, NULL, 'Eno River State Park', 36.07830000, -79.00500000),
       (219, 'FALA', NULL, NULL, 'Falls Lake State Recreation Area', 36.01170000, -78.68880000),
       (220, 'FOFI', NULL, NULL, 'Fort Fisher State Recreation Area', 33.95340000, -77.92900000),
       (221, 'FOFL', NULL, NULL, 'Fonta Flora State Trail', 35.77930000, -81.84290000),
       (222, 'FOMA', NULL, NULL, 'Fort Macon State Park', 34.69795200, -76.67834000),
       (223, 'FRRI', NULL, 198, 'French Broad River State Trail', NULL, NULL),
       (224, 'GOCR', NULL, NULL, 'Goose Creek State Park', 35.48185300, -76.90141400),
       (225, 'GORG', NULL, NULL, 'Gorges State Park', 35.09700000, -82.95220000),
       (226, 'GRMO', NULL, NULL, 'Grandfather Mountain State Park', 36.11139000, -81.81250000),
       (227, 'HABE', NULL, NULL, 'Hammocks Beach State Park', 34.67100000, -77.14290000),
       (228, 'HARI', NULL, NULL, 'Haw River State Park', 36.25064600, -79.75636400),
       (229, 'HARO', NULL, NULL, 'Hanging Rock State Park', 36.41190600, -80.25412200),
       (230, 'HEBL', NULL, NULL, 'Hemlock Bluffs State Natural Area', NULL, NULL),
       (231, 'HINU', NULL, NULL, 'Hickory Nut Gorge State Trail', 35.48470000, -82.36840000),
       (232, 'HORI', NULL, 198, 'Horsepasture River State River', NULL, NULL),
       (233, 'JONE', NULL, NULL, 'Jones Lake State Park', 34.68274300, -78.59542300),
       (234, 'JORD', NULL, NULL, 'Jordan Lake State Recreation Area', 35.73690000, -79.01690000),
       (235, 'JORI', NULL, NULL, 'Jockey''s Ridge State Park', 35.96420000, -75.63300000),
       (236, 'KELA', NULL, NULL, 'Kerr Lake State Recreation Area', 36.44110000, -78.36880000),
       (237, 'LAJA', NULL, NULL, 'Lake James State Park', 35.72871000, -81.90112300),
       (238, 'LANO', NULL, NULL, 'Lake Norman State Park', 35.67254800, -80.93255230),
       (239, 'LAWA', NULL, NULL, 'Lake Waccamaw State Park', 34.27898500, -78.46548500),
       (240, 'LEIS', NULL, 227, 'Lea Island State Natural Area', 34.32140000, -77.68780000),
       (241, 'LIRI', NULL, 198, 'Linville River State River', NULL, NULL),
       (242, 'LOHA', NULL, 234, 'Lower Haw River State Natural Area', 35.77193000, -79.14349000),
       (243, 'LURI', NULL, NULL, 'Lumber River State Park', 34.39002300, -79.00222500),
       (244, 'MAIS', NULL, 207, 'Masonboro Island State Natural Area', 34.13596100, -77.85068500),
       (245, 'MARI', NULL, NULL, 'Mayo River State Park', 36.43880000, -79.93817100),
       (246, 'MEMI', NULL, NULL, 'Merchants Millpond State Park', 36.43710500, -76.70158500),
       (247, 'MEMO', NULL, NULL, 'Medoc Mountain State Park', 36.26390000, -77.88830000),
       (248, 'MIMI', NULL, 219, 'Mitchell Mill State Natural Area', 35.91530000, -78.38754000),
       (249, 'MOJE', NULL, 255, 'Mount Jefferson State Natural Area', 36.39766000, -81.47346600),
       (250, 'MOMI', NULL, NULL, 'Mount Mitchell State Park', 35.75280000, -82.27370000),
       (251, 'MOMO', NULL, NULL, 'Morrow Mountain State Park', 35.37372400, -80.07347700),
       (252, 'MORE', NULL, NULL, 'Mountain Regional Office', NULL, NULL),
       (253, 'MOTS', NULL, NULL, 'Mountains to Sea State Trail', NULL, NULL),
       (254, 'NCMA', NULL, NULL, 'North Carolina Museum of Art', 35.80956080, -78.70181250),
       (255, 'NERI', NULL, NULL, 'New River State Park', 36.46768000, -81.34035000),
       (256, 'NODI', NULL, NULL, 'North District Office', NULL, NULL),
       (257, 'NOPE', NULL, 255, 'Northern Peaks State Trail', NULL, NULL),
       (258, 'OCMO', NULL, 218, 'Occoneechee Mountain State Natural Area', 36.06083500, -79.11690000),
       (259, 'PETT', NULL, NULL, 'Pettigrew State Park', 35.80422600, -76.44905100),
       (260, 'PIBO', NULL, 226, 'Pineola Bog State Natural Area', 36.01717000, -81.89625000),
       (261, 'PIMO', NULL, NULL, 'Pilot Mountain State Park', 36.34127600, -80.46293800),
       (262, 'PIRE', NULL, NULL, 'Piedmont Regional Office', NULL, NULL),
       (263, 'PIVI', NULL, NULL, 'Pisgah View State Park', 35.46861100, -82.76888900),
       (264, 'RARO', NULL, NULL, 'Raven Rock State Park', 35.45970000, -78.91270000),
       (265, 'REMO', NULL, 275, 'Rendezvous Mountain State Park', 36.22714300, -81.29363000),
       (266, 'RUBA', NULL, 209, 'Rumbling Bald State Natural Area', NULL, NULL),
       (267, 'RUHI', NULL, 235, 'Run Hill State Natural Area', 35.99550000, -75.67510000),
       (268, 'SACR', NULL, 246, 'Salmon Creek State Natural Area', 36.01270000, -76.71622900),
       (269, 'SALA', NULL, 233, 'Salters Lake State Lake', 34.68274300, -78.59542300),
       (270, 'SARU', NULL, 211, 'Sandy Run Savannas State Natural Area', 34.63084200, -77.64986500),
       (271, 'SCRI', NULL, 259, 'Scuppernong River State Park', 35.86147400, -76.35656800),
       (272, 'SILA', NULL, NULL, 'Singletary Lake State Park', 34.58310000, -78.44960000),
       (273, 'SODI', NULL, NULL, 'South District Office', NULL, NULL),
       (274, 'SOMO', NULL, NULL, 'South Mountains State Park', 35.59630000, -81.60000000),
       (275, 'STMO', NULL, NULL, 'Stone Mountain State Park', 36.38730000, -81.02730000),
       (276, 'SUMO', NULL, 226, 'Sugar Mountain Bog State Natural Area', 36.08334700, -81.89565200),
       (277, 'THRO', NULL, 222, 'Theodore Roosevelt State Natural Area', 34.69670000, -76.82570000),
       (278, 'WAMI', NULL, 243, 'Warwick Mill Bay State Natural Area', 34.56795400, -78.91943100),
       (279, 'WARE', NULL, NULL, 'Warehouse', NULL, NULL),
       (280, 'WEDI', NULL, NULL, 'West District Office', NULL, NULL),
       (281, 'WEWO', NULL, NULL, 'Weymouth Woods-Sandhills Nature Preserve', 35.14690000, -79.36900000),
       (282, 'WHLA', NULL, 272, 'White Lake State Lake', NULL, NULL),
       (283, 'WIGA', NULL, 198, 'Wilderness Gateway State Trail', NULL, NULL),
       (284, 'WIUM', NULL, NULL, 'William B. Umstead State Park', 35.89050000, -78.75020000),
       (285, 'WOED', NULL, 209, 'Worlds Edge State Natural Area', 35.40610000, -82.26910000),
       (286, 'YARI', NULL, 198, 'Yadkin River State Trail', NULL, NULL),
       (287, 'YEMO', NULL, 226, 'Yellow Mountain State Natural Area', 35.99390000, -82.03850000),
       (288, 'YORK', NULL, NULL, 'Yorkshire Center', 35.96610000, -78.63280000);

CREATE TABLE IF NOT EXISTS `park_county`
(
    `prc_par_id` int(10) unsigned NOT NULL,
    `prc_cty_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`prc_par_id`, `prc_cty_id`),
    KEY `FK_park_county_park` (`prc_par_id`) USING BTREE,
    KEY `FK_park_county_county` (`prc_cty_id`) USING BTREE,
    CONSTRAINT `FK_park_county_county` FOREIGN KEY (`prc_cty_id`) REFERENCES `county` (`cty_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `FK_park_county_park` FOREIGN KEY (`prc_par_id`) REFERENCES `park` (`par_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `park_county`;

CREATE TABLE IF NOT EXISTS `region`
(
    `reg_id`   int(10) unsigned NOT NULL AUTO_INCREMENT,
    `reg_code` varchar(5)       NOT NULL,
    PRIMARY KEY (`reg_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `region`;

CREATE TABLE IF NOT EXISTS `park_region`
(
    `prg_par_id` int(10) unsigned NOT NULL,
    `prg_reg_id` int(10) unsigned NOT NULL,
    KEY `FK_park_region_park` (`prg_par_id`),
    KEY `FK_park_region_region` (`prg_reg_id`),
    CONSTRAINT `FK_park_region_park` FOREIGN KEY (`prg_par_id`) REFERENCES `park` (`par_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `FK_park_region_region` FOREIGN KEY (`prg_reg_id`) REFERENCES `region` (`reg_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `park_region`;

CREATE TABLE IF NOT EXISTS `user`
(
    `usr_id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `usr_username`      varchar(100)     NOT NULL,
    `usr_password_hash` varchar(255)     NOT NULL,
    `usr_par_id`        int(10) unsigned NOT NULL,
    `usr_first_name`    varchar(50)      NOT NULL,
    `usr_middle_name`   varchar(50) DEFAULT NULL,
    `usr_last_name`     varchar(50)      NOT NULL,
    `usr_status`        tinyint     DEFAULT 1,
    PRIMARY KEY (`usr_id`),
    CONSTRAINT `FK_user_park` FOREIGN KEY (`usr_par_id`) REFERENCES `park` (`par_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE KEY `idx_usr_username` (`usr_username`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `user`;

INSERT INTO `user` (`usr_id`, `usr_username`, `usr_password_hash`, `usr_par_id`, `usr_first_name`, `usr_middle_name`,
                    `usr_last_name`, `usr_status`)
VALUES (1, 'permission-0',
        '$argon2id$v=19$m=65536,t=4,p=1$T1VGTzEwRDY0RzBzbE1rcQ$jd/8q8rQMyvn91bUEwpGgtsV5cyoCCFUtwi1fDkqcsQ', 199,
        'NoAccess', 'Mname', 'Staff', 1),
       (2, 'permission-1',
        '$argon2id$v=19$m=65536,t=4,p=1$NnVldzZlcmU0a1R0aXc0bg$KdAn7yANwJxc4rhSW5+DXfbZVWD9584x2ALYFHnAYpg', 210,
        'BaseLevel', 'Mname', 'Staff', 1),
       (3, 'permission-2',
        '$argon2id$v=19$m=65536,t=4,p=1$NEdyTzhMdTliTlhXU3dtVg$I3osaPHZk1idVPay5QPlu7BMxIy7OM9Gkqix2SI9gLQ', 260,
        'Manager', 'Mname', 'Staff', 1),
       (4, 'permission-3',
        '$argon2id$v=19$m=65536,t=4,p=1$U0k2aXcudzVmbmRFdHo3ZQ$sEhSwE5HEwm0/lvQFGAf1XDPkkagi2kpJ8gmQPqfAn4', 198,
        'Admin', 'Mname', 'Staff', 1),
       (5, 'permission-4',
        '$argon2id$v=19$m=65536,t=4,p=1$eWh3WC5OdDB0ZWJZQVNWLg$3n+aJN4D2Te48Jg9YMfM94PQZn50DrxqD86I+Ew0GGU', 198,
        'SuperAdmin', 'Mname', 'Staff', 1);

CREATE TABLE IF NOT EXISTS `application`
(
    `app_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `app_name`   varchar(100)     NOT NULL,
    `app_status` tinyint      DEFAULT 1,
    `app_path`   varchar(255) DEFAULT NULL,
    PRIMARY KEY (`app_id`),
    UNIQUE KEY `idx_app_name` (`app_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `application`;

INSERT INTO `application` (`app_id`, `app_name`, `app_status`, `app_path`)
VALUES (1, 'File Finder', 1, 'filefinder'),
       (2, 'Visitation', 1, 'visitation');

CREATE TABLE IF NOT EXISTS `role`
(
    `rol_id`   int(10) unsigned NOT NULL AUTO_INCREMENT,
    `rol_name` varchar(100)     NOT NULL,
    PRIMARY KEY (`rol_id`),
    UNIQUE KEY `idx_rol_name` (`rol_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `role`;

INSERT INTO `role` (`rol_id`, `rol_name`)
VALUES (0, 'NoAccess'),
       (1, 'BaseLevel'),
       (2, 'Manager'),
       (3, 'Admin'),
       (4, 'SuperAdmin');

CREATE TABLE IF NOT EXISTS `application_user_role`
(
    `aur_usr_id` int(10) unsigned NOT NULL,
    `aur_app_id` int(10) unsigned NOT NULL,
    `aur_rol_id` int(10) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`aur_usr_id`, `aur_app_id`),
    CONSTRAINT `FK_application_user_role_user` FOREIGN KEY (`aur_usr_id`) REFERENCES `user` (`usr_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_application_user_role_application` FOREIGN KEY (`aur_app_id`) REFERENCES `application` (`app_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_application_user_role_role` FOREIGN KEY (`aur_rol_id`) REFERENCES `role` (`rol_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `application_user_role`;

INSERT INTO `application_user_role`
VALUES (1, 1, 1),
       (2, 1, 1),
       (3, 1, 2),
       (4, 1, 3),
       (5, 1, 4),
       (1, 2, 1),
       (2, 2, 1),
       (3, 2, 2),
       (4, 2, 3),
       (5, 2, 4);

-- Visitation --

CREATE TABLE IF NOT EXISTS `function`
(
    `fnc_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `fnc_name`   varchar(28)      NOT NULL,
    `fnc_status` tinyint DEFAULT 1,
    PRIMARY KEY (`fnc_id`),
    UNIQUE KEY `idx_fnc_name` (`fnc_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `function`;

INSERT INTO `function` (`fnc_id`, `fnc_name`, `fnc_status`)
VALUES (1, 'Traffic', 1),
       (2, 'Trail', 1),
       (3, 'Visitor Center', 1);

CREATE TABLE IF NOT EXISTS `type`
(
    `typ_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `typ_name`   varchar(24)      NOT NULL,
    `typ_status` tinyint DEFAULT 1,
    PRIMARY KEY (`typ_id`),
    UNIQUE KEY `idx_typ_name` (`typ_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `type`;

INSERT INTO `type` (`typ_id`, `typ_name`, `typ_status`)
VALUES (1, 'Pneumatic', 1),
       (2, 'Inductive-Loop', 1),
       (3, 'Infra-Red', 1),
       (4, 'Cellular', 1);

CREATE TABLE IF NOT EXISTS `method`
(
    `mtd_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `mtd_name`   varchar(32)      NOT NULL,
    `mtd_status` tinyint DEFAULT 1,
    PRIMARY KEY (`mtd_id`),
    UNIQUE KEY `idx_mtd_name` (`mtd_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `method`;

INSERT INTO `method` (`mtd_id`, `mtd_name`, `mtd_status`)
VALUES (1, 'Manual', 1),
       (2, 'Automatic', 1);

CREATE TABLE IF NOT EXISTS `model`
(
    `mdl_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `mdl_name`   varchar(64)      NOT NULL,
    `mdl_status` tinyint DEFAULT 1,
    PRIMARY KEY (`mdl_id`),
    UNIQUE KEY `idx_mdl_name` (`mdl_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `model`;
INSERT INTO `model` (`mdl_id`, `mdl_name`, `mdl_status`)
VALUES (1, 'Core', 1),
       (2, 'Photon', 1),
       (3, 'Particle MQTT', 1);

CREATE TABLE IF NOT EXISTS `brand`
(
    `brn_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `brn_name`   varchar(64)      NOT NULL,
    `brn_status` tinyint DEFAULT 1,
    PRIMARY KEY (`brn_id`),
    UNIQUE KEY `idx_brn_name` (`brn_name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `brand`;
INSERT INTO `brand` (`brn_id`, `brn_name`, `brn_status`)
VALUES (1, 'Adafruit', 1),
       (2, 'Particle', 1),
       (3, 'RedBear', 1);

CREATE TABLE IF NOT EXISTS `device`
(
    `dev_id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `dev_par_id`        int(10) unsigned NOT NULL,
    `dev_number`        varchar(32)      NOT NULL,
    `dev_name`          varchar(64)      NOT NULL,
    `dev_function`      int(10) unsigned NOT NULL,
    `dev_type`          int(10) unsigned NOT NULL,
    `dev_method`        int(10) unsigned NOT NULL,
    `dev_model`         int(10) unsigned      DEFAULT NULL,
    `dev_brand`         int(10) unsigned      DEFAULT NULL,
    `dev_multiplier`    decimal(3, 1)         DEFAULT NULL,
    `dev_lat`           decimal(10, 8)        DEFAULT NULL,
    `dev_lon`           decimal(10, 8)        DEFAULT NULL,
    `dev_seeinsight_id` varchar(32)           DEFAULT NULL,
    `dev_status`        tinyint               DEFAULT 1,
    `dev_date_uploaded` timestamp        NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`dev_id`),
    UNIQUE KEY `idx_dev_name` (`dev_name`),
    UNIQUE KEY `idx_dev_seeinsight_id` (`dev_seeinsight_id`),
    CONSTRAINT `FK_device_park` FOREIGN KEY (`dev_par_id`) REFERENCES `park` (`par_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_device_function` FOREIGN KEY (`dev_function`) REFERENCES `function` (`fnc_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_device_type` FOREIGN KEY (`dev_type`) REFERENCES `type` (`typ_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_device_method` FOREIGN KEY (`dev_method`) REFERENCES `method` (`mtd_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_device_model` FOREIGN KEY (`dev_model`) REFERENCES `model` (`mdl_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_device_brand` FOREIGN KEY (`dev_brand`) REFERENCES `brand` (`brn_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `device`;

CREATE TABLE IF NOT EXISTS `counter_rule`
(
    `rul_id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `rul_dev_id`     int(10) unsigned NOT NULL,
    `rul_start`      timestamp     DEFAULT NULL,
    `rul_end`        timestamp     DEFAULT NULL,
    `rul_multiplier` decimal(3, 1) DEFAULT NULL,
    `rul_status`     tinyint       DEFAULT 1,
    PRIMARY KEY (`rul_id`),
    CONSTRAINT `FK_rule_device` FOREIGN KEY (`rul_dev_id`) REFERENCES `device` (`dev_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `counter_rule`;

CREATE TABLE IF NOT EXISTS `visit`
(
    `vis_id`               int(10) unsigned NOT NULL AUTO_INCREMENT,
    `vis_par_id`           int(10) unsigned NOT NULL,
    `vis_dev_id`           int(10) unsigned NOT NULL,
    `vis_timestamp`        datetime         NOT NULL,
    `vis_count`            int(10) DEFAULT NULL,
    `vis_count_calculated` int(10) DEFAULT NULL,
    `vis_comments`         text    DEFAULT NULL,
    `vis_status`           tinyint DEFAULT 1,
    PRIMARY KEY (`vis_id`),
    CONSTRAINT `FK_visit_park` FOREIGN KEY (`vis_par_id`) REFERENCES `park` (`par_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_visit_device` FOREIGN KEY (`vis_dev_id`) REFERENCES `device` (`dev_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DELETE
FROM `visit`;

-- FILE FINDER --
CREATE TABLE IF NOT EXISTS `business_unit`
(
    `bun_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `bun_title`  varchar(255)     NOT NULL,
    `bun_count`  int(10)          NOT NULL DEFAULT 0,
    `bun_active` tinyint                   DEFAULT 1,
    PRIMARY KEY (`bun_id`),
    UNIQUE KEY `idx_bun_title` (`bun_title`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `business_unit`;
INSERT INTO business_unit (bun_title)
VALUES ('Accounting/Budget'),
       ('Operations'),
       ('Marketing/Communications'),
       ('Administrative'),
       ('DPR Events/Articles'),
       ('Human Resources'),
       ('Interpretation & Education'),
       ('Law Enforcement'),
       ('Safety/SAR/EMS'),
       ('Warehouse'),
       ('Other'),
       ('Natural Resource Management'),
       ('Information Technology'),
       ('Trails'),
       ('Planning'),
       ('Design & Development'),
       ('Major Maintenance');

CREATE TABLE IF NOT EXISTS `document_type`
(
    `dot_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `dot_title`  varchar(255)     NOT NULL,
    `dot_active` tinyint DEFAULT 1,
    PRIMARY KEY (`dot_id`),
    UNIQUE KEY `idx_dot_title` (`dot_title`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `document_type`;
INSERT INTO document_type (dot_title)
VALUES ('Staff Directives/Guidelines'),
       ('General'),
       ('Form');

CREATE TABLE IF NOT EXISTS `topic`
(
    `top_id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `top_title`       varchar(255)     NOT NULL,
    `top_description` text,
    `top_active`      tinyint DEFAULT 1,
    PRIMARY KEY (`top_id`),
    UNIQUE KEY `idx_top_title` (`top_title`),
    FULLTEXT `idx_top_description` (`top_title`, `top_description`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `topic`;
CREATE TABLE IF NOT EXISTS `tag`
(
    `tag_id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `tag_title`  varchar(255)     NOT NULL,
    `tag_active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`tag_id`),
    UNIQUE KEY `idx_tag_title` (`tag_title`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `tag`;
INSERT INTO tag (tag_title)
VALUES ('Travel Authorization/Travel Request'),
       ('APC'),
       ('P-Card'),
       ('WEX'),
       ('Permits'),
       ('Fire'),
       ('Annual Pass');

CREATE TABLE IF NOT EXISTS `file`
(
    `fil_id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `fil_top_id`        int(10) unsigned NOT NULL,
    `fil_filename`      varchar(255)     NOT NULL,
    `fil_aws_s3_object` text             NOT NULL,
    `fil_time_uploaded` datetime         NOT NULL,
    `fil_uploader_id`   int(10) unsigned,
    `fil_dot_id`        int(10) unsigned NOT NULL,
    `fil_bun_id`        int(10) unsigned NOT NULL,
    `fil_archived`      tinyint DEFAULT 0,
    `fil_time_archived` datetime,
    PRIMARY KEY (`fil_id`),
    CONSTRAINT `FK_file_topic` FOREIGN KEY (`fil_top_id`) REFERENCES `topic` (`top_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_file_user` FOREIGN KEY (`fil_uploader_id`) REFERENCES `user` (`usr_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_file_document_type` FOREIGN KEY (`fil_dot_id`) REFERENCES `document_type` (`dot_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `FK_file_business_unit` FOREIGN KEY (`fil_bun_id`) REFERENCES `business_unit` (`bun_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FULLTEXT `idx_fil_filename` (`fil_filename`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `file`;
CREATE TABLE IF NOT EXISTS `file_tag`
(
    `flt_fil_id` int(10) unsigned NOT NULL,
    `flt_tag_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`flt_fil_id`, `flt_tag_id`),
    CONSTRAINT `FK_file_tag_file` FOREIGN KEY (`flt_fil_id`) REFERENCES `file` (`fil_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_file_tag_tag` FOREIGN KEY (`flt_tag_id`) REFERENCES `tag` (`tag_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `file_tag`;
CREATE TABLE IF NOT EXISTS `file_park`
(
    `flp_fil_id` int(10) unsigned NOT NULL,
    `flp_par_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`flp_fil_id`, `flp_par_id`),
    CONSTRAINT `FK_file_park_file` FOREIGN KEY (`flp_fil_id`) REFERENCES `file` (`fil_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_file_park_park` FOREIGN KEY (`flp_par_id`) REFERENCES `park` (`par_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `file_park`;
CREATE TABLE IF NOT EXISTS `delete_request`
(
    `dlr_fil_id`       int(10) unsigned NOT NULL,
    `dlr_requester_id` int(10) unsigned NOT NULL,
    `dlr_reason`       text             NOT NULL,
    `dlr_request_time` datetime         NOT NULL,
    PRIMARY KEY (`dlr_fil_id`, `dlr_requester_id`),
    CONSTRAINT `FK_delete_request_file` FOREIGN KEY (`dlr_fil_id`) REFERENCES `file` (`fil_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_delete_request_requester` FOREIGN KEY (`dlr_requester_id`) REFERENCES `user` (`usr_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
DELETE
FROM `delete_request`;