<?php

class Category extends Skeleton {
	private $name;

	public static function controler($action, $id, $pdo, $twig) {
		switch ($action) {
			case "create":
				if (isset($_POST["create_category"])) {
					$category = new Category();
					$category->chargePOST();
					$category->createOne($pdo);

					$data = Category::readAll($pdo);

					echo $twig->render("pages/categories.html.twig", ["data" => $data]);
				} else {
					$data = Category::readAll($pdo);

					echo $twig->render("pages/categories.html.twig", ["data" => $data]);
				}
			break;
			case "read":
					$data = Category::readAll($pdo);

					echo $twig->render("pages/categories.html.twig", ["data" => $data]);
			break;
			case "update":
				if (isset($_POST["update_category"])) {
						$category = new Category();
						$category->chargePOST();
						$category->updateOne($pdo, $_POST["category_id"]);

						$data = Category::readAll($pdo);

					echo $twig->render("pages/categories.html.twig", ["data" => $data]);
				} else {
					$data = Category::readAll($pdo);

					echo $twig->render("pages/categories.html.twig", ["data" => $data]);
				}
			break;
			case "delete":
				if (isset($_POST["delete_category"])) {
					$delete = true;
					$articles = Article::readAll($pdo);

					foreach ($articles as $key => $value) {
						if ($value["fk_category_id"] == $_POST["category_id"]) {
							$delete = false;
						}
					}

					if ($delete == true) {
						Category::deleteOne($pdo, $_POST["category_id"]);
					} else {
						$data = Category::readAll($pdo);

						echo $twig->render("pages/categories.html.twig", ["data" => $data]);
						echo("Des articles ont cette catégorie, imposssible de la supprimer.");
					}
				}
			break;
			default:
				echo $twig->render("pages/error404.html.twig");
		}
	}

	public static function readAll($pdo, $sql = "") {
		$sql = "SELECT category_id, name FROM categories";

		return parent::readAll($pdo, $sql);
	}

	public function chargePOST() {
		if (isset($_POST['name'])) {
			$this->name = $_POST['name'];
			$this->name = strip_tags($this->name);
			$this->name = htmlspecialchars($this->name, ENT_QUOTES, 'UTF-8');
		}
	}

	public function createOne($pdo) {
		$sql = "INSERT INTO categories(name) VALUES(:name)";

		$query = $pdo->prepare($sql);

		$query->bindValue(':name', $this->name, PDO::PARAM_STR);

		$query->execute();

		if ($query->errorCode() == "00000") {
			header("Location: /?page=categories");
			exit();
		} else {
			echo("<p>Erreur dans la requête : " . $query->errorInfo()[2] . "</p>");
		}
	}

	public function updateOne($pdo, $category_id) {
		$sql = "UPDATE categories SET name = :name WHERE category_id = :category_id";

		$query = $pdo->prepare($sql);

		$query->bindValue(':category_id', $category_id, PDO::PARAM_INT);
		$query->bindValue(':name', $this->name, PDO::PARAM_STR);

		$query->execute();

		if ($query->errorCode() == "00000") {
			header("Location: /?page=categories");
			exit();
		} else {
			echo("<p>Erreur dans la requête : " . $query->errorInfo()[2] . "</p>");
		}
	}

	public static function deleteOne($pdo, $id, $sql = "", $page = "") {
		$sql = "DELETE FROM categories WHERE category_id = :id";

		parent::deleteOne($pdo, $id, $sql, "categories");
	}
}