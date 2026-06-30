<?= $this->extend('templates/painel') ?>
<?= $this->section('title') ?>Meu perfil<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<h4 class="mb-1 fw-bold">Meu perfil</h4>
<p class="text-muted small mb-4">Atualize seus dados pessoais e senha de acesso.</p>

<div class="card shadow-sm border-0" style="max-width:520px;">
    <div class="card-body p-4">
        <form method="post" action="<?= site_url('usuarios/perfil') ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Nome</label>
                <input type="text" name="nome" class="form-control"
                       value="<?= esc($usuario['nome'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">E-mail</label>
                <input type="email" name="email" class="form-control"
                       value="<?= esc($usuario['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Telefone</label>
                <input type="text" name="telefone" class="form-control"
                       value="<?= esc($usuario['telefone'] ?? '') ?>"
                       placeholder="(00) 00000-0000">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Nova senha</label>
                <input type="password" name="senha" class="form-control"
                       placeholder="Deixe em branco para nao alterar">
                <div class="form-text">Minimo de 6 caracteres.</div>
            </div>

            <button type="submit" class="btn btn-dark w-100 fw-semibold">
                <i class="bi bi-check-lg me-1"></i> Salvar alteracoes
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>