<?php 
if(isset($_POST['responder_chamada'])){
    $mensagem = $_POST['mensagem'];
    $email = $_POST['email'];
    $token = $_POST['token'];
    if(empty($mensagem)){
        echo '<script>alert("preenchar o campo da mensagem")<script>';
    }else{
        $sql = \MySql::connect()->prepare("INSERT INTO interacao_chamado VALUES (null,?,?,?,1)");
        $sql->execute(array($token,$mensagem,1));
        echo '<script>alert("sucesso!")<script>';
      
    }
}else if(isset($_POST['interacao_novo_chamado'])){
    $mensagem = $_POST['mensagem'];
    $token = $_POST['token'];
    if(empty($mensagem)){
        echo '<script>alert("preenchar o campo da mensagem")<script>';
    }else{
        $sql1 = \MySql::connect()->exec("UPDATE interacao_chamado SET statos = 1 WHERE id = $_POST[id] ");
    
        $sql2 = \MySql::connect()->prepare("INSERT INTO interacao_chamado VALUES (null,?,?,1,1)");
        $sql2->execute(array($token,$mensagem));
        echo '<script>alert("sucesso!")<script>';
      
    }
}
?>
<h2>Novos chamados</h2>

<?php
$pegarChamados = \MySql::connect()->prepare("SELECT * FROM chamados ORDER BY id DESC");
$pegarChamados->execute();
$pegarChamados = $pegarChamados->fetchAll();
foreach($pegarChamados as $value){
$pegarInteracao = \MySql::connect()->prepare("SELECT * FROM interacao_chamado WHERE chamada_id = ?");
$pegarInteracao->execute(array($value['token']));
if($pegarInteracao->rowCount() >= 1){
    continue;
}

?>
<h2><?php echo $value['pergunta'];?></h2>
<form method="post">
    <textarea name="mensagem" placeholder="Sua resposta"></textarea>
    <input type="submit" name="responder_chamada" value="resposta">
    <input type="hidden" name="email" value="<?php echo $value['email']?>">
    <input type="hidden" name="token" value="<?php echo $value['token'] ?>">
</form>

<?php } ?>
<hr/>
<h2>Útimas interações</h2>

<?php 
$interacaoChamada = \MySql::connect()->prepare("SELECT * FROM interacao_chamado WHERE addmin = -1 AND statos = 0 ORDER BY id DESC");
$interacaoChamada->execute();
$interacaoChamada = $interacaoChamada->fetchAll();
foreach($interacaoChamada as $value2){
?>
<h3><?php echo $value2['mensagem']?></h3>
<a href="chamado?token=<?php echo $value2['chamada_id']?>">Clique aqui para seber quem é o cliente!</a>

<form method="post">
    <div class="form__group">
        <textarea name="mensagem" placeholder="Sua resposta..." ></textarea>
    </div>
    <div class="form__group">
      <input type="submit" value="responder" name="interacao_novo_chamado"  >
    <input type="hidden" name="id" value="<?php echo $value2['id']; ?>"  >
    <input type="hidden" name="token" value="<?php echo $value2['chamada_id']; ?>" >  
    </div>
    
</form>

<?php } ?>
