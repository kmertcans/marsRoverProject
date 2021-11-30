<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
require_once("Memcache.php");
require_once("Plateau.php");
require_once("Command.php");

class Rover {
    private $id;
    private $plateauId;
    private $name;
    private $heading;
    private $x;
    private $y;
    
    public function getName($name) {
        return $this->name;
    }
    
    public function getHeading() {
        return $this->heading;
    }
        
    public function getRover($id) { 
        $memcacheModel = new Memcache();
        $rovers = $memcacheModel->getData('rovers');
        if (!$rovers) { // if there is any rover
            return false; // No rover
        }
        
        else {
            if (array_key_exists($id,$rovers)) {
                return $rovers[$id];
            }
            else {
                return false; // No rover
            }
        }
    }
        
    public function getRovers() {
        $memcacheModel = new Memcache();
        $rovers = $memcacheModel->getData('rovers');
        return $rovers;
    }
    
    public function getStates() {
        $roverDetails = self::getRovers();
        foreach ($roverDetails as $key => $rover) {
            $rovers[] = $rover;
        }
        return $rovers;
    }
    
    public function getNextCounter() {
        $memcacheModel = new Memcache();
        $data = $memcacheModel->getData('rovers');
        if ($data===false) {
            $counter = 1;
        }
        else {
            $counter = sizeof($data)+1;
        }
        return $counter;
    }

    public function getState($id) {
        $roverDetails = self::getRover($id);
        if (!$roverDetails) {
            return false;
        }
        
        $this->id = $roverDetails['id'];
        $this->plateauId = $roverDetails['plateauId'];
        $this->name = $roverDetails['name'];
        $this->x = $roverDetails['x'];
        $this->y = $roverDetails['y'];
        $this->heading = $roverDetails['heading'];

        return $this->x.",".$this->y.",".$this->heading;
    }
    
    public function getDetails($id) {
        $roverDetails = self::getRover($id);
        if (!$roverDetails) {
            return false;
        }
        
        $this->id = $roverDetails['id'];
        $this->plateauId = $roverDetails['plateauId'];
        $this->name = $roverDetails['name'];
        $this->x = $roverDetails['x'];
        $this->y = $roverDetails['y'];
        $this->heading = $roverDetails['heading'];

        return $this;
    }
    
    public function getAllStates() {
        $this->name = $roverName;
        $this->x = $state['x'];
        $this->y = $state['y'];
        $this->heading = $state['heading'];
        $counter = self::getCounter();
        
        return $this->x.",".$this->y.",".$this->heading;
    }
    
    public function setRoverData($id, $plateauId, $name, $x, $y, $heading) {
        $memcacheModel = new Memcache();
        $data = $memcacheModel->getData('rovers');
        $data[$id] = array(
            'id'=>$id,
            'plateauId'=>$plateauId,
            'name'=>$name,
            'x'=>$x,
            'y'=>$y,
            'heading'=>$heading
        );
        $memcacheModel->setData('rovers',$data);
    }
    
    public function createRover($plateauId, $roverId, $name, $x, $y, $heading) {
        $this->plateauId = $plateauId;
        $this->id = $roverId;
        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
        $this->heading = $heading;
 
        $memcacheModel = new Memcache();
        $plateaus = $memcacheModel->getData('plateaus');
        
        $plateauModel = new Plateau();
        $getPlateau = $plateauModel->getPlateau($plateauId);

        if ($getPlateau && self::checkCreatedPosition($getPlateau['x'], $getPlateau['y'])) {
            self::setRoverData($this->id, $this->plateauId, $this->name, $this->x, $this->y, $this->heading);
            return true;
        }
        else {
            return false;
            
        }
    }
    
    public function applyCommands($commands) {
        $commands = str_split($commands);
        foreach ($commands as $command) {
            $state = $this->getState($this->name, $memcacheModel->getData($this->name));
            self::applyCommand($command);
        }
    }
    
    public function checkCommands($commands) {
        $commands = str_split($commands);
        foreach ($commands as $command) {
            if (!defined('Command::'.$command)) {
                return false;
            }
            else {
                $commandArr[] = $command;
            }
        }
        return $commands;
    }
    
    public function applyCommandsToAllRovers($commands) {
        $rovers = self::getRovers();
        if ($rovers) {
            $commands = self::checkCommands($commands);
            if ($commands) {
                foreach ($commands as $command) {
                    self::applyCommandToAllRovers($command);
                }
                $rovers = self::getRovers();
                return $rovers;
            }
        }
        return false;
    }
    
    public function applyCommandToAllRovers($command) {
        switch ($command) {
            case "L": $this->turnLeft(); break;
            case "R": $this->turnRight(); break;
            case "M": $this->move(); break;
            default: "Exception";
        }
    }
    
    public function applyCommand($command) {
        switch ($command) {
            case "L": $this->turnLeft(); break;
            case "R": $this->turnRight(); break;
            case "M": $this->move(); break;
            default: "Exception";
        }
    }
    
    public function checkNextPosition($x, $y) {	
        $plateauModel = new Plateau();
        $plateauDetails = $plateauModel->getPlateau($this->plateauId);

        if ($x<0 || $y<0 || $x>=$plateauDetails['x'] || $y>=$plateauDetails['y']) { 
            return false;
        }
        else {
            $this->x = $x;
            $this->y = $y;
            return true;
        }
    }
    
    public function checkCreatedPosition($plateauX, $plateauY) {
        if ($this->x<0 || $this->y<0 || $this->x>=$plateauX || $this->y>=$plateauY) { 
            return false;
        }
        else {
            return true;
        }
    }
    
    public function turnLeft() {
        $rovers = self::getRovers();
        foreach ($rovers as $key => $rover) {
            $roverModel = new Rover();
            $roverDetails = $roverModel->getDetails($rover['id']);

            self::setRoverDetails($roverDetails);
            
            switch ($this->heading) {
                case "N": $this->heading = "W"; break;
                case "E": $this->heading = "N"; break;
                case "S": $this->heading = "E"; break;
                case "W": $this->heading = "S"; break;
            }
            self::setRoverData($this->id, $this->plateauId, $this->name, $this->x, $this->y, $this->heading);
        }
    }
    
    public function turnRight() {	
        $rovers = self::getRovers();
        foreach ($rovers as $key => $rover) {
            $roverModel = new Rover();
            $roverDetails = $roverModel->getDetails($rover['id']);

            self::setRoverDetails($roverDetails);
            
            switch ($this->heading) {
                case "N": $this->heading = "E"; break;
                case "E": $this->heading = "S"; break;
                case "S": $this->heading = "W"; break;
                case "W": $this->heading = "N"; break;
                default: "Exception";
            }
            self::setRoverData($this->id, $this->plateauId, $this->name, $this->x, $this->y, $this->heading);
        }
        return $this;
    }
   
    public function move() {
        $rovers = self::getRovers();
        foreach ($rovers as $key => $rover) {
            $roverModel = new Rover();
            $roverDetails = $roverModel->getDetails($rover['id']);

            self::setRoverDetails($roverDetails);

            switch ($roverDetails->heading) {
                case "N": $isChecked = self::checkNextPosition($roverDetails->x,++$roverDetails->y); break;
                case "E": $isChecked = self::checkNextPosition(++$roverDetails->x,$roverDetails->y); break;
                case "S": $isChecked = self::checkNextPosition($roverDetails->x,--$roverDetails->y); break;
                case "W": $isChecked = self::checkNextPosition(--$roverDetails->x,$roverDetails->y); break;
                default: "Exception";
            }

            if ($isChecked) {
                self::setRoverData($this->id, $this->plateauId, $this->name, $this->x, $this->y, $this->heading);
            }
        }
    }
    
    public function getCounter() {
        $memInstance = new Memcached();
        $memInstance->addServer("localhost", 11211);
        $roverCounter = $memInstance->get('roverCounter');
        return $roverCounter;
    }
    
    public function setRoverDetails(Rover $roverModel) {
        $this->plateauId = $roverModel->plateauId;
        $this->id = $roverModel->id;
        $this->name = $roverModel->name;
        $this->x = $roverModel->x;
        $this->y = $roverModel->y;
        $this->heading = $roverModel->heading;
    }
}