<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
|	Lang_model Model
| -------------------------------------------------------------------------
|	
|	@author: 	Pablo Martínez
| 	@link: 		http://www.greenlabs.com.mx
|	@comments:	modelo para idiomas de Jolie Jungle
|	@date:		ene 2012
|
*/
class Lang_model extends CI_Model{

	public function getData($lang){		
		switch ($lang) {
			case 'en':
				return $this->queryData('cont_en');
				break;
			case 'es':
              	return $this->queryData('cont_es');
			  	break;
	  		case 'fr':
              	return $this->queryData('cont_fr');
		  		break;
		}
	}
	public function getBookingData($lang){
		switch ($lang) {
			case 'en':
				return $this->queryData('booking_en');
				break;
			case 'es':
              	return $this->queryData('booking_es');
			  	break;
	  		case 'fr':
              	return $this->queryData('booking_fr');
		  		break;
		}
	}
	//query data from specified language
	public function queryData($lang_string){
		$query = $this->db->select('*')->from($lang_string)->get();
		foreach ($query->result() as $row) {
			$content[$row->cont_tag] = $row->cont_text;
		}
		return $content;
	}
	
}//End Of Model


//	@eof Backoffice_model Model File  =================================================