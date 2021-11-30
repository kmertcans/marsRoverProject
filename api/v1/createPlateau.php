<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
// to clear memcache //
//$memInstance = new Memcached();
//$memInstance->addServer("localhost", 11211);
//$memInstance->flush(1);
//exit;
// to clear memcache //
require_once("../../model/Plateau.php");
require_once("../../model/Request.php");
require_once("../../model/Response.php");

$data = (new Request())->getContent();
$responseModel = new Response();
 
if (isset($data->x) && isset($data->y)) {
    $x = $data->x;
    $y = $data->y;
    if ($x>0 && $y>0) {
        $plateauModel = new Plateau();
        $id = $plateauModel->getNextCounter();
        $plateauName = "plateau".$id;
        $getPlateau = $plateauModel->setPlateauData($id, $plateauName, $x, $y);
        echo $responseModel->success($getPlateau);
        exit;
    }
}
echo $responseModel->failure();
exit;