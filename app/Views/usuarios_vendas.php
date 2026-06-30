<?= $this->extend('templates/painel') ?>
<?= $this->section('title') ?>Painel de vendas<?= $this->endSection() ?>

<?= $this->section('head') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<?php
$role = session()->get('usuario')['role'] ?? 'usuario';
$tipo = session()->get('usuario')['tipo'] ?? 'user';
$isAdmin = ($role === 'super_admin' || $tipo === 'admin');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Painel de vendas</h4>
    <?php if ($isAdmin): ?>
        <a href="<?= site_url('admin/produtos') ?>" class="btn btn-dark">
            <i class="bi bi-basket me-1"></i> Painel administrativo
        </a>
    <?php endif; ?>
</div>

<!-- Filtros de periodo -->
<div class="d-flex flex-wrap gap-2 align-items-center mb-4">
    <a href="<?= site_url('painel/vendas') ?>?periodo=7"
       class="btn btn-sm <?= ($periodo ?? '') === '7' ? 'btn-dark' : 'btn-outline-secondary' ?>">
        <i class="bi bi-calendar3 me-1"></i> Ultimos 7 dias
    </a>
    <a href="<?= site_url('painel/vendas') ?>?periodo=30"
       class="btn btn-sm <?= ($periodo ?? '') === '30' ? 'btn-dark' : 'btn-outline-secondary' ?>">
        <i class="bi bi-calendar3 me-1"></i> Ultimos 30 dias
    </a>
    <a href="<?= site_url('painel/vendas') ?>?periodo=all"
       class="btn btn-sm <?= ($periodo ?? '') === 'all' ? 'btn-dark' : 'btn-outline-secondary' ?>">
        <i class="bi bi-calendar-range me-1"></i> Desde sempre
    </a>
    <form method="get" action="<?= site_url('painel/vendas') ?>" class="d-flex gap-2 align-items-center ms-2">
        <input type="hidden" name="periodo" value="custom">
        <span class="text-muted small">De</span>
        <input type="date" name="data_inicio" value="<?= esc($dataInicio ?? '') ?>"
               class="form-control form-control-sm" style="width:140px;">
        <span class="text-muted small">ate</span>
        <input type="date" name="data_fim" value="<?= esc($dataFim ?? '') ?>"
               class="form-control form-control-sm" style="width:140px;">
        <button type="submit" class="btn btn-sm btn-outline-dark">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

<!-- Metricas -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-dark"><?= $totalPedidos ?></div>
            <div class="text-muted small mt-1">Total de pedidos</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">
                R$ <?= number_format($totalValor ?? 0, 2, ',', '.') ?>
            </div>
            <div class="text-muted small mt-1">Valor total (sem cancelados)</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex flex-wrap gap-1 justify-content-center mb-1">
                <?php foreach ($porStatus ?? [] as $s): ?>
                    <?php
                    $badge = match($s['status']) {
                        'concluido'   => 'bg-success',
                        'cancelado'   => 'bg-danger',
                        'processando' => 'bg-warning text-dark',
                        default       => 'bg-secondary',
                    };
                    ?>
                    <span class="badge <?= $badge ?>"><?= esc($s['status']) ?>: <?= $s['quantidade'] ?></span>
                <?php endforeach; ?>
                <?php if (empty($porStatus)): ?>
                    <span class="text-muted small">Sem dados</span>
                <?php endif; ?>
            </div>
            <div class="text-muted small text-center mt-1">Por status</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                <div class="text-muted text-uppercase small fw-semibold mb-2">Totem líder</div>
                <?php if (!empty($topTotem)): ?>
                    <div class="fs-4 fw-bold"><?= esc($topTotem['nome'] ?? 'Totem') ?></div>
                    <div class="text-muted mb-2"><?= esc($topTotem['codigo'] ?? '') ?></div>
                    <div class="fs-2 fw-bold text-dark"><?= (int) ($topTotem['pedidos'] ?? 0) ?></div>
                    <div class="text-muted small">pedidos no período</div>
                <?php else: ?>
                    <div class="text-muted">Sem dados de totem no período.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0">Totens que mais recebem pedidos</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Totem</th>
                                <th>Código</th>
                                <th class="text-center">Pedidos</th>
                                <th class="text-end pe-3">Valor total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($totensResumo)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Nenhum pedido associado a totem no período.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($totensResumo as $totemLinha): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold"><?= esc($totemLinha['nome'] ?? 'Totem') ?></td>
                                        <td class="text-muted"><?= esc($totemLinha['codigo'] ?? '-') ?></td>
                                        <td class="text-center"><?= (int) ($totemLinha['pedidos'] ?? 0) ?></td>
                                        <td class="text-end pe-3">R$ <?= number_format((float) ($totemLinha['total'] ?? 0), 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Layout dividido: tabela + grafico -->
<div class="row g-4">

    <!-- Tabela de vendas por dia -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0">
                    Vendas por dia
                    <span class="text-muted fw-normal small">
                        <?= esc($dataInicio ?? '') ?> a <?= esc($dataFim ?? '') ?>
                    </span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-3">Data</th>
                                <th class="text-end">Valor total</th>
                                <th class="text-center pe-3">Pedidos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($vendasPorDia)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Nenhuma venda no periodo.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vendasPorDia as $v): ?>
                                    <tr>
                                        <td class="ps-3"><?= esc($v['data']) ?></td>
                                        <td class="text-end fw-semibold">
                                            R$ <?= number_format((float)$v['total'], 2, ',', '.') ?>
                                        </td>
                                        <td class="text-center pe-3"><?= (int)$v['pedidos'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafico de linha -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0">Grafico de vendas do periodo</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <?php if (empty($vendasPorDia)): ?>
                    <div class="text-center text-muted">
                        <i class="bi bi-bar-chart-line fs-2 d-block mb-2"></i>
                        Nenhum dado para exibir no periodo selecionado.
                    </div>
                <?php else: ?>
                    <canvas id="vendasChart" style="max-height:320px;"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    var labels = <?= $chartLabels ?? '[]' ?>;
    var data   = <?= $chartData   ?? '[]' ?>;
    if (!labels.length) return;

    new Chart(document.getElementById('vendasChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Vendas (R$)',
                data: data,
                borderColor: '#212529',
                backgroundColor: 'rgba(33,37,41,0.07)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#212529',
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return 'R$ ' + parseFloat(ctx.raw).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2
                            });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (val) {
                            return 'R$ ' + val.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
}());
</script>
<?= $this->endSection() ?>