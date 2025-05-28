<?php
require('assets/conexionBD.php');
$conexion = obtenerConexion();

$alert = ""; // Mensaje para mostrar alertas

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = md5(trim($_POST['password']));
    $username = htmlspecialchars(trim($_POST['username']));
    $nombre_completo = htmlspecialchars(trim($_POST['nombre_completo']));
    $telefono = preg_replace('/[^0-9]/', '', trim($_POST['telefono']));
    $municipio = htmlspecialchars(trim($_POST['municipio']));
    $direccion = htmlspecialchars(trim($_POST['direccion']));
    $foto_perfil = null;
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/img/fotos_perfil/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            $fileName = uniqid('profile_') . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $uploadPath)) {
                $foto_perfil = $uploadPath;
            }
        }
    }

    // Verificar si ya existe el usuario o correo
    $check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $check_ind = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ?");
        $check_ind->bind_param("s", $username);
        $check_ind->execute();
        $username_exists = $check_ind->get_result()->num_rows > 0;

        if ($username_exists) {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        El nombre de usuario ya está registrado
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        } elseif ($email_exists) {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        El correo ya está registrado
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        }
    } else {
        // Preparar campos faltantes
        $fec_registro = date('Y-m-d H:i:s');
        $tipo_usuario = 1;

        $sql = $conexion->prepare("INSERT INTO usuarios (username, nombre_completo, contraseña, correo, telefono, municipio, direccion, img_perfil, fec_registro, tipo_usuario)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("ssssssssss", $username, $nombre_completo, $password, $email, $telefono, $municipio, $direccion, $foto_perfil, $fec_registro, $tipo_usuario);

        if ($sql->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        Error al registrar: ' . htmlspecialchars($conexion->error) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        }

        $sql->close();
    }
    $check->close();
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <link rel="icon" type="image/x-icon" href="assets/adoptapetcienega.png" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
                    <!-- Imagen lateral -->
                    <div class="card-img-left d-none d-md-flex">
                        <img src="assets/img/design_assets/signup.webp" alt="Imagen de registro" class="img-fluid">
                        <div class="img-overlay">
                            <h3>Únete a nuestra comunidad</h3>
                            <p>Completa el formulario para crear tu cuenta y comenzar a disfrutar de nuestros servicios.</p>
                        </div>
                    </div>

                    <div class="card-body p-4 p-sm-5">
                        <h2 class="card-title text-center mb-4">Registro</h2>
                        <p class="text-center mb-4">Por favor completa todos los campos</p>

                        <?php echo $alert; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="form-section mb-4">
                                <div class="section-title mb-3">Información básica</div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                    <label for="email">Correo electrónico</label>
                                    <div class="invalid-feedback">Por favor ingresa un correo válido</div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="6">
                                    <label for="password">Contraseña</label>
                                    <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres</div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="myusername" required minlength="4">
                                    <label for="username">Nombre de usuario</label>
                                    <div class="invalid-feedback">El nombre de usuario debe tener al menos 4 caracteres</div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" placeholder="Nombre completo" required>
                                    <label for="nombre_completo">Nombre completo</label>
                                    <div class="invalid-feedback">Por favor ingresa tu nombre completo</div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="telefono" name="telefono" pattern="[0-9]{10}" placeholder="Número de telefono" required>
                                    <label for="telefono">Teléfono (10 dígitos)</label>
                                    <div class="invalid-feedback">Por favor ingresa un número de teléfono válido (10 dígitos)</div>
                                </div>
                            </div>

                            <div class="form-section mb-4">
                                <div class="section-title mb-3">Selecciona el municipio al que perteneces</div>

                                <div class="mb-3">
                                    <label for="municipio" class="form-label">Selecciona una opción</label>
                                    <select class="form-select" id="municipio" name="municipio" required>
                                        <option value="" selected disabled>-- Seleccione --</option>
                                        <option value="1">Atotonilco el Alto</option>
                                        <option value="2">Ayotlán</option>
                                        <option value="3">Degollado</option>
                                        <option value="4">Jamay</option>
                                        <option value="5">La Barca</option>
                                        <option value="6">Ocotlán</option>
                                        <option value="7">Poncitlán</option>
                                        <option value="8">Tototlán</option>
                                        <option value="9">Zapotlán del Rey</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor selecciona una opción</div>
                                </div>
                                <div class="form-section mb-4">
                                    <div class="section-title mb-3">Dirección</div>

                                    <div class="form-floating mb-1">
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ej: Calle Reforma 123 Int 4" required>
                                        <label for="direccion">Dirección</label>
                                        <div class="form-text">Ejemplo: Calle Reforma 123 Int 4</div>
                                        <div class="invalid-feedback">Por favor ingresa tu dirección completa</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="foto_perfil" class="form-label">Foto de perfil</label>
                                    <div class="file-upload-wrapper">
                                        <input class="form-control" type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                                        <div class="optional-field">Obligatoria</div>
                                    </div>
                                    <div class="preview-container mt-2">
                                        <img id="preview-foto" class="preview-image" src="#" alt="Vista previa de la imagen">
                                        <div class="preview-text">Vista previa de la imagen</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button class="btn btn-primary btn-lg fw-bold" type="submit">Registrarse</button>
                            </div>

                            <div class="text-center mt-3">
                                <p class="small">¿Ya tienes una cuenta? <a href="index.php">Inicia Sesión</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Mostrar vista previa de la imagen seleccionada
        document.getElementById('foto_perfil').addEventListener('change', function(e) {
            const preview = document.getElementById('preview-foto');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    preview.parentElement.querySelector('.preview-text').style.display = 'none';
                }

                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.src = '#';
                preview.parentElement.querySelector('.preview-text').style.display = 'block';
            }
        });

        // Hacer que las alertas desaparezcan después de 5 segundos
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>

</html>