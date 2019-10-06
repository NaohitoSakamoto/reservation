<?php
	$databaseName = "./db/reservation.db";
	$user = "";
	$password = "";
	$option = [
		PDO::ATTR_EMULATE_PREPARES=>false
	];

try {
	$dbh = new PDO( 'sqlite:' . $databaseName, $user, $password, $option );
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
	die();
}
?>