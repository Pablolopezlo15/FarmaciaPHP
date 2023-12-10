<?php
namespace Controllers;

class ErrorController {
    public static function show_error404():string{
        return "<p>La página que buscas no existe</p>";
    }
}