<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
class Memcache {
  
    private $memInstance;
    
    public function getData($data) {
        return $this->memInstance->get($data);
    }
    
    public function setData($variable, $data) {
        $this->memInstance->set($variable,$data);
    }
    
    public function setPlateauData($roverName, $coordinates, $heading) {
        $memInstance = new Memcached();
        $memInstance->addServer("localhost", 11211);
        $memInstance->set($roverName, array(
            'name'=>$roverName,
            'coordinates'=>$coordinates,
            'heading'=>$heading
        ));
    }
    
    public function setRoverData($plateauId, $roverName, $x, $y, $heading) {
        $this->memInstance->setData($roverName, array(
            'plateauId'=>$plateauId,
            'name'=>$roverName,
            'x'=>$x,
            'y'=>$y,
            'heading'=>$heading
        ));
    }
    
    public function __construct() {
        $this->memInstance = new Memcached();
        $this->memInstance->addServer("localhost", 11211);
    }
}
