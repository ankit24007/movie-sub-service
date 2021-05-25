<?php

namespace Core;

class Request {
    
    public static function uri()
    {
        return trim(str_replace($_ENV['BASE_DIR'],'', $_SERVER['REQUEST_URI']), '/');    
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function data()
    {
        $data = file_get_contents("php://input");
        
        return json_decode($data);
    }

}
