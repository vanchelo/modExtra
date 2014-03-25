<?php

abstract class Controller {
    /**
     * @var modX
     */
    protected $modx;
    /**
     * @var modExtra
     */
    protected $modExtra;

    /**
     * Constructor
     *
     * @param modX $modx
     * @param modExtra $modExtra
     */
    public function __construct(modX $modx, modExtra $modExtra)
    {
        $this->modx = $modx;
        $this->modExtra = $modExtra;

        $this->init();
    }

    protected function init() {}

    public function run($action)
    {
        $action = $action . 'Action';
        if ( ! method_exists($this, $action)) return null;

        return $this->{$action}();
    }

    protected function error()
    {
        $this->modx->sendError();

        return null;
    }

    function __invoke()
    {
        return call_user_func_array([$this, 'run'], func_get_args());
    }

}
