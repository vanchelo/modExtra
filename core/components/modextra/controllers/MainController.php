<?php

require 'Controller.php';

class MainController extends Controller {

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
        return $this->modExtra->render('items', [
            'items' => $this->items->all()
        ]);
    }

    protected function singleAction()
    {
        if ( ! isset($_GET['id']) or ! $id = (int) $_GET['id'])
        {
            return $this->error();
        }

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
