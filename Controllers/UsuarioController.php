<?php

namespace Controllers;
use Models\Usuario;
use Lib\Pages;
use Utils\Utils;

class UsuarioController
{
    private Pages $pages;
    private Usuario $usuario;

    /**
     * Constructor del controlador Usuario.
     */
    public function __construct(){
        $this->pages = new Pages();
        $this->usuario = new Usuario(null, '', '', '', '');

    }

    /**
     * Método para registrar un usuario.
     * @param $data
     * @return Usuario
     */
    public function registro(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $registrado = $_POST['data'];
                $usuario = Usuario::fromArray($registrado);

                if ($usuario->buscaUsername($usuario->getUsername())) {
                    $errores = ["El usuario ya está registrado en la base de datos."];
                    $this->pages->render('/usuario/registro', ['errores' => $errores]);
                    return;
                }

                $errores = $usuario->validarFormularioRegister($registrado);

                if (empty($errores)) {
                    $registrado['password'] = password_hash($registrado['password'], PASSWORD_BCRYPT, ['cost'=>4]);
                    $usuario = Usuario::fromArray($registrado);
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

    /**
     * Método para loguear un usuario.
     * @param $data
     * @return Usuario
     */
    public function login(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $login = $_POST['data'];

                $usuario = Usuario::fromArray($login);
                $errores = $usuario->validarFormularioLogin($login);
                if(empty($errores)) {
                    $verify = $usuario->login();
                    if ($verify!=false){
                        $_SESSION['login'] = $verify;
                    } else {
                        $_SESSION['login'] = "failed";
                    }
                }

                $this->pages->render('/usuario/login', ['errores' => $errores]);

            } else {
                $_SESSION['login'] = "failed";
            }
            $usuario->desconecta();
        }

        $this->pages->render('/usuario/login');
    }

    /**
     * Método para desloguear un usuario.
     */
    public function logout(){
        Utils::deleteSession('login');
        header("Location:".BASE_URL);
    }

    /**
     * Método para ver todos los usuarios.
     * @return Usuario
     */
    public function verTodos(){
        $usuarios = $this->usuario->getAll();
        $this->pages->render('/usuario/verTodos', ['usuarios'=>$usuarios]);
    }

    /**
     * Método para borrar un usuario.
     * @param $id
     */
    public function borrar(){
        if (isset($_GET['id'])){
            $this->usuario->setId($_GET['id']);
            $this->usuario->delete();
        }
        header("Location:".BASE_URL);
    }

    /**
     * Método para editar un usuario.
     * @param $id
     */
    public function editar(){
        if (isset($_GET['id'])){
            $this->usuario->setId($_GET['id']);
            $usuario = $this->usuario->getOne();
            $this->pages->render('/usuario/editar', ['usuario'=>$usuario]);
        } else {
            header("Location:".BASE_URL);
        }
    }

    /**
     * Asciende a un usuario de rango.
     * @param $id
     */
    public function ascender() {
        if (isset($_GET['id'])) {
            $id = $_GET['id']; 
            $this->usuario->setId($id);
            $this->usuario->ascender($id);
            header("Location:".BASE_URL);
        }
    }
    
    /**
     * Degrada a un usuario de rango.
     * @param $id
     */
    public function degradar() {
        if (isset($_GET['id'])) {
            $id = $_GET['id']; 
            $this->usuario->setId($id);
            $this->usuario->degradar($id);
        }
        header("Location:".BASE_URL);

    }




}