<?php
namespace Controllers;
use Models\Pedido;
use Lib\BaseDatos;
use Lib\Pages;
use PDO;

class PedidoController {
    private BaseDatos $db;
    private Pages $pages;

    public function __construct(){
        $this->db = new BaseDatos();
        $this->pages = new Pages();
    }

    // public function mostrar(){
    //     $pedido = new Pedido();
    //     $pedido->getAll();
    //     $this->pages->render('pedido/verTodos', ['pedido' => $pedido]);
    // }

    public static function obtenerPedidos() {
        $pedido = new Pedido();
        return $pedido->getAll();
    }
    
    public function mostrar(){
        $pedido = new Pedido();
        $pedido->getAll();
        $this->pages->render('pedido/verTodos', ['pedido' => $pedido]);
    }

    public function crear() {
        if (isset($_POST['nombre_cliente'])) {
            $pedido = new Pedido();
            $pedido->setNombreCliente($_POST['nombre_cliente']);
            $pedido->setEmailCliente($_POST['email_cliente']);
            $pedido->setMedicamento($_POST['medicamento']);
            $pedido->save();
        } else {
            $this->pages->render('pedido/crear');
        }
        $this->pages->render('pedido/crear');

    }

    public function borrar() {
        if(isset($_GET['id'])) {
            $pedido = new Pedido();
            $pedido->setId($_GET['id']);
            $pedido->delete();
        }
        else {
            $this->pages->render('pedido/verTodos');
        }
        $this->pages->render('pedido/verTodos');
    }

    public function enviarCorreoPedido() {
        $idPedido = $_GET['id'];
        $pedido = new Pedido();

        $detallePedido = $pedido->getById($idPedido);

        if ($detallePedido) {
            $pedido->setId($detallePedido['id']);
            $pedido->setNombreCliente($detallePedido['nombre_cliente']);
            $pedido->setEmailCliente($detallePedido['email_cliente']);
            $pedido->setMedicamento($detallePedido['medicamento']);
            $pedido->setFechaPedido($detallePedido['fecha_pedido']);

            $pedido->enviarEmail();
        } else {
            echo "Pedido no encontrado.";
        }
    }
    

    
}

