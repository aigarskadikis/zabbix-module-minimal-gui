<?php

namespace Modules\TablesCustomCss;

use API;
use Core\CModule as CModule;
use CController as CAction;

class Module extends CModule {

	/**
	 * List of .css files to use on specific action/page
	 */
	protected $action_css = [
		"host/triggers.php"		=> "/public/host.triggers.php.css",
		"template/triggers.php"		=> "/public/template.triggers.php.css",
		"host/host_discovery.php"		=> "/public/host.host_discovery.php.css",
		"template/host_discovery.php"		=> "/public/template.host_discovery.php.css",
		"templates.php"		=> "/public/templates.php.css",
		"items.php"	=> "/public/items.php.css",
		"host_discovery.php"	=> "/public/host_discovery.php.css",
		"disc_prototypes.php"	=> "/public/disc_prototypes.php.css",
		"graphs.php"	=> "/public/graphs.php.css",
		"applications.php"	=> "/public/applications.php.css",
		"trigger_prototypes.php"	=> "/public/trigger_prototypes.php.css",
		"hosts.php"			=> "/public/hosts.php.css"
	];

	protected $css_file = '';

	/**
	 * Before action event handler.
	 *
	 * @param CAction $action    Current request handler object.
	 */
	public function onBeforeAction(CAction $action): void {
		$action_page = $action->getAction();


// exception for trigger page
		if ($action_page === 'triggers.php' && array_key_exists('filter_hostids', $_REQUEST)) {
			$is_host = API::Host()->get([
				'output' => null,
				'hostids' => $_REQUEST['filter_hostids']
			]);
			$action_page = $is_host ? 'host/triggers.php' : 'template/triggers.php';
		}

		// exception for LLD rule list
if ($action_page === 'host_discovery.php' && array_key_exists('filter_hostids', $_REQUEST)) {
                        $is_host = API::Host()->get([
                                'output' => null,
                                'hostids' => $_REQUEST['filter_hostids']
                        ]);
                        $action_page = $is_host ? 'host/host_discovery.php' : 'template/host_discovery.php';
                }




		if (array_key_exists($action_page, $this->action_css)) {
			$this->css_file = $this->action_css[$action_page];
		}
	}

	/**
	 * For login/logout actions update user seession state in multiple databases.
	 */
	public function onTerminate(CAction $action): void {
		$path = __DIR__.$this->css_file;

		if ($this->css_file !== '' && file_exists($path)) {
			echo '<style type="text/css">',file_get_contents($path),'</style>';
		}
	}
}
