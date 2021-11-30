<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
require_once("../../model/Plateau.php");
require_once("../../model/Request.php");
require_once("../../model/Response.php");

$data = (new Request())->getContent();
$responseModel = new Response();

if (isset($data->plateauId)) {
    $plateauId = $data->plateauId;
    if ($plateauId>0) {
        $plateauModel = new Plateau();
        $getPlateau = $plateauModel->getPlateau($plateauId);
        if ($getPlateau) {
            echo $responseModel->success($getPlateau);
            exit;
        }
    }
}
echo $responseModel->failure();
exit;