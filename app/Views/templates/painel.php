<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?: 'Cantina' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <?= $this->renderSection('head') ?>
    <style>
        .sidebar-link { display:block; padding:9px 12px; border-radius:8px; text-decoration:none; color:#495057; font-size:.9rem; }
        .sidebar-link:hover { background:#f1f3f5; color:#212529; }
        .sidebar-link.ativo { background:#212529; color:#fff !important; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4" style="height:56px;">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-light btn-sm d-md-none" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#menuMobile">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand fw-bold mb-0" href="<?= site_url('painel/consumo') ?>">Cantina</a>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="text-white-50 small d-none d-md-inline">
            <?= esc(session()->get('usuario')['nome'] ?? '') ?>
        </span>
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right me-1"></i>Sair
        </a>
    </div>
</nav>

<?php
$uri  = service('request')->getUri()->getPath();
$role = session()->get('usuario')['role'] ?? 'usuario';
$tipo = session()->get('usuario')['tipo'] ?? 'user';
$isAdmin = ($role === 'super_admin' || $tipo === 'admin');

$isOnUsuarios = str_starts_with($uri, '/usuarios') && !str_starts_with($uri, '/usuarios/perfil');
$isOnPerfil   = str_starts_with($uri, '/usuarios/perfil');
$isOnConsumo  = str_starts_with($uri, '/painel/consumo');
$isOnVendas   = str_starts_with($uri, '/painel/vendas');
$isOnProdutos = str_starts_with($uri, '/admin/produtos');
?>

<div class="container-fluid">
    <div class="row">

        <aside class="col-md-2 bg-white border-end d-none d-md-flex flex-column p-3"
               style="min-height:calc(100vh - 56px);">
            <p class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.7rem; letter-spacing:.05em;">MENU</p>
            <nav class="d-flex flex-column gap-1">
                <?php if ($role === 'super_admin'): ?>
                    <a href="<?= site_url('usuarios') ?>" class="sidebar-link <?= $isOnUsuarios ? 'ativo' : '' ?>">
                        <i class="bi bi-people me-2"></i>Usuarios
                    </a>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                    <a href="<?= site_url('admin/produtos') ?>" class="sidebar-link <?= $isOnProdutos ? 'ativo' : '' ?>">
                        <i class="bi bi-basket me-2"></i>Produtos
                    </a>
                    <a href="<?= site_url('admin/produtos/novo') ?>" class="sidebar-link">
                        <i class="bi bi-plus-circle me-2"></i>Novo produto
                    </a>
                <?php endif; ?>
                <a href="<?= site_url('usuarios/perfil') ?>" class="sidebar-link <?= $isOnPerfil ? 'ativo' : '' ?>">
                    <i class="bi bi-person me-2"></i>Meu perfil
                </a>
                <a href="<?= site_url('painel/consumo') ?>" class="sidebar-link <?= $isOnConsumo ? 'ativo' : '' ?>">
                    <i class="bi bi-box-seam me-2"></i>Painel de consumo
                </a>
                <a href="<?= site_url('painel/vendas') ?>" class="sidebar-link <?= $isOnVendas ? 'ativo' : '' ?>">
                    <i class="bi bi-graph-up me-2"></i>Painel de vendas
                </a>
            </nav>
        </aside>

        <main class="col-12 col-md-10 p-4">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('conteudo') ?>
        </main>

    </div>
</div>

<!-- Mobile offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="menuMobile">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="d-flex flex-column gap-1">
            <?php if ($role === 'super_admin'): ?>
                <a href="<?= site_url('usuarios') ?>" class="sidebar-link">
                    <i class="bi bi-people me-2"></i>Usuarios
                </a>
            <?php endif; ?>
            <?php if ($isAdmin): ?>
                <a href="<?= site_url('admin/produtos') ?>" class="sidebar-link"><i class="bi bi-basket me-2"></i>Produtos</a>
                <a href="<?= site_url('admin/produtos/novo') ?>" class="sidebar-link"><i class="bi bi-plus-circle me-2"></i>Novo produto</a>
            <?php endif; ?>
            <a href="<?= site_url('usuarios/perfil') ?>" class="sidebar-link"><i class="bi bi-person me-2"></i>Meu perfil</a>
            <a href="<?= site_url('painel/consumo') ?>" class="sidebar-link"><i class="bi bi-box-seam me-2"></i>Painel de consumo</a>
            <a href="<?= site_url('painel/vendas') ?>" class="sidebar-link"><i class="bi bi-graph-up me-2"></i>Painel de vendas</a>
            <hr>
            <a href="<?= site_url('logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sair</a>
        </nav>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const DEBUG_FLOW = true;

    function logPainel(event, payload = {}) {
        if (!DEBUG_FLOW) return;
        console.log(`[Paginacao][Painel] ${event}`, payload);
    }

    function classifyActionFromPath(path) {
        if (path.includes('/admin/produtos/salvar')) return 'Salvar produto';
        if (path.includes('/admin/produtos/excluir/')) return 'Excluir produto';
        if (path.includes('/admin/produtos/novo')) return 'Abrir cadastro de produto';
        if (path.includes('/admin/produtos/editar/')) return 'Abrir edicao de produto';
        if (path.includes('/estoque/adicionar/')) return 'Abrir adicao de estoque';
        if (path.includes('/estoque/remover/')) return 'Abrir remocao de estoque';
        if (path.includes('/estoque/salvar')) return 'Salvar movimentacao de estoque';
        if (path.includes('/usuarios/salvar')) return 'Salvar usuario';
        if (path.includes('/usuarios/status/')) return 'Bloquear/Desbloquear usuario';
        if (path.includes('/usuarios/perfil')) return 'Atualizar perfil';
        return 'Acao geral';
    }

    document.addEventListener('DOMContentLoaded', () => {
        logPainel('Pagina carregada', {
            path: window.location.pathname,
            query: window.location.search,
        });
    });

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href') || '';
        if (!href) return;

        const path = href.startsWith('http')
            ? new URL(href).pathname
            : href;

        logPainel('Clique em link', {
            action: classifyActionFromPath(path),
            href,
            text: (link.textContent || '').trim(),
        });
    });

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;

        const action = form.getAttribute('action') || window.location.pathname;
        const method = (form.getAttribute('method') || 'GET').toUpperCase();

        const data = {};
        try {
            const fd = new FormData(form);
            for (const [key, value] of fd.entries()) {
                if (key.toLowerCase().includes('senha') || key.toLowerCase().includes('password')) {
                    data[key] = '[MASKED]';
                } else {
                    data[key] = value;
                }
            }
        } catch (error) {
            data._parseError = true;
        }

        logPainel('Submit de formulario', {
            actionLabel: classifyActionFromPath(action),
            action,
            method,
            data,
        });
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>