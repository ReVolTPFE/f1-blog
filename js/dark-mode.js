let darkModeBtn = document.querySelector(".header__nav__list__item__link--dark_mode");
let bgSwitch = document.querySelectorAll(".switch-bg");
let fontSwitch = document.querySelectorAll(".switch-font");
let borderSwitch = document.querySelectorAll(".switch-border");
let inputSwitch = document.querySelectorAll(".switch-input");
let filterLinkSwitch = document.querySelectorAll(".filter-link");
let sortLinkSwitch = document.querySelectorAll(".sort-link");

darkModeBtn.addEventListener("click", () => {
	bgSwitch.forEach(elt => {
		elt.classList.toggle("dark-mode-bg");
	});

	fontSwitch.forEach(elt => {
		elt.classList.toggle("dark-mode-font");
	});

	borderSwitch.forEach(elt => {
		elt.classList.toggle("dark-mode-border");
	});

	inputSwitch.forEach(elt => {
		elt.classList.toggle("dark-mode-input");
	});

	filterLinkSwitch.forEach(elt => {
		elt.classList.toggle("dark-mode-filter-link");
	});

	sortLinkSwitch.forEach(elt => {
		elt.classList.toggle("dark-mode-filter-link");
	});

	darkModeBtn.classList.toggle("dark-mode-enabled");

	if (darkModeBtn.classList.contains("dark-mode-enabled")) {
		darkModeBtn.innerText = "Mode clair";
	} else {
		darkModeBtn.innerText = "Mode sombre";
	}
});