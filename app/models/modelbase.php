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

  public function get($id, $asObject = true, $ignoreCache = false) {
    return $this->getWhere(array('id' => $id), $asObject, $ignoreCache);
  }

  public function getForUser($userId, $id, $asObject = true, $ignoreCache = false) {
    return $this->getWhere(array('user_id' => $userId, 'id' => $id), $asObject, $ignoreCache);
  }

  public function getForUserOrAdmin($user, $id, $asObject = true, $ignoreCache = false) {
    if(!in_array('ROLE_ADMIN', $user->roles))
    {
      $row = $this->getForUser($user->id, $id, $asObject, $ignoreCache])
    } else {
      $row = $this->get($id, $asObject, $ignoreCache);
    }
  }

  public function getWhere($params, $asObject = true, $ignoreCache = false) {
    $row = $this->db->getWhere($params, $asObject, $ignoreCache);
    if($row)
    {
      return $asObject ? $this->getObj($row) : $row;
    }
    return null;
  }

  public function indexForUser($userId, $params = array(), $asObjects = true,  $ignoreCache = false) {
    $params['user_id'] = $userId;
    return $this->index($params, $asObjects, $ignoreCache);
  }

  public function index($params = array(), $asObjects = true, $ignoreCache = false) {
    $list = array();
    foreach ($this->db->index($params, $asObjects, $ignoreCache) as $row) {
      $list[] = $asObjects ? $this->getObj($row) : $row;
    }
    return $list;
  }

  public function save($row) {
    $this->db->save($row);
  }

  public function delete() {
  }

  protected function extractValue($post, $param) {
    return isset($post[$param]) ? $post[$param] : null;
  }

  protected function setRowValueFromPost($post, $param, &$row) {
    if(isset($post[$param]))
    {
      $row[$param] = $post[$param];
    }
  }
}
