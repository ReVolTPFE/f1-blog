<?php

require("include/twig.php");
$twig = init_twig();

require("./include/connection.php");
$pdo = connection();

require("src/Skeleton.php");
require("src/Article.php");
require("src/Category.php");

// récupération de la variable page sur l'URL
if (isset($_GET["page"])) $page = $_GET["page"]; else $page = "home";

// récupération de la variable action sur l'URL
if (isset($_GET["action"])) $action = $_GET["action"]; else $action = "read";

// récupération de l'id s'il existe (par convention la clé 0 correspond à un id inexistant)
if (isset($_GET["id"])) $id = $_GET["id"]; else $id = 0;

$path = "";
$data = [];

// test des différents choix
switch ($page) {
	case "home":
		switch ($action) {
			case "read":
				$data = Article::readLast($pdo);

				echo $twig->render("layouts/card.l.html.twig", ["data" => $data]);
			break;
			default:
				echo $twig->render("pages/error404.html.twig");
			}
		break;

	case "articles":
			Article::controler($action, $id, $pdo, $twig);
		break;

	case "categories":
			Category::controler($action, $id, $pdo, $twig);
		break;
	default:
		echo $twig->render("pages/error404.html.twig");
}