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

                $save = $usuario->create();
                if ($save){
                    $_SESSION['register'] = "complete";
                } else {
                    $_SESSION['register'] = "failed";
                }

            } else {
                $_SESSION['register'] = "failed";
            }
            $usuario->desconecta();
        }

        $this->pages->render('/usuario/registro');
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