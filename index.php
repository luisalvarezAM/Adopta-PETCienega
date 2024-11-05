<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require('assets/conexionBD.php'); //conexion a la base de datos
        $conexion = obtenerConexion();
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        //consulta el usuario y contraseña

        $sql = "SELECT * FROM usuarios WHERE correo='$email' and contraseña='$password'";
        $result = $conexion->query($sql);

        if ($result->num_rows == 1) {
            
            $fila=$result->fetch_assoc();
            $id_usuario=$fila['id_usuario'];
            $nombre_usuario=$fila['nombre_completo'];

            session_start();
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre_usuario'] = $nombre_usuario;

            if ($result->num_rows == 1) { //Visitante
                header("location:adopta/");
            } //Fin de visitante
            else { //administrador
                header($header="admin/");
            }
        } //Fin de checar si existe el usuario y la contraseña
        else {
            echo '<script>
            alert("Correo o contraseña incorrectos");
          </script>';
        } //fin de no encontro el usuario o contraseña incorrecta
        $conexion->close();
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Inicio</title>
    <link rel="icon" type="image/x-icon" href="assets/adoptapetcienega.png" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!--Menú de navegación-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#page-top">Adopta PETCienega</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation">
                Menú
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">¿Quiénes somos?</a></li>
                    <li class="nav-item"><a class="nav-link" href="#projects">Propósito</a></li>
                    <li id="loginBtn2" class="nav-item"><a class="nav-link" href="#login">Únete a la comunidad</a></li>
                </ul>
            </div>
        </div>
        <script src="js/script_login.js"></script>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
            <div class="d-flex justify-content-center">
                <div class="text-center">
                    <h1 class="mx-auto my-0 text-uppercase">Adopta PETCienega</h1>
                    <h2 class="text-white-50 mx-auto mt-2 mb-5">Un hogar deliz para una
                        mascota es un hogar feliz para ti.</h2>
                    <a id="loginBtn" class="btn btn-primary" href="#login">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <!-- About-->
    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <h2 class="text-white mb-4">¿Quiénes Somos?</h2>
                    <p class="text-white-50">
                        Somos una comunidad dedicada a la adopción de perros y gatos en busca
                        de un hogar amoroso. A diferencia de los refugios tradicionales, no contamos
                        con un lugar físico donde los animales estén albergados. Nuestra misión es conectar a mascotas
                        en situación de vulnerabilidad con familias responsables que les brinden nueva
                        oportunidad de vida.
                    </p>
                </div>
            </div>
            <img class="img-fluid" src="assets/img/dog_cat_png" alt="..." />
        </div>
    </section>
    <!-- Projects-->
    <section class="projects-section bg-light" id="projects">
        <div class="container px-4 px-lg-5">
            <!-- Featured Project Row-->
            <div class="row gx-0 mb-4 mb-lg-5 align-items-center">
                <div class="col-xl-8 col-lg-7"><img class="img-fluid mb-3 mb-lg-0" src="assets/img/dog_cat1.jpg"
                        alt="..." /></div>
                <div class="col-xl-4 col-lg-5">
                    <div class="featured-text text-center text-lg-left">
                        <h4>Una segunda oportunidad, un click de distancia </h4>
                        <p class="text-black-50 mb-0">Creemos que cada perro y/o gato merece una
                            segunda oportunidad, por lo que nuestra plataforma facilita el proceso
                            de adopción, permitiendo a los interesados conocer a los animales a travéz
                            de fotos y descripciones detalladas!</p>
                    </div>
                </div>
            </div>
            <!-- Project One Row-->
            <div class="row gx-0 mb-5 mb-lg-0 justify-content-center">
                <div class="col-lg-6"><img class="img-fluid" src="assets/img/dog_cat2.jpg" alt="..." /></div>
                <div class="col-lg-6">
                    <div class="bg-black text-center h-100 project">
                        <div class="d-flex h-100">
                            <div class="project-text w-100 my-auto text-center text-lg-left">
                                <h4 class="text-white">Una segunda oportunidad, un hogar definitivo</h4>
                                <p class="mb-0 text-white-50">No tenemos un espacio físico, pero si un compromiso
                                    infinito por encontrales una familia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Project Two Row-->
            <div class="row gx-0 justify-content-center">
                <div class="col-lg-6"><img class="img-fluid" src="assets/img/dog_cat3.jpg" alt="..." /></div>
                <div class="col-lg-6 order-lg-first">
                    <div class="bg-black text-center h-100 project">
                        <div class="d-flex h-100">
                            <div class="project-text w-100 my-auto text-center text-lg-right">
                                <h4 class="text-white">Mountains</h4>
                                <p class="mb-0 text-white-50">Another
                                    example of a project with its respective
                                    description. These sections work well
                                    responsively as well!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <link href="css/style.css" rel="stylesheet" />
    <!-- Iniciar sesion -->
    <div id="login" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-header">
                <img src="assets/adoptapetcienega.png" alt="Logo" class="logo">
                <h2>Acceder a mi cuenta</h2>
            </div>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <label for="email">Usuario</label>
                <input type="email" id="email" name="email" placeholder="Introduce tu correo" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Introduce tu contraeña" required>

                <a href="">¿Olvidaste la contraseña</a>

                <button type="submit" class="btn">Entrar</button>

                <a href="signup.php" class="btn">Crear cuenta</a>

            </form>
        </div>
    </div>
    <script src="js/script_login.js"></script>
    <!-- Contact-->
    <section class="contact-section bg-black">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card py-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marked-alt text-primary mb-2"></i>
                            <h4 class="text-uppercase m-0">Instagram</h4>
                            <hr class="my-4 mx-auto" />
                            <div class="small text-black-50"><a
                                    href="https://www.instagram.com/adoptapetcienega/">@adoptapetcienega </a></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card py-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-envelope text-primary mb-2"></i>
                            <h4 class="text-uppercase m-0">Email</h4>
                            <hr class="my-4 mx-auto" />
                            <div class="small text-black-50"><a></a>adoptapetcienega@gmail.com</a></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card py-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-mobile-alt text-primary mb-2"></i>
                            <h4 class="text-uppercase m-0">Ubicación</h4>
                            <hr class="my-4 mx-auto" />
                            <div class="small text-black-50">Centro Universitario de la Cienega</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="footer bg-black small text-center text-white-50">
        <div class="container px-4 px-lg-5">Copyright &copy; Adopta PETCienega 2024-2025</div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>