<?php

// require_once __DIR__ . '/../../libraries/helpers.php';

/**
 * The base class for modExtra
 */
class modExtra {
	/**
     * @var modX $modx
     */
	protected $modx;
	/**
     * @var modExtraControllerRequest $request
     */
	protected $request;
    /**
     * @var ItemsRepository
     */
    protected $items;
	protected $chunks = [];


	/**
     * modExtra Constructor
     *
	 * @param modX $modx
	 * @param array $config
	 */
	public function __construct(modX $modx, array $config = [])
    {
		$this->modx = $modx;

		$corePath = $this->modx->getOption('modextra_core_path', $config, $this->modx->getOption('core_path') . 'components/modextra/');
		$assetsUrl = $this->modx->getOption('modextra_assets_url', $config, $this->modx->getOption('assets_url') . 'components/modextra/');
		$connectorUrl = $assetsUrl . 'connector.php';

        $this->config = array_merge([
            'assetsUrl'        => $assetsUrl,
            'cssUrl'           => $assetsUrl . 'css/',
            'jsUrl'            => $assetsUrl . 'js/',
            'imagesUrl'        => $assetsUrl . 'images/',
            'connectorUrl'     => $connectorUrl,

            'corePath'         => $corePath,
            'repositoriesPath' => $corePath . 'repositories/',
            'controllersPath'  => $corePath . 'controllers/',
            'librariesPath'    => $corePath . 'libraries/',
            'modelPath'        => $corePath . 'model/',
            'chunksPath'       => $corePath . 'elements/chunks/',
            'templatesPath'    => $corePath . 'elements/templates/',
            'chunkSuffix'      => '.chunk.tpl',
            'snippetsPath'     => $corePath . 'elements/snippets/',
            'processorsPath'   => $corePath . 'processors/'
        ], $config);

		$this->modx->addPackage('modextra', $this->config['modelPath']);
		$this->modx->lexicon->load('modextra:default');
	}


	/**
	 * Initializes modExtra into different contexts.
	 *
	 * @access public
	 *
	 * @param string $ctx The context to load. Defaults to web.
     *
     * @return mixed
	 */
	public function initialize($ctx = 'web')
    {
		switch ($ctx) {
			case 'mgr':
				if ( ! $this->modx->loadClass('modextra.request.modExtraControllerRequest', $this->config['modelPath'], true, true))
                {
					return 'Could not load controller request handler.';
				}

				$this->request = new modExtraControllerRequest($this);

				return $this->request->handleRequest();
				break;
			case 'web':
				break;
			default:
				/* if you wanted to do any generic frontend stuff here.
				 * For example, if you have a lot of snippets but common code
				 * in them all at the beginning, you could put it here and just
				 * call $modextra->initialize($modx->context->get('key'));
				 * which would run this.
				 */
				break;
		}

		return true;
	}


    /**
     * Items Repository
     *
     * @return ItemsRepository
     */
    public function items()
    {
        if ( ! isset($this->items))
        {
            require_once $this->config['repositoriesPath'] . 'ItemsRepository.php';

            $this->items = new ItemsRepository($this->modx, $this);
        }

        return $this->items;
    }


    /**
     * Main application controller
     *
     * @param string $action
     *
     * @return MainController
     */
    public function controller($action = null)
    {
        static $controller;
        if ( ! isset($controller))
        {
            require_once $this->config['controllersPath'] . 'MainController.php';

            $controller = new MainController($this->modx, $this);
        }

        if ($action) return $controller($action);

        return $controller;
    }


    /**
     * View Service
     *
     * @return View
     */
    public function getView()
    {
        if ( ! isset($this->view))
        {
            require_once $this->config['librariesPath'] . 'View.php';

            $this->view = new View($this->config['corePath'] . 'elements/views/');

            $this->view->share('modExtra', $this);
            $this->view->share('modx', $this->modx);
        }

        return $this->view;
    }


    /**
     * Data container
     *
     * @param $data
     * @return Container
     */
    public function getContainer($data = array())
    {
        if ( ! class_exists('Container'))
        {
            require_once $this->config['librariesPath'] . 'Container.php';
        }

        if (is_object($data) and method_exists($data, 'toArray'))
        {
            $data = $data->toArray();
        }

        return new Container((array) $data);
    }


    /**
     * Fluent Data container
     *
     * @param $data
     * @return Fluent
     */
    public function getFluent($data = array())
    {
        if ( ! class_exists('Fluent'))
        {
            require_once $this->config['librariesPath'] . 'Fluent.php';
        }

        if (is_object($data) and method_exists($data, 'toArray'))
        {
            $data = $data->toArray();
        }

        return new Fluent((array) $data);
    }


    public function getModx()
    {
        return $this->modx;
    }


    public function render($tpl, $data = [])
    {
        return $this->getView()->render($tpl, $data);
    }


	/**
	 * Gets a Chunk and caches it; also falls back to file-based templates
	 * for easier debugging.
	 *
	 * @access public
	 *
	 * @param string $name The name of the Chunk
	 * @param array $properties The properties for the Chunk
	 *
	 * @return string The processed content of the Chunk
	 */
	public function getChunk($name, array $properties = [])
    {
		$chunk = null;
		if ( ! isset($this->chunks[$name]))
        {
			$chunk = $this->modx->getObject('modChunk', ['name' => $name], true);
			if ( ! $chunk)
            {
				$chunk = $this->_getTplChunk($name, $this->config['chunkSuffix']);
				if ($chunk == false)
                {
                    return false;
                }
			}

			$this->chunks[$name] = $chunk->getContent();
		}
		else
        {
			$o = $this->chunks[$name];
			$chunk = $this->modx->newObject('modChunk');
			$chunk->setContent($o);
		}

		$chunk->setCacheable(false);

		return $chunk->process($properties);
	}


	/**
	 * Returns a modChunk object from a template file.
	 *
	 * @access private
	 *
	 * @param string $name The name of the Chunk. Will parse to name.chunk.tpl by default.
	 * @param string $suffix The suffix to add to the chunk filename.
	 *
	 * @return modChunk/boolean Returns the modChunk object if found, otherwise
	 * false.
	 */
	private function _getTplChunk($name, $suffix = '.chunk.tpl') {
		$chunk = false;
		$f = $this->config['chunksPath'] . strtolower($name) . $suffix;
		if (file_exists($f))
        {
			$o = file_get_contents($f);
			$chunk = $this->modx->newObject('modChunk');
			$chunk->set('name', $name);
			$chunk->setContent($o);
		}

		return $chunk;
	}
}
