const menuToggle = document.getElementById("menuToggle");
const navMenu = document.querySelector(".nav-menu");

if (menuToggle) {

    menuToggle.addEventListener("click", function () {

        navMenu.classList.toggle("active");

    });

}