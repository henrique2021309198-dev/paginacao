<?= $this->extend('templates/painel') ?>
<?= $this->section('title') ?><?= $isEdit ? 'Editar usuario' : 'Novo usuario' ?><?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= site_url('usuarios') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0 fw-bold"><?= $isEdit ? 'Editar usuario' : 'Cadastrar usuario' ?></h4>
</div>

<div class="card shadow-sm border-0" style="max-width:560px;">
    <div class="card-body p-4">
        <form method="post" action="<?= site_url('usuarios/salvar') ?>">
            <input type="hidden" name="id" value="<?= esc($usuario['id'] ?? '') ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Nome <span class="text-danger">*</span></label>
                <input type="text" name="nome" class="form-control"
                       value="<?= esc($usuario['nome'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">E-mail <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="<?= esc($usuario['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Telefone</label>
                <input type="text" name="telefone" class="form-control"
                       value="<?= esc($usuario['telefone'] ?? '') ?>"
                       placeholder="(00) 00000-0000">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Senha
                    <?= !$isEdit ? '<span class="text-danger">*</span>' : '' ?>
                </label>
                <input type="password" name="senha" class="form-control"
                       <?= !$isEdit ? 'required' : 'placeholder="Deixe em branco para manter a atual"' ?>>
                <?php if ($isEdit): ?>
                    <div class="form-text">Deixe em branco para nao alterar a senha atual.</div>
                <?php else: ?>
                    <div class="form-text">Minimo de 6 caracteres.</div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Funcao</label>
                <select name="role" class="form-select">
                    <option value="usuario"
                        <?= (($usuario['role'] ?? 'usuario') === 'usuario') ? 'selected' : '' ?>>
                        Usuario
                    </option>
                    <option value="super_admin"
                        <?= (($usuario['role'] ?? '') === 'super_admin') ? 'selected' : '' ?>>
                        Super Admin
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="status" value="1"
                           class="form-check-input" id="statusCheck"
                           <?= (($usuario['status'] ?? 1) == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="statusCheck">Usuario ativo</label>
                </div>
            </div>

            <button type="submit" class="btn btn-dark w-100 fw-semibold">
                <i class="bi bi-check-lg me-1"></i> Salvar
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>