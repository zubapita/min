DROP TABLE IF EXISTS userauth;
CREATE TABLE `userauth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `entryAt` datetime NOT NULL,
  `updateAt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;