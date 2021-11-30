<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
require_once("../../model/Rover.php");
require_once("../../model/Plateau.php");
require_once("../../model/Heading.php");
require_once("../../model/Request.php");
require_once("../../model/Response.php");

$data = (new Request())->getContent();
$responseModel = new Response();

if (isset($data->plateauId) && isset($data->position)) {
    $plateauId = $data->plateauId;
    $position = explode(",",str_replace(' ', '', $data->position));
    $coordinates = array_slice($position, 0, 2);
    $x = $coordinates['0'];
    $y = $coordinates['1'];
    $heading = end($position);
    if ($x>=0 && $y>=0 && defined('Heading::'.$heading)) {
        $roverModel = new Rover();
        $roverId = $roverModel->getNextCounter();
        $roverName = "rover".$roverId;
        $createRover = $roverModel->createRover($plateauId, $roverId, $roverName, $x, $y, $heading);
        if ($createRover) {
            echo $responseModel->success($createRover);
            exit;
        }
    }
}
echo $responseModel->failure();
exit;