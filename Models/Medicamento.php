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

    public function __construct() {
        $this->db = new BaseDatos();
        $this->errores = [];
    }


    public function getId(){
        return $this->id;
    }

    public function setId($id): void{
        $this->id = $id;
    }

    public function getNombre(){
        return $this->nombre;
    }


    public function setNombre($nombre): void{
        $this->nombre = $nombre;
    }

    public function getStock(){
        return $this->stock;
    }

    public function setStock($stock): void{
        $this->stock = $stock;
    }

    public function getPrecio(){
        return $this->precio;
    }

    public function setPrecio($precio): void{
        $this->precio = $precio;
    }

    public function getErrores(){
        return $this->errores;
    }

    public function setErrores($errores): void{
        $this->errores = $errores;
    }

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
    
    public function delete() {
        try {
            $sql = "DELETE FROM medicamentos WHERE id = :id";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
            $stmt->execute();
            $this->db->close();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
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
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
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