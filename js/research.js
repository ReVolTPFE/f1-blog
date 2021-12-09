let searchInput = document.querySelector("#research");
let allArticlesSection = document.querySelector(".article");
let allArticles = document.querySelectorAll(".article__card");

searchInput.addEventListener("keyup", () => {
	while (allArticlesSection.firstChild) {
		allArticlesSection.removeChild(allArticlesSection.firstChild);
	}

	allArticles.forEach(article => {
		let articleTitle = article.childNodes[1].childNodes[1].innerText

		if (articleTitle.toLowerCase().includes(searchInput.value.toLowerCase()) || searchInput.value == "") {
			allArticlesSection.appendChild(article);
		}
	});
});