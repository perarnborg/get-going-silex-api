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

  public function getRow($post, $user, $id = false)
  {
    $row = $this->getRowBase($user, $id);
    $this->setRowValueFromPost($post, 'name', $row);

    return $row;
  }
}
