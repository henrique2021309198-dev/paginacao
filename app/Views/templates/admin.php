<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantina</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar-desktop {
            min-height: 100vh;
            border-right: 1px solid #dee2e6;
        }

        .menu-link {
            display: block;
            padding: 10px 12px;
            text-decoration: none;
            color: #212529;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        .menu-link:hover {
            background-color: #e9ecef;
        }

        .menu-link.ativo {
            background-color: #dee2e6;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- navbar -->
    <nav class="navbar navbar-dark bg-dark px-3">
        <div class="d-flex align-items-center">
            <!-- botão mobile -->
            <button class="btn btn-outline-light d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuMobile" aria-controls="menuMobile">
                ☰
            </button>

            <a class="navbar-brand mb-0" href="<?= site_url('/') ?>">
                Minha Logo
            </a>
        </div>

        <span class="text-white"><?= session()->get('logado') ? session()->get('usuario')['email'] : '' ; ?></span>
    </nav>

    <div class="container-fluid">
        <div class="row">

            
            <aside class="col-md-3 col-lg-2 bg-white sidebar-desktop p-3 d-none d-md-block">
                <h6 class="text-muted mb-3">Menu</h6>

                <a href="<?= site_url('admin/produtos') ?>" class="menu-link ativo">Produtos</a>
                <?php
                    if(session()->get('logado')):?>
                        <a href="<?= site_url('logout') ?>" class="menu-link">Sair</a>
                    <?php endif; ?>

            </aside>

            
            <!-- conteúdo das views aqui -->
            <main class="col-12 col-md-9 col-lg-10 px-3 px-md-4 py-4">
                <?= $this->renderSection('conteudo') ?>
            </main>

        </div>
    </div>

  
    <div class="offcanvas offcanvas-start" tabindex="-1" id="menuMobile" aria-labelledby="menuMobileLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="menuMobileLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>

        <div class="offcanvas-body">
            <a href="<?= site_url('admin/produtos') ?>" class="menu-link ativo"> Produtos</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>