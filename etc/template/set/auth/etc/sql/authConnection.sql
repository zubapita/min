DROP TABLE IF EXISTS authConnection;
CREATE TABLE `authConnection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `provider` varchar(20) NOT NULL,
  `hybridauthSession` text NOT NULL,
  `updateAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userIdProvider` (`userId`,`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;