<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
 /**
 * MAGIX CMS
 * @category   advantage
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2015 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * Author: Salvatore Di Salvo
 * Date: 17-12-15
 * Time: 10:38
 * @name plugins_advantage_public
 * Le plugin advantage
 */
class plugins_search_public extends plugins_search_db {
    /**
     * @var object
     */
    protected $template, $data, $lang, $module, $mods;

	/**
	 * @var string
	 */
    public $query,$filter;
    /**
     * @var string $controller
     */
    public string $controller;

	/**
	 * plugins_search_public constructor.
	 * @param $t
	 */
    public function __construct(frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this,$this->template);
        $this->lang = $this->template->lang;
		$this->module = new frontend_model_module();
		$this->mods = $this->module->load_module('search');
		$formClean = new form_inputEscape();

		if(http_request::isGet('query')) $this->query = $formClean->simpleClean($_GET['query']);
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}
    /**
     *
     */
    private function loadModules() {
        $this->module = new frontend_model_module($this->template);
        if(empty($this->mods)) $this->mods = $this->module->load_module('search');
    }
	/**
	 * @param $data
	 * @param $model
	 * @return array
     * @deprecated
	 */
	private function setItems($data,$model)
	{
		$formatedData = array();
		if($data && $model) {
			foreach ($data as $item) {
				$formatedData[] = $model->setItemData($item,null);
			}
		}
		return $formatedData;
	}

	/**
	 * Assign data to the defined value
     * @deprecated
     */
	public function setConfigData(){
		$newArray = array();
		$config = $this->getItems('modules',null,'all',false);
		foreach($config as $key){
			$newArray[$key['attr_name']] = $key['status'];
		}
		return $newArray;
	}

    /**
     * @param $arr
     * @return bool
     */
	private function is_array_empty($arr) : bool
	{
		$Result = true;

		if (is_array($arr) && count($arr) > 0)
		{
			foreach ($arr as $Value)
			{
				$Result = $Result && $this->is_array_empty($Value);
			}
		}
		else
		{
			$Result = empty($arr);
		}

		return $Result;
	}
    public function extendListProduct(array $data): array {
        return [];
    }
    /**
     * @param array $filter
     * @return array
     */
    public function getProductList(array $filter = []): array {
        if(http_request::isGet('controller')) $this->controller = form_inputEscape::simpleClean($_GET['controller']);
        $extend = [];
        if(isset($this->controller) && $this->controller == 'search') {
            $config = $this->getItems('search', null, 'one', false);
            if (http_request::isGet('filter')) $this->filter = form_inputEscape::arrayClean($_GET['filter']);
            //print_r($this->query);
            if (isset($filter['query']) && $filter['query'] !== '') {
                //print_r($filter);
                if ($config['fulltext_search']) {
                    $extend['extendQueryParams']['filter'] = [
                        [
                            'type' => 'AND',
                            'condition' => 'pc.name_p LIKE ' . '"%' . $this->filter['query'] . '%"' .
                                ' OR MATCH (pc.content_p)
                            AGAINST ("' . $this->filter['query'] . '" IN NATURAL LANGUAGE MODE)'
                        ]
                    ];
                } else {
                    $extend['extendQueryParams']['filter'] = [
                        [
                            'type' => 'AND',
                            'condition' => 'pc.name_p LIKE ' . '"%' . $this->filter['query'] . '%"'
                        ]
                    ];
                }
            }
        }
        $extend['newRow'] = ['search' => 'search'];
        $extend['collection'] = 'search';
        // print_r($extend);
        return $extend;
    }
    //
    /**
     * @param array $filter
     * @return array
     */
    public function getPagesList(array $filter = []): array {
        $extend = [];
        $config = $this->getItems('search', null, 'one',false);
        if(http_request::isGet('filter')) $this->filter = form_inputEscape::arrayClean($_GET['filter']);
        //print_r($this->query);
        //print_r($filter);
        if(isset($filter['query']) && $filter['query'] !== ''){
            if($config['fulltext_search']) {
                $extend['extendQueryParams']['filter'] = [
                    [
                        'type' => 'AND',
                        'condition' => 'pc.name_pages LIKE '.'"%'.$this->filter['query'].'%"'.
                            ' OR MATCH (pc.content_pages)
                            AGAINST ("'.$this->filter['query'].'" IN NATURAL LANGUAGE MODE)'
                    ]
                ];
            }else {
                $extend['extendQueryParams']['filter'] = [
                    [
                        'type' => 'AND',
                        'condition' => 'pc.name_pages LIKE ' . '"%' . $this->filter['query'] . '%"'
                    ]
                ];
            }
        }

        $extend['newRow'] = ['search' => 'search'];
        $extend['collection'] = 'search';
        // print_r($extend);
        return $extend;
    }
	/**
	 *
	 */
	public function run()
	{
        if(http_request::isGet('filter')) $this->filter = form_inputEscape::arrayClean($_GET['filter']);
        if(isset($this->filter) && is_array($this->filter)) {
			$config = $this->getItems('search', null, 'one',false);
			/*$modules = $this->setConfigData();
			$results = array();
			if($config['fulltext_search']) {
				$params = array(
					'name_needle' => '%'.$this->query.'%',
					'content_needle' => $this->query,
					'lang' => $this->lang
				);
			}
			else {
				$params = array(
					'needle' => '%'.$this->query.'%',
					'lang' => $this->lang
				);
			}*/
            //$limit = $params['limit'] ?? 8;
            //['limit' => $limit]
			/*$results['s_about'] = ($modules['about']) ? $this->getItems(($config['fulltext_search'] ? 'ft_':'').'about',$params,'all',false) : null;
			$results['s_pages'] = ($modules['pages']) ? $this->getItems(($config['fulltext_search'] ? 'ft_':'').'pages',$params,'all',false) : null;
			$results['s_categories'] = ($modules['catalog']) ? $this->getItems(($config['fulltext_search'] ? 'ft_':'').'categories',$params,'all',false) : null;*/
			//$pparams = array_merge($params,['town' => '%'.$this->query.'%']);
			//$results['s_products'] = ($modules['catalog']) ? $this->getItems(($config['fulltext_search'] ? 'ft_':'').'products',$params,'all',false) : null;
            $product = new frontend_controller_catalog();
            $results['s_products'] = $product->getProductList(null, false, []);
            $pages = new frontend_controller_pages();
            $results['s_pages'] = $pages->getPagesList(null, []);
            //$monthly = $product->getProductList(null, false, ['type_offers' => 'monthly', 'limit' => $limit]);

            //$results['s_news'] = ($modules['news']) ? $this->getItems(($config['fulltext_search'] ? 'ft_':'').'news',$params,'all',false) : null;

			/*if($results['s_about']) {
				$modelAbout = new frontend_model_about();
				$results['s_about'] = $this->setItems($results['s_about'],$modelAbout);
			}
			if($results['s_pages']) {
				$modelPages = new frontend_model_pages();
				$results['s_pages'] = $this->setItems($results['s_pages'],$modelPages);
			}
			if($results['s_categories']) {
				$modelCategories = new frontend_model_catalog();
				$results['s_categories'] = $this->setItems($results['s_categories'],$modelCategories);
			}*/
			/*if($results['s_products']) {
				$modelProducts = new frontend_model_catalog();
				$results['s_products'] = $this->setItems($results['s_products'],$modelProducts);
			}*/
			/*if($results['s_news']) {
				$modelNews = new frontend_model_news();
				$results['s_news'] = $this->setItems($results['s_news'],$modelNews);
			}*/

			/*if(!empty($this->mods)) {
				foreach ($this->mods as $name => $mod) {
					if(method_exists($mod,'search')) {
						$return = $mod->search($params, $config['fulltext_search']);
						$results = array_merge($results, $return);
					}
				}
			}*/
            //$this->loadModules();
            //print_r($this->mods);

            $this->template->breadcrumb->addItem($this->template->getConfigVars('search'));
			if($this->is_array_empty($results)) {
				$this->template->assign('msg',sprintf($this->template->getConfigVars('no_result_msg'),$this->filter['query']));
			}
            //print($_GET['filter']['query']);
			$this->template->assign('results',$results);
			$this->template->assign('needle',$this->filter['query']);
			$this->template->display('search/index.tpl');
		}
	}
}