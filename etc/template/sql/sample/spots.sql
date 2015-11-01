DROP TABLE IF EXISTS spots;
CREATE TABLE `spots` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `kana` varchar(255) DEFAULT '',
  `romaji` varchar(255) DEFAULT '',
  `address1` varchar(255) NOT NULL DEFAULT '',
  `address2` varchar(255) DEFAULT '',
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`kana`,`romaji`)
) ENGINE=InnoDB ADEFAULT CHARSET=utf8;