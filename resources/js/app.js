import './bootstrap';
import 'bootstrap';


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


const usuariosNav = document.querySelector("#usuariosNav");
const perfilNav = document.querySelector("#perfilNav");
usuariosNav.style.display = "none";
perfilNav.style.display = "none";

document.addEventListener("DOMContentLoaded", async () => {
    const userRol = await getUserRol();


    if (userRol == 1) {
        usuariosNav.style.display = "";
    }

    if (userRol) {
        perfilNav.style.display = "";
    }
});

const getUserRol = async () => {
    try {
        const response = await fetch("/api/userInfoRol", {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('AuthToken')}`
            }
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error al obtener el rol:", error);
        return null;
    }
};
