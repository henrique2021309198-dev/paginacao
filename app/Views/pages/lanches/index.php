<?= $this->extend('templates/admin') ?>

<?= $this->section('conteudo') ?>

<h1>Lanches</h1>

<div class="card shadow-sm">
    <div class="card-body">

    <a href='<?= site_url('admin/lanches/novo') ?>'>Cadastrar novo lanche</a>

        <?php if (!empty($lanches)) : ?>
            
        <form method='get' action='<?=site_url('admin/lanches')?>'>
    <div class="row">
        <!-- Campo de Busca -->
        <div class="col-md-3">
            <input name="busca" value="<?=esc($busca ?? '')?>" placeholder="Filtrar por nome" class="form-control"/>
        </div>

        <!-- Campo de Preço -->
        <div class="col-md-3">
            <select name="preco" class="form-select">
                <option value="">Todos</option>
                <option value="baixo">Abaixo de R$ 5</option>
                <option value="medio">Entre R$ 5 e R$ 10</option>
                <option value="alto">Acima de R$ 10</option>
            </select>
        </div>

        <!-- Campo de Ordenação -->
        <div class="col-md-2">
            <select name="ordenacao" class="form-select">
                <option value="asc">Preço Crescente</option>
                <option value="desc">Preço Decrescente</option>
            </select>
        </div>

        <!-- Botão de Filtrar e Limpar -->
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                Filtrar
            </button>
        </div>

        <!-- Link de Limpar -->
        <div class="col-md-2">
            <?php if ($busca || $preco): ?>
                <a href="<?= site_url('admin/lanches'); ?>" class="btn btn-secondary w-100">
                    Limpar
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>


            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Foto</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lanches as $lanche) : ?>
                            <tr>
                                <td><?= esc($lanche['id']) ?></td>
                                <td><?= esc($lanche['nome']) ?></td>
                                <td>R$ <?= number_format($lanche['preco'], 2, ',', '.') ?></td>
                                <td>
                                    <?php if (!empty($lanche['foto'])):?>
                                        <a><img src='<?= site_url('uploads/lanches/'.$lanche['foto'])?>' style="width: 100px; height: auto;"/>
                                    <?php else:?>
                                        <span> SEM FOTO </span>
                                    <?php endif;?>
                                </td>>
                                <td>
                                    <a href='<?= site_url('admin/lanches/editar/' . $lanche['id']) ?>'>Editar</a>
                                    <a href='<?= site_url('admin/lanches/excluir/' . $lanche['id']) ?>'
                                        onclick='return confirm("Excluir?")'>Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINAÇÃO --> 
             <!--<p>
                Página 
                <?=$pager->getCurrentPage();?> 
                de 
                <?=$pager->getPageCount();?>
                 - apresentando 
                <?=count($lanches);?> 
                 do total de 
                <?=$pager->getTotal();?> 
                 lanches
             </p> -->

             <?=$pager->links('default', 'template_pager');?>

            

        

        <?php else : ?>

            <div class="alert alert-warning">
                <p>Nenhum lanche cadastrado.</p>
            </div>

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>