<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| 	Contact Controller
| -------------------------------------------------------------------------
|
|	@author: 	Pablo Martínez
| 	@link: 		http://www.greenlabs.com.mx
|	@comments:	Contact controller for Jolie jungle
|	@date:		Feb 2012
|
*/
class Contact extends CI_Controller{
	
	public function sendContact(){
		$name = 	$this->input->post('name');
		$email = 	$this->input->post('email');
		$subject = 		$this->input->post('subject');
		$message = $this->input->post('message');
		
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from($email, $name);
		$this->email->to('pablomtzn@gmail.com, info@hotelcasamargarita.com');
		$this->email->subject('Contacto de página web www.hotelcasamargarita.com');
		$this->email->message("
			Nombre: $name \r\n
			Email: $email\r\n
			Asunto: $subject\r\n
			Mensaje: $message\r\n\r\n
		");
		if($this->email->send()){
			echo "Thanks for your comments, we'll contact you shortly!\r\nGracias por sus comentarios, nos pondremos en contacto con usted!";
		}else{
			echo "Your comment could not be sent, please try again.\r\nError:&nsbp;".$this->email->print_debugger();
		}//fin ifelse
	}
}
/*==============================================EOF=====================================================*/