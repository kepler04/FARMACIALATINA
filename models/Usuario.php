<?php
// models/Usuario.php
class Usuario {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function login($usuario, $password) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = ? AND password = ?");
        $stmt->execute([$usuario, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsuarios() {
        $stmt = $this->db->query("SELECT * FROM usuarios ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($usuario, $password, $nombre, $rol) {
        try {
            $stmt = $this->db->prepare("INSERT INTO usuarios (usuario, password, nombre, rol) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$usuario, $password, $nombre, $rol]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>