import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

let idUser = null

window.Pusher.logToConsole = true;

document.addEventListener("DOMContentLoaded", async () => {
    const data =  await obtenerUsuario()
    idUser = data.id
})

const obtenerUsuario = async () => {
    try {
        const response = await fetch('/api/userInfo', {
            headers: {
                 Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
            }
        })
        const data = await response.json()

        return data
    }catch(e){
        console.log("Error aqui "+e)
    }




}



window.Echo.channel("crearTarea")
    .listen('.create', (data)=>{
        if(data == idUser){
            alert("Se te ha asignado una nueva tarea")
        }
    })




