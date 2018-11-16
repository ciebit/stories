CREATE TABLE IF NOT EXISTS `cb_stories` (
  `id`       int(5) unsigned NOT NULL AUTO_INCREMENT,
  `title`    varchar(300) DEFAULT NULL,
  `summary`  varchar(500) DEFAULT NULL,
  `body`     text,
  `datetime` datetime DEFAULT NULL,
  `uri`      varchar(300) DEFAULT NULL,
  `views`    int(10) unsigned NOT NULL DEFAULT '0',
  `language`    varchar(6) DEFAULT NULL,
  `status`   tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='version:2.0';
