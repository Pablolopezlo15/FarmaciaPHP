<?php

namespace Models;
use Lib\BaseDatos;
use PDO;

class Medicamento
{
    private $id;
    private $nombre;
    private $stock;
    private $precio;
    public  $errores;

    private BaseDatos $db;
    /**
     * Medicamento constructor.
     * @param $id
     * @param $nombre
     * @param $stock
     * @param $precio
     * @param $errores
     * @param BaseDatos $db
     * 
     */
    public function __construct() {
        $this->db = new BaseDatos();
        $this->errores = [];
    }

    /**
     * Getters y setters para las propiedades de la clase.
     */

    /**
     * @return int|null
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id): void{
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNombre(){
        return $this->nombre;
    }

    /**
     * @param string|null $nombre
     */
    public function setNombre($nombre): void{
        $this->nombre = $nombre;
    }

    /**
     * @return int|null
     */
    public function getStock(){
        return $this->stock;
    }

    /**
     * @param int|null $stock
     */
    public function setStock($stock): void{
        $this->stock = $stock;
    }

    /**
     * @return float|null
     */
    public function getPrecio(){
        return $this->precio;
    }

    /**
     * @param float|null $precio
     */
    public function setPrecio($precio): void{
        $this->precio = $precio;
    }

    /**
     * @return array
     */
    public function getErrores(){
        return $this->errores;
    }

    /**
     * @param array $errores
     */
    public function setErrores($errores): void{
        $this->errores = $errores;
    }


    /**
     * Obtiene todos los medicamentos ordenados según la columna y orden especificados.
     * @param $ordenacion Columna por la que ordenar.
     * @param $orden Orden de la ordenación (ASC o DESC).
     * @return array Array con los medicamentos.
     */
    public function getAll($ordenacion, $orden) {
        try {
            $stmt = $this->db->prepara("SELECT * FROM medicamentos ORDER BY $ordenacion $orden");
            $stmt->execute();
            $this->db->close();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    /**
     * Obtiene un medicamento por su ID.
     * @param $id ID del medicamento a obtener.
     * @return array Array con los datos del medicamento.
     */
    public function getById() {
        try {
            $stmt = $this->db->prepara("SELECT * FROM medicamentos WHERE id = :id");
            $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
            $stmt->execute();
            $this->db->close();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    /**
     * Guarda un nuevo medicamento en la base de datos.
     * @param $nombre Nombre del medicamento.
     * @param $stock Stock del medicamento.
     * @param $precio Precio del medicamento.
     * @return bool true si se ha guardado correctamente, false si no.
     */
    public function save() {
        try {
            $sql = "INSERT INTO medicamentos VALUES (null, :nombre, :stock, :precio)";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
            $stmt->bindValue(':stock', $this->getStock(), PDO::PARAM_INT);
            $stmt->bindValue(':precio', $this->getPrecio(), PDO::PARAM_STR);
            $stmt->execute();
            $this->db->close();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Elimina un medicamento de la base de datos por su ID.
     * @param $id ID del medicamento a eliminar.
     * @return bool true si se ha eliminado correctamente, false si no.
     */
    public function delete() {
        try {
            $sql = "DELETE FROM medicamentos WHERE id = :id";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
            $stmt->execute();
            $this->db->close();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Edita un medicamento en la base de datos.
     * @param $id ID del medicamento a editar.
     * @param $nombre Nombre del medicamento.
     * @param $stock Stock del medicamento.
     * @param $precio Precio del medicamento.
     * @return bool true si se ha editado correctamente, false si no.
     */
    public function editar() {
        try {
            $sql = "UPDATE medicamentos SET nombre = :nombre, cantidad = :stock, importe = :precio WHERE id = :id";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
            $stmt->bindValue(':stock', $this->getStock(), PDO::PARAM_INT);
            $stmt->bindValue(':precio', $this->getPrecio(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
            $stmt->execute();
            $this->db->close();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Verifica si un medicamento con el nombre dado ya existe en la base de datos.
     * @param $nombreMedicamento Nombre del medicamento a verificar.
     * @return true si existe, false si no.
     */
    public function medicamentoExiste($nombreMedicamento){
        try {
            $stmt = $this->db->prepara("SELECT COUNT(*) as count FROM medicamentos WHERE nombre = :nombre");
            $stmt->bindValue(':nombre', $nombreMedicamento, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            return $count > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca medicamentos por su nombre.
     * @param $nombre Nombre del medicamento a buscar.
     * @return array Array con los resultados de la búsqueda.
     */
    public function buscarPorNombre($nombre) {
        try {
            $sql = "SELECT * FROM medicamentos WHERE LOWER(nombre) LIKE LOWER(:nombre)";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':nombre', '%' . $nombre . '%', PDO::PARAM_STR);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->close();
            return $resultados;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Valida y sanitiza los datos del formulario de medicamentos.
     * @param $nombre Nombre del medicamento.
     * @param $stock Stock del medicamento.
     * @param $precio Precio del medicamento.
     * @return array Array con los errores encontrados.
     */
    public function validarFormulario($nombre, $stock, $precio) {

        $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);
        $stock = filter_var($stock, FILTER_VALIDATE_INT);
        $precio = filter_var($precio, FILTER_VALIDATE_FLOAT);

        if (empty($nombre)) {
            array_push($this->errores, "El nombre es obligatorio.");
        }
    
        if (empty($stock) || $stock < 0) {
            array_push($this->errores, "El stock es obligatorio y debe ser un número positivo.");
        }
    
        if (empty($precio) || $precio < 0) {
            array_push($this->errores, "El importe es obligatorio y debe ser un número positivo.");
        }
    
        return $this->errores;
    }
    

}