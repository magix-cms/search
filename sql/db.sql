CREATE TABLE IF NOT EXISTS `mc_search` (
  `id_config` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fulltext_search` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_config`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_search` (`fulltext_search`) VALUES (0);

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'search';