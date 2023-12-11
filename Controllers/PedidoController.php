<?php
namespace Controllers;
use Models\Pedido;
use Lib\BaseDatos;
use Lib\Pages;
use PDO;

class PedidoController {
    private BaseDatos $db;
    private Pages $pages;

    /**
     * Constructor del controlador Pedido.
     */
    public function __construct(){
        $this->db = new BaseDatos();
        $this->pages = new Pages();
    }

    /**
     * Método estático para obtener todos los pedidos.
     * @return array
     */
    public static function obtenerPedidos() {
        $pedido = new Pedido();
        return $pedido->getAll();
    }
    
    /**
     * Método para obtener todos los pedidos.
     * @return Pedido
     */
    public function mostrar(){
        $pedido = new Pedido();
        $pedido->getAll();
        $this->pages->render('pedido/verTodos', ['pedido' => $pedido]);
    }

    /**
     * Método para crear un pedido.
     * @param $nombreCliente
     * @param $emailCliente
     * @param $medicamento
     */
    public function crear() {
        if (isset($_POST['nombre_cliente'])) {

            $nombreCliente = $_POST['nombre_cliente'];
            $emailCliente = $_POST['email_cliente'];
            $medicamento = $_POST['medicamento'];    

            $pedido = new Pedido();
            $pedido->setNombreCliente($nombreCliente);
            $pedido->setEmailCliente($emailCliente);
            $pedido->setMedicamento($medicamento);
        
            $errores = $pedido->validarFormulario($nombreCliente, $emailCliente, $medicamento);
            if (empty($errores)) {
                $pedido->save();
                $_SESSION['pedido'] = "complete";
                $this->pages->render('pedido/crear');
                exit; 
            } else {
                $_SESSION['pedido'] = "failed";
                $this->pages->render('pedido/crear', ['errores' => $errores]);
            }

        } else {
            $this->pages->render('pedido/crear');
        }
    }

    /**
     * Método para borrar un pedido.
     * @param $id
     */
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

    /**
     * Método para editar un pedido.
     * @param $id
     */
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

