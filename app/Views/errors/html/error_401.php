<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Não autorizado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h1 class="display-4 text-danger">401</h1>
                        <h2 class="mb-3">Não autorizado</h2>
                        <p class="lead">
                            <?= esc(session()->getFlashdata('erros') ?? 'Você não tem permissão para acessar esta página.') ?>
                        </p>
                        <a href="javascript:history.back()" class="btn btn-primary me-2">Voltar</a>
                        <a href="<?= site_url('/') ?>" class="btn btn-secondary">Página inicial</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
