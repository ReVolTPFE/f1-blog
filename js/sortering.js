let articles = document.querySelectorAll(".article__card");
let articlesSection = document.querySelector(".article");
let sortDateBtn = document.querySelector(".sort-date-btn");
let sortAlphabeticBtn = document.querySelector(".sort-alphabetic-btn");

let defaultSort = [];
let titleSort = [];
let alphabeticSort = [];

articles.forEach(article => {
	defaultSort.push(article);
});

sortDateBtn.addEventListener("click", () => {
	//? Tri des articles par date du + récent au + ancien
	while (articlesSection.firstChild) {
		articlesSection.removeChild(articlesSection.firstChild);
	}

	defaultSort.forEach(article => {
		articlesSection.appendChild(article);
	});
});

sortAlphabeticBtn.addEventListener("click", () => {
	titleSort = [];
	alphabeticSort = [];

	//? Tri des titres d'articles par ordre alphabétique
	articles.forEach(article => {
		titleSort.push(article.childNodes[1].childNodes[1].innerText);
	});

	titleSort.sort();

	//? Pour chaque article, ajout dans l'ordre alphabétique en fonction de l'array titleSort
	for (let i = 0; i < articles.length; i++) {
		articles.forEach(article => {
			if (article.childNodes[1].childNodes[1].innerText == titleSort[i]) {
				alphabeticSort.push(article);
			}
		});
	}

	while (articlesSection.firstChild) {
		articlesSection.removeChild(articlesSection.firstChild);
	}

	alphabeticSort.forEach(article => {
		articlesSection.appendChild(article);
	});
});