<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
|	Users Model
| -------------------------------------------------------------------------
|	Modelo que contiene la lógica para comprobar, leer e ingresar datos de usuario
|	@author 	Pablo Martínez
| 	@link 		http://www.greenlabs.com.mx
| 	
|
*/
class Users_model extends CI_Model{
	function validate_user(){
		$this->db->where('email_user', $this->input->post('email'));
		$this->db->where('password_user', md5($this->input->post('password')));
		$this->db->where('status_user', 1);
		$query = $this->db->get('usuarios');
		if($query->num_rows == 1){
			$row = $query->row();
			$data['nombre'] = 	$row->name_user;
			$data['apellido'] = $row->lastname_user;
			$data['email'] = 	$row->email_user;
			$data['tel'] = 		$row->tel_user;
			$data['pais'] =		$row->country_user;
			$data['id'] = 		$row->id_user;
			return $data;
		}else{
			return false;
		}
	}
	
	// inserta el usuario que intenta reservar en la base de datos
	function registroUsuario(){
		$userData 	= array(
			'name_user' => $this->input->post('name', TRUE),
			'lastname_user' => $this->input->post('lastname', TRUE),
			'email_user' => $this->input->post('email', TRUE),
			'tel_user' => $this->input->post('tel', TRUE),
			'country_user' => $this->input->post('country', TRUE),
			'address_user' => $this->input->post('address', TRUE),
			'city_user' => $this->input->post('city', TRUE),
			'zip_user' => $this->input->post('zip', TRUE)
		);
		if($this->db->insert('user', $userData)){
			return true;
		}else{
			return false;
		}
	}
	//extrae nombre completo de usuario por su id
	function getUserNameById($userId){
		$query = $this->db->select('*')->from('user')->where('id_user', $userId)->get();
		if($query->num_rows() > 0){
			$row = $query->row();
			$name = $row->name_user." ".$row->lastname_user;
		}
		return $name;
	}

	public function getUserById($userId){
		$query = $this->db->select('*')->from('user')->where('id_user', $userId)->get();
		if($query->num_rows() > 0){
			$row = $query->row();
			foreach ($row as $key => $value) {
				$user[$key] = $value;
			}
		}
		return $user;
	}
}//end of controller =)