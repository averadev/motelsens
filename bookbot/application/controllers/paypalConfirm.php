<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| 	PaypalConfirm Controller
| -------------------------------------------------------------------------
|
|	@author: 	Pablo Martínez
| 	@link: 		http://www.greenlabs.com.mx
|	@comments:	Controlador para recibir confirmación de paypal
|	@date:		Ago 2010
|
*/
class PaypalConfirm extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('activations_model');
		$this->load->model('reserv_model');
		$this->load->model('backoffice_model');
		$this->load->model('users_model');
	}

	private function sendConfirmationMail($name, $email, $tel){
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@motelsensaciones.com', 'Sistema de reservaciones Sensaciones Motel Boutique');
		$this->email->to($email);
		$this->email->subject('Confirmación de reservación en Sensaciones Motel Boutique');
		$this->email->message("
			Su reservación en Sensaciones Motel Boutique fue efectuada con éxito, porfavor imprima este correo y presentelo en recepción para agilizar su registro y la entrega de su suite.\r\n
			Esperamos verlo pronto y estamos seguros que su estancia con nosotros será de su completo agrado.\r\n
			Saludos!\r\n
		");
		if($this->email->send()){
			$this->email->clear();
			$this->email->set_newline("\r\n");
			$this->email->from('noreply@motelsensaciones.com', 'Sistema de reservaciones Sensaciones Motel Boutique');
			$this->email->to('recepcion@motelsensaciones.com');
			$this->email->bcc('eduardo@motelsensaciones.com, pablomtzn@gmail.com');
			$this->email->subject('Confirmación de reservación en Sensaciones Motel Boutique');
			$this->email->message("
				El usuario $name reservó con éxito desde www.motelsensaciones.com \r\n
				Puede contactarlo en: $email \r\n
				Su teléfono es: $tel \r\n
				Para mas detalles de la reservación porfavor ingresar al Panel de Control. \r\n
			");
			$this->email->send();
			return TRUE;
		}else{
			return FALSE;
		}//fin ifelse
	}//fin function sendConfirmationMail()

	public function confirmation($reserveId){
		if ($confirma = $this->activations_model->activateReservation($reserveId, 'XXXXX-1', date('Y-m-d'))) {
			$reservData = $this->reserv_model->getReservationById($reserveId);
			$data['reserva'] = $reservData;
			$contact = $this->users_model->getUserById($reservData['id_user']);
			if ($this->sendConfirmationMail($contact['name_user'].' '.$contact['lastname_user'], $contact['email_user'], $contact['tel_user'])) {

			}else{
				
			}
			$data['user'] = $contact;
			$data['scripts'] = $this->load->view('site/dynamics/scripts', $data, TRUE);
			$data['styles'] = $this->load->view('site/dynamics/styles', $data, TRUE);
			$this->load->view('site/receive_confirm', $data);
		}else{
			echo "No se pudo realizar la confirmación, porfavor cont&aacute;ctenos al (998)884-61-65, para confirmar su reservación.<br />
			Gracias!";
		}
	}
	

}//End Of Controller


//	@eof PaypalConfirm Controller File ================================================