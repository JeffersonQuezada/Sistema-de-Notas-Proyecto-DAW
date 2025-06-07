

<?php
require_once '../vendor/autoload.php';
class Database {
    private static $instance = null;
    private $connection;
    private $host = 'localhost';
    private $database = 'gestion_notas';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';

    private function __construct() {
        $this->initializeConnection();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initializeConnection() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
            ];

            $this->connection = new PDO($dsn, $this->username, $this->password, $options);

        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public static function connect() {
        return self::getInstance()->getConnection();
    }

    private function __clone() {}

    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>