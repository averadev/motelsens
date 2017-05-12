<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| 	Backoffice Controller
| -------------------------------------------------------------------------
|
|	@author: 	Pablo Martínez
| 	@link: 		http://www.greenlabs.com.mx
|	@comments:	controlador del backoffice de reservas Hotel Casa de Guadalupe
|	@date:		ago 2010
|
*/
class Backoffice extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('backoffice_model');
		$this->load->model('reserv_model');
	}

	//----------------------------FUNCIONES DE USUARIOS Y SESIONES--------------------------------------
	public function loggedIn(){
		$logged = $this->session->userdata('admin_logged_in');
		if(!isset($logged) OR $logged != TRUE){
			redirect('backoffice/login');
		}else{
			return true;
		}
	}
	public function validateAdmin(){
		$this->load->model('backoffice_model');
		$query = $this->backoffice_model->validate_admin();
		if($query){
			$data = array(
				'admin_logged_in' => TRUE,
				'username'	=> $query['username'],
				'id'		=> $query['id']
			);
			$this->session->set_userdata($data);
			redirect('backoffice/console');
		}else{
			$this->login("Nombre de usuario o contrase&ntilde;a no v&aacute;lidos.");
		}
	}
	public function destroy(){
		$this->session->sess_destroy();
		redirect('backoffice/console');
	}
	public function login($error = NULL){
		$data['error'] = $error;
		$this->load->view('bookbot/backoffice/backoffice_login', $data);
	}
	//----------------------------FUNCIONES DE CONTROL PANEL--------------------------------------
	public function index(){
		redirect('backoffice/console');
	}

	public function test(){
		$check = $this->reserv_model->verificaNuevaReserva();
		print_r($check);
	}
	
	public function console(){
		$this->loggedIn();
		$data['reservas'] = $this->backoffice_model->getReservationList();
		$data['unconfirmed'] = $this->backoffice_model->getUnconfirmedReservationList();
		$data['bloqueos'] = $this->backoffice_model->getBlockedDates();
		$data['rooms'] = $this->backoffice_model->getSuiteListing();
		$data['seasons'] = $this->backoffice_model->getSeasonList();
		$data['seasonPrices'] = $this->backoffice_model->getSeasonPrices();
		$data['users'] = $this->backoffice_model->getUserList();
		$data['tax'] = $this->reserv_model->getVariable('tax');
		$data['extra_adult'] = $this->reserv_model->getVariable('extra_adult');
		$data['calendar'] = $this->availabilityCalendar(date('Y'), date('m'));
		$this->load->view('bookbot/backoffice/console_view', $data);
	}
	public function availabilityCalendar($year, $month, $ajax = NULL){		
		$monthStart = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
		$monthEnd = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

		if ($allotmentArray = $this->reserv_model->verifyAllotmentByDateRange($monthStart, $monthEnd, TRUE)) {
			foreach ($allotmentArray as $date => $roomAllotments) {
				foreach ($roomAllotments as $roomId => $allotment) {
					$price = $this->reserv_model->getSuitePriceById($roomId, $date);
					$roomTypeData[$roomId] = $this->reserv_model->getSuiteDataById($roomId);
					$priceArray[$date][$roomId] = array(
						'name' => $roomTypeData[$roomId]['name'],
						'price' => $price,
						'allotment' => $allotment //number of available rooms
					);
				}
			}
			$this->load->model('calendar_model');
			if ($ajax) {
				echo $this->calendar_model->generateBackofficeCalendar($priceArray);
			}else{
				return $this->calendar_model->generateBackofficeCalendar($priceArray);
			}
		}
	}
	public function editSuiteData(){
		$this->loggedIn();
		if ($this->backoffice_model->setRoomData()) {
			
		}
	}
	//Activa reserva sin confirmar
	public function activateUnconfirmedReserve($reserveId){
		$this->loggedIn();
		$time = date('Y-m-d H:i:s');
		if ($response = $this->reserv_model->confirmBooking($reserveId, $time)) {
			echo json_encode($response);
		}
	}
	//Cancela la reservacion pasada como argumento
	public function cancelReserve($id){
		$this->loggedIn();
		if ($response = $this->backoffice_model->cancelReservation($id)) {
			echo json_encode($response);
		}
	}
	//borra cliente de la base de datos
	public function deleteClient($id){
		$this->loggedIn();
		if ($response = $this->backoffice_model->deleteClient($id)) {
			echo json_encode($response);
		}
	}
	//checa fechas para bloquear
	public function checkDatesForBlocking(){
		$check = $this->reserv_model->verifyAllotmentByDateRange();
		echo json_encode($check);
	}
	public function checkDatesForDateRangeBlocking(){
		$check = $this->reserv_model->verifyAllotmentByDateRange();
		echo json_encode($check);
	}
	//bloquea fechas
	public function blockRoomDates(){
		$response = $this->reserv_model->blockDates();
		echo json_encode($response);
	}
	//Alta de temporada
	public function newSeason(){
		$response = $this->backoffice_model->addSeason();
		echo json_encode($response);
	}
	//Precio de temporada
	public function setSeasonPrice(){
		$response = $this->backoffice_model->setSeasonRoomPrice();
		echo json_encode($response);
	}
	//borra temporada
	public function deleteSeason($seasonId){
		$response = $this->backoffice_model->removeSeason($seasonId);
		echo json_encode($response);
	}
	//Inserta valor deuna variable de configuración
	public function setVariable(){
		$this->reserv_model->updateVariable();
		echo  json_encode(array(
			'status' => 1,
			'msg'	 => 'Se ha modificado la variable.'
		));
	}
/*==========================================DB MAINTENANCE METHODS=========================================================*/
	public function cleanNonExistingBookings(){
		if ($this->reserv_model->removeDeletedReservations()) {
			echo "Garbage blockings cleaned up! =)";
		}else{
			echo "There was an error cleaning garbage blocks! =(";
		}
	}




}//End Of Controller


//	@eof Backoffice Controller File ================================================