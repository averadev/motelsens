<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
|	Backoffice_model Model
| -------------------------------------------------------------------------
|	
|	@author: 	Pablo Martínez
| 	@link: 		http://www.greenlabs.com.mx
|	@comments:	modelo para el backoffice de Hotel Casa de Guadalupe
|	@date:		
|
*/
class Backoffice_model extends CI_Model{
	public function __construct(){
		parent::__construct();
		$this->load->helper('text');
	}
// ---------------------------USUARIOS ----------------------------------------------
	function validate_admin(){
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', $this->input->post('password'));
		$this->db->where('status', 1);
		$query = $this->db->get('user_admin');
		if($query->num_rows == 1){
			$row = $query->row();
			$data['id'] = 	$row->id;
			$data['username'] = $row->username;
			return $data;
		}else{
			return false;
		}
	}
// --------------------------- Users (customers) ----------------------------------------------	
	
	public function getUserList(){
		$query = $this->db->select('*')->from('user')
			->order_by('lastname_user')
			->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$users[$row->id_user] = array(
					'id_user' => $row->id_user,
					'name_user' => $row->name_user." ".$row->lastname_user,
					'email_user' => $row->email_user,
					'tel_user' => $row->tel_user,
					'address_user' => $row->address_user,
					'city_user' => $row->city_user,
					'country_user' => $row->country_user
				);
			}
		}
		return $users;
	}
	
	public function deleteClient($clientId){
		if ($this->db->delete('user', "id_user = $clientId")) {
			return array(
				'status' => 1,
				'msg'	 => 'Usuario eliminado exitosamente.'
			);
		}else{
			return array(
				'status' => 1,
				'msg'	 => 'No se ha podido eliminar el usuario, intente de nuevo.'
			);
		}
	}
	
	public function cleanRepeatedUsers(){
		
	}
// --------------------------- Suites ----------------------------------------------	
	function getSuiteListing(){
		$query = $this->db->select('*')->from('room_category')
			->order_by('cat_room_id', 'asc')
			->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$rooms[$row->cat_room_id] = array(
					'cat_room_name' => $row->cat_room_name,
					'cat_room_description_en' => $row->cat_room_description_en,
					'cat_room_description_es' => $row->cat_room_description_es,
					'cat_room_allotment' => $row->cat_room_allotment,
					'cat_room_base_price' => $row->cat_room_base_price
				);
			}
		}
		return $rooms;
	}
	
	function getSuitenameById($id){
		
	}
	
	public function setRoomData(){
		foreach($_POST as $key => $value) {
			$updateData[$key] = ascii_to_entities($value);
		}
		$this->db->where('cat_room_id', $updateData['cat_room_id']);
		if ($this->db->update('room_category', $updateData)) {
			return true;
		}else{
			return false;
		}
	}
	
// --------------------------- Reservations ----------------------------------------------
	
	public function activateReserve($id){
		
	}
	
	public function getReservationList(){
		$today = date("Y-m-d");
		$query = $this->db->select('*')->from('reservation')
			->join('user', 'user.id_user = reservation.id_user')
			->where('reservation.status_reserve', 1)
			->where('checkin >', $today)
			->order_by('reservation.checkin')
			->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$reservations[$row->id_reserve] = array(
					'name' => $row->name_user." ".$row->lastname_user,
					'checkin' => $row->checkin,
                    'arrival' => $row->zip_user,
					'checkout' => $row->checkout,
					'email_user' => $row->email_user,
					'tel_user' => $row->tel_user,
					'country_user' => $row->country_user,
					'room_qty' => $row->roomQty,
					'comments_reserve' => $row->comments_reserve,
					'room_type' => $this->getReservationRoomType($row->id_reserve)
				);
			}
		}
		if (isset($reservations)){
			return $reservations;
		}
	}
	
	public function getUnconfirmedReservationList(){
		$today = date("Y-m-d");
		$query = $this->db->select('*')->from('reservation')
			->join('user', 'user.id_user = reservation.id_user')
			->where('reservation.status_reserve', 0)
			->where('checkin >', $today)
			->order_by('id_reserve')
			->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$reservations[$row->id_reserve] = array(
					'name' => $row->name_user." ".$row->lastname_user,
					'checkin' => $row->checkin,
                    'arrival' => $row->zip_user,
					'checkout' => $row->checkout,
					'email_user' => $row->email_user,
					'tel_user' => $row->tel_user,
					'country_user' => $row->country_user,
					'room_qty' => $row->roomQty,
					'comments_reserve' => $row->comments_reserve,
					'room_type' => $this->getReservationRoomType($row->id_reserve)
				);
			}
		}
		if (isset($reservations)){
			return $reservations;
		}
	}

	private function getReservationRoomType($idReserve){
		$query = $this->db->select('room_category.cat_room_name')->from('room_category')
			->join('reservation_detail', 'reservation_detail.cat_room_id = room_category.cat_room_id')
			->join('reservation', 'reservation.id_reserve=reservation_detail.reservation_id')
			->where('reservation.id_reserve', $idReserve)
			->get();
		$room = $query->row(1);
		return $room->cat_room_name;
	}
	
	public function getBlockedDates(){
		$query = $this->db->select('*')->from('reservation')
			->where('reservation.status_reserve', 3)
			->order_by('id_reserve')
			->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				//Room type query
				$roomName = $this->getReservationRoomType($row->id_reserve);
				$reservations[$row->id_reserve] = array(
					'checkin' => $row->checkin,
					'checkout' => $row->checkout,
					'comments_reserve' => $row->comments_reserve,
					'roomQty' => $row->roomQty,
					'room_type' => $roomName
				);
			}
		}
		if (isset($reservations)){
			return $reservations;
		}
	}
	
	public function getReservationDetails($id){
		
	}
	
	public function cancelReservation($id){
		$data['status_reserve'] = 0;
		if ($this->db->delete('reservation', array('id_reserve' => $id))) {
			return array(
				'status' => 1,
				'msg'	 => 'Reservación cancelada exitosamente.'
			);
		}else{
			return array(
				'status' => 0,
				'msg'	 => 'No se ha podido cancelar la reservación, intente de nuevo.'
			);
		}
	}
// --------------------------- Seasons ----------------------------------------------
	function getSeasonList(){
		$query = $this->db->select('*')->from('reservation_season')->order_by('date_season_start')->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$seasons[$row->season_id] = array(
					'season_name' => $row->season_name,
					'date_season_start' => $row->date_season_start,
					'date_season_end' => $row->date_season_end,
					'season_comments' => $row->season_comments
				);
			}
		return $seasons;
		}
	}
	
	function getSeasonPrices(){
		$query = $this->db->select('reservation_season.season_id, reservation_season.season_name ,room_category.cat_room_name, room_category.cat_room_id, xref_room_season.season_id, xref_room_season.season_room_price')->from('room_category')
			->join('xref_room_season', 'xref_room_season.cat_room_id=room_category.cat_room_id')
			->join('reservation_season', 'reservation_season.season_id=xref_room_season.season_id')
			->order_by('room_category.cat_room_id', 'asc')
			->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$seasonPrices[$row->season_name][$row->cat_room_name] = $row;
			}
			return $seasonPrices;
		}
	}
	
	public function addSeason(){
		$start = date('Y-m-d', strtotime($this->input->post('date_season_start', TRUE)));
		$end = date('Y-m-d', strtotime($this->input->post('date_season_end', TRUE)));
		
		$seasonData = array(
			'season_name' => $this->input->post('season_name', TRUE),
			'date_season_start' => $start,
			'date_season_end' => $end,
			'season_comments' => $this->input->post('season_comments', TRUE)
		);
		if ($this->db->insert('reservation_season', $seasonData)) {
			return array(
				'status' => 1,
				'msg'	 => 'Se ha agregado la temporada.',
				'seasonId' => $this->db->insert_id()
			);
		}else{
			return array(
				'status' => 0,
				'msg'	 => 'No se ha podido agregar la temporada, intente de nuevo.'
			);
		}
	}
	
	public function setSeasonRoomPrice(){
		$exists = $this->db->select('season_room_price')->from('xref_room_season')
			->where('cat_room_id', $this->input->post('roomCat', TRUE))
			->where('season_id', $this->input->post('seasonId', TRUE))
			->get();
		$roomCost = $this->input->post('price', TRUE);
		if ($roomCost == '') {
			$roomCost = NULL;
		}
		if ($exists->num_rows() > 0) {
			$roomSeasonPrice = array('season_room_price' => $this->input->post('price', TRUE));
			$this->db->where('season_id', $this->input->post('seasonId', TRUE));
			$this->db->where('cat_room_id', $this->input->post('roomCat', TRUE));
			if ($this->db->update('xref_room_season', $roomSeasonPrice)) {
				return array(
					'status' => 1,
					'msg'	 => 'Se ha modificado el precio.'
				);
			}else{
				return array(
					'status' => 0,
					'msg'	 => 'No se ha podido modificar el precio de la temporada, intente de nuevo.'
				);
			}
		}else{
			$roomSeasonPrice = array(
				'cat_room_id'=> $this->input->post('roomCat', TRUE),
				'season_id' => $this->input->post('seasonId', TRUE),
				'season_room_price' => $roomCost
			);
			if ($this->db->insert('xref_room_season', $roomSeasonPrice)) {
				return array(
					'status' => 1,
					'msg'	 => 'Se ha agregado el precio de la temporada.'
				);
			}else{
				return array(
					'status' => 0,
					'msg'	 => 'No se ha podido agregar lel precio de la temporada, intente de nuevo.'
				);
			}
		}
	}
	
	public function removeSeason($seasonId){
		$this->db->where('season_id', $seasonId);
		$this->db->delete('reservation_season');
		$this->db->where('season_id', $seasonId);
		$this->db->delete('xref_room_season');
		return array(
			'status' => 1,
			'msg' => 'Season removed'
		);
	}
// ---------------------------SETTERS ----------------------------------------------
	function storeBlock($data){
		
	}
	
	function insertBlock($date, $suite){
		
	}
	
	function getBlockDays($checkIn, $checkOut){
		
	}
	
	function updateSuiteData(){
		
	}
	function deactivateReservation($id){
		
	}
	
	function setSuitePriceByType($id){
		
	}
	
	function setSeasonDates(){
		
	}
}//End Of Model


//	@eof Backoffice_model Model File  =================================================