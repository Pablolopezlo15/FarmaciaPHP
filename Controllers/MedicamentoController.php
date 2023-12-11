<?php

namespace Controllers;

use Lib\Pages;
use Models\Medicamento;

class MedicamentoController {
    private Pages $pages;

    /**
     * Constructor del controlador Medicamento.
     */
    public function __construct() {
        $this->pages = new Pages();
    }

    /**
     * Método estático para obtener todos los medicamentos.
     * @return array
     */
    public static function obtenerMedicamentos() {
        $medicamento = new Medicamento();
        $ordenacion = $_GET['ordenacion'] ?? 'nombre';
        $orden = $_GET['orden'] ?? 'asc';
        return $medicamento->getAll($ordenacion, $orden);
    }

    /**
     * Método estático para obtener un medicamento por su id.
     * @param $id
     * @return Medicamento
     */
    public function mostrar(){
        $medicamento = new Medicamento();
        $ordenacion = $_GET['ordenacion'] ?? 'nombre';
        $orden = $_GET['orden'] ?? 'asc';
        $medicamento->getAll($ordenacion, $orden);
        $this->pages->render('medicamentos/mostrarTodos', ['medicamento' => $medicamento]);
    }

    /**
     * Método para crear un medicamento.
     * @param $nombre
     * @param $stock
     * @param $precio
     */
    public function crear() {
        if (isset($_POST['nombre'])) {
            $medicamento = new Medicamento();
            $medicamento->setNombre($_POST['nombre']);
            $medicamento->setStock($_POST['stock']);
            $medicamento->setPrecio($_POST['precio']);

            if ($medicamento->medicamentoExiste($medicamento->getNombre())) {
                $errores = ["El medicamento ya existe en la base de datos."];
                $this->pages->render('medicamentos/mostrarTodos', ['errores' => $errores]);
                return;
            }
            
            $errores = $medicamento->validarFormulario($_POST['nombre'], $_POST['stock'], $_POST['precio']);
            if (empty($errores)) {
                $save = $medicamento->save();
                if ($save){
                    $_SESSION['medicamento'] = "complete";
                } else {
                    $_SESSION['medicamento'] = "failed";
                }
                $this->pages->render('medicamentos/mostrarTodos');
                exit; 
            } else {
                $this->pages->render('medicamentos/mostrarTodos', ['errores' => $errores]);
            }
        }
    
        $this->pages->render('medicamentos/mostrarTodos');
    }

    /**
     * Método para borrar un medicamento.
     * @param $id
     */
    public function borrar() {
        if(isset($_GET['id'])) {
            $medicamento = new Medicamento();
            $medicamento->setId($_GET['id']);
            $medicamento->delete();
        }
        else {
            $this->pages->render('medicamentos/mostrarTodos');
        }
        $this->pages->render('medicamentos/mostrarTodos');
    }

    /**
     * Método para editar un medicamento.
     * @param $id
     */
    public function editar() {
        if (isset($_GET['id'])) {
            $medicamento = new Medicamento();
            $medicamento->setId($_GET['id']);
            $medicamento->getById();
            $this->pages->render('medicamentos/mostrarTodos', ['medicamento' => $medicamento]);
        } else {
            header('Location: '.BASE_URL);
        }
    }

    /**
     * Método para actualizar un medicamento.
     * @param $id
     * @param $nombre
     * @param $stock
     * @param $precio
     */
    public function actualizar() {
        if (isset($_POST['id'])) {
            $medicamento = new Medicamento();
            $medicamento->setId($_POST['id']);
            $medicamento->setNombre($_POST['nombre']);
            $medicamento->setStock($_POST['stock']);
            $medicamento->setPrecio($_POST['precio']);
    
            $erroresedit = $medicamento->validarFormulario($_POST['nombre'], $_POST['stock'], $_POST['precio']);
            if (!empty($erroresedit)) {
                $this->pages->render('medicamentos/mostrarTodos', ['erroresedit' => $erroresedit]);
                return;
            }
    
            $medicamento->editar();

        } else {
            $this->pages->render('medicamentos/mostrarTodos');
        }

        $this->pages->render('medicamentos/mostrarTodos');
    }

    /**
     * Método para buscar un medicamento por su nombre.
     * @param $nombre
     */
    public function buscar() {
        $medicamento = new Medicamento();
    
        if (isset($_POST['nombre'])) {
            $nombreBusqueda = $_POST['nombre'];
            $resultadoBusqueda = $medicamento->buscarPorNombre($nombreBusqueda);
            $busquedaPorNombre = true;
            $this->pages->render('medicamentos/mostrarPorNombre', ['resultadoBusqueda' => $resultadoBusqueda], $busquedaPorNombre);
        } else {
            $this->pages->render('medicamentos/mostrarTodos');
        }
    }


}
