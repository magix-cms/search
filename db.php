<?php
class plugins_search_db
{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'about':
					$sql = "SELECT * 
							FROM mc_about_page AS p 
							LEFT JOIN mc_about_page_content AS pc USING(id_pages)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang AND pc.name_pages LIKE :needle";
					break;
				case 'ft_about':
					$sql = "SELECT * 
							FROM mc_about_page AS p 
							LEFT JOIN mc_about_page_content AS pc USING(id_pages)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang
							AND (
							  	pc.name_pages LIKE :name_needle
								OR MATCH (pc.content_pages)
        							AGAINST (:content_needle IN NATURAL LANGUAGE MODE)
							)";
					break;
				case 'pages':
					$sql = "SELECT * 
							FROM mc_cms_page AS p 
							LEFT JOIN mc_cms_page_content AS pc USING(id_pages)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang AND pc.name_pages LIKE :needle";
					break;
				case 'ft_pages':
					$sql = "SELECT * 
							FROM mc_cms_page AS p 
							LEFT JOIN mc_cms_page_content AS pc USING(id_pages)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang 
							AND (
							  	pc.name_pages LIKE :name_needle
								OR MATCH (pc.content_pages)
        							AGAINST (:content_needle IN NATURAL LANGUAGE MODE)
							)";
					break;
				case 'categories':
					$sql = "SELECT * 
							FROM mc_catalog_cat AS p 
							LEFT JOIN mc_catalog_cat_content AS pc USING(id_cat)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang AND pc.name_cat LIKE :needle";
					break;
				case 'ft_categories':
					$sql = "SELECT * 
							FROM mc_catalog_cat AS p 
							LEFT JOIN mc_catalog_cat_content AS pc USING(id_cat)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang
							AND (
							  	pc.name_cat LIKE :name_needle
								OR MATCH (pc.content_cat)
        							AGAINST (:content_needle IN NATURAL LANGUAGE MODE)
							)";
					break;
				case 'products':
					$sql = "SELECT * 
							FROM mc_catalog_product AS p 
							LEFT JOIN mc_catalog_product_content AS pc USING(id_product)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang AND pc.name_p LIKE :needle";
					break;
				case 'ft_products':
					$sql = "SELECT * 
							FROM mc_catalog_product AS p 
							LEFT JOIN mc_catalog_product_content AS pc USING(id_product)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang
							AND (
							  	pc.name_p LIKE :name_needle
								OR MATCH (pc.content_p)
        							AGAINST (:content_needle IN NATURAL LANGUAGE MODE)
							)";
					break;
				case 'news':
					$sql = "SELECT * 
							FROM mc_news AS p 
							LEFT JOIN mc_news_content AS pc USING(id_news)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang AND pc.name_news LIKE :needle";
					break;
				case 'ft_news':
					$sql = "SELECT * 
							FROM mc_news AS p 
							LEFT JOIN mc_news_content AS pc USING(id_news)
							LEFT JOIN mc_lang as lg USING(id_lang)
							WHERE lg.iso_lang = :lang
							AND (
							  	pc.name_news LIKE :name_needle
								OR MATCH (pc.content_news)
        							AGAINST (:content_needle IN NATURAL LANGUAGE MODE)
							)";
					break;
				case 'modules':
					$sql = 'SELECT * FROM mc_config';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'search':
					$sql = "SELECT * FROM mc_search ORDER BY id_config DESC LIMIT 0,1";
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'config':
				$sql = 'UPDATE mc_search 
						SET fulltext_search = :fulltext
						WHERE id_config = :id_config';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}