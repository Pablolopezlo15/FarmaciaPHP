<?php

namespace models;

use Lib\BaseDatos;
use mysql_xdevapi\Result;
use PDO;
use PDOException;

class Usuario {
    private string|null $id;
    private string $nombre;
    private string $username;
    private string $password;
    private string $rol;
    private array $errores = [];

    private BaseDatos $db;

    public function __construct(string|null $id, string $nombre, string $username, string $password, string $rol) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->username = $username;
        $this->password = $password;
        $this->rol = $rol;
        $this->errores = [];
        $this->db = new BaseDatos();
    }


    public function getId(): string | null {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getRol(): string {
        return $this->rol;
    }

    public function setRol(string $rol): void {
        $this->rol = $rol;
    }


    public static function fromArray(array $data):usuario {
        return new Usuario(
            $data['id'] ?? null,
            $data['nombre'] ?? '',
            $data['username'] ?? '',
            $data['password'] ?? '',
            $data['rol'] ?? '',
        );
    }

    public function desconecta() {
        $this->db->close();
    }

    public function create(): bool {
        $db = new BaseDatos();
        try {
            $sql = "INSERT INTO usuarios (nombre, username, password, rol) values (:nombre, :username, :password, :rol)";
            $stmt = $db->prepara($sql);
            $stmt->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
            $stmt->bindValue(':username', $this->getUsername(), PDO::PARAM_STR);
            $stmt->bindValue(':password', $this->getPassword(), PDO::PARAM_STR);
            $stmt->bindValue(':rol', $this->getRol(), PDO::PARAM_STR );
    
            $stmt->execute();
            $this->db->close();
    
            return true;
        } catch (PDOException $error){
            return false;
        }
    }

    public function validarFormulario($data) {

        $nombre = filter_var(trim($data['nombre']), FILTER_SANITIZE_STRING);
        $username = filter_var(trim($data['username']), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($data['password']), FILTER_SANITIZE_STRING);
        $rol = filter_var(trim($data['rol']), FILTER_SANITIZE_STRING);

        if (empty($nombre)) {
            array_push($this->errores, "El nombre es obligatorio.");
        }
    
        if (empty($username)) {
            array_push($this->errores, "El nombre de usuario es obligatorio.");
        }
    
        if (empty($password)) {
            array_push($this->errores, "La contraseña es obligatoria.");
        } 
        elseif (!preg_match('/^(?=.*\d).{6,}$/', $password)) {
            array_push($this->errores, "La contraseña debe tener al menos 6 caracteres y contener al menos un número.");
        }

    
        $rolesPermitidos = ['user', 'encargado', 'admin'];
        if (!in_array($rol, $rolesPermitidos)) {
            array_push($this->errores, "Rol no válido.");
        }
    
        return $this->errores;
    }
    
    

    public function login(){
        try {
            $datosUsuario = $this->buscaUsername($this->getUsername());

            if ($datosUsuario !== false){
                $verify = password_verify($this->getPassword(), $datosUsuario->password);

                if ($verify){
                    $result = $datosUsuario;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } catch (PDOException $error){
            $result = false;
        }

        return $result;
    }

    public function buscaUsername($username){
        $select = $this->db->prepara("SELECT * FROM usuarios WHERE username=:username");
        $select->bindValue(':username', $username, PDO::PARAM_STR);

        try {
            $select->execute();
            if ($select && $select->rowCount() == 1){
                $result = $select->fetch(PDO::FETCH_OBJ);
            }
            else {
                $result = false;
            }
        } catch (PDOException $err){
            $result = false;
        }
        $this->db->close();
        return $result;
    }

    public function getAll() {
        try {
            $stmt = $this->db->prepara("SELECT * FROM usuarios");
            $stmt->execute();
            $this->db->close();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete() {
        try {
            $stmt = $this->db->prepara("DELETE FROM usuarios WHERE id = :id");
            $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
            $stmt->execute();
            $this->db->close();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function ascender($id) {
        try {
            $stmt = $this->db->prepara("SELECT rol FROM usuarios WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $rol = $resultado['rol'];
    
                if ($rol == "user") {
                    $stmt = $this->db->prepara("UPDATE usuarios SET rol = 'encargado' WHERE id = :id");
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                } elseif ($rol == "encargado") {
                    $stmt = $this->db->prepara("UPDATE usuarios SET rol = 'admin' WHERE id = :id");
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    echo "Error: Rol desconocido o no válido.";
                }
            } else {
                echo "Error: No se pudo obtener el rol del usuario.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            $this->db->close();
        }
    }
    
    public function degradar($id) {
        try {
            $stmt = $this->db->prepara("SELECT rol FROM usuarios WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $rol = $resultado['rol'];
    
                if ($rol == "encargado") {
                    $stmt = $this->db->prepara("UPDATE usuarios SET rol = 'user' WHERE id = :id");
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                } elseif ($rol == "admin") {
                    $stmt = $this->db->prepara("UPDATE usuarios SET rol = 'encargado' WHERE id = :id");
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    echo "Error: Rol desconocido o no válido.";
                }
            } else {
                echo "Error: No se pudo obtener el rol del usuario.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            $this->db->close();
        }
    }
    


}