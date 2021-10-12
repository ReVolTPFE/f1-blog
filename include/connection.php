<?php

function connection()
{
	$pdo = new PDO('mysql:host=127.0.0.1;dbname=steiner_f1_blog;charset=utf8', 'docker', '');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	if ($pdo) {
		return $pdo;
	}
	else {
		echo("<p>Erreur de connexion</p>");
		exit;
	}
}