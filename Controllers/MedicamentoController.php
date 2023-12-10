<?php

namespace Controllers;

use Lib\Pages;
use Models\Medicamento;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class MedicamentoController {
    private Pages $pages;
    private $errores = [];

    public function __construct() {
        $this->pages = new Pages();
        $this->errores = [];
    }

    public static function obtenerMedicamentos() {
        $medicamento = new Medicamento();
        $ordenacion = $_GET['ordenacion'] ?? 'nombre';
        $orden = $_GET['orden'] ?? 'asc';
        return $medicamento->getAll($ordenacion, $orden);
    }

    public function mostrar(){
        $medicamento = new Medicamento();
        $ordenacion = $_GET['ordenacion'] ?? 'nombre';
        $orden = $_GET['orden'] ?? 'asc';
        $medicamento->getAll($ordenacion, $orden);
        $this->pages->render('medicamentos/mostrarTodos', ['medicamento' => $medicamento]);
    }

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

    public function actualizar() {
        if (isset($_POST['id'])) {
            $medicamento = new Medicamento();
            $medicamento->setId($_POST['id']);
            $medicamento->setNombre($_POST['nombre']);
            $medicamento->setStock($_POST['stock']);
            $medicamento->setPrecio($_POST['precio']);
            $medicamento->editar();
        } else {
            $this->pages->render('medicamentos/mostrarTodos');
        }
        $this->pages->render('medicamentos/mostrarTodos');
    }

    public function buscar() {
        $medicamento = new Medicamento();
    
        if (isset($_POST['nombre'])) {
            $nombreBusqueda = $_POST['nombre'];
            $resultadoBusqueda = $medicamento->buscarPorNombre($nombreBusqueda);
            $this->pages->render('medicamentos/mostrarPorNombre', ['resultadoBusqueda' => $resultadoBusqueda]);
        } else {
            $this->pages->render('medicamentos/mostrarTodos');
        }
    }


}
