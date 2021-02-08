<?php
namespace vale\hcf\manager;

class RanksManager{

  public $database;
  
public function __construct(HCF $plugin){
$this->database = mysqli_connect();
}

}
