<?php

return [

    /*
     * Installation hook.
     */
    'install' => function ($app) {
        $util = $app['db']->getUtility();

        if ($util->tableExists('@galleries') === false) {
            $util->createTable('@galleries', function ($table) {
                $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
                $table->addColumn('user_id', 'integer', ['unsigned' => true, 'length' => 10, 'default' => 0]);
                $table->addColumn('title', 'string', ['length' => 255]);
                $table->addColumn('date', 'datetime');
                $table->addColumn('slug', 'string', ['length' => 255]);
                $table->addColumn('description', 'text', ['notnull' => false]);
                $table->addColumn('photograph', 'string', ['length' => 255, 'notnull' => false]);
                $table->addColumn('status', 'smallint');
                $table->addColumn('data', 'json_array', ['notnull' => false]);
                $table->addColumn('roles', 'simple_array', ['notnull' => false]);
                $table->addColumn('modified', 'datetime');
                $table->setPrimaryKey(['id']);
            });
        }

        if ($util->tableExists('@images') === false) {
            $util->createTable('@images', function ($table) {
                $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
                $table->addColumn('gallery_id', 'integer', ['unsigned' => true, 'length' => 10]);
                $table->addColumn('user_id', 'integer', ['unsigned' => true, 'length' => 10, 'default' => 0]);
                $table->addColumn('title', 'string', ['notnull' => false, 'length' => 255]);
                $table->addColumn('filename', 'string', ['length' => 255]);
                $table->addColumn('sort_order', 'integer', ['notnull' => false, 'length' => 10]);
                $table->addColumn('data', 'json_array', ['notnull' => false]);
                $table->addColumn('modified', 'datetime');
                $table->setPrimaryKey(['id']);
            });
        }
    },

    /*
     * Uninstall hook
     */
    'uninstall' => function ($app) {
        $app['config']->remove('gallery');

        $util = $app['db']->getUtility();

        if ($util->tableExists('@galleries')) {
            $util->dropTable('@galleries');
        }

        if ($util->tableExists('@images')) {
            $util->dropTable('@images');
        }
    },
];
