<?php if(! defined('BASEPATH')) exit ('No direct script access allowed');
/*
|-----------------------------------------------------------------------
| Suites_casa_margarita Controller
|-----------------------------------------------------------------------
|
| @author: 	Pablo Martínez
| @link:	 	http://www.greenlabs.com.mx
| @comments:	Suites view controller
| @date:		Dec 2012
|
*/
class Suites_casa_margarita extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('reserv_model');
	}

	public function en(){
		$this->lang->load('en_site', 'english');
		$this->loadPage();
	}

	public function es(){
		$this->lang->load('es_site', 'spanish');
		$this->loadPage();
	}

	private function loadPage(){
		$data['prices'] = $this->reserv_model->getSuitePriceList();
		$data['styles'] = $this->load->view('site/dynamics/styles', '', TRUE);
		$data['scripts'] = $this->load->view('site/dynamics/scripts', '', TRUE);
		$data['topBar'] = $this->load->view('site/dynamics/top_bar', '', TRUE);
		$data['navigation'] = $this->load->view('site/dynamics/navigation', '', TRUE);
		$data['booking_form'] = $this->load->view('site/dynamics/booking_form', '', TRUE);
		$data['contact_form'] = $this->load->view('site/dynamics/contact_form', '', TRUE);
		$this->load->view('site/suites', $data);
	}

}//end of controller

// @eof Suites_casa_margarita Controller ========================================================