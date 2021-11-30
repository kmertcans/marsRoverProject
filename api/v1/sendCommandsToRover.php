<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
require_once("../../model/Rover.php");
require_once("../../model/Request.php");
require_once("../../model/Response.php");

$data = (new Request())->getContent();
$responseModel = new Response();

if (isset($data->commands)) {
    $commands = $data->commands;
    $roverModel = new Rover();
    $getRovers = $roverModel->applyCommandsToAllRovers($commands);
    if ($getRovers) {
        echo $responseModel->success($getRovers);
        exit;
    }
}
echo $responseModel->failure();
exit;