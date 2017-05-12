<?php if(! defined('BASEPATH')) exit ('No direct script access allowed');
/*
|-----------------------------------------------------------------------
| Paypal_ipn Controller
|-----------------------------------------------------------------------
|
| @author: 	Pablo Martínez
| @link:	 	http://www.greenlabs.com.mx
| @comments:	IPN Paypal purchase confirmation controller
| @date:		Nov 2012
|
*/
class Paypal_ipn extends CI_Controller{

	private $_url;
	private $testFilePath;


	public function __construct(){
		parent::__construct();
		$this->_url = "https://www.paypal.com/cgi-bin/webscr";
		$this->testFilePath = realpath(APPPATH.'../logs');
		$this->load->model('activations_model');
		$this->load->model('reserv_model');
	}

	public function testEmails($id_reserve){
		$this->sendMailToAdmin($id_reserve);
		$this->sendMailToUser($id_reserve);
	}

	public function receiveData($id_reserve){
		$this->verify($id_reserve);
	}

	private function verify($id_reserve){
		$postFields = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value) {
			$postFields .= "&$key=".urlencode($value);
		}
		
		//cURL=================================
		//$ch (cURL Handler)
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $this->_url,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POST => TRUE,
			CURLOPT_POSTFIELDS => $postFields
		));
		$result = curl_exec($ch);
		curl_close($ch);
		//cURL=================================
		
		if ($result == 'VERIFIED') {
			$fh = fopen($this->testFilePath.'/ipn_error_log.txt', 'a');
			$variables = str_replace('&', "\r\n", $postFields);
			$stringToWrite = "New Record--------------------------\r\n-- ".$result." -- \r\n".$variables." \r\n---->>>> Property ID: ".$id_reserve."\r\nEOF Record--------------------------------\r\n";
			fwrite($fh, $stringToWrite);
			//subscr_id        -- checar que no exista en las subscripciones y guardar
			//txn_type         -- checar que sea 'subscr_signup' o 'subscr_payment' si no es una de esas dos, pasar status a 0
			//receiver_email   -- checar que sea el correo del negocio
			//mc_gross		   -- checar que la cantidad sea correcta
			if (!$this->activations_model->verifyReservation($_POST['txn_id'])) {
				if (($_POST['txn_type'] == 'cart') || ($_POST['txn_type'] == 'web_accept')) {
					if ($_POST['receiver_email'] == 'vima_1352924595_biz@gmail.com') {
						if ($this->activations_model->verifyAmount($_POST['mc_gross'], $id_reserve)) {
							$this->activations_model->activateReservation($id_reserve, $_POST['txn_id'], $_POST['payment_date']);
							$this->sendMailToAdmin($id_reserve);
							$this->sendMailToUser($id_reserve);
						}
					}else{
						$fh = fopen($this->testFilePath.'/ipn_error_log.txt', 'a');
						$stringToWrite = "\r\nReceiver Email is Invalid!!\r\n";
						fwrite($fh, $stringToWrite);
					}
				}else{
					$fh = fopen($this->testFilePath.'/ipn_error_log.txt', 'a');
					$stringToWrite = "\r\nTransaction Received is invalid!!\r\n";
					fwrite($fh, $stringToWrite);
				}
			}else{
				//Repeated subscription
			}
		}else{
			$fh = fopen($this->testFilePath.'/ipn_error_log.txt', 'a');
			$variables = str_replace('&', "\r\n", $postFields);
			$stringToWrite = "New Record------Error in result response--------------------------\r\n-- ".$result." -- \r\n".$variables." \r\n---->>>> Property ID: ".$id_reserve."\r\nEOF Record--------------------------------\r\n";
			fwrite($fh, $stringToWrite);
		}
	}

	private function sendMailToAdmin($id_reserve){
		$this->load->library('email');
	
		$reserveData = $this->reserv_model->getReservationById($id_reserve);
		$userData = $this->reserv_model->getUserById($reserveData['id_user']);
		
		$this->email->set_newline("\r\n");
		$this->email->from('pablomtzn@gmail.com', 'Sistema de reservaciones Hotel Casa Margarita');
		$this->email->to('pablomtzn@gmail.com');
		$this->email->bcc('pablomtzn@gmail.com');
		$this->email->subject('Nueva reservación desde página web.');
		$this->email->message("
			Se ha efectuado una reservación en el sistema.\r\n
			Id de la reservacion: ".$reserveData['id_reserve'].".\r\n
			Huesped: ".$userData['name'].".\r\n
			Check-In: ".date("d / F / Y", strtotime($reserveData['checkin'])).".\r\n
			Check-Out: ".date("d / F / Y", strtotime($reserveData['checkout'])).".\r\n
			Pago efectuado en Paypal: ".$reserveData['payment_date'].".\r\n
			Código de transacción de Paypal: ".$reserveData['txn_id'].".\r\n
			Cantidad: $ ".$reserveData['total_amount']." USD.\r\n
			País: ".$userData['country'].".\r\n
		");
		if($this->email->send()){
			$this->email->clear();
			return true;
		}else{
			return false;
		}//fin ifelse
	}//fin function sendMailToAdmin()

	private function sendMailToUser($id_reserve){
		$this->load->library('email');
	
		$reserveData = $this->reserv_model->getReservationById($id_reserve);
		$userData = $this->reserv_model->getUserById($reserveData['id_user']);
		
		$this->email->set_newline("\r\n");
		$this->email->from('pablomtzn@gmail.com', 'Sistema de reservaciones Hotel Casa Margarita');
		$this->email->to($userData['mail']);
		$this->email->bcc('pablomtzn@gmail.com');
		$this->email->subject('Hotel Casa Margarita Reservations.');
		$this->email->message("
			Your booking at Hotel Casa Margarita was successful. / Su reservación en Hotel Casa Margarita fue exitosa.\r\n
			Reservation Id / Id de la reservacion: ".$reserveData['id_reserve'].".\r\n
			Guest / Huesped: ".$userData['name'].".\r\n
			Check-In: ".date("d / F / Y", strtotime($reserveData['checkin'])).".\r\n
			Check-Out: ".date("d / F / Y", strtotime($reserveData['checkout'])).".\r\n
			Payment done in Paypal / Pago efectuado en Paypal: ".$reserveData['payment_date'].".\r\n
			Country / País: ".$userData['country'].".\r\n
		");
		if($this->email->send()){
			$this->email->clear();
			return true;
		}else{
			return false;
		}//fin ifelse
	}//fin function sendMailToAdmin()
}//end of controller
// @eof Paypal_ipn Controller ========================================================