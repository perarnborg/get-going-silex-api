<?php
class ModelBase {
  private $app, $db, $class;

  public function __construct($app, $row = false)
  {
    if($app)
    {
      $this->app = $app;
      $this->class = get_class($this);
      $this->db = new Db($app, strtolower($this->class) . 's');
    }
    if($row)
    {
      $this->init($row);
    }
  }

  public function get($id, $ignoreCache = false) {
    return $this->getWhere(array('id' => $id), $ignoreCache);
  }

  public function getForUser($userId, $id, $ignoreCache = false) {
    return $this->getWhere(array('user_id' => $userId, 'id' => $id), $ignoreCache);
  }

  public function getWhere($params, $ignoreCache = false) {
    $row = $this->db->getWhere($params, $ignoreCache);
    if($row)
    {
      return $this->getObj($row);
    }
    return null;
  }

  public function indexForUser($userId, $params = array(), $ignoreCache = false) {
    $params['user_id'] = $userId;
    return $this->index($params, $ignoreCache);
  }

  public function index($params = array(), $ignoreCache = false) {
    $list = array();
    foreach ($this->db->index($params, $ignoreCache) as $row) {
      $list[] = $this->getObj($row);
    }
    return $list;
  }

  public function save() {
  }

  public function delete() {
  }
}
