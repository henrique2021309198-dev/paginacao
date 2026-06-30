<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?: 'Autenticação' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 0 40px rgba(0,0,0,.08);
        }
    </style>
</head>
<body>
    <div class="auth-card card p-4">
        <div class="card-body">
            <div class="text-center mb-4">
                <h1 class="h4">Cantina</h1>
                <p class="text-muted mb-0">Acesse sua conta</p>
            </div>

            <?php if (session()->getFlashdata('sucesso')) : ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('sucesso')) ?></div>
            <?php endif ?>

            <?php if (session()->getFlashdata('erros')) : ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('erros')) ?></div>
            <?php endif ?>

            <?= $this->renderSection('conteudo') ?>
        </div>
    </div>
</body>
</html>
