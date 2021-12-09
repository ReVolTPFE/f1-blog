let allCards = document.querySelectorAll(".article__card");

let navLinks = document.querySelectorAll(".filter-link");

navLinks.forEach(link => {
	link.addEventListener("click", () => {
		allCards.forEach(card => {
			if (link.innerText != card.childNodes[1].childNodes[3].childNodes[0].innerText && link.innerText != card.childNodes[1].childNodes[3].childNodes[2].innerText) {
				card.hidden = true;
			} else {
				card.hidden = false;
			}

			if (link.innerText == "Tous") {
				card.hidden = false;
			}
		});
	});
});