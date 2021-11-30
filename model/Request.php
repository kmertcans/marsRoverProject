<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
class Request {
  
    public $content;

    public function getContent() {
        return $this->content;
    }
    
    public function __construct() {
        header("Content-Type: application/json; charset=UTF-8");
        $this->content = json_decode(file_get_contents("php://input"));
    }
}
