DROP TABLE IF EXISTS authProvider;
CREATE TABLE `authProvider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `provider` varchar(20) NOT NULL DEFAULT '',
  `providerId` varchar(255) NOT NULL DEFAULT '',
  `updateAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
