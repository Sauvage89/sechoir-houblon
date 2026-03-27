<?php
function	db_connect(): ?PDO
{
	$host	= "localhost";
	$dbname	= "sechoir_houblon";
	$user	= "userdb";
	$pass	= "password";
	$pdo	= null;

	try
	{
		$pdo = new PDO(
			"mysql:host=$host;dbname=$dbname;charset=utf8",
			$user,
			$pass,
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			]
		);

		return ($pdo);
	}
	catch (PDOException $e)
	{
		die("Erreur connexion BDD : " . $e->getMessage());
	}
}

function	db_query(PDO $pdo, string $sql, array $params = []): PDOStatement
{
	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	return ($stmt);
}
?>
