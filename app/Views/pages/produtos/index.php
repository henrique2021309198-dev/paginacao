<?= $this->extend('templates/painel') ?>

<?= $this->section('conteudo') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Produtos</h4>
    <a href="<?= site_url('admin/produtos/novo') ?>" class="btn btn-dark">
        <i class="bi bi-plus-circle me-1"></i> Novo produto
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if (!empty($produtos)) : ?>
            <form method='get' action='<?= site_url('admin/produtos') ?>' class="mb-3">
                <div class="row mb-3">
                    <div class="col-md-3 mb-2">
                        <input name="busca" value="<?= esc($busca ?? '') ?>" placeholder="Filtrar por nome" class="form-control" />
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="preco" class="form-select">
                            <option value="">Todos</option>
                            <option value="baixo">Abaixo de R$ 5</option>
                            <option value="medio">Entre R$ 5 e R$ 10</option>
                            <option value="alto">Acima de R$ 10</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="ordenacao" class="form-select">
                            <option value="asc">Preço Crescente</option>
                            <option value="desc">Preço Decrescente</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                    </div>
                    <div class="col-md-2 mb-2">
                        <?php if ($busca || $preco): ?>
                            <a href="<?= site_url('admin/produtos') ?>" class="btn btn-outline-secondary w-100">Limpar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th class="text-center">Estoque</th>
                            <th>Foto</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto) : ?>
                            <tr>
                                <td><?= esc($produto['id']) ?></td>
                                <td><?= esc($produto['nome']) ?></td>
                                <td><?= esc($produto['categoria'] ?? '-') ?></td>
                                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                                <td class="text-center">
                                    <span class="badge bg-secondary"><?= (int) ($produto['estoque'] ?? 0) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($produto['foto'])) : ?>
                                        <img src='<?= site_url('uploads/produtos/' . $produto['foto']) ?>' style="width: 72px; height: 72px; object-fit: cover; border-radius: 8px;" />
                                    <?php else : ?>
                                        <span class="text-muted">SEM FOTO</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href='<?= site_url('estoque/adicionar/' . $produto['id']) ?>' class="btn btn-sm btn-outline-success" title="Aumentar estoque">
                                            <i class="bi bi-plus-lg"></i>
                                        </a>
                                        <a href='<?= site_url('estoque/remover/' . $produto['id']) ?>' class="btn btn-sm btn-outline-danger" title="Diminuir estoque">
                                            <i class="bi bi-dash-lg"></i>
                                        </a>
                                        <a href='<?= site_url('estoque/historico/' . $produto['id']) ?>' class="btn btn-sm btn-outline-secondary" title="Histórico de estoque">
                                            <i class="bi bi-clock-history"></i>
                                        </a>
                                        <a href='<?= site_url('admin/produtos/editar/' . $produto['id']) ?>' class="btn btn-sm btn-outline-primary">Editar</a>
                                        <a href='<?= site_url('admin/produtos/excluir/' . $produto['id']) ?>' class="btn btn-sm btn-outline-danger" onclick='return confirm("Excluir?")'>Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?= $pager->links('default', 'template_pager'); ?>
        <?php else : ?>
            <div class="alert alert-warning">
                <p>Nenhum produto cadastrado.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
