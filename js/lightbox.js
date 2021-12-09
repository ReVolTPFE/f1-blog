let image = document.querySelector(".lightbox-img");
let bg = document.querySelector(".lightbox-bg");

image.addEventListener("click", () => {
	image.classList.toggle("lightbox-img--activated");
	
	if (image.classList.contains("lightbox-img--activated")) {
		bg.style.display = "block";
	} else {
		bg.style.display = "none";
	}
});