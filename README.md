# Documentação Sistema_de_Suporte
um sistema de suporte simples Utilizando a tecnologia PHP e o banco de dados MySql!!
<h3>O propósito dessa documentação é explicar como funciona o sistema passo a passo! </h3>
<h4>Vamos começar com o banco de dados!</h4>
<p>Esse banco de dados contem relacionamento de tabelas!</p>
<ul>
  <li>Primeiro criar um banco de dados e dá um nome a ela!</li>
  <li>Segundo criar uma tabela com o nome <b>"chamados"</b>, os campos da tabela vão ser o <b>(id)</b> para ficar incrementando na hora de fazer o <b><a href="https://www.w3schools.com/sql/sql_insert.asp">INSERT</a></b>. A <b>(pergunda)</b> que vai ser a pergunda da duvida do cliente
  que está ultilizando o suporte.o (email) do usuário .por último um (token) para ser gerado um token aleatório do usuário que está fazendo a pergunta!</li>
  <li>Terceiro criar a ultima tabela para o banco! ela irá se chamar de <b>"interacao_chamado"</b>, essa tabela vai ser para a interação que vai responder o usuário que está fazendo a pergunda no suporte!!
  os campos que vão ser necessarios para ser criados são: também vai conter o <b>(id)</b> ,campo da (chamada_id) que vai ser o campo para recuperar o token do usuário que fez
  a pergunda do suporte.A <b>(mensagem)</b> que vai ser a resposta do administrador para o usuário.o (admin) que vai ser quem tá respondendo se é o usuário ou administrador.
  e o (status) para ver se foi respondido o suporte ou administrador.</li>
</ul>
<h4>Vamos começar no front-end do suporte!</h4>
<p>No front do suporte vai ser bem simples,irá conter um formulário com o campo para o usuário enviar o E-mail,O campo para o usuário fazer mandar uma duvidar,e o campo
para enviar os dados para o administrador!</p>

![form](https://user-images.githubusercontent.com/89558456/167275733-9400068f-69df-4562-bd33-020a39866198.png)

<p>Lembrando que o foco da documentação não é o <a href="https://www.w3schools.com/css/default.asp">CSS</a>, mais sim a lógica do backend.</p>
<h4>Agora o próximo passo é recupera os dados do formulário via <b><a href="https://www.php.net/manual/pt_BR/reserved.variables.post.php">$_POST[]</a></b> para enviar para o banco de dados e mandar um email para o usuário que vez a pergunda para ele entrar na tela do chamado 
  pelo o token dele que foi cadastrado!</h4>
  
<ol>
  <li>Verificar se existe o name do submit do formulário usando a função nativa do php que é <a href="https://www.php.net/manual/en/function.isset.php">isset()</a> e o valor do token vai ser a função <a href="https://www.php.net/manual/en/function.uniqid.php">uniqid()</a> que é gerar uma chave aleatória .</li>
  <li>Recuperar os dados do formulário pelo o attr <b>"NAME"</b> usando o $_POST[] e colocar dentro de uma $variavel!!</li>
  <li>Verificar se todos os campos estão vázios usando a função <a href="https://www.php.net/manual/en/function.empty.php">empty()</a> usar as variveis atribuidas no parâmetro individualmente.</li>
  <li>Verificar se é um e-mail mesmo a $variavel email usando a função <a href="https://www.php.net/manual/en/function.filter-var.php" >filter_var()</a> e passar como parâmetro a $variavel do "email" e "<b>FILTER_VALIDATE_EMAIL</b>".</li>
  <li>Agora inserir todos os dados na tabela chamados! usando o <a href="https://www.w3schools.com/sql/sql_insert.asp">INSERT</a>.</li>
  <li>E mandar um email para o usuário ,e nesse email vai conter um link para direcionar ele para a tela de chamados!</li>
</ol>

<p>Para fazer o envio de email vai ser necessario instalar uma  dependência no projeto via <a href="https://getcomposer.org/">composer</a>, o nome da  dependência é <a href="https://github.com/PHPMailer/PHPMailer">PHPMailer</a>!</p>

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\PHPException;

    if(isset($_POST['enviar_chamada'])){
	   $email = $_POST['email'];
	   $pergunta = $_POST['pergunta'];
	    $token = md5(uniqid());

	  if(empty($email) || empty($pergunta)){
		  echo 'Preenchar os campos de email e pergunta....';
    }else{
		if(filter_var($email,FILTER_VALIDATE_EMAIL)){
			$inserir = \MySql::connect()->prepare("INSERT INTO chamados (id,pergunta,email,token) VALUES (null,?,?,?)");
			$inserir->execute(array($pergunta,$email,$token));
			$mail = new PHPMailer(true);
			try{
                    
				$mail->SMTPDebug = 0;
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'email';
				$mail->Password = 'senha';
				$mail->Port = 587;
			
				$mail->isHTML(true);
				$mail->CharSet = 'UTF-8';
				$mail->setFrom('email','Gabs');
				$mail->addAddress($email, '');
			
				//$email->isHTML(true);
				$mail->Subject = 'Seu chamado foi aberto';
				$url = BASE3.'chamado?token='.$token;
				$informacoes =  
				'
				Olá o seu chamado foi criado com sucesso!!<br/>utilize o link abaixo para interagir <br/><a href="'.$url.'" >Acessar chamado</a>
				'
				;
				$mail->Body =  $informacoes;
				$mail->AltBody = $informacoes;
			
				if($mail->send()){
					echo 'email enviado com sucesso!';
				}else{
					echo 'email não enviado';
				}
				
			}catch(Exception $e){
				echo "Erro ao enviar o email: {$mail->ErrorInfo}";
			}
			echo '<script>alert("cadastrado com sucesso!");location.href="home"</script>';
		}else{
			echo 'E-mail inválido...';
		}
	}
    }

<h4>Para deixar a tela de chamados segura Vai ser necessario verificar se o token da query da url via <a href="https://www.php.net/manual/en/reserved.variables.globals.php">$_GET[]</a> se já existe cadastrado na tabela do banco.</h4>
<p>se existir na tabela vai ser rederizada a tela de chamado ,caso não exista vai aparece um erro na tela e redirecionar para a tela de suporte novamente.</p>
<p>Agora tem que  puxar todas as informações da tabela "interacao_chamado" onde o campo "chamada_id" vai ser o $_GET['token'] da pagina.</p>
<p>E agora usar a função <a href="https://www.php.net/manual/en/control-structures.foreach.php" >foreach()</a> para fazer um loop de cada inserção de dados que é inserido na tabela com base no campo "chamada_id". Vamos passar como 
parâmetro a $variavel que foi criada que está armazenada todas as informações da tabela e a $variavel $value.</p>
<p>Depois fazer uma verificação com o <a href="https://www.php.net/manual/en/control-structures.if.php">if()</a> que a condição vai ser o $value['admin'] com o campo admin é ingual a 1. se for é o administrador que irá responder o chamado
caso ao contrário é o usuário que vai responder!!</p>

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
      
<h4>Agora verificar se o administrador já respondeu a pergundar</h4>
<ol>
  <li>Fazer uma query da tabela "interacao_chamada" com base no campo "chamada_id" passando como valor o $_GET['token'] e colocar em uma nova $variavel!</li>
  <li>Verificar com a função if() se o a coluna da tabela é 0,se for zero isso significar que o administrador ainda não respondeu o usuário e então vai aparece uma mensagem para aguardar.
  caso ao contrário vai ser necessario criar mais uma $variavel para puxar todos os dados da tabela e fazer mais uma verificação com o if() que vai ser essa condição -> <b>($fetch[0]['addmin'] == -1)</b>.
  e então vai aparece uma mensagem para aguardar o administrador responder,caso ao contrário vai gerar um formúlario com o campo mandar outrar mensagem par conversar com o administrador ,e o botão para mandar a mensagem!</li>
 
</ol>

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
      
 <h4>Agora verificar se o existe o attr "NAME" com a função isset() e inserir na tabela "interacao_chamada" só que no caso do valor do campo "admin" vai ter que ser insirido -1 ,Por que é o usuário que está respondendo!!</h4>
 
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
      
   <h3>O front da tela de chamado é essa imagem abaixo!!</h3>   
      
![chamado](https://user-images.githubusercontent.com/89558456/167277849-37621096-da94-46a0-b2bf-26c996e9bfa2.png)

<h3>Agora fazer a última tela que é a tela do Administrador<></h3>
<p>Vamos começar puxando todos os dados da tabela "chamados" e colocar tudo em uma $variavel e em seguida fazer um <b>foreach()</b>, e dentro desse foreach()
  fazer mais uma query que vai ser um seguinte pegar a tabela "interacao_chamada" com base no campo "chamada_id" e passar como o valor o $value['token'], para pegar o usuário especifico.</p>
  <p>Depois usar a função if() e passar como condição a $variavel que acabou de ser atribuida : <b>($variavel->rowCount() >= 1)</b> caso bata essa condição vai dar um continue. 
  </p>
  <p>Em seguida fazer um formulário dentro do foreach() com o campo para enviar a mensagem o botão para enviar a mensagem para o usuário e dois campos ocultos que vai ter 
  como valor o $value['email'] para recupera o email do usuário e o campo do $value['token].</p>
  
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
  
  <h4>Depois só recuperar os dados via <b>$_POST[]</b> e inserir na tabela "interacao_chamado" só que desta vez o valor do campo "admin" vai ser o 1 porque é o administrador que está respondendo.</h4>
  
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
        }
  
  <h4>Parte final!! agora é para verificar se o usuário mandou uma nova mensagem para o administrador!!<h4>
    <ol>
      <li>Fazer uma query para puxando todos os dados da tabela "interacao_chamado" onde o campo "admin" é ingual a -1 e o campo "statos" é ingual a 0 ,encapsular toda essa query em uma $variavel e usar um <b>foreach()</b> !</li>
      <li>E criar um formulário com os seguintes campos: campo (mensagem) dois campos ocultos com o $value['token'] e $value['id'] e o botão para mandar os valores!!</li>
    </ol>
    
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
    
    <h3>Agora para terminar, recuperar todos os dados do formulário!</h3>
    <p>Antes de fazer a inserção dos dados fazer uma atualização da tabela com o <a href="">UPDATE</a> onde vai ser alterado o statos para 1 e onde o id do campo é o $_POST['id'].</p>
    <p>Agora fazer pode inserir os dados do formulário mais os valores do campo "statos" e "admin" ambos seram 1.</p>
    
            if(isset($_POST['interacao_novo_chamado'])){
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
    
<h3>O Front-end da tela do administrador é a imagem abaixo!!</h3>
    
![administrador](https://user-images.githubusercontent.com/89558456/167278825-d07d355b-2d81-49c9-a79b-697e368155a3.png)
    
<h2>Muito obrigado por ter lido a documentação até aqui, eu espero que a minha didática tenha ajudado a você entender melhor o meu Código :)</h2>
  <h3>Minhas redes sociais.</h3>
  <ul>
      <li><a href="https://www.instagram.com/gabs1405henrique/">Instagram!</a></li>
      <li><a href="https://github.com/Gabs1405dragon/">GitHub!</a></li>
      <li><a href="https://www.linkedin.com/in/gabriel-h-assis-de-souza-60b496207/">Linkedin!</a></li>
  </ul>
    
    
  

