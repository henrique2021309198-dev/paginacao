<?= $this->extend('templates/auth') ?>

<?= $this->section('title') ?>Entrar<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<form action="<?= site_url('login'); ?>" method="POST">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Informe seu email" value="<?= old('email') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" placeholder="Informe sua senha">
    </div>

    <button type="submit" class="btn btn-primary w-100">Entrar</button>
</form>

<p class="mt-3 text-center">
    <a href="<?= site_url('recuperar-senha') ?>">Esqueci minha senha</a>
</p>

<p class="mt-3 text-center">
    Não tem conta? <a href="<?= site_url('cadastrar') ?>">Cadastre-se</a>
</p>
<?= $this->endSection() ?>