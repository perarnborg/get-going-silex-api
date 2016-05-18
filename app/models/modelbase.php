<?php
class ModelBase {
  private $app, $db, $class;

  public function __construct($app, $row = false)
  {
    $this->app = $app;
    $this->class = get_class($this);
    $this->db = new Db($app, strtolower($this->class) . 's');
    if($row)
    {
      $this->init($row);
    }
  }

  public function get($id, $ignoreCache = false) {
    return $this->getWhere(array('id' => $id), $ignoreCache);
  }

  public function getWhere($params, $ignoreCache = false) {
    $row = $this->db->getWhere($params, $ignoreCache);
    if($row)
    {
      return new $this->class($this->app, $row);
    }
    return null;
  }

  public function index($params = array(), $ignoreCache = false) {
    $list = array();
    foreach ($this->db->index($params, $ignoreCache) as $row) {
      $list[] = new $this->class($this->app, $row);
    }
    return $list;
  }

  public function save() {
  }

  public function delete() {
  }
}
