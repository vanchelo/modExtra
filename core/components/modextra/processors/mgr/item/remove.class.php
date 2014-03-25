<?php
/**
 * Remove an Item
 */
class modExtraItemRemoveProcessor extends modObjectRemoveProcessor {
	public $objectType = 'modExtraItem';
	public $classKey = 'modExtraItem';
	public $checkRemovePermission = true;
	public $languageTopics = ['modextra'];
}

return 'modExtraItemRemoveProcessor';
