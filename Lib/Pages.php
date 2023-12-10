<?php

namespace Lib;

class Pages
{
    public function render(string $pageName, array $params = null): void{
        /*  $pageName es el nombre de nuestra plantilla. El nombre del fichero que se pretende mostrar(sin la extensión).
            Por ejemplo, baraja
            $params este array es el contenedor de las variables que deseamos pasar a la vista (la página a mostrar).
            $params es un array con un índice asociativo. Para crear las variables, recorremos la lista y
            usamos el índice como nombre de variable usando la propiedad variable de PHP ($$name = $value)
        */
        if($params != null){
            foreach($params as $name => $value){
                $$name = $value;
            }
        }
        require_once "Views/Layout/header.php";
        require_once "Views/$pageName.php"; // incluimos la pagina indicada
        require_once "Views/Layout/footer.php";
    }
}