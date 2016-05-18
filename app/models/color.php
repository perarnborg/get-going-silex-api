<?php
class Color extends ModelBase {
  public $id, $name;

  protected function getObj($row)
  {
    $obj = new stdClass();
    $obj->id = intval($row['id'], 10);
    $obj->name = $row['name'];
    return $obj;
  }
}
