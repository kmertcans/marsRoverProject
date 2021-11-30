<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
require_once("../../model/Rover.php");
require_once("../../model/Request.php");
require_once("../../model/Response.php");

$data = (new Request())->getContent();
$responseModel = new Response();

if (isset($data->roverId)) {
    $roverId = $data->roverId;
    if ($roverId>0) {
        $getRover = (new Rover())->getState($roverId);
        if ($getRover) {
            echo $responseModel->success($getRover);
            exit;
        }
    }
}
echo $responseModel->failure();
exit;