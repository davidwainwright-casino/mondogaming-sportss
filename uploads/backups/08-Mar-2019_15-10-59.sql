-- --------------------------------------------------------------------------------
-- 
-- @version: tester.sql Mar 8, 2019 15:10 gewa
-- @package 1.00 v.5.10
-- @author wojoscripts.com.
-- @copyright 2019
-- 
-- --------------------------------------------------------------------------------
-- Host: localhost
-- Database: tester
-- Time: Mar 8, 2019-15:10
-- MySQL version: 5.7.23
-- PHP version: 7.2.10
-- --------------------------------------------------------------------------------

#
# Database: `tester`
#


-- --------------------------------------------------
# -- Table structure for table `activity`
-- --------------------------------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `username` varchar(80) DEFAULT NULL,
  `ip` varbinary(16) DEFAULT NULL,
  `failed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `failed_last` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(20) DEFAULT NULL,
  `message` varchar(150) DEFAULT NULL,
  `importance` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 yes, 0 =no',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `activity`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `banlist`
-- --------------------------------------------------
DROP TABLE IF EXISTS `banlist`;
CREATE TABLE `banlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('IP','Email') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'IP',
  `comment` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ban_ip` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------
# Dumping data for table `banlist`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `cart`
-- --------------------------------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `mid` int(11) unsigned NOT NULL DEFAULT '0',
  `cid` int(11) unsigned NOT NULL DEFAULT '0',
  `tax` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `totaltax` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `coupon` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `total` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `originalprice` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `totalprice` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `cart_id` varchar(100) DEFAULT NULL,
  `order_id` varchar(100) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`),
  KEY `idx_user` (`uid`),
  KEY `idx_membership` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `cart`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `countries`
-- --------------------------------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `abbr` varchar(2) NOT NULL,
  `name` varchar(70) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `home` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vat` decimal(13,2) unsigned NOT NULL DEFAULT '0.00',
  `sorting` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `abbrv` (`abbr`)
) ENGINE=MyISAM AUTO_INCREMENT=238 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `countries`
-- --------------------------------------------------

INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('1', 'AF', 'Afghanistan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('2', 'AL', 'Albania', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('3', 'DZ', 'Algeria', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('4', 'AS', 'American Samoa', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('5', 'AD', 'Andorra', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('6', 'AO', 'Angola', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('7', 'AI', 'Anguilla', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('8', 'AQ', 'Antarctica', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('9', 'AG', 'Antigua and Barbuda', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('10', 'AR', 'Argentina', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('11', 'AM', 'Armenia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('12', 'AW', 'Aruba', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('13', 'AU', 'Australia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('14', 'AT', 'Austria', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('15', 'AZ', 'Azerbaijan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('16', 'BS', 'Bahamas', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('17', 'BH', 'Bahrain', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('18', 'BD', 'Bangladesh', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('19', 'BB', 'Barbados', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('20', 'BY', 'Belarus', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('21', 'BE', 'Belgium', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('22', 'BZ', 'Belize', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('23', 'BJ', 'Benin', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('24', 'BM', 'Bermuda', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('25', 'BT', 'Bhutan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('26', 'BO', 'Bolivia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('27', 'BA', 'Bosnia and Herzegowina', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('28', 'BW', 'Botswana', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('29', 'BV', 'Bouvet Island', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('30', 'BR', 'Brazil', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('31', 'IO', 'British Indian Ocean Territory', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('32', 'VG', 'British Virgin Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('33', 'BN', 'Brunei Darussalam', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('34', 'BG', 'Bulgaria', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('35', 'BF', 'Burkina Faso', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('36', 'BI', 'Burundi', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('37', 'KH', 'Cambodia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('38', 'CM', 'Cameroon', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('39', 'CA', 'Canada', '1', '1', '13.00', '1000');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('40', 'CV', 'Cape Verde', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('41', 'KY', 'Cayman Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('42', 'CF', 'Central African Republic', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('43', 'TD', 'Chad', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('44', 'CL', 'Chile', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('45', 'CN', 'China', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('46', 'CX', 'Christmas Island', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('47', 'CC', 'Cocos (Keeling) Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('48', 'CO', 'Colombia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('49', 'KM', 'Comoros', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('50', 'CG', 'Congo', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('51', 'CK', 'Cook Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('52', 'CR', 'Costa Rica', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('53', 'CI', 'Cote D\'ivoire', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('54', 'HR', 'Croatia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('55', 'CU', 'Cuba', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('56', 'CY', 'Cyprus', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('57', 'CZ', 'Czech Republic', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('58', 'DK', 'Denmark', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('59', 'DJ', 'Djibouti', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('60', 'DM', 'Dominica', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('61', 'DO', 'Dominican Republic', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('62', 'TP', 'East Timor', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('63', 'EC', 'Ecuador', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('64', 'EG', 'Egypt', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('65', 'SV', 'El Salvador', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('66', 'GQ', 'Equatorial Guinea', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('67', 'ER', 'Eritrea', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('68', 'EE', 'Estonia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('69', 'ET', 'Ethiopia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('70', 'FK', 'Falkland Islands (Malvinas)', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('71', 'FO', 'Faroe Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('72', 'FJ', 'Fiji', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('73', 'FI', 'Finland', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('74', 'FR', 'France', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('75', 'GF', 'French Guiana', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('76', 'PF', 'French Polynesia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('77', 'TF', 'French Southern Territories', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('78', 'GA', 'Gabon', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('79', 'GM', 'Gambia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('80', 'GE', 'Georgia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('81', 'DE', 'Germany', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('82', 'GH', 'Ghana', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('83', 'GI', 'Gibraltar', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('84', 'GR', 'Greece', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('85', 'GL', 'Greenland', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('86', 'GD', 'Grenada', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('87', 'GP', 'Guadeloupe', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('88', 'GU', 'Guam', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('89', 'GT', 'Guatemala', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('90', 'GN', 'Guinea', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('91', 'GW', 'Guinea-Bissau', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('92', 'GY', 'Guyana', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('93', 'HT', 'Haiti', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('94', 'HM', 'Heard and McDonald Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('95', 'HN', 'Honduras', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('96', 'HK', 'Hong Kong', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('97', 'HU', 'Hungary', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('98', 'IS', 'Iceland', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('99', 'IN', 'India', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('100', 'ID', 'Indonesia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('101', 'IQ', 'Iraq', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('102', 'IE', 'Ireland', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('103', 'IR', 'Islamic Republic of Iran', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('104', 'IL', 'Israel', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('105', 'IT', 'Italy', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('106', 'JM', 'Jamaica', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('107', 'JP', 'Japan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('108', 'JO', 'Jordan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('109', 'KZ', 'Kazakhstan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('110', 'KE', 'Kenya', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('111', 'KI', 'Kiribati', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('112', 'KP', 'Korea, Dem. Peoples Rep of', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('113', 'KR', 'Korea, Republic of', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('114', 'KW', 'Kuwait', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('115', 'KG', 'Kyrgyzstan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('116', 'LA', 'Laos', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('117', 'LV', 'Latvia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('118', 'LB', 'Lebanon', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('119', 'LS', 'Lesotho', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('120', 'LR', 'Liberia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('121', 'LY', 'Libyan Arab Jamahiriya', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('122', 'LI', 'Liechtenstein', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('123', 'LT', 'Lithuania', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('124', 'LU', 'Luxembourg', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('125', 'MO', 'Macau', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('126', 'MK', 'Macedonia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('127', 'MG', 'Madagascar', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('128', 'MW', 'Malawi', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('129', 'MY', 'Malaysia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('130', 'MV', 'Maldives', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('131', 'ML', 'Mali', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('132', 'MT', 'Malta', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('133', 'MH', 'Marshall Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('134', 'MQ', 'Martinique', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('135', 'MR', 'Mauritania', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('136', 'MU', 'Mauritius', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('137', 'YT', 'Mayotte', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('138', 'MX', 'Mexico', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('139', 'FM', 'Micronesia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('140', 'MD', 'Moldova, Republic of', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('141', 'MC', 'Monaco', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('142', 'MN', 'Mongolia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('143', 'MS', 'Montserrat', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('144', 'MA', 'Morocco', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('145', 'MZ', 'Mozambique', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('146', 'MM', 'Myanmar', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('147', 'NA', 'Namibia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('148', 'NR', 'Nauru', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('149', 'NP', 'Nepal', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('150', 'NL', 'Netherlands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('151', 'AN', 'Netherlands Antilles', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('152', 'NC', 'New Caledonia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('153', 'NZ', 'New Zealand', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('154', 'NI', 'Nicaragua', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('155', 'NE', 'Niger', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('156', 'NG', 'Nigeria', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('157', 'NU', 'Niue', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('158', 'NF', 'Norfolk Island', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('159', 'MP', 'Northern Mariana Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('160', 'NO', 'Norway', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('161', 'OM', 'Oman', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('162', 'PK', 'Pakistan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('163', 'PW', 'Palau', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('164', 'PA', 'Panama', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('165', 'PG', 'Papua New Guinea', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('166', 'PY', 'Paraguay', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('167', 'PE', 'Peru', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('168', 'PH', 'Philippines', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('169', 'PN', 'Pitcairn', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('170', 'PL', 'Poland', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('171', 'PT', 'Portugal', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('172', 'PR', 'Puerto Rico', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('173', 'QA', 'Qatar', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('174', 'RE', 'Reunion', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('175', 'RO', 'Romania', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('176', 'RU', 'Russian Federation', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('177', 'RW', 'Rwanda', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('178', 'LC', 'Saint Lucia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('179', 'WS', 'Samoa', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('180', 'SM', 'San Marino', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('181', 'ST', 'Sao Tome and Principe', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('182', 'SA', 'Saudi Arabia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('183', 'SN', 'Senegal', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('184', 'RS', 'Serbia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('185', 'SC', 'Seychelles', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('186', 'SL', 'Sierra Leone', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('187', 'SG', 'Singapore', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('188', 'SK', 'Slovakia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('189', 'SI', 'Slovenia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('190', 'SB', 'Solomon Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('191', 'SO', 'Somalia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('192', 'ZA', 'South Africa', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('193', 'ES', 'Spain', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('194', 'LK', 'Sri Lanka', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('195', 'SH', 'St. Helena', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('196', 'KN', 'St. Kitts and Nevis', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('197', 'PM', 'St. Pierre and Miquelon', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('198', 'VC', 'St. Vincent and the Grenadines', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('199', 'SD', 'Sudan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('200', 'SR', 'Suriname', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('201', 'SJ', 'Svalbard and Jan Mayen Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('202', 'SZ', 'Swaziland', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('203', 'SE', 'Sweden', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('204', 'CH', 'Switzerland', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('205', 'SY', 'Syrian Arab Republic', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('206', 'TW', 'Taiwan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('207', 'TJ', 'Tajikistan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('208', 'TZ', 'Tanzania, United Republic of', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('209', 'TH', 'Thailand', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('210', 'TG', 'Togo', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('211', 'TK', 'Tokelau', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('212', 'TO', 'Tonga', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('213', 'TT', 'Trinidad and Tobago', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('214', 'TN', 'Tunisia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('215', 'TR', 'Turkey', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('216', 'TM', 'Turkmenistan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('217', 'TC', 'Turks and Caicos Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('218', 'TV', 'Tuvalu', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('219', 'UG', 'Uganda', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('220', 'UA', 'Ukraine', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('221', 'AE', 'United Arab Emirates', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('222', 'GB', 'United Kingdom (GB)', '1', '0', '23.00', '999');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('224', 'US', 'United States', '1', '0', '7.50', '998');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('225', 'VI', 'United States Virgin Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('226', 'UY', 'Uruguay', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('227', 'UZ', 'Uzbekistan', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('228', 'VU', 'Vanuatu', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('229', 'VA', 'Vatican City State', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('230', 'VE', 'Venezuela', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('231', 'VN', 'Vietnam', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('232', 'WF', 'Wallis And Futuna Islands', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('233', 'EH', 'Western Sahara', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('234', 'YE', 'Yemen', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('235', 'ZR', 'Zaire', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('236', 'ZM', 'Zambia', '1', '0', '0.00', '0');
INSERT INTO `countries` (`id`, `abbr`, `name`, `active`, `home`, `vat`, `sorting`) VALUES ('237', 'ZW', 'Zimbabwe', '1', '0', '0.00', '0');


-- --------------------------------------------------
# -- Table structure for table `coupons`
-- --------------------------------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `code` varchar(30) NOT NULL,
  `discount` smallint(2) unsigned NOT NULL DEFAULT '0',
  `type` enum('p','a') NOT NULL DEFAULT 'p',
  `membership_id` varchar(50) NOT NULL DEFAULT '0',
  `ctype` varchar(30) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `coupons`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `custom_fields`
-- --------------------------------------------------
DROP TABLE IF EXISTS `custom_fields`;
CREATE TABLE `custom_fields` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(60) NOT NULL,
  `tooltip_en` varchar(100) DEFAULT NULL,
  `name` varchar(20) NOT NULL,
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `section` varchar(30) DEFAULT NULL,
  `sorting` int(4) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `custom_fields`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `custom_fields_data`
-- --------------------------------------------------
DROP TABLE IF EXISTS `custom_fields_data`;
CREATE TABLE `custom_fields_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `field_id` int(11) unsigned NOT NULL DEFAULT '0',
  `digishop_id` int(11) unsigned NOT NULL DEFAULT '0',
  `portfolio_id` int(11) unsigned NOT NULL DEFAULT '0',
  `field_name` varchar(40) DEFAULT NULL,
  `field_value` varchar(100) DEFAULT NULL,
  `section` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_field` (`field_id`),
  KEY `idx_digishop` (`digishop_id`),
  KEY `idx_portfolio` (`portfolio_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `custom_fields_data`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `email_templates`
-- --------------------------------------------------
DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(100) NOT NULL,
  `subject_en` varchar(150) NOT NULL,
  `help_en` tinytext,
  `body_en` text NOT NULL,
  `type` enum('news','mailer') DEFAULT 'mailer',
  `typeid` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `email_templates`
-- --------------------------------------------------

INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('1', 'Registration Email', 'Please verify your email', 'This template is used to send Registration Verification Email, when Configuration->Registration Verification is set to YES', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Welcome to [COMPANY]</h1>\r\n      <h4 style="font-weight:600;margin-bottom:16px">Congratulations</h4>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> You are now registered member.</p>\r\n      <p style="padding:30px 0px 0px 0px;color:#7B7B7B"> The administrator of this site has requested all new accounts to be activated by the users who created them thus your account is currently inactive. To activate your account, please visit the link below. </p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> Here are your login details. Please keep them in a safe place: </p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Username:</strong> [USERNAME] <br>\r\n        <strong>Password:</strong> [PASSWORD]</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Activate your account</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'regMail');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('2', 'Forgot Password Email', 'Password Reset', 'This template is used for retrieving lost user password', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">New password reset from [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> Hey, [NAME]</p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> It seems that you or someone requested a new password for you. We have generated a new password, as requested:: </p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"><strong>New Password:</strong> [PASSWORD]</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Login </a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'userPassReset');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('3', 'Welcome Mail From Admin', 'You have been registered', 'This template is used to send welcome email, when user is added by administrator', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header" data-image="pvy0mjj5q3qg"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Welcome to [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> Hey [NAME], You\'re now a member of [SITE_NAME].</p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> Here are your login details. Please keep them in a safe place: </p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Username:</strong> [USERNAME] <br><strong>Password:</strong> [PASSWORD]</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Go to login </a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" data-image="83l2r3utfiat"></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" data-image="52x4to0expai"></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" data-image="ctjwcqya9l2p"></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'regMailAdmin');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('4', 'Default Newsletter', 'Newsletter', 'This is a default newsletter template', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">[COMPANY] Newsletter</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> Hey, [NAME]</p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">[ATTACHMENT]</p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n        Newsletter content goes here...\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'news', 'newsletter');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('5', 'Transaction Completed', 'Payment Completed', 'This template is used to notify administrator on successful payment transaction', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have received new payment following: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Username:</strong> [NAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Membership:</strong> [ITEMNAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Price:</strong> [PRICE]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Status:</strong> [STATUS]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Processor:</strong> [PP]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>IP:</strong> [IP]</p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'payComplete');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('6', 'Transaction Suspicious', 'Suspicious Transaction', 'This template is used to notify administrator on failed/suspicious payment transaction', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">The following transaction has been disabled due to suspicious activity:</p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Username:</strong> [NAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Membership:</strong> [ITEMNAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Price:</strong> [PRICE]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Status:</strong> [STATUS]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Processor:</strong> [PP]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>IP:</strong> [IP]</p>\r\n      </div>\r\n      <p style="margin:10px;padding:3px;color:#7B7B7B"><em>Please verify this transaction is correct. If it is, please activate it in the transaction section of your site\'s administration control panel. If not, it appears that someone tried to fraudulently obtain products from your site.</em></p>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'payBad');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('7', 'Welcome Email', 'Welcome', 'This template is used to welcome newly registered user when Configuration->Registration Verification and Configuration->Auto Registration are both set to YES', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Welcome to [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> Hey [NAME], You\'re now a member of [SITE_NAME].</p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> Here are your login details. Please keep them in a safe place: </p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Username:</strong> [USERNAME] <br><strong>Password:</strong> [PASSWORD]</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Go to login </a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'welcomeEmail');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('8', 'Membership Expire 7 days', 'Your membership will expire in 7 days', 'This template is used to remind user that membership will expire in 7 days', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Membership Notification From [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">Hey, [NAME]</p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">Your current membership will expire in 7 days. Please login to your user panel to extend or upgrade your membership.</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Login</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'memExp7');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('9', 'Membership expired today', 'Your membership has expired', 'This template is used to remind user that membership had expired', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Membership Notification From [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">Hey, [NAME]</p>\r\n      <p style="margin:0;padding:3px;color:red;font-size:18px">Your current membership has expired!</p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">Please login to your user panel to extend or upgrade your membership.</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Login</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'memExp');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('10', 'Contact Request', 'Contact Inquiry', 'This template is used to send default Contact Request Form', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have a new contact request: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>From:</strong> [NAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Email:</strong> [EMAIL]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Telephone:</strong> [PHONE]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Subject:</strong> [MAILSUBJECT]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>IP:</strong> [IP]</p>\r\n      </div>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left"> [MESSAGE] </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'contact');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('11', 'New Comment', 'New Comment Added', 'This template is used to notify admin when new comment has been added', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have a new comment post. If comments are not auto approved, you will need to manually approve from admin panel. Here are the details: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>From:</strong> [NAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Page:</strong> <a href="[PAGEURL]">[PAGEURL]</a></p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>IP:</strong> [IP]</p>\r\n      </div>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left"> [MESSAGE] </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'newComment');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('12', 'Single Email', 'Single User Email', 'This template is used to email single user', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello [NAME]</h1>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">Your message goes here...  </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'singleMail');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('13', 'Notify Admin', 'New User Registration', 'This template is used to notify admin of new registration when Configuration->Registration Notification is set to YES', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have a new user registration. You can login into your admin panel to view details: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Email:</strong> [EMAIL]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Name:</strong> [NAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>IP:</strong> [IP]</p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'notifyAdmin');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('14', 'Registration Pending', 'Registration Verification Pending', 'This template is used to send Registration Verification Email, when Configuration->Auto Registration is set to NO', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Welcome to [COMPANY]</h1>\r\n      <h4 style="font-weight:600;margin-bottom:16px">Congratulations</h4>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> You are now registered member.</p>\r\n      <p style="padding:30px 0px 0px 0px;color:#7B7B7B"> The administrator of this site has requested all new accounts to be activated by the users who created them thus your account is currently pending verification process. </p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> Here are your login details. Please keep them in a safe place: </p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Username:</strong> [USERNAME] <br>\r\n        <strong>Password:</strong> [PASSWORD]</p>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'regMailPending');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('15', 'Offline Payment', 'Offline Notification', 'This template is used to send notification to a user when offline payment method is being used', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Purchase From [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> Hey [NAME]!</p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> You have purchased the following: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n      [ITEMS]\r\n      </div>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">Please send your payment to: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n      [INFO]\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'offlinePay');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('16', 'Event Payment', 'Event Payment Completed', 'This template is used to notify user on successful booking event payment transaction.', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Purchase From [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> Hey [NAME]!</p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> You have successfully purchased and booked: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n      [ITEM]\r\n      </div>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[EVENTURL]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">View Event Details</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'eventPay');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('17', 'New Invoice', 'You have new invoice', 'This template is used to notify user of a invoice being sent (Invoice Manager)', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Invoice From [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> Hey [NAME]!</p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> We have attached an invoice in the amount of [AMOUNT]: </p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B">You may pay, view and print the invoice online by visiting link bellow.</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">View Invoice</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'newInvoice');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('18', 'Transaction Completed IM', 'Payment Completed IM', 'This template is used to notify administrator on successful payment transaction from Invoice Manager', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have received new payment following: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Client Name:</strong> [NAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Invoice #:</strong> [INVID]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Amount:</strong> [AMOUNT]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Status:</strong> [STATUS]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Processor:</strong> [PP]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>IP:</strong> [IP]</p>\r\n      </div>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You can view this transaction from your admin panel</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Admin Panel</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'payCompleteIM');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('19', 'PsDrive Submission', 'New PsDrive user submission', 'This template is used to notify administrator on successful PsDrive user submission', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have received a new PsDrive file submission: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left">\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Username:</strong> [EMAIL]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Filename:</strong> [FILENAME]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>Url:</strong> [FILEURL]</p>\r\n        <p style="margin:0;padding:3px;color:#7B7B7B"><strong>IP:</strong> [IP]</p>\r\n      </div>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You can view this submission from your admin panel</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Admin Panel</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'psdNotifyAdmin');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('20', 'Digishop User Notification', 'Transaction Completed', 'This template is used to notify user of completed transaction  (Digishop Manager)', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello [NAME]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have purchased the following items: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left"> [ITEMS] </div>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You can now download your item(s) above from</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[URL]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">User Dashboard</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'digiNotifyUser');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('21', 'Visual Form Submission', 'New Form Submission', 'This template is used to notify Admin on new visual form submission', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have a new <strong>[FORMNAME]</strong> form submission: </p>\r\n      <div style="padding:30px 0px 0px 0px;text-align:left"> [FORMDATA] </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'visualFormAdmin');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('22', 'Blog Notification Admin', 'New Article Submission', 'This template is used to notify Admin on new blog article submission', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello Admin</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You have a new [POST] article pending approval. </p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">You can view this submission from your admin panel</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Admin Panel</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'blogAdminNotify');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('23', 'Blog Notification User', 'New Article Submission', 'This template is used to notify user on blog artilce status submission', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">Hello [NAME]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B">Your submitted article has been <strong>[STATUS]</strong></p>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'blogUserNotify');
INSERT INTO `email_templates` (`id`, `name_en`, `subject_en`, `help_en`, `body_en`, `type`, `typeid`) VALUES ('24', 'Forgot Password Admin', 'Password Reset', 'This template is used for retrieving lost admin password', '<div style="background-color:#F2F2F2;margin:0 auto;padding:60px;font-family: Helvetica, Arial, sans-serif; font-size: 16px; color: #404040"> [LOGO]\r\n  <div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">\r\n    <div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;"><img src="[SITEURL]/assets/images/header.png" alt="header"></div>\r\n    <div style="background-color:#ffffff;padding:48px;text-align:center;border-radius: 0 0 6px 6px;">\r\n      <h1 style="font-weight:100;margin-bottom:32px">New password reset from [COMPANY]</h1>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"> Hey, [NAME]</p>\r\n      <p style="margin:0;padding:5px;color:#7B7B7B"> It seems that you or someone requested a new password for you. We have generated a new password, as requested:: </p>\r\n      <p style="margin:0;padding:3px;color:#7B7B7B"><strong>New Password:</strong> [PASSWORD]</p>\r\n      <div style="padding:30px 0px 0px 0px">\r\n        <a target="_blank" href="[LINK]" style="text-decoration: none;border-radius: 6px 6px 6px 6px;display:inline-block;background-color:#eb1515;padding:14px 30px 14px 30px;color:#ffffff;font-weight:500;font-size:18px">Admin Panel </a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div style="padding:48px;text-align:center">\r\n    <p style="margin-bottom:32px;font-size:20px;color:##272822"><em>Stay in touch</em></p>\r\n    <a target="_blank" href="http://facebook.com/[FB]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/twitter.png" alt=""></a>\r\n    <a target="_blank" href="http://facebook.com/[TW]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/facebook.png" alt=""></a>\r\n    <a href="mailto:[CEMAIL]" style="display:inline-block;margin: 0px 5px 0px 5px;"><img src="[SITEURL]/assets/images/email.png" alt=""></a>\r\n    <div style="font-size:12px;color:#6E6E6E;margin-top:24px">\r\n      <p style="margin:0;padding:4px"> This email is sent to you directly from [COMPANY]</p>\r\n      <p style="margin:0"> The information above is gathered from the user input. ©[DATE] <a href="[SITEURL]">[COMPANY]</a>\r\n        . All rights reserved.</p>\r\n    </div>\r\n  </div>\r\n</div>', 'mailer', 'adminPassReset');


-- --------------------------------------------------
# -- Table structure for table `gateways`
-- --------------------------------------------------
DROP TABLE IF EXISTS `gateways`;
CREATE TABLE `gateways` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `displayname` varchar(50) NOT NULL,
  `dir` varchar(25) NOT NULL,
  `live` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `extra_txt` varchar(100) DEFAULT NULL,
  `extra_txt2` varchar(100) DEFAULT NULL,
  `extra_txt3` varchar(100) DEFAULT NULL,
  `extra` varchar(100) DEFAULT NULL,
  `extra2` varchar(100) DEFAULT NULL,
  `extra3` text,
  `is_recurring` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `gateways`
-- --------------------------------------------------

INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('1', 'paypal', 'PayPal', 'paypal', '1', 'Email Address', 'Currency Code', 'Not in Use', 'paypal@address.com', 'CAD', '', '1', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('2', 'skrill', 'Skrill', 'skrill', '1', 'Email Address', 'Currency Code', 'Secret Passphrase', 'moneybookers@address.com', 'EUR', 'mypassphrase', '1', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('3', 'offline', 'Offline Payment', 'offline', '0', 'Not in Use', 'Not in Use', 'Instructions', '', '', 'Please submit all payments to:\nBank Name:\nBank Account:\netc...', '0', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('4', 'stripe', 'Stripe', 'stripe', '0', 'Secret Key', 'Currency Code', 'Publishable Key', '', 'CAD', '', '1', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('5', 'payfast', 'PayFast', 'payfast', '0', 'Merchant ID', 'Merchant Key', 'PassPhrase', '', '', '', '0', '1');
INSERT INTO `gateways` (`id`, `name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES ('6', 'ideal', 'iDeal', 'ideal', '0', 'API Key', 'Currency Code', 'Not in Use', '', 'EUR', '', '0', '1');


-- --------------------------------------------------
# -- Table structure for table `language`
-- --------------------------------------------------
DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `abbr` varchar(2) DEFAULT NULL,
  `langdir` enum('ltr','rtl') DEFAULT 'ltr',
  `color` varchar(7) DEFAULT NULL,
  `author` varchar(200) DEFAULT NULL,
  `home` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `language`
-- --------------------------------------------------

INSERT INTO `language` (`id`, `name`, `abbr`, `langdir`, `color`, `author`, `home`) VALUES ('1', 'English', 'en', 'ltr', '#7ACB95', 'http://www.wojoscripts.com', '1');


-- --------------------------------------------------
# -- Table structure for table `layout`
-- --------------------------------------------------
DROP TABLE IF EXISTS `layout`;
CREATE TABLE `layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plug_id` int(11) unsigned NOT NULL DEFAULT '0',
  `page_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mod_id` int(11) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(30) DEFAULT NULL,
  `page_slug_en` varchar(150) DEFAULT NULL,
  `is_content` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `plug_name` varchar(60) DEFAULT NULL,
  `place` varchar(20) DEFAULT NULL,
  `space` tinyint(1) unsigned NOT NULL DEFAULT '10',
  `type` varchar(8) DEFAULT NULL,
  `sorting` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_page_id` (`page_id`),
  KEY `idx_plug_id` (`plug_id`),
  KEY `idx_mod_id` (`mod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `layout`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `memberships`
-- --------------------------------------------------
DROP TABLE IF EXISTS `memberships`;
CREATE TABLE `memberships` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(80) NOT NULL DEFAULT '',
  `description_en` varchar(150) DEFAULT NULL,
  `thumb` varchar(40) DEFAULT NULL,
  `price` float(10,2) unsigned NOT NULL DEFAULT '0.00',
  `days` smallint(3) unsigned NOT NULL DEFAULT '1',
  `period` varchar(1) NOT NULL DEFAULT 'D',
  `trial` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `recurring` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `private` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `memberships`
-- --------------------------------------------------

INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `thumb`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('1', 'Trial 7', 'This is 7 days trial membership...', 'bronze.png', '0', '7', 'D', '1', '0', '0', '1');
INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `thumb`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('2', 'Basic 30', 'This is 30 days basic membership', 'silver.png', '29.99', '1', 'M', '0', '0', '0', '1');
INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `thumb`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('3', 'Platinum', 'Platinum Monthly Subscription.', 'gold.png', '49.99', '1', 'Y', '0', '1', '0', '1');
INSERT INTO `memberships` (`id`, `title_en`, `description_en`, `thumb`, `price`, `days`, `period`, `trial`, `recurring`, `private`, `active`) VALUES ('4', 'Weekly Access', 'This is 7 days basic membership', 'platinum.png', '5.99', '1', 'W', '0', '0', '0', '1');


-- --------------------------------------------------
# -- Table structure for table `menus`
-- --------------------------------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `page_id` int(11) unsigned NOT NULL DEFAULT '0',
  `page_slug_en` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) NOT NULL,
  `mod_id` int(6) unsigned NOT NULL DEFAULT '0',
  `mod_slug` varchar(100) DEFAULT NULL,
  `caption_en` varchar(100) DEFAULT NULL,
  `content_type` varchar(20) NOT NULL DEFAULT 'page',
  `link` varchar(200) DEFAULT NULL,
  `target` varchar(15) NOT NULL DEFAULT '_blank',
  `icon` varchar(50) DEFAULT NULL,
  `cols` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `position` int(11) unsigned NOT NULL DEFAULT '0',
  `home_page` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_page_id` (`page_id`),
  KEY `idx_mod_id` (`mod_id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `menus`
-- --------------------------------------------------

INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('1', '0', '3', 'our-contact-info', 'Contact Us', '0', 'contact-us', 'Get in touch', 'page', '', '', 'mail icon', '1', '28', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('2', '0', '1', 'home', 'Home Page', '8', 'blog', 'Let&#39;s Start here', 'module', '', '', 'home icon', '1', '1', '1', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('3', '52', '7', 'middle-column', 'Single Column', '0', '', '', 'page', '', '', 'icon brightness', '1', '6', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('5', '51', '5', 'demo-gallery-page', 'Gallery', '1', 'gallery', 'Gallery page', 'module', '#', '', 'photo icon', '1', '18', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('6', '0', '0', '', 'External Link', '0', 'external-link', '', 'web', 'http://www.google.com', '_blank', 'anchor icon', '1', '27', '0', '0');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('7', '0', '0', '', 'More Pages', '0', 'more-pages', 'Demo Pages', 'web', '#', '_self', 'copy icon', '2', '2', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('11', '52', '2', 'what-is-cms-pro', 'About Us', '0', 'about-us', 'Who are we', 'page', '', '', 'globe icon', '1', '8', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('17', '52', '9', 'members-only', 'Members Only', '0', 'members-only', '', 'page', '', '', '', '1', '9', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('18', '52', '10', 'membership-only', 'Membership Only', '0', 'membership-only', '', 'page', '', '', '', '1', '10', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('19', '51', '11', 'event-calendar-demo', 'Event Manager Demo', '0', '', '', 'page', '', '', 'time icon', '1', '19', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('20', '52', '12', 'page-with-comments', 'Comment Page', '0', '', '', 'page', '', '', 'photo icon', '1', '7', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('21', '52', '13', 'right-sidebar', 'Sidebar Right', '0', '', '', 'page', '', '', 'browser icon', '1', '5', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('23', '7', '0', '', 'Shortcodes', '0', '', '', 'web', 'http://ckb.wojoscripts.com', '_blank', '', '1', '21', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('34', '38', '0', 'portfolio', 'Portfolio', '6', 'portfolio', '', 'web', '#', '', 'grid layout icon', '1', '24', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('35', '57', '8', 'timeline-custom-demo', 'Timeline Custom', '0', '', '', 'page', '', '', 'photo icon', '1', '16', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('36', '38', '0', 'digishop', 'Digishop', '7', 'digishop', '', 'web', '#', '', 'cart icon', '1', '25', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('37', '38', '6', 'visual-forms', 'Visual Forms', '0', 'visual-forms', '', 'web', '#', '', 'tasks icon', '1', '26', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('38', '0', '0', '', 'Premium Modules', '0', 'premium-modules', 'Premium Modules', 'web', '#', '', 'puzzle piece icon', '1', '22', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('39', '38', '0', 'blog', 'Blog Manager', '8', 'blog', '', 'web', '#', '', 'tasks icon', '1', '23', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('42', '52', '21', 'left-sidebar', 'Sidebar Left', '0', '', '', 'page', '', '', 'photo icon', '0', '4', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('43', '51', '22', 'demo-faq', 'FAQ Manager', '0', 'faq-manager', '', 'page', '', '', 'help icon', '0', '20', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('51', '7', '0', '', 'Modules', '0', '', 'Modules', 'web', '#', '_self', '', '1', '17', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('52', '7', '0', '', 'Demo Pages', '0', '', 'Demo Pages', 'web', '#', '_self', '', '1', '3', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('53', '57', '0', 'timeline-event-demo', 'Timeline Events', '0', '', '', 'page', '', '', '', '1', '14', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('54', '57', '0', 'timeline-portfolio-demo', 'Timeline Portfolio', '0', '', '', 'page', '', '', '', '1', '13', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('55', '57', '0', 'timeline-blog-demo', 'Timeline Blog', '0', '', '', 'page', '', '', '', '1', '12', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('56', '57', '0', 'timeline-rss-demo', 'Timeline Rss', '0', '', '', 'page', '', '', '', '1', '15', '0', '1');
INSERT INTO `menus` (`id`, `parent_id`, `page_id`, `page_slug_en`, `name_en`, `mod_id`, `mod_slug`, `caption_en`, `content_type`, `link`, `target`, `icon`, `cols`, `position`, `home_page`, `active`) VALUES ('57', '7', '0', '', 'Timeline', '0', '', '', 'web', '#', '_self', '', '1', '11', '0', '1');


-- --------------------------------------------------
# -- Table structure for table `mod_adblock`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_adblock`;
CREATE TABLE `mod_adblock` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(100) NOT NULL,
  `plugin_id` varchar(30) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_views_allowed` int(11) unsigned NOT NULL DEFAULT '0',
  `total_clicks_allowed` int(11) unsigned NOT NULL DEFAULT '0',
  `minimum_ctr` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `image` varchar(50) DEFAULT NULL,
  `image_link` varchar(100) DEFAULT NULL,
  `image_alt` varchar(100) DEFAULT NULL,
  `html` text,
  `total_views` int(11) unsigned NOT NULL DEFAULT '0',
  `total_clicks` int(11) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_adblock`
-- --------------------------------------------------

INSERT INTO `mod_adblock` (`id`, `title_en`, `plugin_id`, `start_date`, `end_date`, `total_views_allowed`, `total_clicks_allowed`, `minimum_ctr`, `image`, `image_link`, `image_alt`, `html`, `total_views`, `total_clicks`, `created`) VALUES ('1', 'Default Campaign', 'adblock/wojo-advert', '2014-04-24', '2020-10-01', '0', '0', '0.00', 'BANNER_sg2GlexD6Fnz.png', 'http://wojoscripts.com/', 'Wojo Advert', '', '4213', '1282', '2019-03-08 14:56:24');


-- --------------------------------------------------
# -- Table structure for table `mod_comments`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_comments`;
CREATE TABLE `mod_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) DEFAULT NULL,
  `section` varchar(20) NOT NULL,
  `vote_up` int(11) unsigned NOT NULL DEFAULT '0',
  `vote_down` int(11) NOT NULL DEFAULT '0',
  `body` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_comment_id` (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_comments`
-- --------------------------------------------------

INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('1', '0', '2', '5', '', 'digishop', '1254', '36', 'Myst is fine piece of great adventure game. Mysterious locations, mysterious music, mysterious world. It was first adventure game with over milion copies sold and it\'s woth every minute time spend with this tittle', '2016-04-13 20:26:01', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('2', '0', '3', '5', '', 'digishop', '895', '24', 'I remember the game when it first came out and was astounded by the graphics, Unfortunately, I cannot get the version I just purchased to run under Win 8.1 despite trying various compatibility settings.', '2016-06-17 22:10:42', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('3', '0', '1', '24', '', 'blog', '654', '14', 'Morbi sodales accumsan arcu sed venenatis. Vivamus leo diam, dignissim eu convallis in, posuere quis magna. Curabitur mollis, lectus sit amet bibendum faucibus, nisi ligula ultricies purus, in malesuada arcu sem ut mauris. Proin lobortis rutrum ultrices.', '2018-12-08 18:10:17', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('4', '0', '2', '24', '', 'blog', '214', '3', 'In hac habit***e platea dictumst.ivamus leo diam, dignissim eu convallis in, posuere quis magna. Curabitur mollis, lectus sit amet bibendum faucibus, nisi ligula ultricies purus', '2018-11-14 18:10:22', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('5', '0', '1', '12', '', 'page', '36', '125', 'The report of the death of the business plan has been an exaggeration, to paraphrase Mark Twain. ', '2016-11-18 09:39:03', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('6', '0', '1', '12', '', 'page', '107', '5', 'When it comes to the question of applying for a bank loan to fund your startup, most experts say, don\'t bother. ', '2017-01-02 09:40:54', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('7', '0', '2', '12', '', 'page', '224', '0', 'Give yourself better odds with thoughtful planning, diligent preparation, and the eight tips', '2017-01-04 09:41:28', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('8', '0', '0', '12', 'Jonh', 'page', '356', '0', 'You will find me friendly and approachable and what I really enjoy is helping people.', '2017-01-06 09:41:48', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('9', '0', '1', '12', '', 'page', '479', '-1', 'After qualifying I spent 16 years out in industry as a finance director, with experience across manufacturing, service and distribution sectors, before starting Facilis back in 2005', '2017-01-17 09:42:11', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('10', '0', '4', '12', '', 'page', '121', '3', 'Next, think about the business plan you`ve just written or updated and then build a financial forecast that includes a balance sheet, income statement, and cash flow statement. ', '2017-01-20 09:42:43', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('11', '7', '6', '12', '', 'page', '514', '100', 'How do experienced executives determine the best capital formation strategy for their company? ', '2017-01-07 09:43:34', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('12', '7', '5', '12', '', 'page', '203', '1', 'Remember, you don\'t want to run out of money', '2017-01-10 09:44:24', '1');
INSERT INTO `mod_comments` (`id`, `comment_id`, `user_id`, `parent_id`, `username`, `section`, `vote_up`, `vote_down`, `body`, `created`, `active`) VALUES ('13', '0', '0', '12', 'Peter', 'page', '2564', '0', 'Now that you have your capital formation strategy organized and ready to execute, you may want to confer with your stakeholders and advisers and listen carefully to their feedback.', '2017-01-20 09:44:47', '1');


-- --------------------------------------------------
# -- Table structure for table `mod_events`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_events`;
CREATE TABLE `mod_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title_en` varchar(100) NOT NULL,
  `venue_en` varchar(100) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `time_start` time DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  `body_en` text,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_email` varchar(80) DEFAULT NULL,
  `contact_phone` varchar(24) DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_events`
-- --------------------------------------------------

INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('1', '1', 'Bon Jovi', 'Air Canada Centre', '2017-04-10', '2017-04-12', '20:00:00', '22:00:00', '<p>Jon Bon Jovi and Co. head out on an extensive tour in 2017 in support of their new record This House Is Not For Sale. The band have previously played a handful of special listening parties, but this is the first series of dates on which most fans will have the chance to hear the group\'s new material..</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#377dff', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('2', '1', 'Norah Jones', 'Toronto Theater', '2017-05-26', '2017-05-27', '17:00:00', '17:00:00', '<p>Award winning vocalist and pianist Norah Jones is one of the most sensational artists of our time, and her unique blend of jazz and pop, and trademark sultry vocals appeal to audiences from a variety of genres.</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#00c9a7', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('3', '1', 'The Peppers&#39; Massive 2017 Tour', 'Toronto Theater', '2017-02-04', '2017-02-04', '17:00:00', '19:00:00', '<p>Following a select number of festival dates in 2016, The Red Hot Chili peppers are heading out onto the road proper for a huge world tour. Their first road trip since 2014, in comes in support of their latest offering The Getaway, a lush, textured album, co-produced by Danger Mouse and Radiohead producer Nigel Godrich, which was a creative reinvention for the band.</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#ffc107', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('4', '1', 'Lionel Richie With Mariah Carey', 'Air Canada Center', '2017-03-30', '2017-03-30', '17:00:00', '19:00:00', '<p>A pop music force of nature, Lionel Richie has been making sweet music for over 50 years, both with the funk outfit The Commodores and as a solo artist. He has an Oscar, five Grammys and 16 American Music Awards to his name, with his songwriting talents matched only by his megawatt charisma and old-time showman stage persona. 2017 see Richie heading out on a nationwide tour, with a very special guest in tow - none other than pop diva Mariah Carey, taking a break from her Vegas residency.</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#377dff', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('5', '1', 'Ariana Grande', 'Toronto Theater', '2017-03-05', '2017-03-05', '21:00:00', '23:00:00', '<p>She may be a pint-sized pop princess but don\'t for a second doubt if Ariana Grande packs a punch! Dangerous Woman is one of the biggest albums of the year, and the long-awaited supporting tour is here! A huge step up from My Everything, Dangerous Woman is a mature effort from the young star, featuring a bunch of starry collaborators - Future, Nicki Minaj - and countless pop belters, such as the sultry title track and the finger clicking \'Into You\'.</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#377dff', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('6', '1', 'Tom Petty And The Heartbreakers', 'Air Canada Center', '2017-06-15', '2017-06-15', '19:00:00', '21:00:00', '<p>Tom Petty and the Heartbreakers are one of the great American bands of the last half century, practically owning the term \'heartland rock\' with their chronicling of the country\'s zeitgeist throughout the decades. Great shakers in the American new wave scene, they\'re best known tracks include \'Mary Jane\'s Last Dance\', \'American Girl\', \'Learnin\' To Fly\', \'Stop Draggin\' My Heart Around\', \'I Won\'t Back Down\', and \'Free Fallin\'.</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#377dff', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('7', '1', 'Justin Bieber', 'Toronto Theater', '2017-09-03', '2017-09-03', '13:30:00', '15:00:00', '<p>Young adult heartthrob and Canadian-born singer-songwriter Justin Bieber has come out of the chrysalis a new artist with a bold new musical statement - 2015\'s Purpose. Gone are the fizzy teeny-bopper hits of yore, replaced with remarkably slick and mature dance tunes like \'What Do U Mean\' and \'Where Are U Now\'. Catch him as he embarks on a mammoth follow-up to his 2016 Purpose world tour, coming to Rogers Centre in Toronto , ON.</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#00c9a7', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('8', '1', 'Abba Mania', 'Air Canada Center', '2017-02-09', '2017-02-09', '21:00:00', '23:00:00', '<p>ABBA fans of the US unite! It\'s time to pull out the platform boots, bellbottom flairs and starched collars as ABBA Mania once again hits American shores. Transported from London\'s West End and multiple tours of the UK, the show is an astounding tribute to the beloved music of one of the most successful acts in musical history. Don\'t miss this chance to relive the best of the ABBA catalogue at Danforth Music Hall in Toronto, ON, February 9, 2017</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#de4437', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('9', '1', 'Bruno Mars', 'Toronto Theater', '2017-08-01', '2017-08-01', '13:30:00', '16:30:00', '<p>Bruno Mars is on top of the world right now. Still basking in the success of his Mark Ronson collaboration \'Uptown Funk\', he followed it up with another fierce of glittering pop funk, \'24K Magic\', the lead single from the album of the same name.</p>\n<p>A showcase for his incredible skills as a songwriter, musician and all round entertainer, 24K Magic is another entry in a hits filled catalogue which has seen Bruno become one of the biggest names in music.</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#de4437', '1');
INSERT INTO `mod_events` (`id`, `user_id`, `title_en`, `venue_en`, `date_start`, `date_end`, `time_start`, `time_end`, `body_en`, `contact_person`, `contact_email`, `contact_phone`, `color`, `active`) VALUES ('10', '1', 'Neil Diamond', 'Air Canada Center', '2017-06-03', '2017-06-06', '11:00:00', '16:00:00', '<p>One of the most successful pop singers in history, the great Neil Diamond has now been in the business for half a century, a fact he\'s celebrating with a huge 50th Anniversary Tour throughout 2017. A member of The Rock \'n\' Roll Hall of Fame, and more than 125 million record sales under his belt, Neil\'s best known tracks include \'Sweet Caroline\', \'Red Red Wine\' and \'I\'m a Believer\' (originally performed by The Monkees).</p>', 'John Doe', 'john@gmail.com', '555-555-5555', '#ffc107', '1');


-- --------------------------------------------------
# -- Table structure for table `mod_faq`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_faq`;
CREATE TABLE `mod_faq` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(4) unsigned NOT NULL DEFAULT '0',
  `question_en` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer_en` text COLLATE utf8_unicode_ci,
  `sorting` int(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ixd_category` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='contains f.a.q. data';

-- --------------------------------------------------
# Dumping data for table `mod_faq`
-- --------------------------------------------------

INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('1', '1', 'Do you have any built-in caching?', '<p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS. </p>', '3');
INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('2', '1', 'Can I add/upgrade my plan at any time?', 'Quotidian linguistas il sed, esser inviar traduction o con. De duo populos auxiliary. Veni ample instruite da del, il pan regno appellate professional. Parlar movimento scientific uno o, ha rapide quales voluntate uso. Duo toto origine interlinguistica da.\n', '1');
INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('3', '1', 'What access comes with my hosting plan?', 'Κυωτ ερρεμ φασιλισις αν μελ. Σενσεριτ σονστιθυαμ ει εσθ, ομνες φιθυπεραθα αργυμενθυμ αν εαμ, μανδαμυς γλοριαθυρ δεφινιεβας πρι νο. Φιδε σαπιενθεμ νο ιυς, χαρυμ ειρμωδ λατινε ηας ετ. Συμ αθκυι ομιθταντυρ αδ, ει συμ θωτα σριψεριθ μεδιοσριθαθεμ, ιδ σιθ δεσερυντ λοβωρτις σονστιθυαμ. Συμμο μελιυς ευμ υθ. Σολυμ φιρθυθε φιξ εα&lt;br /&gt;\n ', '2');
INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('4', '1', 'How do I change my password?', 'पहोचने ऎसाजीस गटको सक्षम अपनि बनाकर तकनिकल बिन्दुओ कम्प्युटर लेकिन क्षमता प्रदान निर्माता भाषा अपनि गटकउसि केन्द्रित विकासक्षमता पहेला हुएआदि कार्यकर्ता संदेश काम तरीके अतित एसलिये उसीएक् खयालात सहायता माध्यम बहुत औषधिक हुएआदि सम्पर्क संस्क्रुति मुख्य सेऔर दौरान किएलोग निर्माता विकेन्द्रियकरण आपको&lt;br /&gt;\n', '4');
INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('5', '2', 'How do I upload files from my phone or tablet?', 'Жят йн ведят мовэт ныглэгэнтур, ут вяш декам оффэндйт, факэтэ аккюсам ратионебюж прё ад. Мэль омйттам нолюёжжэ нэ, нэ дектаж ютроквюы мэль. Ку шапэрэт ыльигэнди атоморюм хаж, шэа ыт адмодум майыжтатйж дёзсэнтёаш. Ку эож ёудико пожжёт рэформйданч. Ат еллум дикунт иреуры векж. Эож вэре конжюль ат, йн копиожаы ёнвидюнт эож, мэль но вэниам нонумй&lt;br /&gt;\n', '3');
INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('6', '2', 'How do I link to a file or folder?', 'ميناء بتطويق بل بال, الفترة الإطلاق التّحول تعد تم. أي أعمال وصافرات بريطانيا، كلّ, لكل أم شمال واحدة. الثقيلة بالولايات عل جوي, إذ المعارك البولندي حيث, حدى الأحمر وصافرات لم. تعد قد مساعدة الأبرياء, الحرة والحزب مارشال بـ ومن. سلاح بهجوم بالعمل ان مما, الا أن الدمج والديون للأسطول, اعتداء الحروب ومن هو. فعل إذ مكارثر الحروب, هو وضم وعلى الشرق، العام،.', '1');
INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('7', '2', 'Can I specify my own private key?', '窃歩井示起秋産門育取図違院止女教織氏。面初貸図読街動掲後新芸意物式質呂。奪動明点属工況気井同見製弱実朝。分全倉示内加浩転聞死図確災暮一名前報記。掲策式森安図真節就面民聞球必園親記絵。診本葉相議足前挑豊館気年極作目全動。皇前長髄因揚取目康邦新妹違。館炉教企仙福地申台内府掲認料挙物出。米能世帰社域関平掲徳会山加井型葉深', '2');
INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('8', '3', 'How can I control if other search engines can link to my profile?', 'Büdeds dredols-li elotidobs-li lü mal, ni bal koap maita neai. No degtelis pösodis tefü mid, ons mö jidünan magot sevob. Dekömomöd fanön kim of, lul ekobikons neodons valasotik ba, geton lelifikam on fin. Bu godi jigok kiöpio ata, go mutoms nämätan sudans sol. Vii mutols saludikosi vi, eprofetobs-li nedetü dom ob. Kapa klotem ojenosöd maf me, ebo bu meni pohetols.', '8');
INSERT INTO `mod_faq` (`id`, `category_id`, `question_en`, `answer_en`, `sorting`) VALUES ('9', '4', 'How can I access my privacy data?', '<p>Informing awareness with the possibility to surprise and delight. Synchronising relevant and engaging content while remembering to maximise share of voice. Leading benchmarking with the aim to build ROI. Leveraging custom solutions to, consequently, re-target key demographics. Drive core competencies in order to be CMSable. Engage innovation to, consequently, make users into advocates.</p>\r\n<p>Synchronise empathy maps with the possibility to be transparent. Generating brand ambassadors with the possibility to further your reach. Leading stakeholder management with a goal to get buy in. Utilising analytics but gain traction. Grow outside the box thinking with the aim to go viral.</p>', '9');


-- --------------------------------------------------
# -- Table structure for table `mod_faq_categories`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_faq_categories`;
CREATE TABLE `mod_faq_categories` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(50) NOT NULL,
  `sorting` int(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf16;

-- --------------------------------------------------
# Dumping data for table `mod_faq_categories`
-- --------------------------------------------------

INSERT INTO `mod_faq_categories` (`id`, `name_en`, `sorting`) VALUES ('1', 'Basics', '1');
INSERT INTO `mod_faq_categories` (`id`, `name_en`, `sorting`) VALUES ('2', 'Syncing', '2');
INSERT INTO `mod_faq_categories` (`id`, `name_en`, `sorting`) VALUES ('3', 'Account', '3');
INSERT INTO `mod_faq_categories` (`id`, `name_en`, `sorting`) VALUES ('4', 'Privacy', '4');


-- --------------------------------------------------
# -- Table structure for table `mod_gallery`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_gallery`;
CREATE TABLE `mod_gallery` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(60) NOT NULL,
  `slug_en` varchar(100) DEFAULT NULL,
  `description_en` varchar(100) DEFAULT NULL,
  `thumb_w` smallint(1) unsigned DEFAULT '500',
  `thumb_h` smallint(1) unsigned NOT NULL DEFAULT '500',
  `poster` varchar(60) DEFAULT NULL,
  `cols` smallint(1) unsigned NOT NULL DEFAULT '300',
  `dir` varchar(40) NOT NULL,
  `resize` varchar(30) DEFAULT NULL,
  `watermark` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ebable watermark',
  `likes` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'enable like',
  `sorting` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_gallery`
-- --------------------------------------------------

INSERT INTO `mod_gallery` (`id`, `title_en`, `slug_en`, `description_en`, `thumb_w`, `thumb_h`, `poster`, `cols`, `dir`, `resize`, `watermark`, `likes`, `sorting`) VALUES ('1', 'Album One', 'album-one', '- New gallery module (albums), responsive images different layouts -', '500', '500', 'image_1.jpg', '400', 'album_one', 'thumbnail', '1', '1', '1');
INSERT INTO `mod_gallery` (`id`, `title_en`, `slug_en`, `description_en`, `thumb_w`, `thumb_h`, `poster`, `cols`, `dir`, `resize`, `watermark`, `likes`, `sorting`) VALUES ('2', 'Album Two', 'album-two', '', '500', '500', 'image_2.jpg', '300', 'album_two', 'bestFit', '0', '0', '2');
INSERT INTO `mod_gallery` (`id`, `title_en`, `slug_en`, `description_en`, `thumb_w`, `thumb_h`, `poster`, `cols`, `dir`, `resize`, `watermark`, `likes`, `sorting`) VALUES ('3', 'Album Three', 'album-three', '', '500', '500', 'image_3.jpg', '400', 'album_three', 'bestFit', '0', '0', '3');
INSERT INTO `mod_gallery` (`id`, `title_en`, `slug_en`, `description_en`, `thumb_w`, `thumb_h`, `poster`, `cols`, `dir`, `resize`, `watermark`, `likes`, `sorting`) VALUES ('4', 'Album Four', 'album-four', '', '500', '500', 'image_4.jpg', '400', 'album_four', 'bestFit', '0', '0', '4');


-- --------------------------------------------------
# -- Table structure for table `mod_gallery_data`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_gallery_data`;
CREATE TABLE `mod_gallery_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title_en` varchar(80) NOT NULL,
  `description_en` varchar(200) DEFAULT NULL,
  `thumb` varchar(80) DEFAULT NULL,
  `likes` int(11) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_gallery_data`
-- --------------------------------------------------

INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('1', '1', 'Design in a Box', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_1.jpg', '151', '1');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('2', '1', 'Social Vision', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_2.jpg', '231', '2');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('3', '1', 'Planning and Planning', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_3.jpg', '328', '3');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('4', '1', 'Up, up and away', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_4.jpg', '489', '4');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('5', '1', 'Flying Ideas', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_5.jpg', '292', '5');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('6', '1', 'Shopping Touch', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_6.jpg', '545', '6');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('7', '1', 'True Colors', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_7.jpg', '754', '7');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('8', '1', 'Touch the Future', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_8.jpg', '660', '8');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('10', '2', 'Design in a Box', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_1.jpg', '156', '1');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('11', '2', 'Social Vision', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_2.jpg', '225', '2');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('12', '2', 'Planning and Planning', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_3.jpg', '358', '3');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('13', '2', 'Up, up and away', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_4.jpg', '487', '4');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('14', '2', 'Flying Ideas', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_5.jpg', '289', '5');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('15', '2', 'Shopping Touch', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_6.jpg', '541', '6');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('16', '2', 'True Colors', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_7.jpg', '752', '7');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('17', '2', 'Touch the Future', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_8.jpg', '657', '8');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('19', '3', 'Design in a Box', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_1.jpg', '150', '1');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('20', '3', 'Social Vision', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_2.jpg', '647', '2');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('21', '3', 'Planning and Planning', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_3.jpg', '325', '3');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('22', '3', 'Up, up and away', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_4.jpg', '487', '4');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('23', '3', 'Flying Ideas', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_5.jpg', '658', '5');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('24', '3', 'Shopping Touch', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_6.jpg', '541', '6');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('25', '3', 'True Colors', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_7.jpg', '752', '7');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('26', '3', 'Touch the Future', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_8.jpg', '657', '8');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('28', '4', 'Design in a Box', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_1.jpg', '150', '1');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('29', '4', 'Social Vision', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_2.jpg', '225', '2');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('30', '4', 'Planning and Planning', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_3.jpg', '325', '3');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('31', '4', 'Up, up and away', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_4.jpg', '487', '4');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('32', '4', 'Flying Ideas', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_5.jpg', '289', '5');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('33', '4', 'Shopping Touch', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_6.jpg', '541', '6');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('34', '4', 'True Colors', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_7.jpg', '752', '7');
INSERT INTO `mod_gallery_data` (`id`, `parent_id`, `title_en`, `description_en`, `thumb`, `likes`, `sorting`) VALUES ('35', '4', 'Touch the Future', 'Hop duon tioma lumigi nv, if tiela poezio sezononomo fri, semi pleje lingvonomo ac unt.', 'image_8.jpg', '897', '8');


-- --------------------------------------------------
# -- Table structure for table `mod_gmaps`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_gmaps`;
CREATE TABLE `mod_gmaps` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `plugin_id` varchar(40) DEFAULT NULL,
  `lat` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `lng` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `body` tinytext,
  `zoom` tinyint(1) unsigned NOT NULL DEFAULT '12',
  `minmaxzoom` varchar(5) DEFAULT NULL,
  `layout` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `type_control` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `streetview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `style` blob,
  `pin` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_gmaps`
-- --------------------------------------------------

INSERT INTO `mod_gmaps` (`id`, `name`, `plugin_id`, `lat`, `lng`, `body`, `zoom`, `minmaxzoom`, `layout`, `type`, `type_control`, `streetview`, `style`, `pin`) VALUES ('1', 'Head Office', 'gmaps/head-office', '43.650319', '-79.378860', '1 Adelaide St W, Toronto, ON M5H 1L6, Canada', '14', '1,20', 'muted-blue', 'roadmap', '0', '0', '[\r\n    {\r\n        "featureType": "all",\r\n        "stylers": [\r\n            {\r\n                "saturation": -100\r\n            },\r\n            {\r\n                "hue": "#e7ecf0"\r\n            }\r\n        ]\r\n    },\r\n    {\r\n        "featureType": "road",\r\n        "stylers": [\r\n            {\r\n                "saturation": -70\r\n            }\r\n        ]\r\n    },\r\n    {\r\n        "featureType": "transit",\r\n        "stylers": [\r\n            {\r\n                "visibility": "off"\r\n            }\r\n        ]\r\n    },\r\n    {\r\n        "featureType": "poi",\r\n        "stylers": [\r\n            {\r\n                "visibility": "off"\r\n            }\r\n        ]\r\n    },\r\n    {\r\n        "featureType": "water",\r\n        "stylers": [\r\n            {\r\n                "visibility": "simplified"\r\n            },\r\n            {\r\n                "saturation": -60\r\n            },\r\n            {\r\n                "color": "#f3f3f3"\r\n            }\r\n        ]\r\n    }\r\n]', 'pin2.png');


-- --------------------------------------------------
# -- Table structure for table `mod_timeline`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_timeline`;
CREATE TABLE `mod_timeline` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `plugin_id` varchar(25) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `limiter` tinyint(1) unsigned NOT NULL DEFAULT '10',
  `showmore` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `maxitems` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `colmode` varchar(20) DEFAULT 'dual',
  `readmore` varchar(150) DEFAULT NULL,
  `rssurl` varchar(200) DEFAULT NULL,
  `fbid` varchar(150) DEFAULT NULL,
  `fbpage` varchar(150) DEFAULT NULL,
  `fbtoken` varchar(150) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_timeline`
-- --------------------------------------------------

INSERT INTO `mod_timeline` (`id`, `name`, `plugin_id`, `type`, `limiter`, `showmore`, `maxitems`, `colmode`, `readmore`, `rssurl`, `fbid`, `fbpage`, `fbtoken`, `created`) VALUES ('1', 'Blog Timeline', 'timeline/blog', 'blog', '10', '0', '0', 'dual', '', '0', '', '', '', '2016-10-28 10:46:39');
INSERT INTO `mod_timeline` (`id`, `name`, `plugin_id`, `type`, `limiter`, `showmore`, `maxitems`, `colmode`, `readmore`, `rssurl`, `fbid`, `fbpage`, `fbtoken`, `created`) VALUES ('2', 'Event Timeline', 'timeline/event', 'event', '16', '10', '5', 'dual', '', '', '', '', '', '2016-10-28 10:46:39');
INSERT INTO `mod_timeline` (`id`, `name`, `plugin_id`, `type`, `limiter`, `showmore`, `maxitems`, `colmode`, `readmore`, `rssurl`, `fbid`, `fbpage`, `fbtoken`, `created`) VALUES ('3', 'Rss Timeline', 'timeline/rss', 'rss', '20', '0', '0', 'dual', '', 'https://www.cbc.ca/cmlink/rss-topstories', '', '', '', '2016-10-28 10:46:39');
INSERT INTO `mod_timeline` (`id`, `name`, `plugin_id`, `type`, `limiter`, `showmore`, `maxitems`, `colmode`, `readmore`, `rssurl`, `fbid`, `fbpage`, `fbtoken`, `created`) VALUES ('4', 'Portfolio Timeline', 'timeline/portfolio', 'portfolio', '12', '0', '0', 'dual', '', '', '', '', '', '2016-10-28 10:46:39');
INSERT INTO `mod_timeline` (`id`, `name`, `plugin_id`, `type`, `limiter`, `showmore`, `maxitems`, `colmode`, `readmore`, `rssurl`, `fbid`, `fbpage`, `fbtoken`, `created`) VALUES ('5', 'Facebook Timeline', 'timeline/news', 'facebook', '10', '0', '0', 'left', '', '', '', '', '', '2016-10-28 10:46:39');
INSERT INTO `mod_timeline` (`id`, `name`, `plugin_id`, `type`, `limiter`, `showmore`, `maxitems`, `colmode`, `readmore`, `rssurl`, `fbid`, `fbpage`, `fbtoken`, `created`) VALUES ('6', 'Custom Timeline', 'timeline/custom_timeline', 'custom', '30', '10', '10', 'dual', '', '', '', '', '', '2016-10-28 10:46:39');


-- --------------------------------------------------
# -- Table structure for table `mod_timeline_data`
-- --------------------------------------------------
DROP TABLE IF EXISTS `mod_timeline_data`;
CREATE TABLE `mod_timeline_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'timeline id',
  `type` varchar(30) DEFAULT NULL,
  `title_en` varchar(100) DEFAULT NULL,
  `body_en` text,
  `images` blob,
  `dataurl` varchar(250) DEFAULT NULL,
  `height` smallint(3) unsigned NOT NULL DEFAULT '300',
  `readmore` varchar(200) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `mod_timeline_data`
-- --------------------------------------------------

INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('1', '6', 'blog_post', 'Single Image Only', '', '["timeline/demo_image_4.jpg"]', '', '300', '', '2016-01-23 01:30:00');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('2', '6', 'blog_post', 'Single Image With Text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales dapibus dui, sed iaculis metus facilisis sed. Curae; Pellentesque ullamcorper nisl id justo ultrices hendrerit', '["timeline/demo_image_2.jpg"]', '', '0', '', '2016-01-22 12:20:29');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('3', '6', 'blog_post', 'Single Image With Read More', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales dapibus dui, sed iaculis metus facilisis sed. Curae; Pellentesque ullamcorper nisl id justo ultrices hendrerit', '["timeline/demo_image_3.jpg"]', '', '0', '//wojoscripts.com', '2016-01-20 12:25:30');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('4', '6', 'blog_post', 'Text Only', '<p>\n\tDuis dapibus aliquam mi, eget euismod sem scelerisque ut. Vivamus at elit quis urna adipiscing iaculis. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas\n</p>\n<p>\nDuis dapibus aliquam mi, eget euismod sem scelerisque ut. Vivamus at elit quis urna adipiscing iaculis. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\n</p>', '', '', '300', '', '2016-01-21 01:30:00');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('5', '6', 'blog_post', 'HTML Support', '<div class="content-center">\r\n  <a><i class="twitter circular inverted positive icon link"></i></a>\r\n  <a><i class="facebook  circular inverted primary icon link"></i></a>\r\n  <a><i class="google circular inverted negative icon link"></i></a>\r\n  <a><i class="pinterest circular inverted secondary icon link"></i></a>\r\n  <a><i class="github circular inverted purple icon link"></i></a>\r\n</div>', '', '', '0', '', '2016-01-19 12:28:02');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('6', '6', 'iframe', 'Google Maps', '', '', 'https://google.com/maps/embed?pb=!1m14!1m8!1m3!1d5774.551951139716!2d-79.38573735330591!3d43.64242624743821!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882b34d68bf33a9b%3A0x15edd8c4de1c7581!2sCN+Tower!5e0!3m2!1sen!2sca!4v1422155824800', '300', '', '2016-01-18 01:30:00');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('7', '6', 'iframe', 'Youtube Videos', '', '', '//youtube.com/embed/YvR8LGOUpNA', '300', '', '2016-01-17 13:26:23');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('8', '6', 'blog_post', 'Image Slider', '', '["timeline\\/gallery_11.jpg","timeline\\/gallery_12.jpg","timeline\\/gallery_13.jpg","timeline\\/gallery_14.jpg","timeline\\/gallery_15.jpg","timeline\\/gallery_16.jpg"]', '', '300', '', '2016-01-24 13:29:15');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('9', '6', 'gallery', 'Gallery Slider', '', '["timeline\\/gallery_11.jpg","timeline\\/gallery_12.jpg","timeline\\/gallery_13.jpg","timeline\\/gallery_14.jpg","timeline\\/gallery_15.jpg","timeline\\/gallery_16.jpg"]', '', '300', '', '2016-01-21 14:08:26');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('10', '6', 'blog_post', 'Single Image Only', '', '["timeline/demo_image_4.jpg"]', '', '0', '', '2015-01-23 09:45:21');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('11', '6', 'blog_post', 'Single Image With Text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales dapibus dui, sed iaculis metus facilisis sed. Curae; Pellentesque ullamcorper nisl id justo ultrices hendrerit', '["timeline/demo_image_2.jpg"]', '', '0', '', '2015-01-22 12:20:29');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('12', '6', 'blog_post', 'Single Image With Read More', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales dapibus dui, sed iaculis metus facilisis sed. Curae; Pellentesque ullamcorper nisl id justo ultrices hendrerit</p>', '["timeline/demo_image_3.jpg"]', '', '300', '//wojoscripts.com', '2015-01-20 01:30:00');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('13', '6', 'blog_post', 'Text Only', 'Duis dapibus aliquam mi, eget euismod sem scelerisque ut. Vivamus at elit quis urna adipiscing iaculis. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas<br><br>Duis dapibus aliquam mi, eget euismod sem scelerisque ut. Vivamus at elit quis urna adipiscing iaculis. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', '', '', '0', '', '2015-01-21 12:24:25');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('14', '6', 'blog_post', 'HTML Support', '<div class="content-center">\r\n  <a><i class="twitter circular inverted positive icon link"></i></a>\r\n  <a><i class="facebook  circular inverted primary icon link"></i></a>\r\n  <a><i class="google circular inverted negative icon link"></i></a>\r\n  <a><i class="pinterest circular inverted secondary icon link"></i></a>\r\n  <a><i class="github circular inverted purple icon link"></i></a>\r\n</div>', '', '', '0', '', '2015-01-19 12:28:02');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('15', '6', 'iframe', 'Google Maps', '', '', 'https://google.com/maps/embed?pb=!1m14!1m8!1m3!1d5774.551951139716!2d-79.38573735330591!3d43.64242624743821!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882b34d68bf33a9b%3A0x15edd8c4de1c7581!2sCN+Tower!5e0!3m2!1sen!2sca!4v1422155824800', '300', '', '2015-01-18 12:44:22');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('16', '6', 'iframe', 'Youtube Videos', '', '', '//youtube.com/embed/YvR8LGOUpNA', '300', '', '2015-01-17 13:26:23');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('17', '6', 'blog_post', 'Image Slider', '', '["timeline\\/gallery_11.jpg","timeline\\/gallery_12.jpg","timeline\\/gallery_13.jpg","timeline\\/gallery_14.jpg","timeline\\/gallery_15.jpg","timeline\\/gallery_16.jpg"]', '', '300', '', '2015-01-24 01:30:00');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('18', '6', 'gallery', 'Gallery Slider', '', '["timeline\\/gallery_11.jpg","timeline\\/gallery_12.jpg","timeline\\/gallery_13.jpg","timeline\\/gallery_14.jpg","timeline\\/gallery_15.jpg","timeline\\/gallery_16.jpg"]', '', '300', '', '2015-01-21 14:08:26');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('19', '6', 'blog_post', 'Single Image Only', '', '["timeline/demo_image_4.jpg"]', '', '0', '', '2014-01-23 09:45:21');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('20', '6', 'blog_post', 'Single Image With Text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales dapibus dui, sed iaculis metus facilisis sed. Curae; Pellentesque ullamcorper nisl id justo ultrices hendrerit', '["timeline/demo_image_2.jpg"]', '', '0', '', '2014-01-22 12:20:29');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('21', '6', 'blog_post', 'Single Image With Read More', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales dapibus dui, sed iaculis metus facilisis sed. Curae; Pellentesque ullamcorper nisl id justo ultrices hendrerit', '["timeline/demo_image_3.jpg"]', '', '0', '//wojoscripts.com', '2014-01-20 12:25:30');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('22', '6', 'blog_post', 'Text Only', 'Duis dapibus aliquam mi, eget euismod sem scelerisque ut. Vivamus at elit quis urna adipiscing iaculis. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas<br><br>Duis dapibus aliquam mi, eget euismod sem scelerisque ut. Vivamus at elit quis urna adipiscing iaculis. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur vitae velit in neque dictum blandit. Proin in iaculis neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas', '', '', '0', '', '2014-01-21 12:24:25');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('23', '6', 'blog_post', 'HTML Support', '<div class="content-center">\r\n  <a><i class="twitter circular inverted positive icon link"></i></a>\r\n  <a><i class="facebook  circular inverted primary icon link"></i></a>\r\n  <a><i class="google circular inverted negative icon link"></i></a>\r\n  <a><i class="pinterest circular inverted secondary icon link"></i></a>\r\n  <a><i class="github circular inverted purple icon link"></i></a>\r\n</div>', '', '', '0', '', '2014-01-19 12:28:02');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('24', '6', 'iframe', 'Google Maps', '', '', 'https://google.com/maps/embed?pb=!1m14!1m8!1m3!1d5774.551951139716!2d-79.38573735330591!3d43.64242624743821!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882b34d68bf33a9b%3A0x15edd8c4de1c7581!2sCN+Tower!5e0!3m2!1sen!2sca!4v1422155824800', '300', '', '2014-01-18 12:44:22');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('25', '6', 'iframe', 'Youtube Videos', '', '', '//youtube.com/embed/YvR8LGOUpNA', '300', '', '2014-01-17 13:26:23');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('26', '6', 'blog_post', 'Image Slider', '', '["timeline\\/gallery_11.jpg","timeline\\/gallery_12.jpg","timeline\\/gallery_13.jpg","timeline\\/gallery_14.jpg","timeline\\/gallery_15.jpg","timeline\\/gallery_16.jpg"]', '', '300', '', '2014-01-24 13:29:15');
INSERT INTO `mod_timeline_data` (`id`, `tid`, `type`, `title_en`, `body_en`, `images`, `dataurl`, `height`, `readmore`, `created`) VALUES ('27', '6', 'gallery', 'Gallery Slider', '', '["timeline\\/gallery_11.jpg","timeline\\/gallery_12.jpg","timeline\\/gallery_13.jpg","timeline\\/gallery_14.jpg","timeline\\/gallery_15.jpg","timeline\\/gallery_16.jpg"]', '', '300', '', '2014-01-21 14:08:26');


-- --------------------------------------------------
# -- Table structure for table `modules`
-- --------------------------------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(120) NOT NULL,
  `info_en` varchar(200) DEFAULT NULL,
  `modalias` varchar(60) NOT NULL,
  `hasconfig` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hascoupon` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasfields` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parent_id` smallint(3) unsigned NOT NULL DEFAULT '0',
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_builder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `keywords_en` varchar(200) DEFAULT NULL,
  `description_en` text,
  `icon` varchar(50) DEFAULT NULL,
  `ver` decimal(4,2) unsigned NOT NULL DEFAULT '1.00',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `modules`
-- --------------------------------------------------

INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('1', 'Gallery', 'Fully featured gallery module', 'gallery', '1', '0', '0', '1', '1', '0', '1', '0', '', '', 'gallery/thumb.svg', '5.00', '2014-04-28 18:19:32', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('3', 'Comments', 'Encourage your readers to join in the discussion and leave comments and respond promptly to the comments left by your readers to make them feel valued', 'comments', '1', '0', '0', '0', '1', '0', '0', '0', '', '', 'comments/thumb.svg', '5.00', '2016-10-15 10:05:56', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('4', 'Event Manager', 'Easily publish and manage your company events.', 'events', '1', '0', '0', '0', '1', '0', '0', '1', '', '', 'events/thumb.svg', '5.00', '2016-10-15 12:03:54', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('6', 'Universal Timeline', 'Create unlimited timline pugins.', 'timeline', '1', '0', '0', '1', '1', '0', '0', '0', '', '', 'timeline/thumb.svg', '5.00', '2016-10-28 08:59:59', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('9', 'AdBlock', 'Manage Ad Campaigns', 'adblock', '1', '0', '0', '0', '0', '0', '0', '0', '', '', 'adblock/thumb.svg', '5.00', '2016-11-14 13:20:18', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('11', 'Location Maps', 'Add Google Maps with multiple markers', 'gmaps', '1', '0', '0', '0', '0', '0', '0', '0', '', '', 'gmaps/thumb.svg', '5.00', '2016-11-19 13:08:30', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('12', 'Album One', '', 'gallery', '0', '0', '0', '0', '0', '1', '0', '1', '', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod', 'gallery/thumb.svg', '1.00', '2017-01-04 05:18:56', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('13', 'Album Two', '', 'gallery', '0', '0', '0', '0', '0', '2', '0', '1', '', '', 'gallery/thumb.svg', '1.00', '2017-01-04 05:27:41', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('14', 'Album Three', '', 'gallery', '0', '0', '0', '0', '0', '3', '0', '1', '', '', 'gallery/thumb.svg', '1.00', '2017-01-04 05:28:17', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('15', 'Album Four', '', 'gallery', '0', '0', '0', '0', '0', '4', '0', '1', '', '', 'gallery/thumb.svg', '1.00', '2017-01-04 05:28:48', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('19', 'Event Timeline', '', 'timeline', '0', '0', '0', '0', '0', '2', '0', '1', '', '', 'timeline/thumb.svg', '1.00', '2017-01-04 05:49:05', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('20', 'Rss Timeline', '', 'timeline', '0', '0', '0', '0', '0', '3', '0', '1', '', '', 'timeline/thumb.svg', '1.00', '2017-01-04 05:49:34', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('23', 'Custom Timeline', '', 'timeline/custom_timeline', '0', '0', '0', '0', '0', '6', '0', '1', '', '', 'timeline/thumb.svg', '1.00', '2017-01-04 05:51:06', '1');
INSERT INTO `modules` (`id`, `title_en`, `info_en`, `modalias`, `hasconfig`, `hascoupon`, `hasfields`, `system`, `content`, `parent_id`, `is_menu`, `is_builder`, `keywords_en`, `description_en`, `icon`, `ver`, `created`, `active`) VALUES ('24', 'F.A.Q. Manager', 'Complete Frequently Asked Question Management Module', 'faq', '1', '0', '0', '0', '1', '0', '0', '1', '', '', 'faq/thumb.svg', '1.00', '2017-05-25 03:54:17', '1');


-- --------------------------------------------------
# -- Table structure for table `pages`
-- --------------------------------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(200) NOT NULL,
  `slug_en` varchar(150) DEFAULT NULL,
  `caption_en` varchar(150) DEFAULT NULL,
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `page_type` enum('normal','home','contact','login','activate','account','register','search','sitemap','profile','policy') NOT NULL DEFAULT 'normal',
  `membership_id` varchar(20) NOT NULL DEFAULT '0',
  `is_comments` tinyint(1) NOT NULL DEFAULT '0',
  `custom_bg_en` varchar(100) DEFAULT NULL,
  `show_header` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `theme` varchar(60) DEFAULT NULL,
  `access` enum('Public','Registered','Membership') NOT NULL DEFAULT 'Public',
  `body_en` text,
  `jscode` text,
  `keywords_en` varchar(200) DEFAULT NULL,
  `description_en` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `created_by_name` varchar(80) DEFAULT NULL,
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `idx_search` (`title_en`,`body_en`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `pages`
-- --------------------------------------------------

INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('1', 'Welcome To Cms pro', 'home', '', '1', 'home', '0', '0', '', '1', '', 'Public', '<div class="section">\n  <div class="row">\n    <div class="columns"> %%slider/master|plugin|3|5%% </div>\n  </div>\n</div>\n<div class="section" style="padding:128px 0 32px 0">\n  <div class="wojo-grid">\n    <div class="row double-gutters align-middle">\n      <div class="columns mobile-100 phone-100">\n        <div data-weditable="true">\n          <h2 class="wojo primary text">CMS Pro in <span class="wojo semi text">frames</span>\n          </h2>\n          <p>Enhance your brand with easy-to-use powerful customization features.</p>\n          <p class="margin-bottom">The time has come to bring those ideas and plans to life. This is where we really begin to visualize your napkin sketches and make them into beautiful pixels.</p>\n          <a href="#" class="wojo small primary right button" data-type="button">Find out More <i class="icon chevron right"></i></a>\n        </div>\n      </div>\n      <div class="columns relative mobile-100 phone-100">\n        <div class="row half-gutters">\n          <div class="columns screen-40 tablet-40 mobile-50 phone-50 align-self-bottom">\n            <a href="[SITEURL]/uploads/images/content_img4.jpg" class="wojo basic rounded image lightbox" data-gallery="images">\n              <img src="[SITEURL]/uploads/thumbs/content_img4.jpg" data-weditable="true">\n              <span class="viewer"><i class="icon plus"></i></span>\n            </a>\n          </div>\n          <div class="columns screen-60 tablet-60 mobile-50 phone-50">\n            <a href="[SITEURL]/uploads/images/content_img3.jpg" class="wojo basic rounded image lightbox" data-gallery="images">\n              <img src="[SITEURL]/uploads/thumbs/content_img3.jpg" data-weditable="true">\n              <span class="viewer"><i class="icon plus"></i></span>\n            </a>\n          </div>\n          <div class="columns screen-offset-10 tablet-offset-10 screen-40 tablet-40 mobile-50 phone-50">\n            <a href="[SITEURL]/uploads/images/content_img2.jpg" class="wojo basic rounded image lightbox" data-gallery="images">\n              <img src="[SITEURL]/uploads/thumbs/content_img2.jpg" data-weditable="true" class="">\n              <span class="viewer"><i class="icon plus"></i></span>\n            </a>\n          </div>\n          <div class="columns screen-50 tablet-50 mobile-50 phone-50">\n            <a href="[SITEURL]/uploads/images/content_img1.jpg" class="wojo basic rounded image lightbox" data-gallery="images">\n              <img src="[SITEURL]/uploads/thumbs/content_img1.jpg" data-weditable="true">\n              <span class="viewer"><i class="icon plus"></i></span>\n            </a>\n          </div>\n        </div>\n        <div style="z-index:-1;position: absolute;top: 50%;-webkit-transform: translate(0, -50%);transform: translate(0, -50%);max-width: 100%">\n          <figure class="wojo basic image">\n            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1109.8 797.1">\n              <path fill="#377dff" d="M105.1 267.1C35.5 331.5-3.5 423 .3 517.7 6.1 663 111 831.9 588.3 790.8c753-64.7 481.3-358.3 440.4-398.3-4-3.9-7.9-7.9-11.7-12L761.9 104.8C639.4-27.6 432.5-35.6 299.9 87L105.1 267.1z" opacity=".1"></path>\n            </svg>\n          </figure>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n<div class="section relative" style="padding:96px 0;background-color: #377dff;">\n  <div class="wojo-grid">\n    <div class="row align-center">\n      <div class="columns screen-60 tablet-80 mobile-100 phone-100">\n        <div class="content-center" data-weditable="true" style="position:relative;z-index:1">\n          <a class="wojo white large passive circular icon button" data-type="button">\n            <i class="icon big umbrella"></i>\n          </a>\n          <h1 class="wojo white text half-top-margin">Device <span class="wojo semi text">friendly</span> features</h1>\n          <p class="wojo white dimmed thin medium text">Your website is fully responsive so visitors can view your content from their choice of device.</p>\n        </div>\n      </div>\n    </div>\n  </div>\n  <figure class="absolute" style="top: 50%;-webkit-transform: translate(0, -50%);transform: translate(0, -50%);width:100%;z-index:0">\n    <svg xmlns="http://www.w3.org/2000/svg" viewBox="803 -87.1 1653 222.1">\n      <g fill="none" stroke="#FFF" stroke-miterlimit="10" opacity=".3">\n        <path d="M803.5 113.1s1.3.4 3.8 1.2"></path>\n        <path stroke-dasharray="7.9643,7.9643" d="M815 116.3c31.8 8.4 125.8 28.7 233.9 11.3 74.3-12 150.4-23.1 219.4-55 51.4-23.8 93.7-64.1 147.1-84.2 24.2-9.1 49.4-15.5 74.7-20.5 65.8-12.9 137.3-16.7 205.9-8 67.6 8.6 125.9 33.9 179.7 52.6 335.2 116.6 538.6-59.1 574.2-93.5"></path>\n        <path d="M2452.7-83.7c1.8-1.9 2.8-2.9 2.8-2.9"></path>\n      </g>\n      <circle cx="2089.1" cy="52" r="7.9" fill="none" stroke="#FFF" stroke-miterlimit="10" stroke-opacity=".15" stroke-width="12"></circle>\n      <circle cx="2089.1" cy="52" r="4.5" fill="none" stroke="#FFF" stroke-miterlimit="10" stroke-width="5"></circle>\n      <circle cx="1400.1" cy="-5" r="7.9" fill="none" stroke="#FFF" stroke-miterlimit="10" stroke-opacity=".15" stroke-width="12"></circle>\n      <circle cx="1400.1" cy="-5" r="4.5" fill="none" stroke="#FFF" stroke-miterlimit="10" stroke-width="5"></circle>\n      <g fill="none" stroke="#FFF" stroke-miterlimit="10">\n        <circle cx="1251.1" cy="79" r="7.9" stroke-opacity=".15" stroke-width="12"></circle>\n        <circle cx="1251.1" cy="79" r="4.5" stroke-width="5"></circle>\n      </g>\n    </svg>\n  </figure>\n</div>\n<div class="section" style="padding:96px 0 32px 0">\n  <div class="wojo-grid">\n    <div class="row align-center">\n      <div class="columns screen-50 tablet-80 phone-100 mobile-100 content-center">\n        What we do?\n        <h3 class="wojo semi text">Fast and easy design</h3>\n      </div>\n    </div>\n  </div>\n</div>\n<div class="section" style="padding:32px 0">\n  <div class="wojo-grid">\n    <div class="row screen-block-2 tablet-block-2 mobile-block-1 phone-block-1 double-gutters">\n      <div class="column">\n        <div class="wojo basic icon message" data-weditable="true">\n          <figure class="wojo basic small image margin-right">\n            <img src="[SITEURL]/uploads/builder/icon9.svg" alt="Email us">\n          </figure>\n          <div class="content half-left-padding">\n            <h5>Responsive</h5>\n            <p>CMS Pro is an incredibly beautiful, fully responsive, and mobile-first projects on the web.</p>\n            <a href="#">Explore now <i class="icon middle chevron right" data-type="icon"></i></a>\n          </div>\n        </div>\n      </div>\n      <div class="column">\n        <div class="wojo basic icon message" data-weditable="true">\n          <figure class="wojo basic small image margin-right">\n            <img src="[SITEURL]/uploads/builder/icon3.svg" alt="Email us">\n          </figure>\n          <div class="content half-left-padding">\n            <h5>Customizable</h5>\n            <p>CMS Pro can be easily customized with its cutting-edge components and features.</p>\n            <a href="#">Explore now <i class="icon middle chevron right" data-type="icon"></i></a>\n          </div>\n        </div>\n      </div>\n      <div class="column">\n        <div class="wojo basic icon message" data-weditable="true">\n          <figure class="wojo basic small image margin-right">\n            <img src="[SITEURL]/uploads/builder/icon5.svg" alt="Email us">\n          </figure>\n          <div class="content half-left-padding">\n            <h5>Premium</h5>\n            <p>We want to answer all of your queries. Get in touch and we\'ll get back to you as soon as we can.</p>\n            <a href="#">Explore now <i class="icon middle chevron right" data-type="icon"></i></a>\n          </div>\n        </div>\n      </div>\n      <div class="column">\n        <div class="wojo basic icon message" data-weditable="true">\n          <figure class="wojo basic small image margin-right">\n            <img src="[SITEURL]/uploads/builder/icon2.svg" alt="Email us">\n          </figure>\n          <div class="content half-left-padding">\n            <h5>Documentation </h5>\n            <p>Every component and plugin is well documented with live examples.</p>\n            <a href="#">Explore now <i class="icon middle chevron right" data-type="icon"></i></a>\n          </div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n<div class="section" style="padding:32px 0">\n  <div class="wojo-grid">\n    <div class="row">\n      <div class="columns screen-100 tablet-100 mobile-100 phone-100 content-center">\n        <div data-weditable="true">\n          <span class="wojo positive label half-bottom-margin">Our Team</span>\n          <h2 class="wojo primary text">Trust the <span class="wojo semi text">professionals</span>\n          </h2>\n          <p>Our top professionals are ready to help with your business.</p>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n<div class="section" style="padding:32px 0 128px 0">\n  <div class="wojo-grid">\n    <div class="row">\n      <div class="columns"> %%carousel/team|plugin|3|36%% </div>\n    </div>\n  </div>\n</div>\n<div class="section" style="padding:128px 0;background-color: #f8f9fa">\n  <div class="wojo-grid">\n    <div class="row double-vertical-gutters align-center">\n      <div class="columns screen-60 tablet-80 mobile-100 phone-100 content-center">\n        <div data-weditable="true" class="">\n          <span class="wojo positive label half-bottom-margin">news</span>\n          <h2 class="wojo primary text">Read our <span class="wojo semi text">news &amp; blogs</span>\n          </h2>\n          <p>Our duty towards you is to share our experience we\'re reaching in our work path with you.</p>\n        </div>\n      </div>\n      <div class="columns screen-100 tablet-100 mobile-100 phone-100"> %%blog/carousel|plugin|0|37%% </div>\n    </div>\n  </div>\n</div>', '""', 'builder,mistaken,idea,denouncing,pleasure,praising,pain,give,complete,account,system,expound,actual,teachings,explorer,truth,master,human,happiness', 'Cms pro is a web content management system made for the peoples who don&#39;t have much technical knowledge of HTML or PHP but know how to use a simple notepad with computer keyboard', '2014-01-27 13:11:36', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('2', 'A few words about us', 'what-is-cms-pro', 'We are the only professional consulting firm backed by a business association.', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section" style="background-color: #f8fafd;background-image: url([SITEURL]/uploads/builder/shapes_1_bg.svg);height:100vh;background-repeat: no-repeat;background-size: cover;background-position: top center;">\n  <div class="wojo-grid">\n    <div class="row gutters align-middle align-center" style="min-height:100vh">\n      <div class="columns screen-40 tablet-80 mobile-100 phone-100 content-center">\n        <div data-weditable="true">\n          <h1 class="wojo primary semi huge text">Make Cms Pro work for you</h1>\n          <p class="wojo thin medium text margin-bottom">Building brands people can\'t live without is how our clients grow.</p>\n          <a href="#next_section" data-scroll="true" data-offset="140" data-type="button" class="wojo relaxed big rounded primary button">Get Started</a>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n<div id="next_section" class="section" style="padding:80px 0;">\n  <div class="wojo-grid">\n    <div class="row gutters">\n      <div class="columns screen-40 tablet-50 mobile-100 phone-100">\n        <div data-weditable="true">\n          <span class="wojo positive label half-bottom-margin">Opportunity</span>\n          <h2 class="wojo primary text">Our core <span class="wojo demi text">values</span></h2>\n          <p class="margin-bottom">At CMS Pro, we don\'t just accept difference—we celebrate it, we support it, and we thrive on it for the benefit of our employees, our products, and our community.</p>\n          <div class="row horizontal-gutters">\n            <div class="columns">\n              <div class="wojo relaxed flex list align-middle">\n                <div class="item">\n                  <div class="content shrink"><span class="wojo small negative circular icon button" data-type="button"><i class="icon heart"></i></span></div>\n                  <div class="content half-left-padding">Empathy</div>\n                </div>\n                <div class="item">\n                  <div class="content shrink"><span class="wojo small primary circular icon button" data-type="button"><i class="icon smile"></i></span></div>\n                  <div class="content half-left-padding">Courtesy</div>\n                </div>\n                <div class="item">\n                  <div class="content shrink"><span class="wojo small positive circular icon button" data-type="button"><i class="icon maple leaf"></i></span></div>\n                  <div class="content half-left-padding">Thriving</div>\n                </div>\n              </div>\n            </div>\n            <div class="columns">\n              <div class="wojo relaxed flex list align-middle">\n                <div class="item">\n                  <div class="content shrink"><span class="wojo small negative circular icon button" data-type="button"><i data-type="icon" class="icon feather"></i></span></div>\n                  <div class="content half-left-padding">Craftsmanship</div>\n                </div>\n                <div class="item">\n                  <div class="content shrink"><span class="wojo small primary circular icon button" data-type="button"><i class="icon tennis ball"></i></span></div>\n                  <div class="content half-left-padding">Playfulness</div>\n                </div>\n                <div class="item">\n                  <div class="content shrink"><span class="wojo small positive circular icon button" data-type="button"><i class="icon storage"></i></span></div>\n                  <div class="content half-left-padding">Solidarity</div>\n                </div>\n              </div>\n            </div>\n          </div>\n        </div>\n      </div>\n      <div class="columns screen-60 tablet-50 mobile-100 phone-100">\n        <figure data-weditable="true" class="relative" style="background-image: url([SITEURL]/uploads/builder/shapes_6_bg.svg);background-repeat: no-repeat;background-size: auto;background-position: top center;padding:3em;">\n          <a href="http://vimeo.com/75976293" data-wbox="video" class="wojo huge white circular icon button centered lightbox" data-type="button">\n            <i class="icon medium play"></i>\n          </a>\n          <img src="[SITEURL]/uploads/builder/office-people.jpg" alt="In The Office">\n        </figure>\n      </div>\n    </div>\n  </div>\n</div>\n<div class="section" style="padding:0 32px 0 0;">\n  <div class="wojo-grid">\n    <div class="row">\n      <div class="columns">\n        <h2 class="wojo primary semi text content-center">Our Greatful Clients</h2>\n      </div>\n    </div>\n  </div>\n</div>\n<div class="section" style="padding: 100px 0px;">\n  <div class="wojo-grid">\n    <div class="row phone-block-1 mobile-block-1 tablet-block-2 screen-block-2 gutters">\n      <div class="column">\n        <div class="wojo basic icon message align-middle">\n          <div class="wojo basic image"><img src="[SITEURL]/uploads/images/partner2.png" alt="Frank\'s Co."></div>\n          <div class="content">\n            <div class="header">Frank\'s Co.</div>\n            www.franksco.comp\n            <p>We are open and transparent about the work we do and how we do it.</p>\n          </div>\n        </div>\n      </div>\n      <div class="column">\n        <div class="wojo basic icon message align-middle">\n          <div class="wojo basic image"><img src="[SITEURL]/uploads/images/partner3.png" alt="Retro Beats"></div>\n          <div class="content">\n            <div class="header">Retro Beats</div>\n            www.retrobeats.comp\n            <p>We commit to achieving demonstrable impact for our stakeholders.</p>\n          </div>\n        </div>\n      </div>\n      <div class="column">\n        <div class="wojo basic icon message align-middle">\n          <div class="wojo basic image"><img src="[SITEURL]/uploads/images/partner4.png" alt="Tourner"></div>\n          <div class="content">\n            <div class="header">Tourner</div>\n            www.tourner.comp\n            <p>We are awed by human resilience, and believe in the ability of all people to thrive.</p>\n          </div>\n        </div>\n      </div>\n      <div class="column">\n        <div class="wojo basic icon message align-middle">\n          <div class="wojo basic image"><img src="[SITEURL]/uploads/images/partner5.png" alt="Retro Press"></div>\n          <div class="content">\n            <div class="header">Retro Press</div>\n            www.retropress.comp\n            <p>We believe that all people have the right to live in peaceful communities.</p>\n          </div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>\n<div class="section" style="background-color:#f8f9fa; padding: 64px 0 0 0;">\n  <div class="wojo-grid">\n    <div class="row double-gutters">\n      <div class="columns screen-50 tablet-50 mobile-100 phone-100">\n        <div data-weditable="true">\n          <h4 class="wojo primary text">Progress Bars</h4>\n          <p>Cool looking progress bars</p>\n          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor ua labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat</p>\n          <div class="wojo teal mini progress" data-percent="35">\n            <div class="bar"></div>\n            <div class="label">35% Funded</div>\n          </div>\n          <div class="wojo violet mini progress" data-percent="92">\n            <div class="bar"></div>\n            <div class="label">32% Uploading</div>\n          </div>\n          <div class="wojo red mini progress" data-percent="60">\n            <div class="bar"></div>\n            <div class="label">60% Earned</div>\n          </div>\n          <div class="wojo yellow tiny progress" data-percent="81">\n            <div class="bar"></div>\n            <div class="label">81% To Go</div>\n          </div>\n          <div class="wojo green tiny progress" data-percent="100">\n            <div class="bar"></div>\n            <div class="label">100% Completed</div>\n          </div>\n        </div>\n      </div>\n      <div class="columns screen-50 tablet-50 mobile-100 phone-100">\n        <h4 class="wojo primary text">Youtube Player</h4>\n        <p>Crete unlimited youtube players with built in CMS Pro yplayer plugin</p>\n        %%yplayer/vertical|plugin|2|19%%</div>\n    </div>\n  </div>\n</div>\n<div class="section relative" style="background-color: #f8f9fa ;padding:32px 0 164px 0">\n  <div class="wojo-grid">\n    <div class="row gutters align-center">\n      <div class="columns screen-40 tablet-60 mobile-100 phone-100">\n        <div class="content-center" data-weditable="true">\n          <div class="margin-bottom">\n            <span class="wojo white icon passive circular button" data-type="button"><i class="icon big wojologo alt"></i></span>\n          </div>\n          <div class="margin-bottom">\n            <h2 class="wojo primary text">\n              <span class="wojo semi text">Ready</span> to hire us? </h2>\n            <p>Cms Pro helps you managing your Enterprise identity.</p>\n          </div>\n          <a class="wojo primary inverted rounded wide button" data-type="button">Hire Our Team</a>\n        </div>\n      </div>\n    </div>\n  </div>\n  <figure class="absolute" style="bottom:0;left:0;width:100%">\n    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 150 512 200" width="100%" height="200px">\n      <path fill="#fff" d="M38.6 264.9S111.8 196 188 236.5c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 121.8 34 121.8 34v107l-521.4.3V264.9z" opacity=".4"></path>\n      <path fill="#fffefe" d="M-72 267s70.2-69.7 146.3-29.1c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 119.4 32.4 119.4 32.4v108.8l-516 .4V267z" opacity=".4"></path>\n      <path fill="#fff" d="M-72 302s94.3-90.3 204-54.7c139.7 45.4 190 12.3 250.3-9.5C472.9 205.1 560 311 560 311v74H-72v-83z"></path>\n    </svg>\n  </figure>\n</div>', '', '', '', '2014-01-28 13:11:36', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('3', 'Our Contact Info', 'our-contact-info', 'Got a question?', '1', 'normal', '0', '0', '', '1', '', 'Public', '<div class="section" style="padding:60px 0">\r\n  <div class="row half-gutters">\r\n    <div class="columns screen-25 tablet-50 mobile-100 phone-100">\r\n      <div class="content-center padding" data-weditable="true">\r\n        <figure class="wojo basic small image half-bottom-margin">\r\n          <img src="[SITEURL]/uploads/builder/icon8.svg" alt="Address">\r\n        </figure>\r\n        <h6>Address</h6>\r\n        <p class="wojo small text">153 Toronto Plaza, 09514</p>\r\n      </div>\r\n    </div>\r\n    <div class="columns screen-25 tablet-50 mobile-100 phone-100 relative divider">\r\n      <div class="content-center padding" data-weditable="true">\r\n        <figure class="wojo basic small image half-bottom-margin">\r\n          <img src="[SITEURL]/uploads/builder/icon15.svg" alt="Email">\r\n        </figure>\r\n        <h6>Email</h6>\r\n        <p class="wojo small text">support@wojoscripts.com</p>\r\n      </div>\r\n    </div>\r\n    <div class="columns screen-25 tablet-50 mobile-100 phone-100 relative divider">\r\n      <div class="content-center padding" data-weditable="true">\r\n        <figure class="wojo basic small image half-bottom-margin">\r\n          <img src="[SITEURL]/uploads/builder/icon16.svg" alt="Phone Number">\r\n        </figure>\r\n        <h6>Phone Number</h6>\r\n        <p class="wojo small text">+1 (416) 109-9222</p>\r\n      </div>\r\n    </div>\r\n    <div class="columns screen-25 tablet-50 mobile-100 phone-100 relative divider">\r\n      <div class="content-center padding" data-weditable="true">\r\n        <figure class="wojo basic small image half-bottom-margin">\r\n          <img src="[SITEURL]/uploads/builder/icon17.svg" alt="Fax">\r\n        </figure>\r\n        <h6>Fax</h6>\r\n        <p class="wojo small text">+1 (416) 109-9223</p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="section" style="padding:32px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row align-center">\r\n      <div class="columns screen-60 tablet-80 mobile-100 phone-100"> %%contact|plugin|1|5%% </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="section" style="padding:64px 0 0 0">\r\n  <div class="row gutters">\r\n    <div class="columns"> %%gmaps/head-office|plugin|1|20%% </div>\r\n  </div>\r\n</div>', '', '', '', '2010-07-23 08:11:55', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('5', 'Demo Gallery Page', 'demo-gallery-page', 'Responsive fluid gallery...', '0', 'normal', '0', '0', '', '1', '', 'Public', '', '', '', '', '2010-07-23 08:11:55', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('7', 'Middle Column', 'middle-column', 'Featuring middle column only layout', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section">\r\n  <div class="row">\r\n    <div class="columns"> %%slider/master|plugin|1|8%% </div>\r\n  </div>\r\n</div>\r\n<div class="section" style="padding:64px">\r\n  <div class="wojo-grid">\r\n    <div class="row align-center">\r\n      <div class="columns screen-70 tablet-100 mobile-100 phone-100">\r\n        <h2 class="wojo center aligned header">Page Without Sidebar Centered</h2>\r\n        <p class="sub header">- Built with CMS Pro /Pagebuilder/ -</p>\r\n        <div class="wojo space divider"></div>\r\n        <p>Perhaps you are a new entrepreneur about to launch a business or innovation you have been dreaming about for years. Or maybe you have an established business and things are going well, or maybe even too well. In both instances you are going to need capital - the \'oxygen\' that every business needs to grow and prosper.</p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="section" style="background-color: #f8f9fa;padding:128px 0 96px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters">\r\n      <div class="columns mobile-50 phone-100">\r\n        <div class="wojo photo basic card" data-weditable="true">\r\n          <img class="wojo basic image" src="[SITEURL]/uploads/builder/card-image-small-1.jpg" alt="Image Description">\r\n          <div class="content">\r\n            <small class="wojo secondary text">March 20, 2018</small>\r\n            <h6>\r\n              <a href="#" class="black">Remote workers, here\'s how to dodge distractions</a>\r\n            </h6>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div class="columns mobile-50 phone-100">\r\n        <div class="wojo photo basic card" data-weditable="true">\r\n          <img class="wojo basic image" src="[SITEURL]/uploads/builder/card-image-small-2.jpg" alt="Image Description">\r\n          <div class="content">\r\n            <small class="wojo secondary text">April 29, 2018</small>\r\n            <h6>\r\n              <a href="#" class="black">How to make trust your competitive advantage</a>\r\n            </h6>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div class="columns mobile-50 phone-100">\r\n        <div class="wojo photo basic card" data-weditable="true">\r\n          <img class="wojo basic image" src="[SITEURL]/uploads/builder/card-image-small-3.jpg" alt="Image Description">\r\n          <div class="content">\r\n            <small class="wojo secondary text">March 20, 2018</small>\r\n            <h6>\r\n              <a href="#" class="black">How to change careers or start a home-based business?</a>\r\n            </h6>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="section" style="padding:64px">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters">\r\n      <div class="columns screen-40 tablet-50 mobile-100 phone-100">\r\n        <h5>Bringing the culture to everyone.</h5>\r\n        <p>We know the power of sharing is real, and we want to create an opportunity for everyone to try CMS Pro and explore how transformative open communication can be. Now you can have a team of one or two designers and unlimited spectators (think PMs, management, marketing, etc.) share work and explore the design process earlier</p>\r\n        <p>Capital that is generated internally through positive cash flow from business operations (e.g., selling stuff), or from external funding sources. The new entrepreneur is limited to only one option - external funding <a href="#">sources</a>.</p>\r\n      </div>\r\n      <div class="columns screen-60 tablet-50 mobile-100 phone-100">\r\n        <h4>Latest Twitts</h4>\r\n        <p>- This plugin is included in CMS Pro core -</p>\r\n        %%twitts|plugin|0|15%% </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="section relative" style="background-image: linear-gradient(150deg, #2d1582 0%, #19a0ff 100%);padding:128px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row align-middle">\r\n      <div class="columns">\r\n        <div class="wojo white text content-center" data-weditable="true">\r\n          <h3 class="wojo semi warning text half-bottom-margin">Ready to get started?</h3>\r\n          <p class="wojo white medium thin text">\r\n            <span class="wojo semi text">CMS Pro</span> – the simplest and fastest way to build sites. </p>\r\n          <a class="wojo white button" href="#" data-type="button">Start Free Trial</a>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <figure class="absolute mobile-hide phone-hide" style="top:0;left:0;max-width: 13rem;">\r\n    <svg xmlns="http://www.w3.org/2000/svg" viewBox="168 -117.3 384.1 283.3">\r\n      <circle cx="361.8" cy="140.5" r="14.4" fill="none" stroke="#00DFFC" stroke-miterlimit="10" stroke-width="3"/>\r\n      <circle cx="370.8" cy="102.7" r="5.4" fill="none" stroke="#00DFFC" stroke-miterlimit="10" stroke-width="2"/>\r\n      <path fill="#00DFFC" d="M168 4.6v-121.9h205.9C398.5-80 354.3-43.4 325-26.4c-26.8 15.5-53.5 29.6-82.8 39.9-15.2 5.4-31.6 8.8-47.5 5.9-12.1-2.2-20.4-7.6-26.7-14.8z"/>\r\n      <path fill="#FFC107" d="M208.9-65.6c-13.7 24.7-22.3 55.9-14.8 83.9 7.5 28.2 36.2 56.4 66.7 40.8 35.1-18 43.6-64.2 82.1-79.3 24.1-9.4 45.7 1.1 63 18.9 11 11.4 19.3 25.2 26.5 39.4 16.5 32.6 49.9 60.7 86.9 35.6 53.3-36 28-118.9 17.8-169.5-1.4-7.1-2.9-14.3-4.3-21.5H227.4c.3 18.8-9.1 34.7-18.5 51.7z"/>\r\n    </svg>\r\n  </figure>\r\n  <figure class="absolute mobile-hide phone-hide" style="bottom:0;right:0;max-width: 13rem;">\r\n    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 350 280">\r\n      <path fill="#55C8E7" d="M226.1 110.7c7.3 20.5 16.6 50.9 8 71.1-6.6 15.6-19 38.5-34.6 35.4-13.1-16.9-39.8-52.3-57-66.2-14.2-11.4-36-23.3-49.8-35.7C58.6 84.4 45.4 42.5 26.4.6 61 4.1 95.2 10.6 129 20.1c21.8 6.2 44.3 14.2 60.7 29.5 18.4 15.7 28.8 38.2 36.4 61.1z"/>\r\n      <path fill="#FFF" d="M28.5 2.4c40.3 19.3 77.3 44.3 109 75.3 34.4 34.1 57.5 74.1 83.2 114.1.4 1.2 2.7 0 1.6-1.2C178.2 107.9 116.1 38.1 28.5 1.2c0 .1-.8.5 0 1.2zm23.4 52.7c27.8-1.6 52-.4 76.2 14.9 1.6 1.2 3.1-1.6 1.6-2.7C108.3 53.2 77 44.5 51.6 53.2c-.8.7-.8 1.9.3 1.9zm43.4 46.2c13.3 0 26.2-.4 39.1 2.7 11.7 2.7 23.1 7 34.8 11.3 1.6.4 2-1.6.4-2.7-23.5-8.6-50.1-18.9-75-12.5.4.8.4 1.2.7 1.2zm51.6 41.2c9 .4 18.8 1.6 27.8 3.1 10.6 2 17.6 7.4 24.6 15.6 1.2 1.2 3.1-.4 2-2-6.3-7.4-12.9-12.9-23.1-15.6-10.2-2-21.1-2.7-31.7-2 0-.3 0 .9.4.9zM109 22.9c.4 10.2 5.9 20.4 9.8 29.8 2.7 5.9 4.7 16 12.5 17.2.4 0 1.2-.4.4-1.2-12.9-10.6-13.3-31.8-21.9-45.9 0-.7-.8-.7-.8.1zM150.8 35c2.7 27.9 11.3 55.3 21.1 81.2.4 1.2 2.7.4 2-.4C163.7 89.6 158.3 62.5 152 35c-.4-.4-1.2-.4-1.2 0zm45 58.9c0 23.6 5.9 47.8 9.8 71 .4 1.6 3.1 1.2 2.7-.4-4.3-23.6-7.4-46.6-11.3-70.6-.1-1.2-1.2-1.2-1.2 0z"/>\r\n      <path fill="none" stroke="#55C8E7" stroke-miterlimit="10" stroke-width="3" d="M318.9 48.9c-.1-1.9-1-3.6-2.5-4.7-2.3-1.7-6.1-2.2-11.4 5.5-8.4 12.2-.5 11.7.6 11.6h.3l7.3-.5c3.5-.2 6.2-3.3 6-6.8l-.3-5.1z"/>\r\n      <path fill="#FDC113" d="M196 180.5c-11.3-15.7-29.2-24.9-48.6-24.7-50.2.5-69.3 46.9-76.2 89.9-1.8 11.5-3.1 23.3-4.6 34.9h146.2c-5.4-32.9 3.8-71.4-16.8-100.1z"/>\r\n      <linearGradient id="a" x1="2751.534" x2="3158.839" y1="757.064" y2="757.064" gradientTransform="rotate(142.902 1630.174 56.262)" gradientUnits="userSpaceOnUse">\r\n        <stop offset="0" stop-color="#4f75ba"/>\r\n        <stop offset="1" stop-color="#332c7d"/>\r\n      </linearGradient>\r\n      <path fill="url(#a)" d="M348.8 257.6c0-.9.1-1.9.1-2.9 2.9-75.4-83.3-119.8-143.8-75.1-28.3 20.9-68.9 43.3-123 55-44.9 9.7-69.1 27.6-81.9 46h350c-1.3-7.1-1.9-14.7-1.4-23z"/>\r\n    </svg>\r\n  </figure>\r\n</div>\r\n', '', '', 'Aliquam vitae metus non elit laoreet varius. Pellentesque et enim lorem. Suspendisse potenti. Nam ut iaculis lectus. Ut et leo odio. In euismod lobortis nisi, eu placerat nisi laoreet a.\nCras lobortis lobortis elit, at pellentesque erat vulputate ac. Phasellus in sapien non elit semper pellentesque ut a turpis. Quisque mollis auctor feugiat. Fusce a nisi diam, eu dapibus nibh.Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Etiam a justo libero, aliquam auctor felis. Nulla a odio ut magna ultrices vestibulum.\nInteger urna magna, euismod sed pharetra eget, ornare in dolor. Etiam bibendum mi ut nisi facilisis lobortis. Phasellus turpis orci, interdum adipiscing aliquam ut, convallis volutpat tellus. Nunc massa nunc, dapibus eget scelerisque ac, eleifend eget ligula. Maecenas accumsan tortor in quam adipiscing hendrerit. Donec ac risus nec est molestie malesuada ac id risus. In hac habitasse platea dictumst. In quam dui, blandit id interdum id, facilisis a leo.\nNullam fringilla quam pharetra enim interdum accumsan. Phasellus nec euismod quam. Donec tempor accumsan posuere. Phasellus ac metus orci, ac venenatis magna. Suspendisse sit amet odio at enim ultricies pellentesque eget ac risus. Vestibulum eleifend odio ut tellus faucibus malesuada feugiat nisi rhoncus. Proin nec sem ut augue placerat blandit ut ut orci. Cras aliquet venenatis enim, quis rutrum urna sollicitudin vel.', '2010-07-23 08:40:19', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('8', 'Right Sidebar', 'right-sidebar', 'Right sidebar demo page', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section" style="padding:0;">\r\n  <div class="row">\r\n    <div class="columns screen-50 tablet-50 mobile-100 phone-100 relative wojo primary gradient">\r\n      <div class="double-padding">\r\n        <div class="row align-right">\r\n          <div class="columns screen-50 tablet-100 mobile-100 phone-100">\r\n            <div data-weditable="true">\r\n              <h2 class="wojo white text">Have a project?</h2>\r\n              <p class="wojo white dimmed text">Hire us. Our top professionals are ready to help with your business.</p>\r\n              <a class="wojo wide white button" data-type="button" href="#">Hire Us</a>\r\n            </div>\r\n          </div>\r\n        </div>\r\n        <figure class="absolute phone-hide" style="bottom:0;right:3em;width:250px;">\r\n          <img data-weditable="true" src="[SITEURL]/uploads/builder/building-site.svg" alt="Building Site">\r\n        </figure>\r\n      </div>\r\n    </div>\r\n    <div class="screen-50 tablet-50 mobile-100 phone-100" style="background-image: linear-gradient(25deg, #ffc107 30%, #de4437 100%);background-repeat: repeat-x;">\r\n      <div class="row">\r\n        <div class="columns relative screen-50 tablet-100 mobile-100 phone-100">\r\n          <div class="double-padding">\r\n            <div data-weditable="true">\r\n              <h2 class="wojo white text">Get in touch</h2>\r\n              <p class="wojo white dimmed text">If you would like to find out more about how we can help you, please give us a call or drop us an email..</p>\r\n              <a class="wojo wide white button" data-type="button" href="#">Contact Us</a>\r\n            </div>\r\n            <figure class="absolute phone-hide" style="bottom:0;right:0;width:250px;">\r\n              <img data-weditable="true" src="[SITEURL]/uploads/builder/pushing-boundaries.svg" alt="Pushing Boundaries">\r\n            </figure>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="section" style="padding:96px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row double-gutters">\r\n      <div class="columns screen-70 tablet-60 mobile-100 phone-100">\r\n        <h2 class="wojo primary text">Page With Right Sidebar</h2>\r\n        <p>- Built with CMS Pro /Pagebuilder/ -</p>\r\n        <div class="wojo divider"></div>\r\n        <p>Perhaps you are a new entrepreneur about to launch a business or innovation you have been dreaming about for years. Or maybe you have an established business and things are going well, or maybe even too well. In both instances you are going to need capital - the \'oxygen\' that every business needs to grow and prosper. </p>\r\n        <figure class="vertical-margin" data-weditable="true">\r\n          <img alt="" src="[SITEURL]/uploads/builder/analysis-illustration.svg" class="wojo basic image">\r\n        </figure>\r\n        <div class="content-center bottom-margin"><small>We know how to help you</small></div>\r\n        <p>So now you are writing your first business plan or touching up the old one in anticipation of raising capital. Capital can only come into a business in one of two ways. Capital that is generated internally through positive cash flow from business operations (e.g., selling stuff), or from external funding sources. The new entrepreneur is limited to only one option - external funding sources. </p>\r\n        <div class="wojo divider"></div>\r\n        <h4>Contact Us</h4>\r\n        <p>- Place contact plugin on any page -</p>\r\n        <div class="row gutters">\r\n          <div class="columns">%%contact|plugin|1|0%% </div>\r\n        </div>\r\n      </div>\r\n      <div class="columns screen-30 tablet-40 mobile-100 phone-100">\r\n        <div class="wojo plugin attached segment" data-weditabe="true">\r\n          <a href="[SITEURL]/uploads/builder/in-the-office.svg" class="lightbox"><img alt="" src="[SITEURL]/uploads/builder/in-the-office.svg"></a>\r\n          <div class="content-center vertical-margin">\r\n            <p>Our business solutions are designed around the real needs of businesses, our information resources, tools and... </p>\r\n            <p><a class="wojo secondary button" href="[SITEURL]/page/what-is-cms-pro/">learn more</a>\r\n            </p>\r\n          </div>\r\n        </div>\r\n        %%adblock/wojo-advert|plugin|1|21%%\r\n        %%blog/latest|plugin|0|27%% </div>\r\n    </div>\r\n  </div>\r\n</div>', '', '', '', '2010-08-10 10:06:58', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('9', 'Members Only', 'members-only', '', '0', 'normal', '0', '0', '', '1', '', 'Registered', '<div class="section" style="padding:64px">\r\n  <div class="wojo-grid">\r\n    <div class="row align-center">\r\n      <div class="columns screen-70 tablet-100 mobile-100 phone-100">\r\n        <h2>Members Only Page</h2>\r\n        <p>- Limited  Access-</p>\r\n        <div class="wojo divider"></div>\r\n        <p>Perhaps you are a new entrepreneur about to launch a business or innovation you have been dreaming about for years. Or maybe you have an established business and things are going well, or maybe even too well. In both instances you are going to need capital - the \'oxygen\' that every business needs to grow and prosper.</p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '', '', '', '2011-05-20 03:28:29', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('10', 'Membership Only', 'membership-only', '', '0', 'normal', '2,4', '0', '', '1', '', 'Membership', '<div class="section" style="padding:64px">\r\n  <div class="wojo-grid">\r\n    <div class="row align-center">\r\n      <div class="columns screen-70 tablet-100 mobile-100 phone-100">\r\n        <h2>Members Only Page</h2>\r\n        <p>- Limited  Access-</p>\r\n        <div class="wojo divider"></div>\r\n        <p>Perhaps you are a new entrepreneur about to launch a business or innovation you have been dreaming about for years. Or maybe you have an established business and things are going well, or maybe even too well. In both instances you are going to need capital - the \'oxygen\' that every business needs to grow and prosper.</p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '', '', '', '2011-05-20 03:28:48', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('11', 'Event Manager Demo', 'event-calendar-demo', '', '0', 'normal', '0', '0', '', '1', '', 'Public', '<div class="section" style="padding:64px 0"> \r\n<div class="wojo-grid">\r\n  <div class="row gutters">\r\n    <div class="columns">\r\n       %%events|module|0|4%% \r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n', '', 'Event calendar', 'Event Manager', '2012-01-02 13:05:43', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('12', 'Page With Comments', 'page-with-comments', 'Comments Demo', '0', 'normal', '0', '1', '', '1', '', 'Public', '<div class="section" style="padding:64px 0 32px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row">\r\n      <div class="columns">\r\n        <h2>Page With Comments</h2>\r\n        <p>- Built with CMS Pro /Pagebuilder/ -</p>\r\n        <p>Perhaps you are a new entrepreneur about to launch a business or innovation you have been dreaming about for years. Or maybe you have an established business and things are going well, or maybe even too well. In both instances you are going to need capital - the \'oxygen\' that every business needs to grow and prosper.</p>\r\n        <p>So now you are writing your first business plan or touching up the old one in anticipation of raising capital. Capital can only come into a business in one of two ways. Capital that is generated internally through positive cash flow from business operations (e.g., selling stuff), or from external funding sources. The new entrepreneur is limited to only one option - external funding sources. </p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="section" style="padding:32px 0 64px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters">\r\n      <div class="columns">\r\n        <figure data-weditable="true"> \r\n        <img alt="" src="[SITEURL]/uploads/images/demoimage2.jpg" class="wojo basic rounded image">\r\n        </figure>\r\n      </div>\r\n      <div class="columns">\r\n        <figure data-weditable="true"> \r\n        <img alt="" src="[SITEURL]/uploads/images/demoimage2.jpg" class="wojo basic rounded image">\r\n        </figure>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '', '', '', '2012-01-02 13:08:46', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('13', 'Left Sidebar', 'left-sidebar', 'Left sidebar demo page', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section" style="background-image:url([SITEURL]/uploads/builder/header_1_bg.png);background-repeat: no-repeat;background-size: auto;background-position:center top;padding:160px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters align-center">\r\n      <div class="columns screen-100 tablet-100 mobile-100 phone-100">\r\n        <h1 class="wojo white huge text content-center" data-weditable="true">Say <span class="wojo semi text">Hello </span> to all businesses</h1>\r\n      </div>\r\n      <div class="columns screen-50 tablet-100 mobile-100 phone-100">\r\n        <p class="wojo white thin medium dimmed text content-center half-bottom-margin" data-weditable="true">Achieve virtually any look and layout from within the one template. Covert more visitors, and win more business with CMS pro.</p>\r\n      </div>\r\n    </div>\r\n    <div class="row gutters align-center">\r\n      <div class="columns screen-40 tablet-100 mobile-100 phone-100">\r\n       <form id="wojo_form_newsletter" name="wojo_form_newsletter" method="post">\r\n        <div class="wojo fluid large right action input">\r\n          <input type="email" name="email" placeholder="Email">\r\n          <button type="button" data-url="plugins_/newsletter" data-hide="true" data-action="processNewsletter" name="dosubmit" class="wojo simple icon button"><i class="icon medium primary paper plane"></i></button>\r\n        </div>\r\n         </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n\r\n\r\n<div class="section" style="padding:96px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row double-gutters">\r\n      <div class="columns screen-30 tablet-40 mobile-50 phone-100">\r\n        <div class="wojo plugin attached segment" data-weditabe="true">\r\n          <a href="[SITEURL]/uploads/images/services1.jpg" class="lightbox"><img alt="" src="[SITEURL]/uploads/images/services1.jpg"></a>\r\n          <div class="content-center vertical-margin">\r\n            <p>Our business solutions are designed around the real needs of businesses, our information resources, tools and... </p>\r\n            <p><a class="wojo secondary button" href="[SITEURL]/page/what-is-cms-pro/">learn more</a></p>\r\n          </div>\r\n        </div>\r\n        %%upevent|plugin|0|16%%\r\n        %%adblock/wojo-advert|plugin|1|21%% </div>\r\n      <div class="columns screen-70 tablet-60 mobile-50 phone-100">\r\n        <h2 class="wojo primary text">Page With Left Sidebar</h2>\r\n        <p>- Built with CMS Pro /Pagebuilder/ -</p>\r\n        <div class="wojo divider"></div>\r\n        <p>Perhaps you are a new entrepreneur about to launch a business or innovation you have been dreaming about for years. Or maybe you have an established business and things are going well, or maybe even too well. In both instances you are going to need capital - the \'oxygen\' that every business needs to grow and prosper.</p>\r\n        <figure class="vertical-margin" data-weditable="true">\r\n          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 614.8 747.7">\r\n            <path class="wojo primary fill" opacity=".05" d="M186.9,138.9C125.8,144.5,69.7,176,34.4,226c-54.3,76.8-67.1,204.7,193.8,371.7c411.5,263.5,387.9,6,382.7-30.6 c-0.5-3.6-0.9-7.2-1.3-10.7l-22.1-241.9C576.8,198.3,474,112.7,357.7,123.3L186.9,138.9z"></path>\r\n            <g>\r\n              <defs>\r\n                <path id="a" d="M168.6,729.7L168.6,729.7c135.8,53,290.2-14.8,343.2-150.6l85-217.9C649.8,225.4,582,71,446.2,18l0,0 C310.4-34.9,156,32.8,103,168.6L18,386.5C-34.9,522.3,32.8,676.7,168.6,729.7z"></path>\r\n              </defs>\r\n              <clipPath id="b">\r\n                <use xlink:href="#a" style="overflow:visible;"></use>\r\n              </clipPath>\r\n              <g style="clip-path:url(#b);">\r\n                <!-- Apply your (615px width to 750px height) image here -->\r\n                <image style="overflow:visible;" width="100%" height="100%" xlink:href="[SITEURL]/uploads/builder/content_1.jpg" transform="matrix(1 0 0 1 -0.1366 -0.9532)"></image>\r\n              </g>\r\n            </g>\r\n            <circle class="wojo negative fill" cx="126.3" cy="693.8" r="16.3"></circle>\r\n            <circle class="wojo positive fill" cx="132.9" cy="632.6" r="10.6"></circle>\r\n            <circle class="wojo primary fill" cx="77" cy="655.9" r="21.6"></circle>\r\n            <circle class="wojo warning fill" cx="38.8" cy="708.1" r="3.9"></circle>\r\n          </svg>\r\n        </figure>\r\n        <div class="content-center bottom-margin"><small>We know how to help you</small></div>\r\n        <p>So now you are writing your first business plan or touching up the old one in anticipation of raising capital. Capital can only come into a business in one of two ways. Capital that is generated internally through positive cash flow from business operations (e.g., selling stuff), or from external funding sources. The new entrepreneur is limited to only one option - external funding sources. </p>\r\n        <div class="wojo divider"></div>\r\n        <h4>Poll &amp; Donations</h4>\r\n        <p>- Both of these plugins are included -</p>\r\n        <div class="row gutters">\r\n          <div class="columns tablet-100 mobile-100 phone-100"> %%donation/paypal|plugin|1|13%% </div>\r\n          <div class="columns tablet-100 mobile-100 phone-100"> %%poll/install|plugin|1|3%% </div>\r\n        </div>\r\n        <h4>All Our Clients</h4>\r\n        <p>- Built with carousel plugin -</p>\r\n        <div class="row gutters">\r\n          <div class="columns"> %%carousel/clients|plugin|2|26%% </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="section" style="background-color:#377dff;padding:48px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row align-middle">\r\n      <div class="columns">\r\n        <div class="wojo white text content-center" data-weditable="true">\r\n          <span class="wojo semi text">CMS Pro</span>\r\n          <span class="wojo thin text half-right-margin">– the simplest and fastest way to build sites.</span>\r\n          <a class="wojo white small button" href="#" data-type="button">Start Enterprise Trial</a>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '', 'testimonials,carousel,plugin', '\n\n\n\nCLIENTS &amp; TESTIMONIALS\n- Create unlimited carousels, with built in carousel plugin -\n\n%%carousel/testimonials|plugin|1|23%%\n\n\n', '2012-01-02 13:08:53', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('14', 'Video Slider', 'video-slider', '', '0', 'normal', '0', '0', '', '1', '', 'Public', '', '', '', '', '2012-01-02 13:09:08', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('15', 'Login Page', 'login', '', '1', 'login', '0', '0', '', '1', '', 'Public', '', '', '', '', '2014-04-27 10:11:36', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('16', 'User Registration', 'registration', '', '1', 'register', '0', '0', '', '1', '', 'Public', '', '', '', '', '2014-04-27 13:22:53', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('17', 'Account Acctivation', 'activate', '', '1', 'activate', '0', '0', '', '1', '', 'Public', '', '', '', '', '2014-04-28 01:08:29', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('18', 'User Dashboard', 'dashboard', '', '1', 'account', '0', '0', '', '1', '', 'Public', '', '', '', '', '2014-04-28 02:06:43', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('19', 'How can we help?', 'search', 'Search our site', '1', 'search', '0', '0', '', '1', '', 'Public', '', '', '', '', '2014-04-29 11:32:44', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('20', 'Sitemap', 'sitemap', '', '1', 'sitemap', '0', '0', '', '1', '', 'Public', '', '', '', '', '2014-05-08 05:00:53', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('21', 'Slider Manager', 'slider-manager', 'Responsive multiple sliders', '0', 'normal', '0', '0', '', '1', '', 'Public', '', '', '', '', '2014-05-27 05:02:13', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('22', 'Demo F.A.Q', 'demo-faq', 'Browse our help section', '0', 'normal', '0', '1', '', '0', '', 'Public', '<div class="section relative" style="background-image: linear-gradient(150deg, #2d1582 0%, #19a0ff 100%);background-repeat: repeat-x;padding:100px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters align-middle">\r\n      <div class="columns screen-40 tablet-50 mobile-100 phone-100">\r\n        <div data-weditable="true">\r\n          <h1 class="wojo primary semi huge white text"><span class="wojo warning text">Improve</span>\r\n            &amp; boost your services</h1>\r\n          <p class="wojo dimmed white text">We provide all kinds of services and will help your audience grow through all platforms..</p>\r\n        </div>\r\n      </div>\r\n      <div class="columns screen-60 tablet-50 mobile-100 phone-100">\r\n        <figure class="wojo basic big image" data-weditable="true"><img data-weditable="true" src="[SITEURL]/uploads/builder/teamwork-concept-illustration.svg" alt="teamwork concept"></figure>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <figure class="absolute" style="bottom:0">\r\n    <svg xmlns="http://www.w3.org/2000/svg" style="margin-bottom:-4px" viewBox="0 0 1920 140">\r\n      <path d="M960 92.9c-148.6.4-297.2-3.5-444.7-13.9-138.6-9.8-277.1-26.2-409-53.3C97.8 24 0 6.5 0 0v140l960-1.2 960 1.2V0c0 2.7-42.1 11.3-45.8 12.2-45.1 11-91.5 20.1-138.4 28.1-176.2 30.1-359.9 43.8-542.9 48.9-77.5 2.2-155.2 3.5-232.9 3.7z" class="wojo white fill"/>\r\n    </svg>\r\n  </figure>\r\n</div>\r\n\r\n<div class="section" style="padding:40px 0;">\r\n  <div class="wojo-grid">\r\n    <div class="row double-gutters">\r\n      <div class="columns screen-50 tablet-50 mobile-100 phone-100">\r\n        <div class="double-padding content-center" data-weditable="true">\r\n          <figure class="margin-bottom">\r\n            <img src="[SITEURL]/uploads/builder/pushing-boundaries.svg" alt="Pushing Boundaries">\r\n          </figure>\r\n          <h4>Using CMS Pro</h4>\r\n          <p>Find the answer to any question, from the basics all the way to advanced tips and tricks!</p>\r\n          <a class="wojo primary button" data-type="button" href="http://ckb.wojoscripts.com" target="_blank">Browse all article</a>\r\n        </div>\r\n      </div>\r\n      <div class="columns screen-50 tablet-50 mobile-100 phone-100 relative divider">\r\n        <div class="double-padding content-center" data-weditable="true">\r\n          <figure class="margin-bottom">\r\n            <img src="[SITEURL]/uploads/builder/building-site.svg" alt="Building Site">\r\n          </figure>\r\n          <h4>Workspace administration</h4>\r\n          <p>Want to learn more about setting up and managing your team? Look no further!</p>\r\n          <a class="wojo primary button" data-type="button" href="[SITEURL]/admin/">Browse all article</a>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<section style="padding:0 0 64px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters">\r\n      <div class="columns"> %%faq|module|24|0%% </div>\r\n    </div>\r\n  </div>\r\n</section>\r\n<div class="section" style="padding:60px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row double-gutters">\r\n      <div class="columns screen-50 tablet-50 mobile-100 phone-100">\r\n        <div class="wojo basic icon message" data-weditable="true">\r\n          <figure class="wojo basic small image margin-right">\r\n            <img src="[SITEURL]/uploads/builder/icon15.svg" alt="Email us">\r\n          </figure>\r\n          <div class="content half-left-padding">\r\n            <h5>Can\'t find your answer?</h5>\r\n            <p>We want to answer all of your queries. Get in touch and we\'ll get back to you as soon as we can.</p>\r\n            <a href="#">Email us <i class="icon middle chevron right"></i></a>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div class="columns screen-50 tablet-50 mobile-100 phone-100 relative divider">\r\n        <div class="wojo basic icon message" data-weditable="true">\r\n          <figure class="wojo basic small image margin-right">\r\n            <img src="[SITEURL]/uploads/builder/icon4.svg" alt="Have a question">\r\n          </figure>\r\n          <div class="content half-left-padding">\r\n            <h5>Technical questions</h5>\r\n            <p>Have some technical questions? Hit us on community page or just say hello..</p>\r\n            <a href="#">Open ticket <i class="icon middle chevron right"></i></a>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '', '', '', '2014-06-02 09:06:27', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('23', 'Profile', 'profile', 'Public User Profile', '0', 'profile', '0', '0', '', '1', '', 'Public', '', '', '', '', '2014-11-14 08:27:25', '1', 'Web Master', '1', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('24', 'Timeline Blog Demo', 'timeline-blog-demo', 'Timeline demo and Blog Manager', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section relative" style="background-color: #f8f9fa ;padding:64px 0 164px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters align-center">\r\n      <div class="columns screen-40 tablet-60 mobile-100 phone-100">\r\n        <div class="content-center" data-weditable="true">\r\n          <h2 class="wojo primary text">\r\n            <span class="wojo semi text">Timeline demo</span> and Blog Manager </h2>\r\n          <p>Create beautiful timeline in a matter of minutes.</p>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <figure class="absolute" style="bottom:0;left:0;width:100%">\r\n    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 150 512 200" width="100%" height="200px">\r\n      <path fill="#fff" d="M38.6 264.9S111.8 196 188 236.5c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 121.8 34 121.8 34v107l-521.4.3V264.9z" opacity=".4"/>\r\n      <path fill="#fffefe" d="M-72 267s70.2-69.7 146.3-29.1c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 119.4 32.4 119.4 32.4v108.8l-516 .4V267z" opacity=".4"/>\r\n      <path fill="#fff" d="M-72 302s94.3-90.3 204-54.7c139.7 45.4 190 12.3 250.3-9.5C472.9 205.1 560 311 560 311v74H-72v-83z"/>\r\n    </svg>\r\n  </figure>\r\n</div>\r\n<div class="section" style="padding:0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters">\r\n      <div class="columns"> %%timeline/blog_timeline|module|18|1%% </div>\r\n    </div>\r\n  </div>\r\n</div>', '', '', '', '2015-01-20 14:43:27', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('25', 'Timeline Event Demo', 'timeline-event-demo', 'Timeline demo and Event Manager', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section relative" style="background-color: #f8f9fa ;padding:64px 0 164px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters align-center">\r\n      <div class="columns screen-40 tablet-60 mobile-100 phone-100">\r\n        <div class="content-center" data-weditable="true">\r\n          <h2 class="wojo primary text">\r\n            <span class="wojo semi text">Timeline demo</span> and Event Manager </h2>\r\n          <p>This module is included in CMS pro base package.</p>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <figure class="absolute" style="bottom:0;left:0;width:100%">\r\n    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 150 512 200" width="100%" height="200px">\r\n      <path fill="#fff" d="M38.6 264.9S111.8 196 188 236.5c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 121.8 34 121.8 34v107l-521.4.3V264.9z" opacity=".4"/>\r\n      <path fill="#fffefe" d="M-72 267s70.2-69.7 146.3-29.1c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 119.4 32.4 119.4 32.4v108.8l-516 .4V267z" opacity=".4"/>\r\n      <path fill="#fff" d="M-72 302s94.3-90.3 204-54.7c139.7 45.4 190 12.3 250.3-9.5C472.9 205.1 560 311 560 311v74H-72v-83z"/>\r\n    </svg>\r\n  </figure>\r\n</div>\r\n<div class="section";padding:0">\r\n<div class="wojo-grid">\r\n  <div class="row gutters">\r\n    <div class="columns"> %%timeline/custom_timeline|module|19|2%% </div>\r\n  </div>\r\n</div>\r\n</div>', '', '', '', '2015-01-22 15:16:40', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('27', 'Timeline Portfolio Demo', 'timeline-portfolio-demo', 'Timeline demo and Portfolio Module', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section relative" style="background-color: #f8f9fa ;padding:64px 0 164px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters align-center">\r\n      <div class="columns screen-40 tablet-60 mobile-100 phone-100">\r\n        <div class="content-center" data-weditable="true">\r\n          <h2 class="wojo primary text">\r\n            <span class="wojo semi text">Timeline demo</span> and Portfolio Module </h2>\r\n          <p>Create beautiful timeline in a matter of minutes.</p>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <figure class="absolute" style="bottom:0;left:0;width:100%">\r\n    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 150 512 200" width="100%" height="200px">\r\n      <path fill="#fff" d="M38.6 264.9S111.8 196 188 236.5c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 121.8 34 121.8 34v107l-521.4.3V264.9z" opacity=".4"/>\r\n      <path fill="#fffefe" d="M-72 267s70.2-69.7 146.3-29.1c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 119.4 32.4 119.4 32.4v108.8l-516 .4V267z" opacity=".4"/>\r\n      <path fill="#fff" d="M-72 302s94.3-90.3 204-54.7c139.7 45.4 190 12.3 250.3-9.5C472.9 205.1 560 311 560 311v74H-72v-83z"/>\r\n    </svg>\r\n  </figure>\r\n</div>\r\n<div class="section" style="padding:0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters">\r\n      <div class="columns"> %%timeline/portfolio_timeline|module|21|4%% </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n', '', '', '', '2015-01-23 08:08:19', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('26', 'Timeline Rss Demo', 'timeline-rss-demo', 'Timeline demo and Rss Feed', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section relative" style="background-color: #f8f9fa ;padding:64px 0 164px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters align-center">\r\n      <div class="columns screen-40 tablet-60 mobile-100 phone-100">\r\n        <div class="content-center" data-weditable="true">\r\n          <h2 class="wojo primary text">\r\n            <span class="wojo semi text">Timeline demo</span> and custom RSS feed </h2>\r\n          <p>This module is included in CMS pro base package.</p>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <figure class="absolute" style="bottom:0;left:0;width:100%">\r\n    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 150 512 200" width="100%" height="200px">\r\n      <path fill="#fff" d="M38.6 264.9S111.8 196 188 236.5c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 121.8 34 121.8 34v107l-521.4.3V264.9z" opacity=".4"/>\r\n      <path fill="#fffefe" d="M-72 267s70.2-69.7 146.3-29.1c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 119.4 32.4 119.4 32.4v108.8l-516 .4V267z" opacity=".4"/>\r\n      <path fill="#fff" d="M-72 302s94.3-90.3 204-54.7c139.7 45.4 190 12.3 250.3-9.5C472.9 205.1 560 311 560 311v74H-72v-83z"/>\r\n    </svg>\r\n  </figure>\r\n</div>\r\n<div class="section" style="padding:0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters">\r\n      <div class="columns"> %%timeline/rss_timeline|module|20|3%% </div>\r\n    </div>\r\n  </div>\r\n</div>', '', '', '', '2015-01-22 07:31:30', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('29', 'Timeline Custom', 'timeline-custom-demo', 'Timeline demo custom', '0', 'normal', '0', '0', '', '0', '', 'Public', '<div class="section relative" style="background-color: #f8f9fa ;padding:64px 0 164px 0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters align-center">\r\n      <div class="columns screen-40 tablet-60 mobile-100 phone-100">\r\n        <div class="content-center" data-weditable="true">\r\n          <h2 class="wojo primary text">\r\n            <span class="wojo semi text">Timeline demo</span> and custom mixed content </h2>\r\n          <p>This module is included in CMS pro base package.</p>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <figure class="absolute" style="bottom:0;left:0;width:100%">\r\n    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 150 512 200" width="100%" height="200px">\r\n      <path fill="#fff" d="M38.6 264.9S111.8 196 188 236.5c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 121.8 34 121.8 34v107l-521.4.3V264.9z" opacity=".4"/>\r\n      <path fill="#fffefe" d="M-72 267s70.2-69.7 146.3-29.1c131.9 70.3 193.4-3.9 250.3-9.5 83-8.1 119.4 32.4 119.4 32.4v108.8l-516 .4V267z" opacity=".4"/>\r\n      <path fill="#fff" d="M-72 302s94.3-90.3 204-54.7c139.7 45.4 190 12.3 250.3-9.5C472.9 205.1 560 311 560 311v74H-72v-83z"/>\r\n    </svg>\r\n  </figure>\r\n</div>\r\n<div class="section" style="padding:0">\r\n  <div class="wojo-grid">\r\n    <div class="row gutters">\r\n      <div class="columns"> %%timeline/custom_timeline|module|23|6%% </div>\r\n    </div>\r\n  </div>\r\n</div>', '', '', '', '2015-01-23 09:35:50', '1', 'Web Master', '0', '1');
INSERT INTO `pages` (`id`, `title_en`, `slug_en`, `caption_en`, `is_admin`, `page_type`, `membership_id`, `is_comments`, `custom_bg_en`, `show_header`, `theme`, `access`, `body_en`, `jscode`, `keywords_en`, `description_en`, `created`, `created_by`, `created_by_name`, `is_system`, `active`) VALUES ('31', 'Privacy Policy', 'privacy-policy', 'Privacy Policy', '1', 'policy', '0', '0', '', '0', '', 'Public', '<div class="section" style="padding:64px 0">\r\n    <div class="wojo-grid">\r\n      <div class="row align-center">\r\n        <div class="columns screen-70 tablet-100 mobile-100 phone-100">\r\n          <div class="wojo fitted card">\r\n            <div class="header relative wojo primary gradient">\r\n              <div class="double-padding">\r\n                <h1 class="wojo white semi text">Privacy &amp; Policy</h1>\r\n                <p class="wojo white dimmed text padding-bottom">Last modified: March 27, 2018</p>\r\n              </div>\r\n              <figure class="absolute" style="bottom:0;left:0;width:100%;">\r\n                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" width="100%" height="140px" style="margin-bottom:-32px" viewBox="20 -20 300 100"  xml:space="preserve">\r\n                  <path d="M30.913 43.944s42.911-34.464 87.51-14.191c77.31 35.14 113.304-1.952 146.638-4.729 48.654-4.056 69.94 16.218 69.94 16.218v54.396H30.913V43.944z" class="wojo white fill" opacity=".4"/>\r\n                  <path d="M-35.667 44.628s42.91-34.463 87.51-14.191c77.31 35.141 113.304-1.952 146.639-4.729 48.653-4.055 69.939 16.218 69.939 16.218v54.396H-35.667V44.628z" class="wojo white fill" opacity=".4"/>\r\n                  <path d="M-34.667 62.998s56-45.667 120.316-27.839C167.484 57.842 197 41.332 232.286 30.428c53.07-16.399 104.047 36.903 104.047 36.903l1.333 36.667-372-2.954-.333-38.046z" class="wojo white fill"/>\r\n                </svg>\r\n              </figure>\r\n            </div>\r\n            <div class="content">\r\n              <p>My Company ("us", "we", or "our") operates http://www.mysite.com  (the\r\n                "Site"). This page informs you of our policies regarding the\r\n                collection, use and disclosure of Personal Information we receive from users of\r\n                the Site.</p>\r\n              <p>We use your Personal Information only for providing and\r\n                improving the Site. By using the Site, you agree to the collection and use of\r\n                information in accordance with this policy.<br>\r\n                <br>\r\n              </p>\r\n              <p><b>Information\r\n                Collection And Use</b></p>\r\n              <p>While using our Site, we may ask you to provide us with\r\n                certain personally identifiable information that can be used to contact or\r\n                identify you. Personally identifiable information may include, but is not\r\n                limited to your name ("Personal Information").<br>\r\n                <br>\r\n                <br>\r\n              </p>\r\n              <p><b>Log Data</b></p>\r\n              <p>Like many site operators, we collect information that your\r\n                browser sends whenever you visit our Site ("Log Data").</p>\r\n              <p>This Log Data may include information such as your\r\n                computer\'s Internet Protocol ("IP") address, browser type, browser\r\n                version, the pages of our Site that you visit, the time and date of your visit,\r\n                the time spent on those pages and other statistics.</p>\r\n              <p>In addition, we may use third party services such as Google\r\n                Analytics that collect, monitor and analyze this …<br>\r\n                <br>\r\n                <br>\r\n              </p>\r\n              <p><b>Communications</b></p>\r\n              <p>We may use your Personal Information to contact you with\r\n                newsletters, marketing or promotional materials and other information that ...<br>\r\n                <br>\r\n                <br>\r\n              </p>\r\n              <p><b>Cookies</b></p>\r\n              <p>Cookies are files with small amount of data, which may\r\n                include an anonymous unique identifier. Cookies are sent to your browser from a\r\n                web site and stored on your computer\'s hard drive.</p>\r\n              <p>Like many sites, we use "cookies" to collect\r\n                information. You can instruct your browser to refuse all cookies or to indicate\r\n                when a cookie is being sent. However, if you do not accept cookies, you may not\r\n                be able to use some portions of our Site.<br>\r\n                <br>\r\n                <br>\r\n              </p>\r\n              <p><b>Security</b></p>\r\n              <p>The security of your Personal Information is important to\r\n                us, but remember that no method of transmission over the Internet, or method of\r\n                electronic storage, is 100% secure. While we strive to use commercially\r\n                acceptable means to protect your Personal Information, we cannot guarantee its\r\n                absolute security.<br>\r\n                <br>\r\n                <br>\r\n              </p>\r\n              <p><b>Changes To This\r\n                Privacy Policy</b></p>\r\n              <p>This Privacy Policy is effective as of (October 20. 2017) and will remain in effect except\r\n                with respect to any changes in its provisions in the future, which will be in\r\n                effect immediately after being posted on this page.</p>\r\n              <p>We reserve the right to update or change our Privacy Policy\r\n                at any time and you should check this Privacy Policy periodically. Your\r\n                continued use of the Service after we post any modifications to the Privacy\r\n                Policy on this page will constitute your acknowledgment of the modifications\r\n                and your consent to abide and be bound by the modified Privacy Policy.</p>\r\n              <p>If we make any material changes to this Privacy Policy, we\r\n                will notify you either through the email address you have provided us, or by\r\n                placing a prominent notice on our website.<br>\r\n                <br>\r\n                <br>\r\n              </p>\r\n              <p><b>Contact Us</b></p>\r\n              <p>If you have any questions about this Privacy Policy, please <a href="[SITEURL]/page/contact-us">contact us.</a>\r\n              </p>\r\n            </div>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </div>', '""', '', '', '2018-06-19 17:27:26', '1', 'Web Master', '1', '1');


-- --------------------------------------------------
# -- Table structure for table `payments`
-- --------------------------------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(50) DEFAULT NULL,
  `membership_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `rate_amount` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `coupon` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `currency` varchar(4) DEFAULT NULL,
  `pp` varchar(20) NOT NULL DEFAULT 'Stripe',
  `ip` varbinary(16) DEFAULT '000.000.000.000',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_membership` (`membership_id`),
  KEY `idx_user` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `payments`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `plug_carousel`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_carousel`;
CREATE TABLE `plug_carousel` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(100) NOT NULL,
  `body_en` text,
  `dots` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `nav` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `autoplay` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `margin` smallint(4) unsigned NOT NULL DEFAULT '0',
  `loop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `settings` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_carousel`
-- --------------------------------------------------

INSERT INTO `plug_carousel` (`id`, `title_en`, `body_en`, `dots`, `nav`, `autoplay`, `margin`, `loop`, `settings`) VALUES ('1', 'Testimonials', '<div class="row gutters">\n<div class="columns screen-20 tablet-20 mobile-100 phone-100 content-center"><img src="[SITEURL]/uploads/images/partner1.png" alt="" />\n<p class="wojo bold text half-vertical-margin">Retro Press Inc.</p>\n<p><a href="#">www.retropress.corp</a></p>\n</div>\n<div class="columns screen-40 tablet-40 mobile-100 phone-100">\n<p>Our team of specialists is constantly expanding and now we are looking for business consulting experts. If you know what is included in dealing with business management of all kinds - from entrepreneurship, management, business planning, financial analysis, and more, we will be glad to see you at our company.</p>\n<div class="wojo big space divider"></div>\n<p>We are also searching for financial consultants who would like to change the economic and financial state of their countries for the better. Browse our website to find out more about necessary skills and experience for the position of financial consultant at our company. experience for the position of financial consultant at our company.</p>\n</div>\n<div class="columns screen-40 tablet-40 mobile-100 phone-100">\n<div class="wojo primary bg relaxed fluid basic card">\n<div class="content"><img class="left floated wojo small circular image" src="[SITEURL]/uploads/avatars/av6.jpg" />\n<p>Thanks a lot for the quick response. I was really impressed, your solution is excellent! Your competence is justified!</p>\n<div class="wojo primary text bold">Michelle Smith, manager</div>\n</div>\n</div>\n<div class="wojo primary bg relaxed fluid basic card">\n<div class="content"><img class="left floated wojo small circular image" src="[SITEURL]/uploads/avatars/av1.jpg" />\n<p>Thank you for your prompt response and real help. You always have a quick solution to any problem.</p>\n<div class="wojo primary text bold">Timothy Holmes, manager</div>\n</div>\n</div>\n</div>\n</div>\n<div class="row gutters">\n<div class="columns screen-20 tablet-10 mobile-100 phone-100 content-center"><img src="[SITEURL]/uploads/images/partner1.png" alt="" />\n<p class="wojo bold text half-vertical-margin">Retro Press Inc.</p>\n<p><a href="#">www.retropress.corp</a></p>\n</div>\n<div class="columns screen-40 tablet-40 mobile-100 phone-100">\n<p>Our team of specialists is constantly expanding and now we are looking for business consulting experts. If you know what is included in dealing with business management of all kinds - from entrepreneurship, management, business planning, financial analysis, and more, we will be glad to see you at our company.</p>\n<div class="wojo big space divider"></div>\n<p>We are also searching for financial consultants who would like to change the economic and financial state of their countries for the better. Browse our website to find out more about necessary skills and experience for the position of financial consultant at our company. experience for the position of financial consultant at our company.</p>\n</div>\n<div class="columns screen-40 tablet-50 mobile-100 phone-100">\n<div class="wojo primary bg relaxed fluid basic card">\n<div class="content"><img class="left floated wojo small circular image" src="[SITEURL]/uploads/avatars/av6.jpg" />\n<p>Thanks a lot for the quick response. I was really impressed, your solution is excellent! Your competence is justified!</p>\n<div class="wojo primary text bold">Michelle Smith, manager</div>\n</div>\n</div>\n<div class="wojo primary bg relaxed fluid basic card">\n<div class="content"><img class="left floated wojo small circular image" src="[SITEURL]/uploads/avatars/av1.jpg" />\n<p>Thank you for your prompt response and real help. You always have a quick solution to any problem.</p>\n<div class="wojo primary text bold">Timothy Holmes, manager</div>\n</div>\n</div>\n</div>\n</div>\n<div class="row gutters">\n<div class="columns screen-20 tablet-10 mobile-100 phone-100 content-center"><img src="[SITEURL]/uploads/images/partner1.png" alt="" />\n<p class="wojo bold text half-vertical-margin">Retro Press Inc.</p>\n<p><a href="#">www.retropress.corp</a></p>\n</div>\n<div class="columns screen-40 tablet-40 mobile-100 phone-100">\n<p>Our team of specialists is constantly expanding and now we are looking for business consulting experts. If you know what is included in dealing with business management of all kinds - from entrepreneurship, management, business planning, financial analysis, and more, we will be glad to see you at our company.</p>\n<div class="wojo big space divider"></div>\n<p>We are also searching for financial consultants who would like to change the economic and financial state of their countries for the better. Browse our website to find out more about necessary skills and experience for the position of financial consultant at our company. experience for the position of financial consultant at our company.</p>\n</div>\n<div class="columns screen-40 tablet-50 mobile-100 phone-100">\n<div class="wojo primary bg relaxed fluid basic card">\n<div class="content"><img class="left floated wojo small circular image" src="[SITEURL]/uploads/avatars/av6.jpg" />\n<p>Thanks a lot for the quick response. I was really impressed, your solution is excellent! Your competence is justified!</p>\n<div class="wojo primary text bold">Michelle Smith, manager</div>\n</div>\n</div>\n<div class="wojo primary bg relaxed fluid basic card">\n<div class="content"><img class="left floated wojo small circular image" src="[SITEURL]/uploads/avatars/av1.jpg" />\n<p>Thank you for your prompt response and real help. You always have a quick solution to any problem.</p>\n<div class="wojo primary text bold">Timothy Holmes, manager</div>\n</div>\n</div>\n</div>\n</div>', '1', '0', '0', '0', '0', '{"dots":true,"nav":false,"autoplay":false,"margin":0,"loop":false,"responsive":{"0":{"items":1},"769":{"items":1},"1024":{"items":1}}}');
INSERT INTO `plug_carousel` (`id`, `title_en`, `body_en`, `dots`, `nav`, `autoplay`, `margin`, `loop`, `settings`) VALUES ('2', 'Our Clients', '<div><img src="[SITEURL]/uploads/images/partner2.png" alt="" /></div>\n<div><a href="[SITEURL]/our-contact-info/"><img src="[SITEURL]/uploads/images/partner3.png" alt="" /></a></div>\n<div><img src="[SITEURL]/uploads/images/partner4.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner5.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner6.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner2.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner3.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner4.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner5.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner6.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner2.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner3.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner4.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner5.png" alt="" /></div>\n<div><img src="[SITEURL]/uploads/images/partner6.png" alt="" /></div>', '1', '0', '1', '40', '0', '{"dots":true,"nav":false,"autoplay":true,"margin":40,"loop":true,"responsive":{"0":{"items":1},"769":{"items":3},"1024":{"items":5}}}');
INSERT INTO `plug_carousel` (`id`, `title_en`, `body_en`, `dots`, `nav`, `autoplay`, `margin`, `loop`, `settings`) VALUES ('3', 'Our Team', '          <div class="items">\r\n            <div class="row gutters">\r\n              <div class="columns phone-100 align-self-bottom">\r\n                <h4>Maria Muszynska</h4>\r\n                <span class="wojo primary label">#maria</span>\r\n                <p class="wojo small text vertical-margin">I am an ambitious workaholic, but apart from that, pretty simple person.</p>\r\n                <div class="padding-top">\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon facebook"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon google"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon twitter"></i></a>\r\n                </div>\r\n              </div>\r\n              <div class="columns phone-100">\r\n                <figure class="wojo basic rounded image"><img src="[SITEURL]/uploads/images/team/person1.jpg" alt="Image Description"></figure>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div class="items">\r\n            <div class="row gutters">\r\n              <div class="columns phone-100 align-self-bottom">\r\n                <h4>Jack Wayley</h4>\r\n                <span class="wojo primary label">#jack</span>\r\n                <p class="wojo small text vertical-margin">I am an ambitious workaholic, but apart from that, pretty simple person.</p>\r\n                <div class="padding-top">\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon facebook"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon google"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon twitter"></i></a>\r\n                </div>\r\n              </div>\r\n              <div class="columns">\r\n                <figure class="wojo basic rounded image"><img src="[SITEURL]/uploads/images/team/person2.jpg" alt="Image Description"></figure>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div class="items">\r\n            <div class="row gutters">\r\n              <div class="columns phone-100 align-self-bottom">\r\n                <h4>Tammy Ballis</h4>\r\n                <span class="wojo primary label">#tammy</span>\r\n                <p class="wojo small text vertical-margin">I am an ambitious workaholic, but apart from that, pretty simple person.</p>\r\n                <div class="padding-top">\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon facebook"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon google"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon twitter"></i></a>\r\n                </div>\r\n              </div>\r\n              <div class="columns">\r\n                <figure class="wojo basic rounded image"><img src="[SITEURL]/uploads/images/team/person5.jpg" alt="Image Description"></figure>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div class="items">\r\n            <div class="row gutters">\r\n              <div class="columns phone-100 align-self-bottom">\r\n                <h4>Emmely Jackson</h4>\r\n                <span class="wojo primary label">#emily</span>\r\n                <p class="wojo small text vertical-margin">I am an ambitious workaholic, but apart from that, pretty simple person.</p>\r\n                <div class="padding-top">\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon facebook"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon google"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon twitter"></i></a>\r\n                </div>\r\n              </div>\r\n              <div class="columns">\r\n                <figure class="wojo basic rounded image"><img src="[SITEURL]/uploads/images/team/person3.jpg" alt="Image Description"></figure>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div class="items">\r\n            <div class="row gutters">\r\n              <div class="columns phone-100 align-self-bottom">\r\n                <h4>Mark McManus</h4>\r\n                <span class="wojo primary label">#mark</span>\r\n                <p class="wojo small text vertical-margin">I am an ambitious workaholic, but apart from that, pretty simple person.</p>\r\n                <div class="padding-top">\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon facebook"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon google"></i></a>\r\n                  <a href="#" class="wojo small secondary icon button"><i class="icon twitter"></i></a>\r\n                </div>\r\n              </div>\r\n              <div class="columns">\r\n                <figure class="wojo basic rounded image"><img src="[SITEURL]/uploads/images/team/person4.jpg" alt="Image Description"></figure>\r\n              </div>\r\n            </div>\r\n          </div>', '1', '0', '0', '64', '0', '{"margin":64,"items":2,"loop":false,"nav":false,"dots":true,"responsive": {"0": {"items": 1},"769": {"items": 1},"1024": {"items": 2}}}');


-- --------------------------------------------------
# -- Table structure for table `plug_donation`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_donation`;
CREATE TABLE `plug_donation` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(80) DEFAULT NULL,
  `target_amount` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `pp_email` varchar(80) NOT NULL,
  `redirect_page` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_donation`
-- --------------------------------------------------

INSERT INTO `plug_donation` (`id`, `title`, `target_amount`, `pp_email`, `redirect_page`) VALUES ('1', 'Paypa Donations', '150.00', 'webmaster@paypal.com', '1');
INSERT INTO `plug_donation` (`id`, `title`, `target_amount`, `pp_email`, `redirect_page`) VALUES ('2', 'Paypa Donations II', '2500.00', 'webmaster@paypal.com', '1');


-- --------------------------------------------------
# -- Table structure for table `plug_donation_data`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_donation_data`;
CREATE TABLE `plug_donation_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `name` varchar(80) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `pp` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_donation_data`
-- --------------------------------------------------

INSERT INTO `plug_donation_data` (`id`, `parent_id`, `amount`, `name`, `email`, `pp`, `created`) VALUES ('1', '1', '12.00', 'Timothy Fields', 'jrussell1@ameblo.jp', 'PayPal', '2016-10-02 03:23:40');
INSERT INTO `plug_donation_data` (`id`, `parent_id`, `amount`, `name`, `email`, `pp`, `created`) VALUES ('2', '1', '15.00', 'Keith Butler', 'kmontgomery8@jigsy.com', 'PayPal', '2016-10-02 03:23:47');


-- --------------------------------------------------
# -- Table structure for table `plug_newsletter`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_newsletter`;
CREATE TABLE `plug_newsletter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_newsletter`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `plug_poll_options`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_poll_options`;
CREATE TABLE `plug_poll_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) unsigned NOT NULL,
  `value` varchar(150) NOT NULL,
  `position` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_question` (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_poll_options`
-- --------------------------------------------------

INSERT INTO `plug_poll_options` (`id`, `question_id`, `value`, `position`) VALUES ('4', '1', 'Hard', '4');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value`, `position`) VALUES ('3', '1', 'Easy', '3');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value`, `position`) VALUES ('2', '1', 'Very Easy', '2');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value`, `position`) VALUES ('1', '1', 'Piece of cake', '1');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value`, `position`) VALUES ('5', '2', 'CMS PRO', '1');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value`, `position`) VALUES ('6', '2', 'Wordpress', '2');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value`, `position`) VALUES ('7', '2', 'Joomla', '3');
INSERT INTO `plug_poll_options` (`id`, `question_id`, `value`, `position`) VALUES ('8', '2', 'Drupal', '4');


-- --------------------------------------------------
# -- Table structure for table `plug_poll_questions`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_poll_questions`;
CREATE TABLE `plug_poll_questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_poll_questions`
-- --------------------------------------------------

INSERT INTO `plug_poll_questions` (`id`, `question`, `created`, `status`) VALUES ('1', 'How do you find CMS pro! Installation?', '2010-10-13 19:42:18', '1');
INSERT INTO `plug_poll_questions` (`id`, `question`, `created`, `status`) VALUES ('2', 'What is the best CMS?', '2016-06-16 05:07:11', '1');


-- --------------------------------------------------
# -- Table structure for table `plug_poll_votes`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_poll_votes`;
CREATE TABLE `plug_poll_votes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `option_id` int(11) unsigned NOT NULL,
  `voted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_option` (`option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_poll_votes`
-- --------------------------------------------------

INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('1', '2', '2010-10-15 02:00:55', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('2', '1', '2010-10-15 02:01:27', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('3', '1', '2010-10-15 02:02:04', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('4', '1', '2010-10-15 02:02:13', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('5', '3', '2010-10-15 02:02:16', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('6', '4', '2010-10-15 02:02:21', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('7', '3', '2010-10-15 02:02:24', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('8', '1', '2010-10-15 02:02:27', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('9', '2', '2010-10-15 02:02:31', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('11', '1', '2010-10-15 02:02:38', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('12', '2', '2010-10-15 02:02:43', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('13', '1', '2010-10-15 02:02:46', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('14', '1', '2010-10-15 02:02:50', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('15', '1', '2010-10-15 02:05:26', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('16', '1', '2010-10-15 02:05:29', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('17', '4', '2010-10-15 02:05:33', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('18', '2', '2010-10-15 02:05:36', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('19', '1', '2010-10-15 02:05:40', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('20', '3', '2010-10-15 02:05:46', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('21', '2', '2010-10-15 02:05:49', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('22', '2', '2010-10-15 02:21:37', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('23', '1', '2010-10-15 02:21:53', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('25', '1', '2010-10-15 02:35:27', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('26', '1', '2010-10-15 12:42:05', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('27', '3', '2010-10-15 12:49:42', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('28', '2', '2010-10-15 13:22:00', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('29', '2', '2010-10-15 13:24:51', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('30', '1', '2010-10-15 13:37:21', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('31', '1', '2010-10-15 13:38:48', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('32', '1', '2010-10-15 13:41:30', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('33', '1', '2010-10-15 13:42:21', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('34', '1', '2010-10-15 16:53:42', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('35', '3', '2010-10-15 17:09:14', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('36', '3', '2010-11-25 12:00:27', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('37', '3', '2010-11-28 15:56:07', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('38', '3', '2012-12-23 12:57:05', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('39', '1', '2012-12-23 13:46:26', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('41', '1', '2012-12-27 11:20:01', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('42', '1', '2014-04-21 00:45:03', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('43', '3', '2014-04-21 00:46:53', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('44', '1', '2014-04-21 00:47:38', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('46', '3', '2014-04-24 05:07:37', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('47', '3', '2014-04-24 05:11:36', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('48', '3', '2014-05-20 05:09:13', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('49', '1', '2014-05-20 05:13:01', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('50', '5', '2016-06-17 06:43:10', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('51', '5', '2016-06-17 06:43:10', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('52', '5', '2016-06-17 06:43:11', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('53', '5', '2016-06-17 06:43:11', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('54', '5', '2016-06-17 06:43:11', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('55', '5', '2016-06-17 06:43:11', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('56', '5', '2016-06-17 06:43:12', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('57', '5', '2016-06-17 06:43:12', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('58', '6', '2016-06-17 06:43:36', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('59', '7', '2016-06-17 06:43:37', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('60', '8', '2016-06-17 06:43:38', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('61', '6', '2016-06-17 06:43:54', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('62', '7', '2016-06-17 06:43:55', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('63', '1', '2017-01-18 06:33:31', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('64', '1', '2017-01-18 06:34:07', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('65', '1', '2017-01-18 07:21:46', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('66', '1', '2017-01-18 08:00:36', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('67', '1', '2017-01-18 08:23:35', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('68', '1', '2017-01-18 08:30:55', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('69', '5', '2017-01-18 08:43:26', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('70', '5', '2017-01-18 08:47:00', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('71', '5', '2017-01-18 08:48:23', '127.0.0.1');
INSERT INTO `plug_poll_votes` (`id`, `option_id`, `voted_on`, `ip`) VALUES ('72', '1', '2018-12-13 19:48:03', '127.0.0.1');


-- --------------------------------------------------
# -- Table structure for table `plug_rss`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_rss`;
CREATE TABLE `plug_rss` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `url` varchar(120) NOT NULL,
  `items` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `show_date` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `show_desc` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `max_words` smallint(4) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_rss`
-- --------------------------------------------------

INSERT INTO `plug_rss` (`id`, `title`, `url`, `items`, `show_date`, `show_desc`, `max_words`) VALUES ('1', 'CTV Top Stories', 'https://www.ctvnews.ca/rss/ctvnews-ca-top-stories-public-rss-1.822009', '5', '1', '1', '20');
INSERT INTO `plug_rss` (`id`, `title`, `url`, `items`, `show_date`, `show_desc`, `max_words`) VALUES ('2', 'Yahoo Feed', 'http://rss.news.yahoo.com/rss/entertainment', '10', '1', '1', '100');


-- --------------------------------------------------
# -- Table structure for table `plug_slider`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_slider`;
CREATE TABLE `plug_slider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `type` varchar(15) DEFAULT NULL,
  `layout` varchar(25) DEFAULT NULL,
  `autoplay` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `autoplaySpeed` smallint(1) unsigned NOT NULL DEFAULT '1000',
  `autoplayHoverPause` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `autoloop` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `height` smallint(3) unsigned NOT NULL DEFAULT '100',
  `fullscreen` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `settings` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_slider`
-- --------------------------------------------------

INSERT INTO `plug_slider` (`id`, `title`, `type`, `layout`, `autoplay`, `autoplaySpeed`, `autoplayHoverPause`, `autoloop`, `height`, `fullscreen`, `settings`) VALUES ('1', 'Image Slider', 'image', 'dots', '1', '1500', '1', '0', '60', '0', '{"autoloop":false,"fullscreen":0,"autoplay":true,"autoplaySpeed":"1500","autoplayHoverPause":true,"layout":"dots","thumbs":false,"arrows":false,"buttons":true}');
INSERT INTO `plug_slider` (`id`, `title`, `type`, `layout`, `autoplay`, `autoplaySpeed`, `autoplayHoverPause`, `autoloop`, `height`, `fullscreen`, `settings`) VALUES ('2', 'Carousel Slider', 'carousel', 'basic', '1', '1000', '1', '1', '50', '0', '');
INSERT INTO `plug_slider` (`id`, `title`, `type`, `layout`, `autoplay`, `autoplaySpeed`, `autoplayHoverPause`, `autoloop`, `height`, `fullscreen`, `settings`) VALUES ('3', 'Content Slider', 'image', 'standard', '0', '1000', '1', '1', '100', '0', '{"autoloop":true,"fullscreen":0,"autoplay":false,"autoplaySpeed":"1000","autoplayHoverPause":true,"layout":"standard","thumbs":false,"arrows":true,"buttons":false}');
INSERT INTO `plug_slider` (`id`, `title`, `type`, `layout`, `autoplay`, `autoplaySpeed`, `autoplayHoverPause`, `autoloop`, `height`, `fullscreen`, `settings`) VALUES ('4', 'Sync Slider', 'sync', 'thumbs_down', '1', '1000', '1', '1', '50', '0', '');


-- --------------------------------------------------
# -- Table structure for table `plug_slider_data`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_slider_data`;
CREATE TABLE `plug_slider_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `html_raw` text,
  `html` text,
  `image` varchar(60) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `attrib` varchar(60) DEFAULT NULL,
  `mode` varchar(2) NOT NULL DEFAULT 'bg',
  `sorting` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_parent` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_slider_data`
-- --------------------------------------------------

INSERT INTO `plug_slider_data` (`id`, `parent_id`, `title`, `html_raw`, `html`, `image`, `color`, `attrib`, `mode`, `sorting`) VALUES ('1', '1', 'Third Slide', '<div class="uitem" id="item_1" data-type="bg">\n  <div class="uimage" style="background-size: cover; background-position: center center; background-repeat: no-repeat; background-image: url([SITEURL]/uploads/slider/slider_img1.jpg); min-height: 600px;">\n    <div class="ucontent align-center align-middle" style="min-height: 600px;">\n      <div class="row" data-id="nwEKD2r">\n        <div class="columns">\n          <div class="ws-layer" data-delay="50" data-duration="600" data-animation="popInLeft">\n            <div data-text="true" style="font-size: 40px;"><span style="color: #ffffff;">WELCOME TO CMS PRO</span></div>\n          </div>\n        </div>\n      </div>\n      <div class="row" data-id="HtoyaqB">\n        <div class="columns">\n          <div class="ws-layer" data-delay="100" data-duration="600" data-animation="popInLeft">\n            <div data-text="true" style="font-size: 40px; letter-spacing: 2px;" class="wojo bold text"><span style="color: #ffffff;">WE ARE <span class="wojo primary text">BUSNESS</span> COMPANY</span></div>\n          </div>\n        </div>\n      </div>\n      <div class="row" data-id="BNU5eGH">\n        <div class="columns">\n          <div class="ws-layer" data-delay="500" data-duration="800" data-animation="unfold">\n            <i style="width: 128px; height: 128px; font-size: 96px; color: #fff; border-radius: 128px;" class="icon wojologo"></i>\n          </div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>', '<div class="row">\n        <div class="columns">\n          <div class="ws-layer" data-delay="50" data-duration="600" data-animation="popInLeft">\n            <div data-text="true" style="font-size: 40px;"><span style="color: #ffffff;">WELCOME TO CMS PRO</span></div>\n          </div>\n        </div>\n      </div>\n      <div class="row">\n        <div class="columns">\n          <div class="ws-layer" data-delay="100" data-duration="600" data-animation="popInLeft">\n            <div data-text="true" style="font-size: 40px; letter-spacing: 2px;" class="wojo bold text"><span style="color: #ffffff;">WE ARE <span class="wojo primary text">BUSNESS</span> COMPANY</span></div>\n          </div>\n        </div>\n      </div>\n      <div class="row">\n        <div class="columns">\n          <div class="ws-layer" data-delay="500" data-duration="800" data-animation="unfold">\n            <i style="width: 128px; height: 128px; font-size: 96px; color: #fff; border-radius: 128px;" class="icon wojologo"></i>\n          </div>\n        </div>\n      </div>', 'slider/slider_img1.jpg', '#dddddd', 'align-center align-middle', 'bg', '1');
INSERT INTO `plug_slider_data` (`id`, `parent_id`, `title`, `html_raw`, `html`, `image`, `color`, `attrib`, `mode`, `sorting`) VALUES ('2', '1', 'Second Slide', '<div class="uitem" id="item_2" data-type="bg">\n  <div class="uimage" style="background-size: cover; background-position: center center; background-repeat: no-repeat; background-image: url([SITEURL]/uploads/slider/slider_img3.jpg); min-height: 600px;">\n    <div class="ucontent align-center align-middle" style="min-height: 600px;">\n      <div class="row" data-id="eDRof6n">\n        <div class="columns">\n          <div class="ws-layer" data-delay="50" data-duration="600" data-animation="fadeInTop">\n            <div data-text="true" style="font-size: 40px;"><span style="color: #ffffff;">WELCOME TO CMS PRO</span></div>\n          </div>\n        </div>\n      </div>\n      <div class="row" data-id="hpQY4I4">\n        <div class="columns">\n          <div class="ws-layer" data-delay="100" data-duration="600" data-animation="fadeInTop">\n            <div data-text="true" style="font-size: 40px; letter-spacing: 2px;" class="wojo bold text"><span style="color: #ffffff;">WE ARE <span class="wojo primary text">BUSNESS</span> COMPANY</span></div>\n          </div>\n        </div>\n      </div>\n      <div class="row" data-id="ARnjU6n">\n        <div class="columns">\n          <div class="ws-layer" data-delay="500" data-duration="800" data-animation="spinIn"><img src="[SITEURL]/uploads/svg/checked.svg" alt="checked.svg" class="wojo small basic image"></div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>', '<div class="row">\n        <div class="columns">\n          <div class="ws-layer" data-delay="150" data-duration="600" data-animation="fadeInTop">\n            <div data-text="true" style="font-size: 40px;"><span style="color: #ffffff;">WELCOME TO CMS PRO</span></div>\n          </div>\n        </div>\n      </div>\n      <div class="row">\n        <div class="columns">\n          <div class="ws-layer" data-delay="200" data-duration="600" data-animation="fadeInTop">\n            <div data-text="true" style="font-size: 40px; letter-spacing: 2px;" class="wojo bold text"><span style="color: #ffffff;">WE ARE <span class="wojo primary text">BUSNESS</span> COMPANY</span></div>\n          </div>\n        </div>\n      </div>\n      <div class="row">\n        <div class="columns">\n          <div class="ws-layer" data-delay="500" data-duration="800" data-animation="spinIn"><img src="[SITEURL]/uploads/svg/checked.svg" alt="checked.svg" class="wojo small basic image"></div>\n        </div>\n      </div>', 'slider/slider_img3.jpg', 'rgba(0, 0, 0, 0)', 'align-center align-middle', 'bg', '2');
INSERT INTO `plug_slider_data` (`id`, `parent_id`, `title`, `html_raw`, `html`, `image`, `color`, `attrib`, `mode`, `sorting`) VALUES ('3', '1', 'First Slide', '<div class="uitem" id="item_3" data-type="bg">\n  <div class="uimage" style="background-size: cover; background-position: center center; background-repeat: no-repeat; background-image: url([SITEURL]/uploads/slider/slider_img2.jpg); min-height: 600px;">\n    <div class="ucontent align-center" style="min-height: 600px;">\n      <div class="row" data-id="hvF4TT5">\n        <div class="columns">\n          <div class="ws-layer" data-delay="300" data-duration="600" data-animation="fadeInTop" data-type="header">\n            <div data-text="true" style="font-size: 40px;"><span style="color: #ffffff;">WELCOME TO CMS PRO</span></div>\n          </div>\n          <div class="ws-layer" data-delay="400" data-duration="600" data-animation="fadeInTop" data-type="header" style="margin-bottom:4rem">\n            <div data-text="true" style="font-size: 40px; letter-spacing: 2px;color: #ffffff;" class="wojo bold text">WE ARE <span class="wojo primary text">BUSNESS</span> COMPANY</div>\n          </div>\n        </div>\n      </div>\n      <div class="row" data-id="eFgSb0A">\n        <div class="columns shrink">\n          <div class="ws-layer" data-delay="100" data-duration="800" data-animation="rollInLeft" data-type="para">\n            <img src="[SITEURL]/uploads/slider/wlogo1.png" alt="">\n          </div>\n        </div>\n        <div class="columns shrink">\n          <div class="ws-layer" data-delay="200" data-duration="800" data-animation="rollInRight">\n            <img src="[SITEURL]/uploads/slider/wlogo2.png" alt="">\n          </div>\n        </div>\n      </div>\n      <div class="row" data-id="aMdMRmb">\n        <div class="columns shrink">\n          <div class="ws-layer" data-delay="150" data-duration="800" data-animation="rollInLeft">\n            <img src="[SITEURL]/uploads/slider/wlogo3.png" alt="">\n          </div>\n        </div>\n        <div class="columns shrink">\n          <div class="ws-layer" data-delay="250" data-duration="800" data-animation="rollInRight">\n            <img src="[SITEURL]/uploads/slider/wlogo4.png" alt="">\n          </div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>', '<div class="row">\r\n  <div class="columns">\r\n    <div class="ws-layer" data-delay="300" data-duration="600" data-animation="fadeInTop" data-type="header">\r\n      <div data-text="true" style="font-size: 40px;"><span style="color: #ffffff;">WELCOME TO CMS PRO</span></div>\r\n    </div>\r\n    <div class="ws-layer" data-delay="400" data-duration="600" data-animation="fadeInTop" data-type="header" style="margin-bottom:4rem">\r\n      <div data-text="true" style="font-size: 40px; letter-spacing: 2px;color: #ffffff;" class="wojo bold text">WE ARE <span class="wojo primary text">BUSNESS</span> COMPANY</div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="row">\r\n  <div class="columns shrink">\r\n    <div class="ws-layer" data-delay="100" data-duration="800" data-animation="rollInLeft" data-type="para">\r\n      <img src="[SITEURL]/uploads/slider/wlogo1.png" alt="">\r\n    </div>\r\n  </div>\r\n  <div class="columns shrink">\r\n    <div class="ws-layer" data-delay="200" data-duration="800" data-animation="rollInRight">\r\n      <img src="[SITEURL]/uploads/slider/wlogo2.png" alt="">\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="row">\r\n  <div class="columns shrink">\r\n    <div class="ws-layer" data-delay="150" data-duration="800" data-animation="rollInLeft">\r\n      <img src="[SITEURL]/uploads/slider/wlogo3.png" alt="">\r\n    </div>\r\n  </div>\r\n  <div class="columns shrink">\r\n    <div class="ws-layer" data-delay="250" data-duration="800" data-animation="rollInRight">\r\n      <img src="[SITEURL]/uploads/slider/wlogo4.png" alt="">\r\n    </div>\r\n  </div>\r\n</div>', 'slider/slider_img2.jpg', 'rgba(0, 0, 0, 0)', 'align-center', 'bg', '3');
INSERT INTO `plug_slider_data` (`id`, `parent_id`, `title`, `html_raw`, `html`, `image`, `color`, `attrib`, `mode`, `sorting`) VALUES ('4', '3', 'slide 1', '<div class="uitem" id="item_4" data-type="bg">\n  <div class="uimage" style="background-size: cover; background-position: center center; background-repeat: no-repeat; background-image: url([SITEURL]/uploads/slider/slider_12.jpg); min-height: 100vh;">\n    <div class="ucontent align-center" style="min-height: 100vh;">\n      <div class="row" data-id="iGsvF2E">\n        <div class="columns">\n          <div class="ws-layer" data-delay="50" data-duration="400" data-animation="fadeInBottom">\n            <p style="font-size: 1.5rem;color:#fff">CMS Pro is a</p>\n          </div>\n        </div>\n      </div>\n      <div class="row" data-id="eQSySCB">\n        <div class="columns">\n          <div class="ws-layer" data-delay="200" data-duration="1000" data-animation="fadeInBottom">\n            <h1 style="font-size: 3.5rem;font-weight: 300;line-height: 1.2;color:#fff" data-text="true">Self-<span class="wojo semi text">mastering </span><br>\n              piece of art</h1>\n          </div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>', '<div class="row">\n        <div class="columns">\n          <div class="ws-layer" data-delay="50" data-duration="400" data-animation="fadeInBottom">\n            <p style="font-size: 1.5rem;color:#fff">CMS Pro is a</p>\n          </div>\n        </div>\n      </div>\n      <div class="row">\n        <div class="columns">\n          <div class="ws-layer" data-delay="200" data-duration="1000" data-animation="fadeInBottom">\n            <h1 style="font-size: 3.5rem;font-weight: 300;line-height: 1.2;color:#fff" data-text="true">Self-<span class="wojo semi text">mastering </span><br>\n              piece of art</h1>\n          </div>\n        </div>\n      </div>', 'slider/slider_12.jpg', 'rgba(0, 0, 0, 0)', 'align-center', 'bg', '0');
INSERT INTO `plug_slider_data` (`id`, `parent_id`, `title`, `html_raw`, `html`, `image`, `color`, `attrib`, `mode`, `sorting`) VALUES ('5', '3', 'slide 2', '<div class="uitem" id="item_5" data-type="bg">\n  <div class="uimage" style="background-size: cover; background-position: center center; background-repeat: no-repeat; background-image: url([SITEURL]/uploads/slider/slider_11.jpg); min-height: 100vh;">\n    <div class="ucontent align-center" style="min-height: 100vh;">\n      <div class="row" data-id="FQ27Ayl">\n        <div class="column">\n          <div class="ws-layer" data-delay="50" data-duration="400" data-animation="fadeInBottom">\n            <p class="wojo white text" style="font-size: 1.5rem;">it is on</p>\n          </div>\n        </div>\n      </div>\n      <div class="row" data-id="dv1hZka">\n        <div class="column">\n          <div class="ws-layer" data-delay="200" data-duration="1000" data-animation="fadeInBottom">\n            <h1 class="wojo white text" style="font-size: 3.5rem;font-weight: 300;line-height: 1.2;" data-text="true"><span class="wojo semi text">Easy</span> Business with <br>\n              CMS pro</h1>\n          </div>\n        </div>\n      </div>\n    </div>\n  </div>\n</div>', '<div class="row">\n        <div class="column">\n          <div class="ws-layer" data-delay="50" data-duration="400" data-animation="fadeInBottom">\n            <p class="wojo white text" style="font-size: 1.5rem;">it is on</p>\n          </div>\n        </div>\n      </div>\n      <div class="row">\n        <div class="column">\n          <div class="ws-layer" data-delay="200" data-duration="1000" data-animation="fadeInBottom">\n            <h1 class="wojo white text" style="font-size: 3.5rem;font-weight: 300;line-height: 1.2;" data-text="true"><span class="wojo semi text">Easy</span> Business with <br>\n              CMS pro</h1>\n          </div>\n        </div>\n      </div>', 'slider/slider_11.jpg', 'rgba(0, 0, 0, 0)', 'align-center', 'bg', '0');


-- --------------------------------------------------
# -- Table structure for table `plug_yplayer`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plug_yplayer`;
CREATE TABLE `plug_yplayer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `layout` varchar(10) DEFAULT NULL,
  `config` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plug_yplayer`
-- --------------------------------------------------

INSERT INTO `plug_yplayer` (`id`, `title`, `layout`, `config`) VALUES ('1', 'Horizontal Player', 'horizontal', '{"playlist":false,"channel":false,"user":false,"videos":"F2UFA8btZ-4,IP3pHwh8kq4,n9DwoQ7HWvI,v-4rYf0x-F4,2onxgmKT1fw,6VpNkwkSPZo","api_key":"YTKEY","max_results":"50","pagination":"1","continuous":"1","show_playlist":false,"playlist_type":"horizontal","show_channel_in_playlist":"1","show_channel_in_title":"1","now_playing_text":"Now Playing","load_more_text":"Load More","autoplay":false,"force_hd":"0","share_control":"0","colors":{"controls_bg":"rgba(0,0,0,.75)","buttons":"rgba(255,255,255,.5)","buttons_hover":"rgba(255,255,255,1)","buttons_active":"rgba(255,255,255,1)","time_text":"#FFFFFF","bar_bg":"rgba(255,255,255,.5)","buffer":"rgba(255,255,255,.25)","fill":"#FFFFFF","video_title":"#FFFFFF","video_channel":"rgba(255, 0, 0, 0.35)","playlist_overlay":"rgba(0,0,0,.75)","playlist_title":"#FFFFFF","playlist_channel":"rgba(255, 0, 0, 0.35)","scrollbar":"#FFFFFF","scrollbar_bg":"rgba(255,255,255,.25)"}}');
INSERT INTO `plug_yplayer` (`id`, `title`, `layout`, `config`) VALUES ('2', 'Vertical Player', 'vertical', '{"playlist":false,"channel":false,"user":false,"videos":"F2UFA8btZ-4,IP3pHwh8kq4,n9DwoQ7HWvI,v-4rYf0x-F4,2onxgmKT1fw,6VpNkwkSPZo","api_key":"YTKEY","max_results":"50","pagination":"1","continuous":"1","show_playlist":false,"playlist_type":"vertical","show_channel_in_playlist":"1","show_channel_in_title":"1","now_playing_text":"Now Playing","load_more_text":"Load More","autoplay":false,"force_hd":"0","share_control":"0","colors":{"controls_bg":"rgba(0,0,0,.75)","buttons":"rgba(255,255,255,.5)","buttons_hover":"rgba(255,255,255,1)","buttons_active":"rgba(255,255,255,1)","time_text":"#FFFFFF","bar_bg":"rgba(255,255,255,.5)","buffer":"rgba(255,255,255,.25)","fill":"#FFFFFF","video_title":"#FFFFFF","video_channel":"rgba(255, 0, 0, 0.35)","playlist_overlay":"rgba(0,0,0,.75)","playlist_title":"#FFFFFF","playlist_channel":"rgba(255, 0, 0, 0.35)","scrollbar":"#FFFFFF","scrollbar_bg":"rgba(255,255,255,.25)"}}');


-- --------------------------------------------------
# -- Table structure for table `plugins`
-- --------------------------------------------------
DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(120) NOT NULL,
  `body_en` text,
  `jscode` text,
  `show_title` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `alt_class` varchar(30) DEFAULT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `cplugin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `info_en` varchar(150) DEFAULT NULL,
  `plugalias` varchar(50) DEFAULT NULL,
  `hasconfig` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `multi` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `plugin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `groups` varchar(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `icon` varchar(60) DEFAULT NULL,
  `ver` decimal(4,2) NOT NULL DEFAULT '1.00',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_plugin_id` (`plugin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `plugins`
-- --------------------------------------------------

INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('1', 'Universal Slider', '', '', '0', '', '1', '0', '', 'slider', '1', '1', '0', '0', 'slider', '2016-06-17 22:28:53', 'slider/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('2', 'Ajax Poll', '', '', '0', '', '1', '1', '', 'poll', '1', '1', '0', '0', 'poll', '2016-06-17 22:30:15', 'poll/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('3', 'How do you find CMS pro! Installation?', '', '', '0', 'primary', '1', '1', '', 'poll/install', '0', '0', '2', '1', 'poll', '2016-06-17 22:31:45', 'poll/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('4', 'What is the best CMS?', '', '', '0', '', '1', '0', '', 'poll/cms', '0', '0', '2', '2', 'poll', '2016-06-17 22:36:05', 'poll/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('5', 'Image Slider', '', '', '0', '', '1', '1', '', 'slider/master', '0', '0', '1', '1', 'slider', '2016-06-17 22:36:35', 'slider/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('6', 'Carousel Slider', '', '', '0', '', '1', '1', '', 'slider', '0', '0', '1', '2', 'slider', '2016-06-17 22:36:56', 'slider/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('7', 'Content Slider', '', '', '0', '', '1', '1', '', 'slider/demo', '0', '0', '1', '3', 'slider', '2016-06-17 22:37:15', 'slider/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('8', 'Sync Slider', '', '', '0', '', '1', '1', '', 'slider', '0', '0', '1', '4', 'slider', '2016-06-17 23:47:50', 'slider/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('9', 'Rss Feed', '', '', '0', '', '1', '1', '', 'rss', '1', '1', '0', '0', 'rss', '2016-09-30 08:58:22', 'rss/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('10', 'CTV Top Stories', '', '', '0', '', '1', '0', '', 'rss/ctv', '0', '0', '9', '1', 'rss', '2016-09-30 23:44:52', 'rss/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('11', 'Yahoo Feed', '', '', '0', '', '1', '0', '', 'rss/yahoo', '0', '0', '9', '2', 'rss', '2016-09-30 23:46:22', 'rss/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('12', 'Donate', '', '', '0', '', '1', '1', '', 'donation', '1', '1', '0', '0', 'donation', '2016-10-02 01:14:27', 'donation/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('13', 'Paypal Donations', 'Help us raise $150 with a matching gift opportunity.', '', '1', '', '1', '0', '', 'donation/paypal', '0', '0', '12', '1', 'donation', '2016-10-02 03:20:02', 'donation/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('14', 'Paypal Donations II', '', '', '0', '', '1', '0', '', 'donation/paypal_alt', '0', '0', '12', '2', 'donation', '2016-10-02 03:20:46', 'donation/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('15', 'Latest Twitts', '', '', '0', '', '1', '1', '', 'twitts', '1', '0', '0', '0', 'twitts', '2016-10-02 10:31:04', 'twitts/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('16', 'Upcoming Events', '', '', '1', '', '1', '1', '', 'upevent', '1', '0', '0', '0', 'upevent', '2016-10-18 07:30:27', 'upevent/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('17', 'Youtube Player', '', '', '0', '', '1', '0', '', 'yplayer', '1', '1', '0', '0', 'yplayer', '2016-10-19 05:53:43', 'yplayer/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('18', 'Horizontal Player', '', '', '0', '', '1', '1', '', 'yplayer/horizontal', '0', '0', '17', '1', 'yplayer', '2016-10-19 12:14:25', 'yplayer/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('19', 'Vertical Player', '', '', '0', '', '1', '1', '', 'yplayer/vertical', '0', '0', '17', '2', 'yplayer', '2016-10-19 12:17:27', 'yplayer/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('20', 'Head Office', '', '', '0', '', '1', '1', '', 'gmaps/head-office', '0', '0', '0', '1', 'gmaps', '2016-11-22 06:22:56', 'gmaps/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('21', 'Default Campaign', '', '', '0', '', '1', '1', '', 'adblock/wojo-advert', '0', '0', '0', '1', 'adblock', '2016-12-30 09:02:28', 'adblock/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('22', 'Universal Carousel', '', '', '0', '', '1', '0', '', 'carousel', '1', '1', '0', '0', 'carousel', '2017-01-11 06:19:47', 'carousel/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('23', 'Testimonials', '', '', '0', '', '1', '1', '', 'carousel/testimonials', '0', '0', '22', '1', 'carousel', '2017-01-11 07:55:40', 'carousel/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('24', 'Testimonial', '<div class="relative" style="background-image: linear-gradient(150deg, #2d1582 0%, #19a0ff 100%);background-repeat: repeat-x; padding:8rem 0">\r\n  <div class="wojo-grid">\r\n    <div class="row align-center">\r\n      <div class="columns content-center screen-60">\r\n        <figure class="wojo small circular image">\r\n          <img src="[SITEURL]/uploads/avatars/avatar_0015.jpg" alt="Image Description">\r\n        </figure>\r\n        <div class="wojo white thin tall large text margin-bottom"> The template is really nice and offers quite a large set of options. It\'s beautiful and the coding is done quickly and seamlessly. Thank you! </div>\r\n        <h4 class="wojo warning text">Maria Muszynska</h4>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <figure class="absolute" style="bottom:-3rem;left:0;width:25%">\r\n    <svg xmlns="http://www.w3.org/2000/svg" viewBox="-19 21 8 8">\r\n      <path fill="#FFF" d="M-15.3 26.5c0 .9-.7 1.6-1.6 1.6-.7 0-1.3-.4-1.5-1.1-.2-.5-.3-1.1-.3-1.4 0-1.6.8-2.9 2.5-3.7l.2.5c-1 .4-1.8 1.4-1.8 2.3v.4c.3-.2.6-.3.9-.3.9 0 1.6.8 1.6 1.7zm2.4-1.7c-.3 0-.6.1-.9.3v-.4c0-.9.8-1.9 1.8-2.3l-.2-.5c-1.7.8-2.5 2.1-2.5 3.7 0 .4.1.9.3 1.4.2.6.8 1.1 1.5 1.1.9 0 1.6-.7 1.6-1.6s-.7-1.7-1.6-1.7z" opacity=".1"/>\r\n    </svg>\r\n  </figure>\r\n  <figure class="absolute" style="left:0;bottom:0;width:100%">\r\n    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="140px" data-parent="#SVGwave1BottomSMShape" preserveAspectRatio="none" style="margin-bottom:-8px" viewBox="0 0 300 100">\r\n      <style>\r\n    .wave-bottom-1-sm-0{fill:#fff}\r\n  </style>\r\n      <defs>\r\n        <path id="waveBottom1SMID1" d="M0 0h300v100H0z"/>\r\n      </defs>\r\n      <clipPath id="waveBottom1SMID2">\r\n        <use overflow="visible" xlink:href="#waveBottom1SMID1"/>\r\n      </clipPath>\r\n      <path d="M10.9 63.9s42.9-34.5 87.5-14.2c77.3 35.1 113.3-2 146.6-4.7 48.7-4 70 16.2 70 16.2v54.4H10.9V63.9z" class="wave-bottom-1-sm-0 fill-white" clip-path="url(#waveBottom1SMID2)" opacity=".4"/>\r\n      <path d="M-55.7 64.6s42.9-34.5 87.5-14.2c77.3 35.1 113.3-2 146.6-4.7 48.7-4.1 69.9 16.2 69.9 16.2v54.4h-304V64.6z" class="wave-bottom-1-sm-0 fill-white" clip-path="url(#waveBottom1SMID2)" opacity=".4"/>\r\n      <path fill-opacity="0" d="M23.4 118.3s48.3-68.9 109.1-68.9c65.9 0 98 67.9 98 67.9v3.7H22.4l1-2.7z" class="wave-bottom-1-sm-0 fill-white" clip-path="url(#waveBottom1SMID2)" opacity=".4"/>\r\n      <path d="M-54.7 83s56-45.7 120.3-27.8c81.8 22.7 111.4 6.2 146.6-4.7 53.1-16.4 104 36.9 104 36.9l1.3 36.7-372-3-.2-38.1z" class="wave-bottom-1-sm-0 fill-white" clip-path="url(#waveBottom1SMID2)"/>\r\n    </svg>\r\n  </figure>\r\n</div>', '', '0', '', '0', '0', 'Testimonial used on blog page', '', '0', '0', '0', '0', '', '2017-01-13 08:33:21', '', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('25', 'About Us', '<div class="sidebar-module">\r\n  <h4>about us</h4>\r\n  <div class="thumbnail-3 thumbnail-mod-2"><img alt="" src="[SITEURL]/uploads/images/services1.jpg">\r\n    <div class="caption">\r\n      <p>Our business solutions are designed around the real needs of businesses, our information resources, tools and... </p>\r\n      <a class="wojo red button" href="about.html">learn more</a> </div>\r\n  </div>\r\n</div>', '', '0', '', '0', '0', '', '', '0', '0', '0', '0', '', '2017-01-16 16:23:52', '', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('26', 'Our Clients', '', '', '0', '', '1', '1', '', 'carousel/clients', '0', '0', '22', '2', 'carousel', '2017-01-19 12:14:27', 'carousel/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('35', 'Newsletter', '', '', '0', '', '1', '1', '', 'newsletter', '1', '0', '0', '0', 'newsletter', '2017-05-27 02:00:20', 'newsletter/thumb.svg', '1.00', '1');
INSERT INTO `plugins` (`id`, `title_en`, `body_en`, `jscode`, `show_title`, `alt_class`, `system`, `cplugin`, `info_en`, `plugalias`, `hasconfig`, `multi`, `parent_id`, `plugin_id`, `groups`, `created`, `icon`, `ver`, `active`) VALUES ('36', 'Our Team', '', '', '1', '', '0', '1', '', 'carousel/team', '0', '0', '22', '3', 'carousel', '2018-04-14 01:17:18', 'carousel/thumb.svg', '1.00', '1');


-- --------------------------------------------------
# -- Table structure for table `privileges`
-- --------------------------------------------------
DROP TABLE IF EXISTS `privileges`;
CREATE TABLE `privileges` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `mode` varchar(8) NOT NULL,
  `type` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `privileges`
-- --------------------------------------------------

INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('1', 'manage_users', 'Manage Users', 'Permission to add/edit/delete users', 'manage', 'Users');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('2', 'manage_files', 'Manage Files', 'Permission to access File Manager', 'manage', 'Files');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('3', 'manage_pages', 'Manage Pages', 'Permission to Add/edit/delete pages', 'manage', 'Pages');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('4', 'manage_menus', 'Manage Menus', 'Permission to Add/edit and delete menus', 'manage', 'Menus');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('5', 'manage_email', 'Manage Email Templates', 'Permission to modify email templates', 'manage', 'Emails');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('6', 'manage_languages', 'Manage Language Phrases', 'Permission to modify language phrases', 'manage', 'Languages');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('7', 'manage_backup', 'Manage Database Backups', 'Permission to create backups and restore', 'manage', 'Backups');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('8', 'manage_memberships', 'Manage Memberships', 'Permission to manage memberships', 'manage', 'Memberships');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('9', 'edit_user', 'Edit Users', 'Permission to edit user', 'edit', 'Users');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('10', 'add_user', 'Add User', 'Permission to add users', 'add', 'Users');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('11', 'delete_user', 'Delete Users', 'Permission to delete users', 'delete', 'Users');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('12', 'manage_plugins', 'Manage Plugins', 'Permission to Add/Edit and delete user plugins', 'manage', 'Plugins');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('13', 'manage_fields', 'Manage Custom Fields', 'Permission to Add/Edit and delete custom fields', 'manage', 'Fields');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('14', 'manage_newsletter', 'Manage Newsletter', 'Permission to send newsletter', 'manage', 'Newsletter');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('15', 'manage_countries', 'Manage Countries', 'Permission to manage countries', 'manage', 'Countries');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('16', 'manage_coupons', 'Manage Coupons', 'Permission to Add/Edit and delete coupons', 'manage', 'Coupons');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('17', 'manage_modules', 'Manage Modules', 'Permission to manage all modules', 'manage', 'Modules');
INSERT INTO `privileges` (`id`, `code`, `name`, `description`, `mode`, `type`) VALUES ('18', 'manage_layout', 'Manage Layouts', 'Permission to access layout manager', 'manage', 'Layout');


-- --------------------------------------------------
# -- Table structure for table `role_privileges`
-- --------------------------------------------------
DROP TABLE IF EXISTS `role_privileges`;
CREATE TABLE `role_privileges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(6) unsigned NOT NULL DEFAULT '0',
  `pid` int(6) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx` (`rid`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `role_privileges`
-- --------------------------------------------------

INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('1', '1', '1', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('2', '2', '1', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('3', '3', '1', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('4', '1', '2', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('5', '2', '2', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('6', '3', '2', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('7', '1', '3', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('8', '2', '3', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('9', '3', '3', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('10', '1', '4', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('11', '2', '4', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('12', '3', '4', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('13', '1', '5', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('14', '2', '5', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('15', '3', '5', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('16', '1', '6', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('17', '2', '6', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('18', '3', '6', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('19', '1', '7', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('20', '2', '7', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('21', '3', '7', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('22', '1', '8', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('23', '2', '8', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('24', '3', '8', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('25', '1', '9', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('26', '2', '9', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('27', '3', '9', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('28', '1', '10', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('29', '2', '10', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('30', '3', '10', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('31', '1', '11', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('32', '2', '11', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('33', '3', '11', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('34', '1', '12', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('35', '2', '12', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('36', '3', '12', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('37', '1', '13', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('38', '2', '13', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('39', '3', '13', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('40', '1', '14', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('41', '2', '14', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('42', '3', '14', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('43', '1', '15', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('44', '2', '15', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('45', '3', '15', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('46', '1', '16', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('47', '2', '16', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('48', '3', '16', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('49', '1', '17', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('50', '2', '17', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('51', '3', '17', '0');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('52', '1', '18', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('53', '2', '18', '1');
INSERT INTO `role_privileges` (`id`, `rid`, `pid`, `active`) VALUES ('54', '3', '18', '0');


-- --------------------------------------------------
# -- Table structure for table `roles`
-- --------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `icon` varchar(20) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `roles`
-- --------------------------------------------------

INSERT INTO `roles` (`id`, `code`, `icon`, `name`, `description`) VALUES ('1', 'owner', 'badge', 'Site Owner', 'Site Owner is the owner of the site, has all privileges and could not be removed.');
INSERT INTO `roles` (`id`, `code`, `icon`, `name`, `description`) VALUES ('2', 'staff', 'trophy', 'Staff Member', 'The &#34;Staff&#34; members  is required to assist the Owner, has different privileges and may be created by Site Owner.');
INSERT INTO `roles` (`id`, `code`, `icon`, `name`, `description`) VALUES ('3', 'editor', 'note', 'Editor', 'The "Editor" is required to assist the Staff Members, has different privileges and may be created by Site Owner.');


-- --------------------------------------------------
# -- Table structure for table `settings`
-- --------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `site_dir` varchar(50) DEFAULT NULL,
  `site_email` varchar(50) NOT NULL,
  `theme` varchar(32) NOT NULL,
  `perpage` tinyint(2) unsigned NOT NULL,
  `backup` varchar(64) NOT NULL,
  `thumb_w` tinyint(3) unsigned NOT NULL,
  `thumb_h` tinyint(3) unsigned NOT NULL,
  `img_w` smallint(3) unsigned NOT NULL,
  `img_h` smallint(3) unsigned NOT NULL,
  `avatar_w` tinyint(2) unsigned NOT NULL,
  `avatar_h` tinyint(2) unsigned NOT NULL,
  `short_date` varchar(20) NOT NULL,
  `long_date` varchar(30) NOT NULL,
  `time_format` varchar(10) DEFAULT NULL,
  `dtz` varchar(120) DEFAULT NULL,
  `locale` varchar(200) DEFAULT NULL,
  `weekstart` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `lang` varchar(2) NOT NULL DEFAULT 'en',
  `lang_list` blob NOT NULL,
  `ploader` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `eucookie` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `offline` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `offline_msg` text,
  `offline_d` date DEFAULT NULL,
  `offline_t` time DEFAULT NULL,
  `offline_info` text,
  `logo` varchar(50) DEFAULT NULL,
  `plogo` varchar(50) DEFAULT NULL,
  `showlang` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `showlogin` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `showsearch` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `showcrumbs` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `currency` varchar(4) DEFAULT NULL,
  `enable_tax` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `file_size` int(4) unsigned NOT NULL DEFAULT '20971520',
  `file_ext` varchar(150) NOT NULL DEFAULT 'png,jpg,jpeg,bmp',
  `reg_verify` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `auto_verify` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `notify_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `flood` int(3) unsigned NOT NULL DEFAULT '3600',
  `attempt` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `logging` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `analytics` varchar(100) DEFAULT NULL,
  `mailer` enum('PHP','SMTP','SMAIL') DEFAULT NULL,
  `sendmail` varchar(60) DEFAULT NULL,
  `smtp_host` varchar(150) DEFAULT NULL,
  `smtp_user` varchar(50) DEFAULT NULL,
  `smtp_pass` varchar(50) DEFAULT NULL,
  `smtp_port` varchar(3) DEFAULT NULL,
  `is_ssl` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `inv_info` text,
  `inv_note` text,
  `system_slugs` blob,
  `url_slugs` blob,
  `social_media` blob,
  `ytapi` varchar(120) DEFAULT NULL,
  `mapapi` varchar(120) DEFAULT NULL,
  `wojon` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `wojov` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `settings`
-- --------------------------------------------------

INSERT INTO `settings` (`id`, `site_name`, `company`, `site_dir`, `site_email`, `theme`, `perpage`, `backup`, `thumb_w`, `thumb_h`, `img_w`, `img_h`, `avatar_w`, `avatar_h`, `short_date`, `long_date`, `time_format`, `dtz`, `locale`, `weekstart`, `lang`, `lang_list`, `ploader`, `eucookie`, `offline`, `offline_msg`, `offline_d`, `offline_t`, `offline_info`, `logo`, `plogo`, `showlang`, `showlogin`, `showsearch`, `showcrumbs`, `currency`, `enable_tax`, `file_size`, `file_ext`, `reg_verify`, `auto_verify`, `notify_admin`, `flood`, `attempt`, `logging`, `analytics`, `mailer`, `sendmail`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `is_ssl`, `inv_info`, `inv_note`, `system_slugs`, `url_slugs`, `social_media`, `ytapi`, `mapapi`, `wojon`, `wojov`) VALUES ('1', 'cms pro', 'Wojoscripts', 'test', 'alex.kuzmanovic@email.com', 'master', '12', '24-May-2017_15-36-58.sql', '150', '150', '800', '800', '250', '250', 'dd MMM yyyy', 'MMMM dd, yyyy hh:mm a', 'HH:mm', 'America/Toronto', 'en_CA', '1', 'en', '[{"id":1,"name":"English","abbr":"en","langdir":"ltr","color":"#7ACB95","author":"http:\\/\\/www.wojoscripts.com","home":1}]', '0', '0', '0', '<p>Our website is under construction, we are working very hard to give you the best experience on our new web site.</p>', '2019-04-01', '18:00:00', '<p>Instructions for offline payments...</p>\r\n<p>Your bank name etc...</p>', 'logo.svg', 'print_logo.png', '1', '1', '1', '1', 'CAD', '0', '20971520', 'png,jpg,jpeg,bmp,zip,pdf,doc,docx,txt,mp4', '1', '1', '1', '1800', '3', '1', '', 'PHP', '/usr/sbin/sendmail -t -i', 'mail.hostname.com', 'yourusername', 'yourpass', '25', '0', '<p><b>ABC Company Pty Ltd</b><br />123 Burke Street, Toronto ON, CANADA<br />Tel : (416) 1234-5678, Fax : (416) 1234-5679, Email : sales@abc-company.com<br />Web Site : www.abc-company.com</p>', '<p>TERMS &amp; CONDITIONS<br />1. Interest may be levied on overdue accounts. <br />2. Goods sold are not returnable or refundable</p>', '{"home":[{"page_type":"home","slug_en":"home"}],"normal":[{"page_type":"normal","slug_en":"our-contact-info"}],"login":[{"page_type":"login","slug_en":"login"}],"register":[{"page_type":"register","slug_en":"registration"}],"activate":[{"page_type":"activate","slug_en":"activate"}],"account":[{"page_type":"account","slug_en":"dashboard"}],"search":[{"page_type":"search","slug_en":"search"}],"sitemap":[{"page_type":"sitemap","slug_en":"sitemap"}],"profile":[{"page_type":"profile","slug_en":"profile"}],"policy":[{"page_type":"policy","slug_en":"privacy-policy"}]}', '{\r\n   "moddir":{\r\n      "digishop":"digishop",\r\n      "blog":"blog",\r\n      "portfolio":"portfolio",\r\n      "gallery":"gallery"\r\n   },\r\n   "pagedata":{\r\n      "page":"page"\r\n   },\r\n   "module":{\r\n      "digishop":"digishop",\r\n      "digishop-cat":"category",\r\n      "digishop-checkout":"checkout",\r\n      "blog":"blog",\r\n      "blog-cat":"category",\r\n      "blog-search":"search",\r\n      "blog-archive":"archive",\r\n      "blog-author":"author",\r\n      "blog-tag":"tag",\r\n      "portfolio":"portfolio",\r\n      "portfolio-cat":"category",\r\n      "gallery":"gallery",\r\n      "gallery-album":"album"\r\n   }\r\n}', '{"facebook":"facebook_page","twitter":"twitter_page"}', 'AIzaSyAqlXS1Kd8u42l4pOm_geud-HZZqxl_N5k', 'AIzaSyAbflOjxx-oxzmV35LCHFb7NeBBs7wQDbA', '1.00', '5.10');


-- --------------------------------------------------
# -- Table structure for table `stats`
-- --------------------------------------------------
DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pageviews` int(11) unsigned NOT NULL DEFAULT '0',
  `uniquevisitors` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `stats`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `trash`
-- --------------------------------------------------
DROP TABLE IF EXISTS `trash`;
CREATE TABLE `trash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` varchar(15) DEFAULT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(15) DEFAULT NULL,
  `dataset` blob,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `trash`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `user_memberships`
-- --------------------------------------------------
DROP TABLE IF EXISTS `user_memberships`;
CREATE TABLE `user_memberships` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `mid` int(11) unsigned NOT NULL DEFAULT '0',
  `activated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expire` timestamp NULL DEFAULT NULL,
  `recurring` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = expired, 1 = active',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `user_memberships`
-- --------------------------------------------------



-- --------------------------------------------------
# -- Table structure for table `users`
-- --------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `fname` varchar(32) NOT NULL,
  `lname` varchar(32) NOT NULL,
  `email` varchar(60) NOT NULL,
  `membership_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mem_expire` datetime DEFAULT NULL,
  `trial_used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `memused` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `salt` varchar(25) NOT NULL,
  `hash` varchar(70) NOT NULL,
  `token` varchar(40) NOT NULL DEFAULT '0',
  `userlevel` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` varchar(10) NOT NULL DEFAULT 'member',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` datetime DEFAULT NULL,
  `lastip` varbinary(16) DEFAULT '000.000.000.000',
  `avatar` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `country` varchar(4) DEFAULT NULL,
  `notify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `access` text,
  `notes` tinytext,
  `info` text,
  `fb_link` varchar(100) DEFAULT NULL,
  `tw_link` varchar(100) DEFAULT NULL,
  `gp_link` varchar(100) DEFAULT NULL,
  `newsletter` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `stripe_cus` varchar(100) DEFAULT NULL,
  `modaccess` varchar(150) DEFAULT NULL,
  `plugaccess` varchar(150) DEFAULT NULL,
  `active` enum('y','n','t','b') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- --------------------------------------------------
# Dumping data for table `users`
-- --------------------------------------------------

INSERT INTO `users` (`id`, `username`, `fname`, `lname`, `email`, `membership_id`, `mem_expire`, `trial_used`, `memused`, `salt`, `hash`, `token`, `userlevel`, `type`, `created`, `lastlogin`, `lastip`, `avatar`, `address`, `city`, `state`, `zip`, `country`, `notify`, `access`, `notes`, `info`, `fb_link`, `tw_link`, `gp_link`, `newsletter`, `stripe_cus`, `modaccess`, `plugaccess`, `active`) VALUES ('1', 'admin', 'Web', 'Master', 'alex.kuzmanovic@gmail.com', '0', '', '0', '1', '1Zcp4EavFlJ5fSmqAU9Bx', '$2a$10$1Zcp4EavFlJ5fSmqAU9Bx.9VhfWtzH02YgvOe75wNW3EwXbvtNXDC', '0', '9', 'owner', '2014-03-27 22:47:02', '2019-03-08 14:59:05', '127.0.0.1', 'av1.jpg', '', '', '', '', '', '0', '', '', 'I am a project manager from Canada. This is where I sit down, grab a cup of coffee and dial in the details. Understanding the task at hand and ironing out the wrinkles is key. The time has come to bring those ideas and plans to life. This is where we really begin to visualize your napkin sketches and make them into beautiful pixels.', '', '', '', '0', '', '', '', 'y');


