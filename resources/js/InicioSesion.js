import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';


const botonLogin = document.querySelector("#btn-login")

botonLogin.addEventListener("click",async ()=>{

    const email = document.querySelector("#email").value
    const password = document.querySelector("#password").value

    const response =await fetch(`/api/login?email=${email}&password=${password}`)
    const data =await response.json()

    localStorage.setItem("AuthToken", data)

    window.location.href = "/";
})


