<?php

namespace Modules\Auth\Library; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailVerification {
	/**
	 * user params
	 * @var [type]
	 */
	private $params;

	private $mailer;

	public function __construct($userParams) {
		$this->params = $userParams;
		$this->mailer = new PHPMailer(true);
	}

	public function sendEmail() {
		// return [
		// 	'params' => $this->params,
		// 	'mailer' => $this->mailer
		// ]; die();

		$response = [];
		try {
			$this->mailer->isSMTP();
			$this->mailer->Host = VERIFICATION_EMAIL_HOST; // smtp host
			$this->mailer->SMTPSecure = VERIFICATION_EMAIL_SMTP_SECURE;
			$this->mailer->SMTPAuth   = VERIFICATION_EMAIL_SMTP_AUTH; 
			$this->mailer->Port = VERIFICATION_EMAIL_SMTP_PORT; // port number
			$this->mailer->Username = VERIFICATION_EMAIL_USERNAME; // email from 
			$this->mailer->Password = VERIFICATION_EMAIL_PASSWORD; // password

			// my email
			$this->mailer->setFrom(VERIFICATION_EMAIL_FROM, VERIFICATION_EMAIL_FROM_NAME);
			
			// subject
			$this->mailer->Subject = "VERIFICATION EMAIL - BACKEND80";

			// send to
			$this->mailer->addAddress($this->params['email']); // recipient

			// create message
			$msg = "Halo {$this->params['full_nm']},<br><br/>Kode verifikasi anda adalah {$this->params['verif_code']}";
			$this->mailer->msgHTML($msg);

			if(!$this->mailer->send()) {
				// email gagal terkirim
				throw new Exception("Verification email failed to send. Please register again", 1);
			} else {
				// email berhasil dikirim
				$response = [
					'status' => 'ok',
					'message' => "Email verifikasi berhasil dikirim ke {$this->params['email']}. Masukkan kode verifikasi anda disini"
				];				
			}

			$this->mailer->clearAddresses(); // clear recipient addresses			
		} catch(Exception $e) {
			$response = [
				'status' => 'error',
				'message' => $e->getMessage()				
			];
		}

		return $response;

	}

	private function _debug($data) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}