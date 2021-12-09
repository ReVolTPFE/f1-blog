let showHideBtn = document.querySelectorAll(".show-hide");

showHideBtn.forEach(btn => {
	btn.addEventListener("click", () => {
		btn.classList.toggle("hide-section");

		if (btn.innerText.includes("Image")) {
			if (btn.classList.contains("hide-section")) {
				btn.innerHTML = "<i class=\"fas fa-chevron-right\"></i> Image";
				btn.nextElementSibling.classList.add("hide-elt");
				btn.nextElementSibling.classList.remove("show-elt");
			} else {
				btn.innerHTML = "<i class=\"fas fa-chevron-down\"></i> Image";
				btn.nextElementSibling.classList.add("show-elt");
				btn.nextElementSibling.classList.remove("hide-elt");
			}
		} else if (btn.innerText.includes("Contenu")) {
			if (btn.classList.contains("hide-section")) {
				btn.innerHTML = "<i class=\"fas fa-chevron-right\"></i> Contenu";
				btn.nextElementSibling.classList.add("hide-elt");
				btn.nextElementSibling.classList.remove("show-elt");
			} else {
				btn.innerHTML = "<i class=\"fas fa-chevron-down\"></i> Contenu";
				btn.nextElementSibling.classList.add("show-elt");
				btn.nextElementSibling.classList.remove("hide-elt");
			}
		}
	});
});
