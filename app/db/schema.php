<?php
    if (!$schema->tablesExist('users')) {
      $users = new Table('users');
      $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
      $users->setPrimaryKey(array('id'));
      $users->addColumn('username', 'string', array('length' => 32));
      $users->addUniqueIndex(array('username'));
      $users->addColumn('password', 'string', array('length' => 255));
      $users->addColumn('roles', 'string', array('length' => 255));

      $schema->createTable($users);

      $this->app['db']->insert('users', array(
        'username' => 'admin',
        'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', // chimay or admin?
        'roles' => 'ROLE_ADMIN'
      ));
    }

    if (!$schema->tablesExist('colors')) {
      $colors = new Table('colors');
      $colors->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
      $colors->setPrimaryKey(array('id'));
      $colors->addColumn('name', 'string', array('length' => 32));
      $colors->addUniqueIndex(array('name'));

      $schema->createTable($colors);

      $this->app['db']->insert('colors', array(
        'name' => 'Red'
      ));
      $this->app['db']->insert('colors', array(
        'name' => 'Green'
      ));
      $this->app['db']->insert('colors', array(
        'name' => 'Blue'
      ));
    }

    if (!$schema->tablesExist('secrets')) {
      $secrets = new Table('secrets');
      $secrets->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
      $secrets->setPrimaryKey(array('id'));
      $secrets->addColumn('name', 'string', array('length' => 32));
      $secrets->addUniqueIndex(array('name'));
      $colors->addColumn('user_id', 'integer');

      $schema->createTable($secrets);

      $this->app['db']->insert('secrets', array(
        'name' => 'Love is great'
      ));
    }
