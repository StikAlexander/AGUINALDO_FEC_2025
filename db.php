<?php
class Database {
    private $conn;

    public function __construct() {
        $host = getenv('DB_HOST') ?: 'localhost';
        $db   = getenv('DB_NAME') ?: 'fecolsub_tienda_2';
        $user = getenv('DB_USER') ?: 'fecolsub_usermysql';
        $pass = getenv('DB_PASS') ?: 'UhYf+iFj_5vV';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    // Guardar sesión OTP
    public function guardarOTP($cookie_id, $identificacion, $nombre, $telefono, $codigo_otp) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);
        $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $sql = "INSERT INTO aguinaldo_consulta_2025
                (cookie_id, identificacion, nombre, telefono, codigo_otp, fecha_expiracion, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cookie_id, $identificacion, $nombre, $telefono, $codigo_otp, $fecha_expiracion, $ip_address, $user_agent]);
    }

    // Obtener sesión por cookie
    public function obtenerSesion($cookie_id) {
        $sql = "SELECT * FROM aguinaldo_consulta_2025 WHERE cookie_id = ? AND estado IN ('activo', 'validado') AND fecha_expiracion > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cookie_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Validar OTP
    public function validarOTP($cookie_id, $codigo_ingresado) {
        // Obtener la sesión
        $sesion = $this->obtenerSesion($cookie_id);

        if (!$sesion) {
            return ['exito' => false, 'mensaje' => 'Sesión expirada o inválida'];
        }

        // Verificar intentos fallidos
        if ($sesion['intentos_fallidos'] >= 3) {
            $this->bloquearSesion($cookie_id);
            return ['exito' => false, 'mensaje' => 'Demasiados intentos fallidos. Intente nuevamente'];
        }

        // Verificar código
        if ((int)$sesion['codigo_otp'] === (int)$codigo_ingresado) {
            $sql = "UPDATE aguinaldo_consulta_2025 SET estado = 'validado', fecha_validacion = NOW() WHERE cookie_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cookie_id]);
            return ['exito' => true, 'mensaje' => 'Código validado correctamente'];
        } else {
            // Incrementar intentos fallidos
            $sql = "UPDATE aguinaldo_consulta_2025 SET intentos_fallidos = intentos_fallidos + 1 WHERE cookie_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cookie_id]);
            return ['exito' => false, 'mensaje' => 'Código OTP incorrecto'];
        }
    }

    // Bloquear sesión
    public function bloquearSesion($cookie_id) {
        $sql = "UPDATE aguinaldo_consulta_2025 SET estado = 'bloqueado' WHERE cookie_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cookie_id]);
    }

    // Limpiar sesiones expiradas
    public function limpiarExpiradas() {
        $sql = "UPDATE aguinaldo_consulta_2025 SET estado = 'expirado' WHERE fecha_expiracion <= NOW() AND estado = 'activo'";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute();
    }

    // Obtener auditoría
    public function getAuditoria($identificacion = null, $dias = 30) {
        if ($identificacion) {
            $sql = "SELECT * FROM aguinaldo_consulta_2025 WHERE identificacion = ? AND fecha_creacion >= DATE_SUB(NOW(), INTERVAL ? DAY) ORDER BY fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$identificacion, $dias]);
        } else {
            $sql = "SELECT * FROM aguinaldo_consulta_2025 WHERE fecha_creacion >= DATE_SUB(NOW(), INTERVAL ? DAY) ORDER BY fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$dias]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
