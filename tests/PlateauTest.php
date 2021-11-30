<?php declare(strict_types=1);

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
use PHPUnit\Framework\TestCase;

require_once("/var/www/marsrover/model/Plateau.php");
require_once("/var/www/marsrover/model/Memcache.php");

final class PlateauTest extends TestCase {
    
    private $array = [
        'id' => 1,
        'name' => 'plateau1',
        'x' => '10',
        'y' => '10'
    ];
    
    public function testGetPlateau() {
        $plateauModel = new Plateau();
        $this->assertEquals($this->array,$plateauModel->getPlateau('1'));
    }
}
