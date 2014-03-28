<?php

require_once 'Controller.php';

class MainController extends Controller {

    const REQUEST_PARAM = 'item';
    /**
     * @var ItemsRepository
     */
    protected $items;

    protected function init()
    {
        $this->items = $this->modExtra->items();
    }

    protected function indexAction()
    {
        /*
        $items = [];
        foreach ($this->items->all() as $i)
        {
            $items[] = $this->modExtra->getFluent($i);
        }
        */

        if (isset($_GET[self::REQUEST_PARAM]) and $id = (int) $_GET[self::REQUEST_PARAM])
        {
            return $this->singleAction($id);
        }

        return $this->modExtra->render('items', [
            'items' => $this->items->all()
        ]);
    }

    protected function singleAction($id = 0)
    {
        $item = $this->items->get($id);

        if ( ! $item)
        {
            return $this->error();
        }

        return $this->modExtra->render('item', [
            'item' => $item
        ]);
    }

}
