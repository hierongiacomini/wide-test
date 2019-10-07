<?php
class Database{
    private $host = '';
    private $db_name = '';
    private $username = '';
    private $password = '';
    public $conn;

    function __construct($a){
        $this->host = $a["host"];
        $this->db_name = substr($a["path"],1);
        $this->username = $a["user"];
        $this->password = $a["pass"];
    }
    public function getConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$database = new Database($cleardb_url);
$db = $database->getConnection();
?>