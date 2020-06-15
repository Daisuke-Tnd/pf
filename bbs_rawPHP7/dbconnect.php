<?php
$dsn = 'mysql:host=localhost; dbname=xxxxxx; charset=utf8mb4';
$user = 'tsunoda277';
$pass_pdo = 'xxxxxx';
try {
	$dbh = new PDO ($dsn, $user, $pass_pdo);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	echo 'DB接続エラー:' . $e->getMessage();
	exit();
}
