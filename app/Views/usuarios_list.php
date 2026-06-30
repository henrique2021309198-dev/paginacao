<?= $this->extend('templates/painel') ?>
<?= $this->section('title') ?>Usuarios<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Controle de usuarios</h4>
    <a href="<?= site_url('usuarios/novo') ?>" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i> Novo usuario
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="get" action="<?= site_url('usuarios') ?>" class="d-flex flex-wrap gap-2 mb-3">
            <input type="text" name="buscar" value="<?= esc($buscar ?? '') ?>"
                   class="form-control" style="max-width:280px;"
                   placeholder="Buscar por nome ou e-mail">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-search me-1"></i>Buscar
            </button>
            <?php if (!empty($buscar)): ?>
                <a href="<?= site_url('usuarios') ?>" class="btn btn-outline-danger">Limpar</a>
            <?php endif; ?>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Funcao</th>
                        <th>Status</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Nenhum usuario encontrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td class="fw-semibold"><?= esc($u['nome']) ?></td>
                                <td class="text-muted"><?= esc($u['email']) ?></td>
                                <td><?= esc($u['telefone'] ?? '—') ?></td>
                                <td>
                                    <?php if (($u['role'] ?? 'usuario') === 'super_admin'): ?>
                                        <span class="badge bg-dark">Super Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Usuario</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (($u['status'] ?? 1) == 1): ?>
                                        <span class="badge bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Bloqueado</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="<?= site_url('usuarios/editar/' . $u['id']) ?>"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="<?= site_url('usuarios/status/' . $u['id']) ?>"
                                       class="btn btn-sm <?= ($u['status'] ?? 1) == 1 ? 'btn-outline-danger' : 'btn-outline-success' ?>"
                                       onclick="return confirm('Confirmar alteracao de status?')">
                                        <?php if (($u['status'] ?? 1) == 1): ?>
                                            <i class="bi bi-lock"></i> Bloquear
                                        <?php else: ?>
                                            <i class="bi bi-unlock"></i> Desbloquear
                                        <?php endif; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>