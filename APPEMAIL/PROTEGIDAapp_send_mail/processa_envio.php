<?php

	//print_r($_POST);

	//Importacao do PHPMailer
	require "./bibliotecas/PHPMailer/Exception.php";
	require "./bibliotecas/PHPMailer/OAuth.php";
	require "./bibliotecas/PHPMailer/PHPMailer.php";
	require "./bibliotecas/PHPMailer/POP3.php";
	require "./bibliotecas/PHPMailer/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	class Mensagem{

		//atributos
		private $para = null;
		private $assunto = null;
		private $mensagem = null;
		public $status = array("codigo_status" => null, "descricao_status"=> "");

		//get e set
		public function __get($atributo){
			return $this->$atributo;
		}

		public function __set($atributo, $valor){
			return $this->$atributo = $valor;
		}

		//funcao que verifica a validade da mensagem
		public function mensagemValida(){
			//verifica se os campos estão nulos
			if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
				return false;
			}
			return true;
		}
	}

	$mensagem = new Mensagem();
	//recuperando valores
	$mensagem->__set("para", $_POST["para"]);
	$mensagem->__set("assunto", $_POST["assunto"]);
	$mensagem->__set("mensagem", $_POST["mensagem"]);

	//se mensagem for invalida "morre"
	if(!$mensagem->mensagemValida()){
		echo "A mensagem é invalida";
		//die(); Ao inves de morrer a aplicacao aprimoramos voltando para o index
		header("Location.index.php");
	}

	//phpmailer instancia
	//configurada padrao gmail
	$mail = new PHPMailer(true);
	try {
		//Server settings
		$mail->SMTPDebug = false;//2 antes era 2 comentamos false para na hora do envio na aparecer na tela o debug;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		
		
		//mudar aqui////////////////////////////////////////////
		$mail->Username   = 'seuemail';                     //SMTP username
		//As vezes tem que no cliente de email configurar algum token para apps, o gmail precisa!
		//$mail->Password   = 'suasenha';                               //SMTP password
		$mail->Password   = 'senhadotoken';
		///////////////////////////////////////////////////////
		
		
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		//Recipients
		$mail->setFrom('projetosgeorgetesteweb@gmail.com', 'George Web Teste Remetente');
		//$mail->addAddress('projetosgeorgetesteweb@gmail.com', 'George Web Teste Destinatário');     //Add a recipient
		$mail->addAddress($mensagem->__get('para'));     //Add a recipient
		/*$mail->addAddress('ellen@example.com');               //Name is optional
		$mail->addReplyTo('info@example.com', 'Information');
		$mail->addCC('cc@example.com');
		$mail->addBCC('bcc@example.com');*/

		//Attachments
		/*$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
		*/
		
		//Content
		//Setamento dos valores
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = $mensagem->__get('assunto');
		$mail->Body    = $mensagem->__get('mensagem');
		$mail->AltBody = 'É necessario utilizar um client que suporte HTML para ter acesso total ao conteúdo dessa mensagem';//conteudo alternativo sem marcacao HTML

		//Envia o email
		$mail->send();
		//se envia o status muda
		//sucesso é igual a 1
		$mensagem->status["codigo_status"] = 1;
		$mensagem->status["descricao_status"] = "Email enviado com sucesso";
		//echo 'Email foi enviado com sucesso!!';
		} catch (Exception $e) {
			/*echo "Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde.";
			echo 'Detalhes do erro: ' . $mail->ErrorInfo;*/
			//Aqui aprimoramos, ao inves do echo temos a variavel array status que verifca o status 
			//2 retorna erro
			$mensagem->status["codigo_status"] = 2;
			$mensagem->status["descricao_status"] = "Não foi possivel enviar o email!! Detalhes do erro: ". $mail->ErroorInfo;
		}

	
		
?>

	<!--Corpo da página-->

	<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Send Mail</title>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>

	<body>

		<div class="container">  

			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

      		<div class="row">
      			<div class="col-md-12">

					<!--Verifica se o codigo for 1, sucesso
						Se tiver sucesso exibe a mensagem a e descricao
					-->
					<?php if($mensagem->status["codigo_status"] == 1){ ?>

						<div class="container">
							<h1 class="display-4 text-success">Sucesso</h1>
							<p><?= $mensagem->status["descricao_status"] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

						<?php }	?>

						<!--Verifica se o codigo for 2, insucesso
							Se tiver insucesso exibe a mensagem a e descricao
						-->
						<?php if($mensagem->status["codigo_status"] == 2){ ?>

							<div class="container">
								<h1 class="display-4 text-danger">Ops!</h1>
								<p><?= $mensagem->status["descricao_status"] ?></p>
								<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
							</div>
					<?php } ?>
						
				</div>
      		</div>
      	</div>

	</body>
</html>