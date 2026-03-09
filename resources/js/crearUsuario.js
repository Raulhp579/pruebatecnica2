const botonCrearUsuario = document.querySelector("#btnCrearUsuario");

botonCrearUsuario.addEventListener("click", async () => {
    const nombre = document.querySelector("#name").value;
    const correo = document.querySelector("#email").value;
    const contrasena = document.querySelector("#password").value;
    const esAdmin = document.querySelector("#administrador").checked;

    const usuario = {
        nombre,
        correo,
        contrasena,
        esAdmin,
    };

    const response = await fetch("/api/user", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify(usuario),
    });

    const data = await response.json();

    console.log(data);
});
