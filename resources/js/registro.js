import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';


const nombreInput = document.querySelector("#nombre")
const emailInput = document.querySelector("#email")
const passwordInput = document.querySelector("#password")
const passwordConfirmInput = document.querySelector("#passwordConfirm")

const btnRegistrarse = document.querySelector("#btn-register")

btnRegistrarse.addEventListener("click",async () => {

    if(passwordConfirmInput.value != passwordInput.value){
        alert("las contraseñas no coinciden")
        return
    }


    const datos = {
        name:nombreInput.value,
        email:emailInput.value,
        password:passwordInput.value
    }

    const response = await fetch('/api/registro',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
        },
        body:JSON.stringify(datos)

    })


    const data = await response.json()

    localStorage.setItem('AuthToken', data)

    window.location.href = "/home"
})

