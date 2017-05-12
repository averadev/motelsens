<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
|	Login Controller
| -------------------------------------------------------------------------
|	Conrtrolador de Loggeo de usuarios
|	@author 	Pablo Martínez
| 	@link 		http://www.greenlabs.com.mx
|
*/
class Login extends CI_Controller{

	function loginView(){
		$this->load->view('login_view');
	}

	function validateUser(){
		$query = $this->users_model->validate_user();
		if($query){
			$data = array(
				'email' 	=> $this->input->post('email'),
				'logged_in' => TRUE,
				'nombre'	=> $query['nombre'],
				'apellido'  => $query['apellido'],
				'email'		=> $query['email'],
				'tel'		=> $query['tel'],
				'pais'		=> $query['pais'],
				'id'		=> $query['id']
			);
			$this->session->set_userdata($data);
			redirect('reservaciones/reservaLoggedIn');
		}else{
			redirect('');
		}
	}
/*
| -------------------------------------------------------------------------
|	Logout
| -------------------------------------------------------------------------
*/
	function destroy(){
		$this->session->sess_destroy();
		redirect('');
	}
}//end of class =)
//	@eof login controller File  =================================================