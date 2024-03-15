CREATE TABLE IF NOT EXISTS `mc_search` (
  `id_config` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fulltext_search` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_config`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_search` (`fulltext_search`) VALUES (0);