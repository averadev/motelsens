<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
|	Reserv_model Model
| -------------------------------------------------------------------------
|	
|	@author: 	Pablo Martínez
| 	@link: 		http://www.greenlabs.com.mx
|	@comments:	Modelo para reservar habitaciones de hotel casa de guadalupe
|	@date:		julio 2010
|
*/
class Reserv_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Mexico_City');
	}
	
	//Regresa valores de variables de configuracion
	public function getVariable($variable){
		$query = $this->db->select('unit_variable')->from('variables')->where('name_variable', $variable)->get('');
		$result = $query->row();
		return $result->unit_variable;
	}
	public function updateVariable(){
		$variable = $this->input->post('variable');
		$value = $this->input->post('value');
		if ($variable == 'tax') {
			$value = (int)$value / 100;
		}
		$query = $this->db->where('name_variable', $variable)->update('variables', array('unit_variable' => $value));
	}
	//==================================Verifica reservaciones tentativas==================================================

	public function verificaNuevaReserva(){
		$checkIn =	strtotime($this->input->post('checkIn', TRUE));
		//$checkOut = strtotime($this->input->post('checkOut', TRUE));
		$checkOut = strtotime($this->input->post('checkIn', TRUE). ' + 1 day');
		echo "$checkOut";
		
		if($checkIn > $checkOut){
			return false;
		}
		$vacationDates = $this->getVacationDays($checkIn, $checkOut);
		foreach ($vacationDates as $key => $date){
			$allotmentArray[$date] = $this->checkAvailability($date);
		}
		//arreglo con allotments por fecha tentativa de reserva
		return $allotmentArray;
	}

	public function verifyAllotmentByDateRange($checkIn = NULL, $checkOut = NULL, $calendar = NULL){
		if ($checkIn == NULL && $checkOut == NULL) {
			$checkIn =	strtotime($this->input->post('checkIn', TRUE));
			$checkOut = strtotime($this->input->post('checkOut', TRUE));
		}else{
			$checkIn = strtotime($checkIn);
			$checkOut = strtotime($checkOut);
		}
		
		if($checkIn > $checkOut){
			return false;
		}
		if ($this->input->post('dateRange') || $calendar) {
			$vacationDates = $this->getVacationDays($checkIn, $checkOut);
		}else{
			$vacationDates = $this->getVacationDays($checkIn, $checkOut, TRUE);
		}
		foreach ($vacationDates as $key => $date){
			$allotmentArray[$date] = $this->checkAvailability($date);
		}
		return $allotmentArray;
	}
	
	//funcion que devuelve un arreglo con los dias que estará el huesped en el hotel
	private function getVacationDays($checkIn, $checkOut, $blockCheck = NULL){
		//calcula el numero de dias
		$nights = (int) date('d', $checkOut - $checkIn);
		$actualDate = $checkIn;
		if ($blockCheck == NULL) {
			$dayIndex = 0;	
		}else{
			$dayIndex = 1;
		}
		do {
			$vacationDays[$dayIndex] = date('Y-m-d', $actualDate);
			$actualDate = $actualDate + 86400; // 1 day in seconds
			$dayIndex++;
		} while ($dayIndex <= $nights);
		return $vacationDays;
	}

	//checa el dia pasado como argumento y revisa la disponibilidad de cuartos
	//recibe una fecha y devuelve un arreglo con las suites ocupadas.
	public function checkAvailability($date){
		$reservations = $this->db->select('reservation_detail.control_id, reservation_detail.cat_room_id')
			->from('reservation_detail')
			->join('reservation', 'reservation.id_reserve = reservation_detail.reservation_id')
			->where("reservation_detail.date_reservation ='$date' AND (reservation.status_reserve =1 OR reservation.status_reserve =3)")
			->get();
		$allotments = $this->getAllotments();
		
		if($reservations->num_rows() > 0){
			foreach ($reservations->result() as $row){
				$allotments[$row->cat_room_id]--;
			}
		}
		return $allotments;
	}
	
	private function getAllotments(){
		$roomDetails = $this->db->select('cat_room_allotment, cat_room_id')->from('room_category')->get();
		if ($roomDetails->num_rows() > 0) {
			foreach ($roomDetails->result() as $room) {
				$allotment[$room->cat_room_id] = $room->cat_room_allotment;
			}
			return $allotment;
		}
	}
	
	public function getSuitePriceById($roomId, $date){
		$season = $this->db->select('season_id')->from('reservation_season')
			->where('date_season_start <=', $date)
			->where('date_season_end >=', $date)
			->get();
		if ($season->num_rows() > 0) {
			$season = $season->row();
			$season = $season->season_id;
			$roomPriceByseason = $this->db->select('season_room_price')->from('xref_room_season')
				->where('cat_room_id', $roomId)
				->where('season_id', $season)
				->get();
			if ($roomPriceByseason->num_rows() > 0) {
				$roomPriceByseason = $roomPriceByseason->row();
				return $roomPriceByseason->season_room_price;
			}else{
			 	$roomBasePrice = $this->db->select('cat_room_base_price')->from('room_category')
			 		->where('cat_room_id', $roomId)
			 		->get();
			 	$roomBasePrice = $roomBasePrice->row();
			 	return $roomBasePrice->cat_room_base_price;
			 }
		}else{
			$roomBasePrice = $this->db->select('cat_room_base_price')->from('room_category')
				->where('cat_room_id', $roomId)
				->get();
			$roomBasePrice = $roomBasePrice->row();
			return $roomBasePrice->cat_room_base_price;
		}
	}

	public function getSuitePriceList(){
		$suitesList = $this->getSuiteCategoryList();
		foreach ($suitesList as $suiteId => $data) {
			$priceList[$suiteId] = $this->getSuitePriceById($suiteId, date('Y-m-d'));
		}
		return $priceList;
	}

	public function getSuiteDataById($roomId){
		$query = $this->db->select('*')->from('room_category')->where('cat_room_id', $roomId)->get();
		$roomData = $query->row();
		$roomData = array(
			'name' => $roomData->cat_room_name,
			'allotment' => $roomData->cat_room_allotment,
			'description_en' => $roomData->cat_room_description_en,
			'description_es' => $roomData->cat_room_description_es,
			'description_fr' => $roomData->cat_room_description_fr,
			'base_price' => $roomData->cat_room_base_price
		);
		return $roomData;
	}
	public function getSuiteCategoryList(){
		$sql = $this->db->select('*')->from('room_category')->where('cat_room_status', 1)->get();
		if ($sql->num_rows() > 0) {
			foreach ($sql->result() as $room) {
				$roomCategory[$room->cat_room_id] = array(
					'cat_room_name' => $room->cat_room_name,
					'cat_room_allotment' => $room->cat_room_allotment,
					'cat_room_description_en' => $room->cat_room_description_en,
					'cat_room_description_es' => $room->cat_room_description_es,
					'cat_room_base_price' => $room->cat_room_base_price
				);
			}
			return $roomCategory;
		}
	}
	
//==================================INSERTA RESERVACIONES A LA DB==================================================

	public function storeReserve(){
		$reservation = array(
			'id_user' => $this->input->post('userId', TRUE),
			'checkin' => $this->input->post('checkin', TRUE),
			'checkout' => $this->input->post('checkout', TRUE),
			'comments_reserve' => $this->input->post('comments', TRUE),
			'roomQty' => $this->input->post('roomQty', TRUE),
			'total_amount' => $this->input->post('total_amount', TRUE)
		);
		$rooms = $this->input->post('roomQty', TRUE);
		$roomType =  $this->input->post('roomType', TRUE);
		
		//Step 1 = store reserve main info
		if($this->db->insert('reservation', $reservation)){
			for ($i=0; $i < $rooms; $i++) {
				if ($i == 0) {
					$reserveId = $this->db->insert_id();
				}
				$checkinArray = explode("-", $reservation['checkin']);
				$checkoutArray = explode("-", $reservation['checkout']);
				//formato era 2012-02-05 y se cambia a 02/05/2012
				$checkinFormatted = strtotime($checkinArray[1]."/".$checkinArray[2]."/".$checkinArray[0]);
				$checkoutFormatted = strtotime($checkoutArray[1]."/".$checkoutArray[2]."/".$checkoutArray[0]);
				$reserveNights = $this->getVacationDays($checkinFormatted, $checkoutFormatted);
				//elimina la ultima noche en el arreglo de fechas
				array_pop($reserveNights);

				foreach ($reserveNights as $key => $date) {
					$reserveDetails[$key] = array(
						'date_reservation' => $date,
						'reservation_id' => $reserveId,
						'cat_room_id' => $roomType,
						'reservation_status' => 1
					);
				}
				if ($this->storeReserveDetails($reserveDetails)){
					$success = TRUE;
				}else{
					return array(
						'status' => 0,
						'msg' => 'Reservation could not be completed, try again'
					);
				}
			}		
		}else{
			return array(
				'status' => 0,
				'msg' => 'Reservation could not be completed'
			);
		}
		if ($success == TRUE){
			return array(
				'status' 		=> 1,
				'msg' 			=> 'Reservation complete!',
				'reservationId'	=> $reserveId
			);
		}
	}
	
	public function blockDates(){
		$reservation = array(
			'checkin' => $this->input->post('start', TRUE),
			'checkout' => $this->input->post('end', TRUE),
		);
		$block = array(
			'id_user' => 1,
			'checkin' => date('Y-m-d', strtotime($reservation['checkin'])),
			'checkOut' => date('Y-m-d', strtotime($reservation['checkout'])),
			'comments_reserve' => 'Bloqueado por mantenimiento',
			'roomQty' => $this->input->post('roomQty', TRUE),
			'status_reserve' => 3
		);
		$roomType =  $this->input->post('roomType', TRUE);
		$rooms = $this->input->post('roomQty', TRUE);
		//Step 1 = store reserve main info
		if($this->db->insert('reservation', $block)){
			for ($i=0; $i < $rooms; $i++) {
				if ($i == 0) {
					$reserveId = $this->db->insert_id();
				}
				$checkinArray = explode("/", $reservation['checkin']);
				$checkoutArray = explode("/", $reservation['checkout']);
				$checkinFormatted = strtotime($checkinArray[0]."/".$checkinArray[1]."/".$checkinArray[2]);
				$checkoutFormatted = strtotime($checkoutArray[0]."/".$checkoutArray[1]."/".$checkoutArray[2].'+ 1 day');
				if ($this->input->post('range')) {
					$reserveNights = $this->getVacationDays($checkinFormatted, $checkoutFormatted);
				}else{
					$reserveNights = $this->getVacationDays($checkinFormatted, $checkoutFormatted, TRUE);
				}
				//Quita la ultima fecha del arreglo pues es el checkout y no nos sirve.
				array_pop($reserveNights);
				//Recorre el arreglo de fechas (noches) Y los guarda como detalles de la reservación (bloqueo).
				foreach ($reserveNights as $key => $date) {
					$reserveDetails[$key] = array(
						'date_reservation' => $date,
						'reservation_id' => $reserveId,
						'cat_room_id' => $roomType,
						'reservation_status' => 3
					);
				}
				if ($this->storeReserveDetails($reserveDetails)){
					$success = TRUE;
				}else{
					return array(
						'status' => 0,
						'msg' => 'Blocking could not be completed, try again'
					);
				}
			}
		}else{
			return array(
				'status' => 0,
				'msg' => 'Blocking could not be completed'
			);
		}
		if ($success == TRUE){
			return array(
				'status' 		=> 1,
				'msg' 			=> 'Blocking complete!',
				'reservationId'	=> $reserveId
			);
		}
	}
	
	private function storeReserveDetails($reserveDetails){
		$storeStatus = true;
		foreach ($reserveDetails as $date => $reservationData) {
			if (!$this->db->insert('reservation_detail', $reservationData)) {
				$storeStatus = false;
			}
		}
		return $storeStatus;
	}
	
//==================================Activa reservaciones confirmadas==================================================

	public function confirmBooking($reserveId){
		$reserve = $this->getReservationById($reserveId);
		$query = $this->db->select('*')->from('user')->where('id_user', $reserve['id_user'])->get();
		if($query->num_rows() > 0){
			$query = $query->row();
			$reserve['name'] = $query->name_user." ".$query->lastname_user;
			$reserve['tel'] = $query->tel_user;
			$reserve['mail'] = $query->email_user;
			$reserve['confirm'] = $reserveId;
			$reserve['requestStatus'] = 1;
		}
		return $reserve;
	}

//==================================Helper functions==================================================
	public function getUserById($userId){
		$query = $this->db->select('*')->from('user')->where('id_user', $userId)->get();
		if($query->num_rows() > 0){
			$query = $query->row();
			$user['name'] = $query->name_user." ".$query->lastname_user;
			$user['tel'] = $query->tel_user;
			$user['mail'] = $query->email_user;
			$user['country'] = $query->country_user;
		}
		return $user;
	}
	
	public function getReservationById($reserveId){		
		$query = $this->db->select('*')->from('reservation')->where('id_reserve', $reserveId)->get();
		if($query->num_rows() > 0){
			$query = $query->row();
			$reservation = array(
				'id_user' => $query->id_user,
				'checkin' => $query->checkin,
				'checkout' => $query->checkout,
				'status_reserve' => $query->status_reserve,
				'comments_reserve' => $query->comments_reserve,
				'payment_date' => $query->payment_date,
				'id_user' => $query->id_user,
				'id_reserve' => $query->id_reserve,
				'txn_id' => $query->txn_id,
				'total_amount' => $query->total_amount
			);
			return $reservation;
		}
	}
/*==============================================MAINTENANCE METHODS=====================================================*/
	public function removeDeletedReservations(){
		$query = "DELETE FROM reservation_detail WHERE reservation_id NOT IN(SELECT id_reserve FROM reservation)";
		return $this->db->query($query);	
	}
/*==============================================TEST & DEBUG METHODS=====================================================*/
	public function getDayBookingData($date){
		$reservations = $this->db->select('reservation_detail.control_id, reservation_detail.cat_room_id')
			->from('reservation_detail')
			->join('reservation', 'reservation.id_reserve = reservation_detail.reservation_id')
			->where("reservation_detail.date_reservation ='$date' AND (reservation.status_reserve =1 OR reservation.status_reserve =3)")
			->get();
		$allotments = $this->getAllotments();
		
		if($reservations->num_rows() > 0){
			$acum = 0;
			foreach ($reservations->result() as $row){
				foreach ($row as $key => $value) {
					$res[$acum][$key] = $value;
				}
				$acum++;
			}
		}
		return $res;
	}
}//End Of Model

//	@eof Reserv_model Model File  =================================================