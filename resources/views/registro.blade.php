<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>

</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">Bienvenido</h2>
                    <p class="text-muted">Introduce tus datos para registrarte</p>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Introduce tu nombre</label>
                    <input type="nombre" class="form-control" id="nombre" placeholder="">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" placeholder="tu@ejemplo.com">
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label mb-0">Contraseña</label>
                    </div>
                    <input type="password" class="form-control mt-2" id="password" placeholder="">
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="passwordConfirm" class="form-label mb-0">Comfirma la contraseña</label>
                    </div>
                    <input type="password" class="form-control mt-2" id="passwordConfirm" placeholder="">
                </div>

                <div class="d-grid">
                    <button type="button" class="btn btn-primary btn-lg" id="btn-register" >Registrarse</button>
                </div>

            </div>
        </div>
    </div>


    @vite(['resources/js/registro.js'])

</body>
</html>
