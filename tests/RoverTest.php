<?php declare(strict_types=1);

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
use PHPUnit\Framework\TestCase;

require_once("/var/www/marsrover/model/Rover.php");
require_once("/var/www/marsrover/model/Memcache.php");

final class RoverTest extends TestCase {
    
    private $array = [
        'id' => '1',
        'plateauId' => '1',
        'name' => 'rover1',
        'x' => '0',
        'y' => '7',
        'heading' => 'N'
    ];
    
    private $state = "0,7,N";
    
    public function testGetRover() {
        $roverModel = new Rover();
        $this->assertEquals($this->array,$roverModel->getRover('1'));
    }
    
    public function testGetState() {
        $roverModel = new Rover();
        $this->assertEquals($this->state,$roverModel->getState('1'));
    }
    
    public function testSetRoverData() {
        $roverModel = new Rover();
        $this->assertEquals($this->state,$roverModel->setRoverData('1'));
    }
}
