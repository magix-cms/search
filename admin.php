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
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
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
 * Date: 16-12-15
 * Time: 14:00
 * @name plugins_advantage_admin
 * Le plugin advantage
 */
class plugins_search_admin extends plugins_search_db {
	/**
	 * @var object
	 */
	protected $controller,
		$data,
		$message,
		$module,
		$mods,
		$template;

	/**
	 * Les variables globales
	 * @var integer $edit
	 * @var string $action
	 * @var string $tabs
	 */
	public $edit = 0,
		$action = '',
		$tabs = '';

	/**
	 * Les variables plugin
	 * @var array $search
	 */
	public
		$search = array();

    /**
	 * Construct class
	 */
	public function __construct(){
		$this->template = new backend_model_template();
		$this->data = new backend_model_data($this);
		$this->module = new backend_controller_module();
		$this->mods = $this->module->load_module('search');
		$this->message = new component_core_message($this->template);

		$formClean = new form_inputEscape();

		// --- GET
		if(http_request::isGet('controller')) {
			$this->controller = $formClean->simpleClean($_GET['controller']);
		}
		if (http_request::isGet('edit')) {
			$this->edit = $formClean->numeric($_GET['edit']);
		}
		if (http_request::isGet('action')) {
			$this->action = $formClean->simpleClean($_GET['action']);
		} elseif (http_request::isPost('action')) {
			$this->action = $formClean->simpleClean($_POST['action']);
		}
		if (http_request::isGet('tabs')) {
			$this->tabs = $formClean->simpleClean($_GET['tabs']);
		}

		if (http_request::isPost('search')) {
			$this->search = $formClean->arrayClean($_POST['search']);
		}
	}

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName()
	{
		return $this->template->getConfigVars('search_plugin');
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
	 * Update data
	 * @param array $config
	 */
	private function upd($config)
	{
		switch ($config['type']) {
			case 'config':
				parent::update(
					array('type' => $config['type']),
					$config['data']
				);
				break;
		}
	}

	/**
	 * Execute the plugin
	 */
	public function run()
	{
		if ($this->action) {
			switch ($this->action) {
				case 'edit':
					if (isset($this->search)) {
						$config = $this->getItems('search', null, 'one',false);
						$this->search['fulltext'] = (isset($this->search['fulltext']) && $this->search['fulltext']) ? 1 : 0;
						$this->upd(array(
							'type' => 'config',
							'data' => $this->search
						));
						try {
							if($this->search['fulltext'] !== $config['fulltext_search']) {
								$routingDB = new component_routing_db();
								$filepath = component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.'search'.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR;

								if($this->search['fulltext']) {
									$files = $filepath.'add_fulltext.sql';
									if(file_exists($files)) $routingDB->setupSQL($files);
								}
								else {
									$files = $filepath.'drop_fulltext.sql';
									if(file_exists($files)) $routingDB->setupSQL($files);
								}
							}
							$this->message->json_post_response(true, 'update');
						}
						catch (Exception $e) {
							$this->message->json_post_response(false, 'error','Exception reçue : '.$e->getMessage());
						}

						if(!empty($this->mods)) {
							foreach ($this->mods as $name => $mod) {
								if(method_exists($mod,'toggle_fulltext')) {
									$mod->toggle_fulltext($this->search['fulltext'] ? 1 : 0);
								}
							}
						}
					}
					break;
			}
		}
		else {
			$this->getItems('search', null, 'one');
			$this->template->display('index.tpl');
		}
	}
}