<?php
/**
 * Class modExtraMainController
 */

require_once __DIR__ . '/model/modextra/modextra.class.php';

abstract class modExtraMainController extends modExtraManagerController {
	/**
     * @var modExtra $modExtra
     */
	public $modExtra;

	/**
	 * @return void
	 */
	public function initialize()
    {
		$this->modExtra = new modExtra($this->modx);

        $this->addCss($this->modExtra->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->modExtra->config['jsUrl'] . 'mgr/modextra.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			modExtra.config = ' . json_encode($this->modExtra->config) . ';
			modExtra.config.connector_url = "' . $this->modExtra->config['connectorUrl'] . '";
		});
		</script>');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics()
    {
		return ['modextra:default'];
	}


	/**
	 * @return bool
	 */
	public function checkPermissions()
    {
		return true;
	}
}

/**
 * Class IndexManagerController
 */
class IndexManagerController extends modExtraMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController()
    {
		return 'home';
	}
}
