<?php  
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
				$mail->Username = 'souzagabriel1405.henrique@gmail.com';
				$mail->Password = 'SONW.8634';
				$mail->Port = 587;
			
				$mail->isHTML(true);
				$mail->CharSet = 'UTF-8';
				$mail->setFrom('souzagabriel1405.henrique@gmail.com','Gabs');
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
?>
<section class="surporte" >
	<div style="padding: 0 2%;" class="center">
		<h2>Abrir chamado</h2>
		<form method="post">
			<input style="width:100%;padding:10px;margin-bottom:20px" type="email" name="email" placeholder="Seu E-mail...">
			<textarea style="width:100%;padding:10px;height:120px;margin-bottom:20px" placeholder="Fazer uma pergunta!" name="pergunta" ></textarea>
			<input type="hidden" name="token" >
			<input style="width:100%;padding:10px;" type="submit" name="enviar_chamada" value="Enviar Chamada!">
		</form>
	</div>
</section>