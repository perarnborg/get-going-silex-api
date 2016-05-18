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
        'charset'  => 'utf8',
      ),
    ));


    $schema = $this->app['db']->getSchemaManager();

    include(__DIR__.'/../db/schema.php');

  }
}
