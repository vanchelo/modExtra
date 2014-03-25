<?php
$xpdo_meta_map['modExtraItem'] = array(
    'package' => 'modextra',
    'version' => '1.1',
    'table' => 'modextra_items',
    'extends' => 'xPDOSimpleObject',
    'fields' => array(
        'name' => '',
        'published' => 0,
        'publishedon' => 0,
        'description' => '',
    ),
    'fieldMeta' => array(
        'name' => array(
            'dbtype' => 'varchar',
            'precision' => '100',
            'phptype' => 'string',
            'null' => false,
            'default' => '',
        ),
        'published' => array(
            'dbtype' => 'tinyint',
            'precision' => '1',
            'phptype' => 'boolean',
            'attributes' => 'unsigned',
            'default' => 0
        ),
        'publishedon' => array(
            'dbtype' => 'int',
            'precision' => '20',
            'phptype' => 'timestamp',
            'attributes' => 'unsigned',
            'default' => 0
        ),
        'description' => array(
            'dbtype' => 'text',
            'phptype' => 'text',
            'null' => true,
            'default' => '',
        ),
    ),
    'indexes' => array(
        'name' => array(
            'alias' => 'name',
            'primary' => false,
            'unique' => false,
            'type' => 'BTREE',
            'columns' => array(
                'name' => array(
                    'length' => '',
                    'collation' => 'A',
                    'null' => false,
                ),
            ),
        ),
    ),
);
