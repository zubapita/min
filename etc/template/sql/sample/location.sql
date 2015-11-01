DROP TABLE IF EXISTS location;
CREATE TABLE `location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `spotsId` int(11) NOT NULL,
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL,
  `lstlng` geometrycollection NOT NULL,
  PRIMARY KEY (`id`),
  SPATIAL KEY `geo` (`lstlng`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
