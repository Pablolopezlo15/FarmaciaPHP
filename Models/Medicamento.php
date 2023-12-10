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

    private BaseDatos $db;

    public function __construct()
    {
        $this->db = new BaseDatos();
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
            $existingMedication = $this->buscarPorNombre($this->getNombre());
            if (!empty($existingMedication)) {
                echo "No se puede dar de alta el registro: el nombre del medicamento no se puede repetir.";
                return;
            }
            $sql = "INSERT INTO medicamentos VALUES (null, :nombre, :stock, :precio)";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
            $stmt->bindValue(':stock', $this->getStock(), PDO::PARAM_INT);
            $stmt->bindValue(':precio', $this->getPrecio(), PDO::PARAM_STR);
            $stmt->execute();
            $this->db->close();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
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

}