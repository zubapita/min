DROP TABLE IF EXISTS outline;
CREATE TABLE `outline` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` varchar(32) DEFAULT NULL,
  `text` varchar(1024) DEFAULT NULL,
  `lft` double DEFAULT NULL,
  `rgt` double DEFAULT NULL,
  `opened` varchar(5) DEFAULT NULL,
  `disabled` varchar(5) DEFAULT NULL,
  `selected` varchar(5) DEFAULT NULL,
  `userauthId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;