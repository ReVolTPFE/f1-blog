<?php

class Skeleton {
	public static function readAll($pdo, $sql = "") {
		$query = $pdo->prepare($sql);
		$query->execute();

		$array = $query->fetchAll();

		return $array;
	}

	public static function readOne($pdo, $sql = "") {
		$query = $pdo->prepare($sql);

		$query->bindValue(':id', $_GET["id"], PDO::PARAM_STR);

		$query->execute();

		$array = $query->fetch();

		$creation_date = new \DateTime($array["creation_date"]);
		$array["creation_date"] = $creation_date->format("d/m/Y");

		return $array;
	}

	public static function deleteOne($pdo, $id, $sql = "", $page = "") {
		$query = $pdo->prepare($sql);

		$query->bindValue(':id', $id, PDO::PARAM_STR);

		$query->execute();

		if ($query->errorCode() == "00000") {
			header("Location: /?page=$page");
			exit();
		} else {
			echo("<p>Erreur dans la requête : " . $query->errorInfo()[2] . "</p>");
		}
	}
}