<?= $this->extend('templates/auth') ?>

<?= $this->section('title') ?>Cadastrar<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<form action="<?= site_url('salvar_usuario'); ?>" method="POST">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Informe seu email" value="<?= old('email') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" placeholder="Informe sua senha">
    </div>

    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
</form>

<p class="mt-3 text-center">
    Já tem conta? <a href="<?= site_url('login') ?>">Faça login</a>
</p>
<?= $this->endSection() ?>