<?php

/* 
 * // Editor: Kemal Mertcan SAGUN //
 */
class Response {
  
    public $code;
    public $status;
    public $content;

    public function success($content) {
        $this->code = "200";
        $this->status = "true";
        if ($content==null) {
            $this->content = new stdClass();
        }
        else {
            $this->content = $content;
        }
        return json_encode($this);
    }
    
    public function failure($content) {
        $this->code = "400";
        $this->status = "false";
        if ($content==null) {
            $this->content = new stdClass();
        }
        else {
            $this->content = $content;
        }
        return json_encode($this);
    }
}
