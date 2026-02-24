<header>
    <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                    <h4 class="text-white">About</h4>
                    <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
                </div>
                <div class="col-sm-4 offset-md-1 py-4">
                    <h4 class="text-white">Contact</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Follow on Twitter</a></li>
                        <li><a href="#" class="text-white">Like on Facebook</a></li>
                        <li><a href="#" class="text-white">Email me</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a href="../presentacion/index.html" class="navbar-brand">
                <strong>Autoparts mx</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="catalogo.php" class="nav-link active">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a href="../presentacion/index.html" class="nav-link">Inicio</a>
                    </li>

                </ul>

                <form action="catalogo.php" method="get" autocomplete="off">
                    <div class="input-group pe-3">
                    <input type="text" name="q" id="q" class="form-control form-control-sm" 
                    placeholder="Buscar por nombre..." aria-describedby="icon-buscar">
                    <button type="submit" id="icon-buscar" class="btn btn-light btn-outline-info btn-sm">
                        <i class="fas fa-search"></i>
                    </button>

                    </div>
                </form>

                <a href="checkout.php" class="btn btn-dark btn-sm me-3">
                    <i class="fas fa-shopping-cart"></i>
                    Mi carrito<span id="num_cart" class="badge bg-warning"><?php echo $num_cart; ?></span>
                </a>

                <?php if (isset($_SESSION['user_id'])) { ?>

                    <div class="dropdown">
                        <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="btn_session" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user"></i> &nbsp; <?php echo $_SESSION['user_name']; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btn_session">
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                            <li><a class="dropdown-item" href="compras.php">Mi historial de compras</a></li>

                        </ul>
                    </div>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-success btn-sm"><i class="fa-solid fa-user"></i>Ingresar</a>
                <?php } ?>

            </div>
        </div>
    </div>
</header>