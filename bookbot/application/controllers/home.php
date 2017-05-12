<?php if(! defined('BASEPATH')) exit ('No direct script access allowed');
/*
|-----------------------------------------------------------------------
| Home Controller
|-----------------------------------------------------------------------
|
| @author: 	Pablo Martínez
| @link:	 	http://www.greenlabs.com.mx
| @comments:	Home view
| @date:		Dec 2012
|
*/
class Home extends CI_Controller{

	public function __construct(){
		parent::__construct();
		//->load->model('');
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
		$data['styles'] = $this->load->view('site/dynamics/styles', '', TRUE);
		$data['scripts'] = $this->load->view('site/dynamics/scripts', '', TRUE);
		$data['topBar'] = $this->load->view('site/dynamics/top_bar', '', TRUE);
		$data['navigation'] = $this->load->view('site/dynamics/navigation', '', TRUE);
		$data['booking_form'] = $this->load->view('site/dynamics/booking_form', '', TRUE);
		$data['contact_form'] = $this->load->view('site/dynamics/contact_form', '', TRUE);
		$this->load->view('site/home', $data);
	}
	

}//end of controller

// @eof Home Controller ========================================================