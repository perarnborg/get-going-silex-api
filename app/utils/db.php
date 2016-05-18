<?php
use Doctrine\DBAL\Schema\Table;

class Db {
  public $app, $table, $cache;

  public function __construct($app, $table)
  {
    $this->app = $app;
    $this->table = $table;
    $this->cache = new SimplePhpCacher('no');
    $this->connect();
  }

  public function get($id, $ignoreCache = false) {
    return $this->getWhere(array('id' => $id, $ignoreCache));
  }

  public function getWhere($params, $ignoreCache = false) {
    $cacheKeys = $this->getCacheKeys('getWhere', $params);
    if(!$ignoreCache) {
      $isCached = false;
      $cached = $this->cache->get($cacheKeys, $isCached);
      if($isCached) {
        return $cached;
      }
    }
    $bindValues = array();
    $sql = $this->getStatement('SELECT * FROM ' . $this->table, $params, $bindValues);
    $row = $this->app['db']->fetchAssoc($sql, $bindValues);
    $this->cache->set($cacheKeys, $row);
    return $row;
  }

  public function index($params = array(), $ignoreCache = false) {
    $cacheKeys = $this->getCacheKeys('index', $params);
    if(!$ignoreCache) {
      $isCached = false;
      $cached = $this->cache->get($cacheKeys, $isCached);
      if($isCached) {
        return $cached;
      }
    }
    $bindValues = array();
    $sql = $this->getStatement('SELECT * FROM ' . $this->table, $params, $bindValues);
    $rows = $this->app['db']->fetchAll($sql, $bindValues);
    $this->cache->set($cacheKeys, $rows);
    return $rows;
  }

  public function save($row) {
    if(isset($row['id']) && $row['id'])
    {
      return $this->update($row['id'], $row);
    } else {
      return $this->create($row);
    }
  }

  public function update($id, $row) {
    return $this->updateWhere(array('id' => $id), $row);
  }

  public function updateWhere($params, $row) {
    $affected = $this->app['db']->update($this->table, $row, $params);
    return $affected;
  }

  public function create($row) {
    $id = $this->app['db']->insert($this->table, $row);
    $row['id'] = $id;
    return $row;
  }

  public function delete($id) {
    return $this->deleteWhere(array('id' => $id));
  }

  public function deleteWhere($params) {
    $affected = $this->app['db']->delete($this->table, $params);
    return $affected;
  }

  private function getStatement($sql, $params = array(), &$bindValues = array()) {
    if($params)
    {
      $isFirst = true;
      foreach ($params as $field => $value) {
        if($isFirst)
        {
          $sql .= ' WHERE ';
          $isFirst = false;
        }
        else
        {
          $sql .= ' AND ';
        }
        $sql .= $field . ' = ?';
        $bindValues[] = $value;
      }
    }
    return $sql;
  }

  public function clearCache($method, $params) {
    $cacheKey = $this->getCacheKeys($method, $params);
    $this->cache->delete($cacheKeys);
  }

  private function getCacheKeys($method, $params = array()) {
    $keys = array($method);
    if($params) {
      foreach ($params as $key => $value) {
        $keys[] = $key . ':' . $value;
      }
    }
    return $keys;
  }

  private function connect()
  {
    if(isset($this->app['db'])) {
      return;
    }
    $this->app->register(new Silex\Provider\DoctrineServiceProvider(), array(
      'db.options' => array(
        'dbname' => 'silex',
        'user' => 'silexdb',
        'password' => 'silexdb',
        'host' => 'localhost',
        //        'port' => '8889',
        'driver' => 'pdo_mysql',
      ),
    ));


    $schema = $this->app['db']->getSchemaManager();
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
        'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
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

      $schema->createTable($secrets);

      $this->app['db']->insert('secrets', array(
        'name' => 'Love is great'
      ));
    }
  }
}
