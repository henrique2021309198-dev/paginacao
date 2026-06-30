<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?: 'Cantina' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('/') ?>">Cantina</a>
            <div>
                <a href="<?= site_url('/') ?>" class="btn btn-sm btn-outline-light me-2">Home</a>
                <?php if (! session()->get('logado')) : ?>
                    <a href="<?= site_url('login') ?>" class="btn btn-sm btn-primary me-2">Entrar</a>
                    <a href="<?= site_url('cadastrar') ?>" class="btn btn-sm btn-outline-light">Cadastrar</a>
                <?php else : ?>
                    <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-danger">Sair</a>
                <?php endif ?>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <?= $this->renderSection('conteudo') ?>
    </main>
</body>
</html>
