<?php

class Article extends Skeleton {
	private $title;
	private $content;
	private $author;
	private $category_id;
	private $category2_id;

	public static function controler($action, $id, $pdo, $twig) {
		switch ($action) {
			case "create":
				if (isset($_POST["create_article"])) {
					$article = new Article();
					$article->chargePOST();
					$article->createOne($pdo);
				} else {
					$data = Category::readAll($pdo);

					echo $twig->render("pages/createOneArticle.html.twig", ["data" => $data]);
				}
			break;
			case "read":
				if ($id != 0) {
					$data = Article::readOne($pdo);
					$categories = Category::readAll($pdo);
					$tags = Article::readTags($pdo);

					$tag1 = $tags[0][0];
					$tag2 = $tags[1][0];

					$allArticles = Article::readAll($pdo);

					echo $twig->render("pages/oneArticle.html.twig", ["data" => $data, "tag1" => $tag1, "tag2" => $tag2, "categories" => $categories, "allArticles" => $allArticles]);
				} else {
					$data = Article::readAll($pdo);
					$categories = Category::readAll($pdo);

					echo $twig->render("pages/articles.html.twig", ["data" => $data, "categories" => $categories]);
				}
			break;
			case "update":
				if (isset($_POST["update_article"])) {
					if ($id != 0) {
						$article = new Article();
						$article->chargePOST();
						$article->updateOne($pdo, $id);
					}
				} else {
					$categories = Category::readAll($pdo);
					$data = Article::readOne($pdo);
					$tags = Article::readTags($pdo);

					$tag1 = $tags[0][0];
					$tag2 = $tags[1][0];

					echo $twig->render("pages/updateOneArticle.html.twig", ["data" => $data, "tag1" => $tag1, "tag2" => $tag2, "categories" => $categories]);
				}
			break;
			case "delete":
				if ($id != 0) {
					Article::deleteOne($pdo, $id);
				}
			break;
			default:
				echo $twig->render("pages/error404.html.twig");
		}
	}

	public static function readAll($pdo, $sql = "") {
		$sql = "SELECT article_id, title, photo, creation_date, author, categories.name
		FROM articles
		INNER JOIN tags
		ON articles.article_id = tags.fk_article_uuid
		JOIN categories
		ON tags.fk_category_id = categories.category_id ORDER BY creation_date DESC";

		$request = parent::readAll($pdo, $sql);
		$result = [];

		foreach ($request as $key => $value) {
			if ($key % 2 == 0) {
				//? if even => push in the array
				array_push($result, $request[$key]);
			} else {
				//? else => push the second category in the article space
				$count = sizeOf($result) - 1;
				array_push($result[$count], $value["name"]);
			}
		}

		return $result;
	}

	public static function readOne($pdo, $sql = "") {
		$sql = "SELECT article_id, title, photo, content, creation_date, author, categories.name
		FROM articles 
		INNER JOIN tags
		ON articles.article_id = tags.fk_article_uuid
		JOIN categories
		ON tags.fk_category_id = categories.category_id
		WHERE article_id = :id";

		return parent::readOne($pdo, $sql);
	}

	public static function readTags($pdo) {
		$sql = "SELECT fk_category_id FROM tags WHERE fk_article_uuid = :id";

		$query = $pdo->prepare($sql);

		$query->bindValue(':id', $_GET["id"], PDO::PARAM_INT);

		$query->execute();

		$array = $query->fetchAll();

		return $array;
	}

	public static function readLast($pdo) {
		$sql = "SELECT article_id, title, photo, author
		FROM articles
		ORDER BY creation_date DESC LIMIT 3";

		$query = $pdo->prepare($sql);
		$query->execute();

		$array = $query->fetchAll();

		return $array;
	}

	public function chargePOST() {
		if (isset($_POST['title'])) {
			$this->title = $_POST['title'];
			$this->title = strip_tags($this->title);
			$this->title = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
		}

		if (isset($_POST['photo'])) {
			$this->photo = $_POST['photo'];
			$this->photo = strip_tags($this->photo);
			$this->photo = htmlspecialchars($this->photo, ENT_QUOTES, 'UTF-8');
		}

		if (isset($_POST['content'])) {
			$this->content = $_POST['content'];
			$this->content = strip_tags($this->content);
			$this->content = htmlspecialchars($this->content, ENT_QUOTES, 'UTF-8');
		}

		if (isset($_POST['author'])) {
			$this->author = $_POST['author'];
			$this->author = strip_tags($this->author);
			$this->author = htmlspecialchars($this->author, ENT_QUOTES, 'UTF-8');
		}

		if (isset($_POST['category_id']) && is_numeric($_POST['category_id'])) {
			$this->category_id = intval($_POST['category_id']);
		}

		if (isset($_POST['category2_id']) && is_numeric($_POST['category2_id'])) {
			$this->category2_id = intval($_POST['category2_id']);
		}
	}

	public function createOne($pdo) {
		$photo = Article::importPhoto();

		$uuid = Article::genUuid($pdo);

		if ($this->category_id == 5) $this->category_id = 12;

		$sql = "INSERT INTO articles(article_id, title, photo, content, author) VALUES(:article_uuid, :title, :photo, :content, :author); INSERT INTO tags(fk_article_uuid, fk_category_id) VALUES(:article_uuid, :category_id); INSERT INTO tags(fk_article_uuid, fk_category_id) VALUES(:article_uuid, :category2_id);";

		$query = $pdo->prepare($sql);

		$query->bindValue(':title', $this->title, PDO::PARAM_STR);
		$query->bindValue(':photo', $photo, PDO::PARAM_STR);
		$query->bindValue(':content', $this->content, PDO::PARAM_STR);
		$query->bindValue(':author', $this->author, PDO::PARAM_STR);
		$query->bindValue(':article_uuid', $uuid, PDO::PARAM_STR);
		$query->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
		$query->bindValue(':category2_id', $this->category2_id, PDO::PARAM_INT);

		$query->execute();

		if ($query->errorCode() == "00000") {
			header("Location: /?page=articles");
			exit();
		} else {
			echo("<p>Erreur dans la requête : " . $query->errorInfo()[2] . "</p>");
		}
	}

	public function updateOne($pdo, $article_id) {
		$sql = "SELECT fk_category_id FROM tags WHERE fk_article_uuid = :article_id";

		$query = $pdo->prepare($sql);

		$query->bindValue(':article_id', $article_id, PDO::PARAM_STR);

		$query->execute();

		$array = $query->fetchAll();

		$tag1 = intval($array[0][0]);
		$tag2 = intval($array[1][0]);

		if ($tag2 == $tag1) $tag2 = 5;

		$sql2 = "UPDATE articles SET title = :title, content = :content, author = :author WHERE article_id = :article_id; UPDATE tags SET fk_category_id = :category_id WHERE fk_article_uuid = :article_id AND fk_category_id = :tag1; UPDATE tags SET fk_category_id = :category2_id WHERE fk_article_uuid = :article_id AND fk_category_id = :tag2;";
		
		$query = $pdo->prepare($sql2);

		$query->bindValue(':article_id', $article_id, PDO::PARAM_STR);
		$query->bindValue(':title', $this->title, PDO::PARAM_STR);
		$query->bindValue(':content', $this->content, PDO::PARAM_STR);
		$query->bindValue(':author', $this->author, PDO::PARAM_STR);
		$query->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
		$query->bindValue(':category2_id', $this->category2_id, PDO::PARAM_INT);
		$query->bindValue(':tag1', $tag1, PDO::PARAM_INT);
		$query->bindValue(':tag2', $tag2, PDO::PARAM_INT);

		$query->execute();

		if ($query->errorCode() == "00000") {
			header("Location: /?page=articles&action=read&id=$article_id");
			exit();
		} else {
			echo("<p>Erreur dans la requête : " . $query->errorInfo()[2] . "</p>");
		}
	}

	public static function deleteOne($pdo, $id, $sql = "", $page = "") {
		$sql = "DELETE FROM articles WHERE article_id = :id";

		parent::deleteOne($pdo, $id, $sql, "articles");
	}

	public static function importPhoto() {
		if (!isset($_FILES["photo"])) {
			return;
		}
	
		// $_FILES contient le fichier téléchargé
		$temporary_file_path = $_FILES["photo"]["tmp_name"];
		$file_name = $_FILES["photo"]["name"];
		$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
	
		// Autorisation de certains formats de fichiers
		$authorized_extensions = ["jpg", "png", "jpeg"];
	
		// Vérification des bons formats de fichiers
		if (in_array($file_extension, $authorized_extensions)) {
			// Path où est enregistrée la photo
			$new_file_path = "./img/articles/";
			$final_path = $new_file_path . $file_name;
	
			if (move_uploaded_file($temporary_file_path, $final_path)) {
				// Retourne le nom du nouveau fichier
				return $file_name;
			}
			else{            
				echo("<p>Impossible d\'uploader le fichier</p>");
				return;
			}
		} else{
			echo("<p>L\'extension du fichier n\'est pas autorisée</p>");
			return;
		}
	}

	// public static function genUuid($pdo) {
	// 	$sql = "SELECT article_id FROM articles";

	// 	$query = $pdo->prepare($sql);
	// 	$query->execute();

	// 	$array = $query->fetchAll();

	// 	$characters = '0123456789';
	// 	$charactersLength = strlen($characters);

	// 	$isSame = true;
	// 	$finalUuid = 0;

	// 	while ($isSame == true) {
	// 		$randomString = '';

	// 		for ($i = 0; $i < 10; $i++) {
	// 			$randomString .= $characters[rand(0, $charactersLength - 1)];
	// 		}

	// 		foreach ($array as $key => $value) {
	// 			if (intval($randomString) == intval($value)) {
	// 				$isSame = true;
	// 				break;
	// 			} else {
	// 				$isSame = false;
	// 				$finalUuid = intval($randomString);
	// 			}
	// 		}
	// 	}

	// 	return $finalUuid;
	// }

	public static function genUuid($pdo) {
		$characters = '0123456789';
		$charactersLength = strlen($characters);

		$isSame = true;
		$randomString = '';

		for ($i = 0; $i < 10; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		return intval($randomString);
	}
}