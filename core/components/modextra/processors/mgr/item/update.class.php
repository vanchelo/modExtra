<?php
/**
 * Update an Item
 */
class modExtraItemUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'modExtraItem';
	public $classKey = 'modExtraItem';
	public $permission = 'edit_document';
	public $languageTopics = ['modextra'];
}

return 'modExtraItemUpdateProcessor';
