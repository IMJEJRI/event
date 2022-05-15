/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)

import './styles/app.scss';
// les variables
// variables globales

let compteur = 0;
// compteur qui permet de connaitre l'image sur laquelle on se trouve
let timer, elements, slides, slideWidth;


window.onload = () => {
    // on récupère le diaporama
    const diapo = document.querySelector(".diapo");
    elements = document.querySelector(".elements");

    // clone de l'image 1

    let firstImage = elements.firstElementChild.cloneNode(true);

    //  on injecte le clone à la fin du diapo
    elements.appendChild(firstImage);

    // Chargement de tous les élts sous forme d'un tableau

    slides = Array.from(elements.children);

    // on récupère la largeur du slide
    slideWidth = diapo.getBoundingClientRect().width;

    // on récupère les flèches

    let next = document.querySelector("#nav-droite");
    let prev = document.querySelector("#nav-gauche");

    // on gère les clic

    next.addEventListener("click", slideNext);
    prev.addEventListener("click", slidePrev);


}
function slideNext() {
    // cette fct fait défiler la diaporamavers la dte
    compteur++;
    elements.style.transition = "1s linear";
    let decal = -slideWidth * compteur;
    elements.style.transform = `translateX(${decal}px)`;

    setTimeout(function () {
        if (compteur >= slides.length - 1) {
            compteur = 0;
            elements.style.transition = "unset";
            elements.style.transform = "translateX(0)";
        }

    }, 1000);

}
function slidePrev() {
    compteur--;
    elements.style.transition = "1s linear";


    if (compteur < 0) {
        compteur = slides.length - 1;
        let decal = -slideWidth * compteur;
        elements.style.transition = "unset";
        elements.style.transform = `translateX(${decal}px)`;
        setTimeout(slidePrev, 1);

    }
    let decal = -slideWidth * compteur;
    elements.style.transform = `translateX(${decal}px)`;
}




// start the Stimulus application
import './bootstrap';
