<?php
class Secret extends ModelBase {
  public $id, $name;

  protected function init($row)
  {
    $this->id = intval($row['id'], 10);
    $this->name = $row['name'];
    return $this;
  }
}
