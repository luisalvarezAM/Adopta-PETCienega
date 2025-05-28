<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Profesional</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/adoptapetcienega.png" />
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <h3>Administrador<span>Pro</span></h3>
                <strong>AP</strong>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="usuarios.php">
                        <i class="fas fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="administradores.php">
                        <i class="fas fa-user-cog"></i>
                        <span>Administradores</span>
                    </a>
                </li>
                <li>
                    <a href="mascotas.php">
                        <i class="fas fa-paw"></i>
                        <span>Mascotas</span>
                    </a>
                </li>
                <li>
                    <a href="adopciones.php">
                        <i class="fas fa-calendar-check"></i>
                        <span>Adopciones</span>
                    </a>
                </li>
                <li>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-cog"></i>
                        <span>Editar perfil</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <div class="user-profile ml-auto">
                        <div class="user-info">
                            <span class="user-name">Administrador</span>
                            <span class="user-role">Super Admin</span>
                        </div>
                        <img src="https://via.placeholder.com/40" alt="User" class="user-avatar rounded-circle">
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="main-content">
                <div class="container-fluid">
                    <div class="page-header">
                        <h2>Resumen General</h2>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h5>Usuarios</h5>
                                        <h3>27</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="stat-icon">
                                        <i class="fas fa-user-cog"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h5>Administradores</h5>
                                        <h3>24</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="stat-icon">
                                        <i class="fas fa-paw"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h5>Mascotas</h5>
                                        <h3>25</h3>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Tables Row -->
                    <div class="row">
                        <div class="col-lg-8 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Actividad Reciente</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Usuario</th>
                                                    <th>Tipo</th>
                                                    <th>Fecha</th>
                                                    <th>Estatus
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Maria García</td>
                                                    <td>Gato</td>
                                                    <td>10 Abril 2025</td>
                                                    <td><span class="badge bg-success">Disponible</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Juan Pérez</td>
                                                    <td>Perro</td>
                                                    <td>9 Abril 2025</td>
                                                    <td><span class="badge bg-warning">Pendiente</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Admin User</td>
                                                    <td>Gato</td>
                                                    <td>8 Abril 2025</td>
                                                    <td><span class="badge bg-success">Adoptado</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Luisa Martínez</td>
                                                    <td>Gato</td>
                                                    <td>7 Abril 2025</td>
                                                    <td><span class="badge bg-success">Disponible</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Distribución de Mascotas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="petChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="../js/admin.js"></script>
</body>

</html>