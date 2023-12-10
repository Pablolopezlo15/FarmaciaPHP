<?php
    session_start();
    require_once "vendor/autoload.php";
    require_once "config/config.php";

    use Controllers\FrontController;
    FrontController::main();

?>

