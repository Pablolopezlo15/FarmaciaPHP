<?php

namespace Controllers;
use Models\Usuario;
use Lib\Pages;
use Utils\Utils;

class UsuarioController
{
    private Pages $pages;
    private Usuario $usuario;


    public function __construct(){
        $this->pages = new Pages();
        $this->usuario = new Usuario(null, '', '', '', '');

    }

    public function registro(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $registrado = $_POST['data'];
                $registrado['password'] = password_hash($registrado['password'], PASSWORD_BCRYPT, ['cost'=>4]);
                $usuario = Usuario::fromArray($registrado);

                if ($usuario->buscaUsername($usuario->getUsername())) {
                    $errores = ["El usuario ya está registrado en la base de datos."];
                    $this->pages->render('/usuario/registro', ['errores' => $errores]);
                    return;
                }

                $errores = $usuario->validarFormulario($registrado);

                if (empty($errores)) {
                    $save = $usuario->create();
                    if ($save){
                        $_SESSION['register'] = "complete";
                    } else {
                        $_SESSION['register'] = "failed";
                    }
                    $this->pages->render('/usuario/registro');
                    exit; 
                } else {
                    $this->pages->render('/usuario/registro', ['errores' => $errores]);
                }

            } else {
                $_SESSION['register'] = "failed";
            }
            $usuario->desconecta();
        }

        $this->pages->render('/usuario/registro');
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

    public function login(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $login = $_POST['data'];

                $usuario = Usuario::fromArray($login);

                $verify = $usuario->login();

                if ($verify!=false){
                    $_SESSION['login'] = $verify;
                } else {
                    $_SESSION['login'] = "failed";
                }

            } else {
                $_SESSION['login'] = "failed";
            }
            $usuario->desconecta();
        }

        $this->pages->render('/usuario/login');
    }

    public function logout(){
        Utils::deleteSession('login');
        header("Location:".BASE_URL);
    }

    public function verTodos(){
        $usuarios = $this->usuario->getAll();
        $this->pages->render('/usuario/verTodos', ['usuarios'=>$usuarios]);
    }

    public function borrar(){
        if (isset($_GET['id'])){
            $this->usuario->setId($_GET['id']);
            $this->usuario->delete();
        }
        header("Location:".BASE_URL);
    }

    public function editar(){
        if (isset($_GET['id'])){
            $this->usuario->setId($_GET['id']);
            $usuario = $this->usuario->getOne();
            $this->pages->render('/usuario/editar', ['usuario'=>$usuario]);
        } else {
            header("Location:".BASE_URL);
        }
    }

    public function ascender() {
        if (isset($_GET['id'])) {
            $id = $_GET['id']; 
            $this->usuario->setId($id);
            $this->usuario->ascender($id);
            header("Location:".BASE_URL);
        }
    }
    
    public function degradar() {
        if (isset($_GET['id'])) {
            $id = $_GET['id']; 
            $this->usuario->setId($id);
            $this->usuario->degradar($id);
        }
        header("Location:".BASE_URL);

    }




}