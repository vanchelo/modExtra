<?php

require_once 'update.class.php';

class modExtraItemUpdateFromGridProcessor extends modExtraItemUpdateProcessor {

    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = $this->modx->fromJSON($data);
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}

return 'modExtraItemUpdateFromGridProcessor';
