<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| 	Reservaciones< Controller
| -------------------------------------------------------------------------
|
|	@author: 	Pablo Martínez
| 	@link: 		http://www.greenlabs.com.mx
|	@comments:	Controlador para reservaciones de casa guadalupe
|	@date:		julio 2010
|
*/
class Reservaciones extends CI_Controller{
	
	public $cont = array();
	public $bookCont = array();
	
	public function __construct(){
		parent::__construct();
		$this->load->model('reserv_model');
		$this->load->model('users_model');
		$this->load->model('lang_model');
		date_default_timezone_set('America/Mexico_City');
	}
	//========================================== view methods ========================
	
	function index(){
		redirect('/reservaciones/date_select/en');
	}
	
	function date_select($lang){
		$this->cont = $this->lang_model->getData($lang);
		$this->bookCont = $this->lang_model->getBookingData($lang);
		$data['cont'] = $this->cont;
		$data['bookCont'] = $this->bookCont;
		$data['langNav'] = $this->load->view('navigations/lang_nav', '', TRUE);
		$data['nav'] = $this->load->view('navigations/nav', $data, TRUE);
		$data['footer'] = $this->load->view('site/footer', $data, TRUE);
		$data['adultCost'] = $this->reserv_model->getVariable('extra_adult');
		$this->load->view('reserve_view', $data);
	}
	
	private function displayCalendar($datesArray){
		$this->load->model('calendar_model');
		return $this->calendar_model->generaCal($datesArray);
	}
	
/*==============================================  =====================================================*/
	public function checkDates($lang){
		switch ($lang) {
			case 'es':
				$this->lang->load('es_site', 'spanish');
				break;
			case 'en':
				$this->lang->load('en_site', 'english');
				break;
			default:
				$this->lang->load('en_site', 'english');
				break;
		}

		//Rooms the user is trying to reserve
		$rooms = $this->input->post('rooms', TRUE);
		//Number of adults
		$adults = $this->input->post('adults', TRUE);
		//Number of children
		$children = $this->input->post('children', TRUE);
		//Total people in booking
		$totalPeople = $adults + $children;
		//Extra adults based on 2 adults per room.
		$extra = $adults-$rooms*2;
		//Max people per room = 4 If more don't continue
		if (($totalPeople/$rooms) > 4) {
			redirect('reservaciones/date_select/'.$lang);
		}
		//Verifies allotment for each date and processes the info
		if ($allotmentArray = $this->reserv_model->verificaNuevaReserva()) {
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
			//Content in proper language for site interface
			$this->cont = $this->lang_model->getData($lang);
			//Content in proper language for booking interface
			$this->bookCont = $this->lang_model->getBookingData($lang);
			$data['cont'] = $this->cont;
			$data['bookCont'] = $this->bookCont;
			//$data['langNav'] = $this->load->view('navigations/lang_nav', '', TRUE);
			//$data['nav'] = $this->load->view('navigations/nav', $data, TRUE);
			$data['scripts'] = $this->load->view('site/dynamics/scripts', $data, TRUE);
			$data['styles'] = $this->load->view('site/dynamics/styles', $data, TRUE);
			$data['rooms'] = $rooms;
			// Array containing Room info, total allotment & price per Date
			$data['priceArray'] = $priceArray;
			//Array containing Room info, total allotment & price
			$data['roomTypeList'] = $roomTypeData;
			$data['calendar'] = $this->displayCalendar($data['priceArray']);
			$data['extra'] = $extra;
			$data['extraAdultCost'] = $this->reserv_model->getVariable('extra_adult');
			$data['tax'] = $this->reserv_model->getVariable('tax');
			
			$this->load->view('bookbot/select_room_view', $data);
		}else{
			$error = array(
				'status' => 0,
				'msg'	 => 'Dates are invalid, please try again'
			);
		}
	}
//================================ Funciones publicas ( registro y login )===============
	function registroUsuario(){
		$this->load->library('form_validation');
		//Reglas de validacion
		$this->form_validation->set_rules('name', 'Nombre', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Apellido(s)', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('tel', 'Telefono', 'trim|required');
		$this->form_validation->set_rules('country', 'Pais', 'trim|required');
		$this->form_validation->set_rules('address', 'Direccion', 'trim|required');
		$this->form_validation->set_rules('city', 'Ciudad', 'trim|required');
		$this->form_validation->set_rules('zip', 'Zip Code', 'trim|required');
		
		if($this->form_validation->run() == FALSE){
			//arreglo con los datos del usuario
			$error = array(
				'status' => 0,
				'msg' => validation_errors()
			);
			echo json_encode($error);
		}else{
			$registro = $this->users_model->registroUsuario();
			if ($registro){
				$response = array(
					'status' => 1,
					'user' => $this->db->insert_id(),
				);
				echo json_encode($response);
			}
		}
	}
	
	public function confirmAndBook(){
		$book = $this->reserv_model->storeReserve();
		echo json_encode($book);
	}
//================================ Funciones De Confirmación de pago y reservación ===============

	public function receiveConfirm($lang, $reserveId){
		$this->cont = $this->lang_model->getData($lang);
		$this->bookCont = $this->lang_model->getBookingData($lang);
		$data['cont'] = $this->cont;
		$data['bookCont'] = $this->bookCont;
		$data['scripts'] = $this->load->view('site/dynamics/scripts', $data, TRUE);
		$data['styles'] = $this->load->view('site/dynamics/styles', $data, TRUE);
		//$data['footer'] = $this->load->view('site/footer', $data, TRUE);
		if ($data['res'] = $this->reserv_model->confirmBooking($reserveId)) {
			$this->load->view('bookbot/reserve_receive_confirm', $data);
		}else{
			echo "Error en el sistema, presione regresar.";
		}
	}
	
	public function debug(){
		//Just in case =)
	}
	
}//End Of Controller
//	@eof Reservations Controller File ================================================