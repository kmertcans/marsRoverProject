<?php
/**
 * @OA\Info(title="API TEST", version="0.1")
 */
/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
require_once("Rover.php");
require_once("Memcache.php");

class Plateau {
  
    private $id;
    private $name;
    private $maxX;
    private $maxY;
    private $rovers;

    
    /**
    *     @OA\Post(
    *          path="/api/v1/getPlateau.php",
    *          tags={"Plateaus"},
    *          @OA\RequestBody(
    *              description="Takes plateau id",
    *              required=true,
    *          @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="plateauId", type="number"),
    *          ),
    *     ),
    *     @OA\Response(
    *         response=405,
    *         description="Invalid input",
    *     ),
    * )
    */
    public function getPlateau($plateauId) {
        $this->id = $plateauId;
        $memcacheModel = new Memcache();
        $plateaus = $memcacheModel->getData('plateaus');
        $plateau = self::checkPlateauExists($plateauId, $plateaus);
        return $plateau;
    }
    
    public function getRovers($plateauId) {
        $memcacheModel = new Memcache();
        $rovers = $memcacheModel->getData('rovers');
        
        foreach ($rovers as $key => $rover) {
            if ($rover['plateauId']==$plateauId) {
                $this->rovers[]=$rover;
            }
        }
        return $this->rovers;
    }
    
    public function checkPlateauExists($plateauId, $plateaus) {
        if (!$plateaus) { // if there is any plateau
            return false; // "No plateau"
        }
        else {
            if (array_key_exists($plateauId, $plateaus)) {
                return $plateaus[$plateauId];
            }
            else {
                return false; // "No plateau"
            }
        }
    }
    
    public function getNextCounter() {
        $memcacheModel = new Memcache();
        $data = $memcacheModel->getData('plateaus');
        
        if ($data===false) {
            $counter = 1;
        }
        else {
            $counter = sizeof($data)+1;
        }
        return $counter;
    }
    
    /**
    *     @OA\Post(
    *          path="/api/v1/createPlateau.php",
    *          tags={"Plateaus"},
    *          @OA\RequestBody(
    *              description="Creates a plateau",
    *              required=true,
    *          @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="x", type="number"),
    *             @OA\Property(property="y", type="number"),
    *          ),
    *     ),
    *     @OA\Response(
    *         response=405,
    *         description="Invalid input",
    *     )
    * )
    */
    public function setPlateauData($id, $name, $x, $y) {
        $memcacheModel = new Memcache();
        $data = $memcacheModel->getData('plateaus');
        $data[$id] = array(
            'id'=>$id,
            'name'=>$name,
            'x'=>$x,
            'y'=>$y
        );
        $memcacheModel->setData('plateaus',$data);
        
        $this->id = $id;
        $this->name = $name;
        $this->maxX = $x;
        $this->maxY = $y; 
    }
}
