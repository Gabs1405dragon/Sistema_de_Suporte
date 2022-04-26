<div class="container">
<?php 
$token = $_GET['token'];


?>
<p style="margin-bottom:20px; " >A sua pergunta é: <?php echo $var['pergunta'] ?></p>

<?php  
$puxandoInteracao = \MySql::connect()->prepare("SELECT * FROM interacao_chamado WHERE chamada_id = ?");
$puxandoInteracao->execute(array($token));
$puxandoInteracao = $puxandoInteracao->fetchAll();
foreach($puxandoInteracao as $key => $value){
    if($value['addmin'] == '1'){
        echo '<div class="circler blue" ></div><p class="admin" >Admin: '.$value['mensagem'].'</p>';
        echo '<div class="clear" ></div>';
    }else{
        echo '<span class="circler orange" ></span><p class="admin" >Eu: '.$value['mensagem'].'</p>';
        echo '<div class="clear" ></div>';
    }
}
?>

<?php
if(isset($_POST['responder'])){
    $mensagem = $_POST['mensagem'];
    if(empty($mensagem)){
        echo 'preenchar o campo da mensagem';
    }else{
        $responder = \MySql::connect()->prepare("INSERT INTO interacao_chamado (id,chamada_id,mensagem,addmin,statos) VALUES (null,?,?,?,0)");
        $responder->execute(array($token,$mensagem,-1));
        echo '<script>alert("sua resposta foi enviada com sucesso!! aguarde o admin responde-lo(a)")</script>';
        echo '<script>location.href="chamado?token='.$token.'"</script>';
    }
}
?>

<?php 
$sql = \MySql::connect()->prepare("SELECT * FROM interacao_chamado WHERE chamada_id = ? ORDER BY id DESC");
$sql->execute(array($token));
if($sql->rowCount() == 0){
    echo 'aguarde até a resposta do admin para continua o suporte.';
}else{
    $fetch = $sql->fetchAll();
    if($fetch[0]['addmin'] == -1){
        echo 'aguarde até a resposta do admin para continua o suporte.';
    }else{
        echo '
            <form method="post">
                <textarea value="'.\models\FuncModel::pegarPost('mensagem').'" name="mensagem"  ></textarea>
                <input type="submit" name="responder" value="mandar" >
            </form>
        ';
    }
}

?>
</div>