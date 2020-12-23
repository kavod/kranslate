CREATE TABLE IF NOT EXISTS `kranslate_tradLine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin` varchar(128) DEFAULT NULL,
  `lang` varchar(128) DEFAULT NULL,
  `file_path` varchar(128) DEFAULT NULL,
  `from` TEXT DEFAULT NULL,
  `to` TEXT DEFAULT NULL,
  `unused` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
