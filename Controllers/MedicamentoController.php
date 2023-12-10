<?php

namespace Controllers;

use Lib\Pages;
use Models\Medicamento;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class MedicamentoController {
    private Pages $pages;

    public function __construct() {
        $this->pages = new Pages();
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
            $medicamento->save();
        } else {
            $this->pages->render('medicamentos/crear');
        }
        $this->pages->render('medicamentos/crear');

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
