<?=$this->extend('/templates/admin');?>

<?$this->section('conteudo');?>

<h1>Gestão de Estoques</h1>

<?php if(empty($produtos)): ?>
    <p>Nenhum produto encontrado.</p>
    <a href="<?=site_url('produtos/novo'); ?>">Cadastrar novo produto agora</a>
<?php else:?>
    <?php foreach($produtos as $produto):?>
        <ul>
            <li>
                <?=$produto['nome'];?> - Estoque <?=$produto['estoque'];?>
                <!-- localhost/pasta/estoque/adicionar/1-->
                <a href="<?=site_url('estoque/adicionar/'.$produto['id']);?>">Adicionar estoque</a>
                <a href="<?=site_url('estoque/remover/'.$produto['id']);?>">Remover estoque</a>
                <a href="<?=site_url('estoque/historico/'.$produto['id']);?>">Histórico</a>
            </li>
        </ul>
                
    <? endforeach;?>

<?php endif;?>

<?$this->endSection();?>