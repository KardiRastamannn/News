<?php
namespace News\Core;
use PDO;
use PDOExceptions;
 class Connection {

	private string $host = 'localhost'; 
	private string $db = 'newportal';
	private string $user = 'root';
	private string $pass = '';
	private string $charset = 'utf8mb4';
	private $pdo;

	public function __construct()
	{

		$dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
		$opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,

		];
		try {
			$this->pdo = new PDO($dsn, $this->user, $this->pass, $opt);
		} catch(PDOException $e) {
		 	die("Adatbázis kapcsolat sikertelen: " . $e->getMessage());
		}
	}
	
	// Minden adatbázis művelethez ezt kell használni, kivéve a selectekhez. Visszatérési értéke a módosított sorok száma.
	public function pdoQuery($query, $values = []): int {
		$affectedRows = 0;

		if (!empty($this->pdo)) {
			$stmt = $this->pdo->prepare($query);
			if ($stmt->execute($values)) {
				$affectedRows = $stmt->rowCount();
			}
		}
		return $affectedRows; // 0 vagy több
	}
	
	// Ezt a műveletet kell használni amikor listát szeretnénk lekérdezni.
	public function pdoSelect(string $query, array $values = []): array {
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($values);
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$this->pdo = null;
		return $result;
	}
}

?>