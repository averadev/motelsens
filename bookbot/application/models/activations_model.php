<?php if(! defined('BASEPATH')) exit ('No direct script access allowed');
/*
|-----------------------------------------------------------------------
| Activations_model Model
|-----------------------------------------------------------------------
|
| @author: 	Pablo Martínez
| @link:	 	http://www.greenlabs.com.mx
| @comments:	Membership validations & activations
| @date:		Nov 2012
|
*/
class Activations_model extends CI_Model{

	public function __construct(){
		$this->testFilePath = realpath(APPPATH.'../logs');
		parent::__construct();
	}
	
	public function verifyReservation($txn_id){
		$query = $this->db->select('txn_id')->from('reservation')->where('txn_id', $txn_id)->get();
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function verifyAmount($amount, $id_reserve){
		$query = $this->db->select('total_amount')->from('reservation')->where('id_reserve', $id_reserve)->get();
		if($query->num_rows() > 0){
			$row = $query->row();
			$total_amount = $row->total_amount;
			if ($total_amount == $amount) {
				return TRUE;
			}else{
				$fh = fopen($this->testFilePath.'/ipn_error_log.txt', 'a');
				$stringToWrite = "\r\nAmount is wrong, Received: ".$_POST['mc_gross']." ---> Expected: $row->total_amount !!\r\n";
				fwrite($fh, $stringToWrite);
				return FALSE;
			}
		}
	}

	public function activateReservation($id_reserve, $txn_id, $payment_date){
		$data = array(
			'txn_id' => $txn_id,
			'payment_date' => $payment_date,
			'status_reserve' => 1
		);
		$this->db->where('id_reserve', $id_reserve);
		$this->db->update('reservation', $data);
		return TRUE;
	}

}//end of model

// @eof Activations_model Model ========================================================