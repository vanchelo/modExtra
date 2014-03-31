<?php
require_once dirname(__FILE__) . '/update.class.php';
/**
 * Update an item from a grid
 *
 * @package modExtra
 * @subpackage processors.mgr.item
 */
class modExtraItemUpdateFromGridProcessor extends modExtraItemUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = $this->modx->fromJSON($data);
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
return 'modExtraItemUpdateFromGridProcessor';
