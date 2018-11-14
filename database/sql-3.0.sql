CREATE TABLE `cb_stories` (
  `id` int(5) UNSIGNED NOT NULL NULL AUTO_INCREMENT,
  `title` varchar(300) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `body` text,
  `datetime` datetime DEFAULT NULL,
  `uri` varchar(300) DEFAULT NULL,
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `language` varchar(6) DEFAULT NULL,
  `languages_references` varchar(300) DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='version:1.0';

ALTER TABLE `cb_stories`
  ADD PRIMARY KEY (`id`);
