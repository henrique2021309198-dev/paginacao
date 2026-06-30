<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Cantina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; display:flex; align-items:center; justify-content:center; background:#f8f9fa; }
    </style>
</head>
<body>
    <div class="card shadow-sm" style="width:100%;max-width:420px;border-radius:16px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold">Cantina</h1>
                <p class="text-muted small mb-0">Acesse sua conta</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2 small">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success py-2 small">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('login') ?>">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">E-mail</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="seu@email.com" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Senha</label>
                    <input type="password" name="senha" class="form-control"
                           placeholder="••••••" required>
                </div>
                <button type="submit" class="btn btn-dark w-100 fw-semibold">
                    Entrar
                </button>
            </form>

            <p class="text-muted text-center small mt-3 mb-0">
                Padrao: <code>admin@teste.com</code> / <code>123456</code>
            </p>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>