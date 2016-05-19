<?php
class Secret extends ModelBase {
  public $id, $name;

  protected function getObj($row)
  {
    $obj = new stdClass();
    $obj->id = intval($row['id'], 10);
    $obj->name = $row['name'];
    return $obj;
  }

  protected function getRow($post, $user, $id = false)
  {
    $row = array();
    if($id)
    {
      $row = $this->getForUserOrAdmin($user, $id, false, true);
    } else {
      $row['user_id'] = $user['id'];
    }
    $this->setRowValueFromPost($post, 'name', $row);

    return $row;
  }
}
