<?= $this->extend('templates/painel') ?>
<?= $this->section('title') ?>Painel de consumo<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<?php
$role = session()->get('usuario')['role'] ?? 'usuario';
$tipo = session()->get('usuario')['tipo'] ?? 'user';
$isAdmin = ($role === 'super_admin' || $tipo === 'admin');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Painel de consumo</h4>
    <?php if ($isAdmin): ?>
        <a href="<?= site_url('admin/produtos') ?>" class="btn btn-dark">
            <i class="bi bi-basket me-1"></i> Painel administrativo
        </a>
    <?php endif; ?>
</div>

<!-- Metricas -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-dark"><?= $totalProdutos ?></div>
            <div class="text-muted small mt-1">Total de produtos</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-dark"><?= $totalMovimentacoes ?></div>
            <div class="text-muted small mt-1">Movimentacoes de estoque</div>
        </div>
    </div>
    <div class="col-sm-4">
        <?php $baixoEstoque = count(array_filter($produtos ?? [], fn($p) => (int)($p['estoque'] ?? 0) <= (int)($p['estoque_limite'] ?? 0))); ?>
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold <?= $baixoEstoque > 0 ? 'text-warning' : 'text-success' ?>"><?= $baixoEstoque ?></div>
            <div class="text-muted small mt-1">Produtos com baixo estoque</div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="get" action="<?= site_url('painel/consumo') ?>" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label small fw-semibold mb-1">Categorias:</label>
                <select name="categoria" class="form-select form-select-sm" style="min-width:160px;">
                    <option value="">Todos</option>
                    <?php foreach ($categorias ?? [] as $cat): ?>
                        <option value="<?= esc($cat['categoria']) ?>"
                            <?= (($categoria ?? '') === $cat['categoria']) ? 'selected' : '' ?>>
                            <?= esc($cat['categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label small fw-semibold mb-1">Filtrar:</label>
                <input type="text" name="buscar" class="form-control form-control-sm"
                       value="<?= esc($buscar ?? '') ?>" placeholder="Nome do produto"
                       style="min-width:200px;">
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-dark">
                    <i class="bi bi-funnel me-1"></i>Filtrar
                </button>
                <a href="<?= site_url('painel/consumo') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de produtos -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px;"></th>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th class="text-center">Disponivel</th>
                        <th class="text-center">Stock atual</th>
                        <th class="text-center">Qtd vendida</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($produtos)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td class="ps-3">
                                    <?php if (!empty($p['foto']) && file_exists(FCPATH . 'uploads/produtos/' . $p['foto'])): ?>
                                        <img src="<?= base_url('uploads/produtos/' . $p['foto']) ?>" alt=""
                                             class="rounded" style="width:40px;height:40px;object-fit:cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width:40px;height:40px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= esc($p['nome']) ?></td>
                                <td class="text-muted"><?= esc($p['categoria']) ?></td>
                                <td class="text-center">
                                    <?php if ((int)($p['estoque'] ?? 0) > 0): ?>
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $est = (int)($p['estoque'] ?? 0);
                                    $lim = (int)($p['estoque_limite'] ?? 0);
                                    $cor = ($est <= $lim && $lim > 0) ? 'text-danger fw-bold' : '';
                                    ?>
                                    <span class="<?= $cor ?>"><?= $est ?></span>
                                    <?php if ($est <= $lim && $lim > 0): ?>
                                        <i class="bi bi-exclamation-triangle-fill text-warning ms-1" title="Estoque baixo"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center fw-semibold"><?= (int)($p['quantidade_vendida'] ?? 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>