CREATE TABLE IF NOT EXISTS `cb_stories` (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(300) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `body` text,
  `datetime` datetime DEFAULT NULL,
  `uri` varchar(300) DEFAULT NULL,
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `language` varchar(6) DEFAULT NULL,
  `languages_references` varchar(300) DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='version:3.1';
