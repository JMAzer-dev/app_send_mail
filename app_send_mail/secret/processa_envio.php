<?php
    require "lib/PHPMailer/Exception.php";
    require "lib/PHPMailer/OAuth.php";
    require "lib/PHPMailer/PHPMailer.php";
    require "lib/PHPMailer/POP3.php";
    require "lib/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    class Message{
        private $to = null;
        private $subject = null;
        private $message = null;
        public $status = ['codigo_status' => null, 'descricao_status' => null];

        public function __get($attr){
            return $this->$attr;
        }
        public function __set($attr, $value){
            $this->$attr = $value;
        }   
        public function messageValidation(){
            //verificar se o attb em questão está vazio
            if(empty($this->to) || empty($this->subject) || empty($this->message)){
                return false;
            } else {
                return true;
            }
        }
    }  
    $message = new Message();
    $message->__set('to', $_POST['to']);
    $message->__set('subject', $_POST['subject']);
    $message->__set('message', $_POST['message']);

    if(!$message->messageValidation()){
        echo 'Mensagem invalida';
        header('Location: index.php'); 
    } 
    $mail = new PHPMailer(true);
	try {
			//Server settings
			$mail->SMTPDebug = 2;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'seuemail@emai.com';                     //SMTP username
			$mail->Password   = 'secret';                               //SMTP password
			$mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

			//Recipients
			$mail->setFrom('seuemail@email.com', 'Remetente');   // 'De' Remetente
			$mail->addAddress($message->__get('to'), 'Destinatario');     // 'Para' Destinatario
			// $mail->addAddress('ellen@example.com');               //Copia
			// $mail->addReplyTo('info@example.com', 'Information');  // PADRAO PARA REPLICA
			// $mail->addCC('cc@example.com');
			// $mail->addBCC('bcc@example.com');

			//Attachments
			//$mail->addAttachment('/var/tmp/file.tar.gz');         //Anexos
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    

			//Content
			$mail->isHTML(true);                                  //Corpo do email
			$mail->Subject = $message->__get('subject');
			$mail->Body    = $message->__get('message');
			$mail->AltBody = 'CORPO DO EMAIL'; //Alternativa s/ HTML 

			$mail->send();
            $mensagem->status['codigo_status'] = 1;
            $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso.';
	} catch (Exception $e) {
        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'Não foi possível enviar o email - Detalhes do erro: ' . $mail->ErrorInfo;
	}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Status E-mail</title>
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
                    <? if($message->status['codigo_status'] == 1) { ?>

                        <div class="container">
                            <h1 class="display-4 text-success">Sucesso</h1>
                            <p><?= $mensagem->status['descricao_status']; ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>
                    
                    <? } ?>

                    <? if($message->status['codigo_status'] == 2) { ?>
                        
                        <div class="container">
                            <h1 class="display-4 text-danger">Ops!</h1>
                            <p><?= $mensagem->status['descricao_status']; ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>
                    
                    <? } ?>
                </div>
            </div>
        </div>
    </body>
</html>