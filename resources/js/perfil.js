const tituloUsuario = document.querySelector("#nombreUsuario")
const tituloCorreo = document.querySelector("#tituloCorreo")
const btnGuardar = document.querySelector("#btnGuardarPerfil")
const inputNombre = document.querySelector("#perfil_name")
const inputEmail = document.querySelector("#perfil_email")
const inputContraseña = document.querySelector("#perfil_password")
const inputConfirmarContraseña = document.querySelector("#perfil_password_confirmation")
let usuarioInfo = null
const avatar = document.querySelector("#avatarNombre")


document.addEventListener('DOMContentLoaded',async ()=>{
    console.log(await getUserInfo())
    usuarioInfo =await getUserInfo()
    tituloUsuario.textContent = usuarioInfo.name
    tituloCorreo.textContent = usuarioInfo.email
    const nombreAvatar = encodeURIComponent(usuarioInfo.name || 'Usuario');

    avatar.src = `https://ui-avatars.com/api/?name=${nombreAvatar}&background=0D8ABC&color=fff&size=128`;
})

const getUserInfo =async () => {
    const response = await fetch("/api/userInfo",{
        headers:{
            'Authorization':`Bearer ${localStorage.getItem('AuthToken')}`
        }
    })

    const data =await response.json()

    return data
}

btnGuardar.addEventListener("click",async ()=> {
    let nombre = usuarioInfo.name
    let email = usuarioInfo.email
    let contraseña = usuarioInfo.password
    const inputActual = document.querySelector("#perfil_password_actual")

    if(inputNombre.value){
        nombre = inputNombre.value
    }
    if(inputEmail.value){
        email = inputEmail.value
    }

    if(inputContraseña.value){
        if(inputContraseña.value == inputConfirmarContraseña.value){
            contraseña = inputContraseña.value
            const datos = {
                password: contraseña,
                actual: inputActual.value
            }

            console.log(datos)

            const response =await fetch("/api/cambiarPassword",{
                method:'PUT',
                headers:{
                    'Content-Type':'application/json',
                    'Authorization':`Bearer ${localStorage.getItem("AuthToken")}`
                },
                body:JSON.stringify(datos)
            })

            const data = await response.json()

            console.log(data)

        }else{
            alert("las contraseñas no coinciden")
        }
    }

    const datos = {
        name: nombre,
        email: email,
    }


    const response = await fetch("/api/cambiarDatos",{
        method: 'PUT',
        headers:{
            'Content-Type':'application/json',
            'Authorization':`Bearer ${localStorage.getItem('AuthToken')}`
        },
        body: JSON.stringify(datos)
    })

    const data =await response.json()

    console.log(data)
})


