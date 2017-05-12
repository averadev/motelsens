<?php
class Calendar_model extends CI_Model{
	
	//atributo de configuracion de la clase
	var $config;
	var $backofficeConfig;
	
	//constructor
	//define el dia de inicio del calendario, activa botones de mes siguiente y anterior
	//Configura el template del calendario para poder manipularlo con CSS y JS
	function Calendar_model(){
		date_default_timezone_set('America/Mexico_City');

		$this->config = array(
			'start_day' => 'monday',
			'show_next_prev' => FALSE,
			'next_prev_url' => base_url().'index.php/calendario/display'
		);

		$this->backofficeConfig = array(
			'start_day' => 'monday',
			'show_next_prev' => TRUE,
			'next_prev_url' => ''.base_url().'index.php/backoffice/availabilityCalendar/'
		);
		
		$this->config['template'] = '
			{table_open}<table class="calendario" border="0" cellpadding="0" cellspacing="0">{/table_open}

			{heading_row_start}<tr>{/heading_row_start}

			{heading_previous_cell}<th id="prevBt"><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
			{heading_title_cell}<th id="actualMonth" colspan="{colspan}">{heading}</th>{/heading_title_cell}
			{heading_next_cell}<th id="nextBt"><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

			{heading_row_end}</tr>{/heading_row_end}

			{week_row_start}<tr>{/week_row_start}
			{week_day_cell}<td>{week_day}</td>{/week_day_cell}
			{week_row_end}</tr>{/week_row_end}

			{cal_row_start}<tr class="days">{/cal_row_start}
			{cal_cell_start}<td class="day">{/cal_cell_start}

			{cal_cell_content}<div class="dayNumber highlight">{day}</div><div class="content"></div>{/cal_cell_content}
			
			{cal_cell_content_today}<div class="dayNumber ">{day}</div><div class="content"></div>{/cal_cell_content_today}

			{cal_cell_no_content}<div class="dayNumber">{day}</div>{/cal_cell_no_content}
			
			{cal_cell_no_content_today}<div class="dayNumber">{day}</div>{/cal_cell_no_content_today}

			{cal_cell_blank}&nbsp;{/cal_cell_blank}

			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}

			{table_close}</table>{/table_close}
		';

		$this->backofficeConfig['template'] = '
			{table_open}<table class="dashboardCalendar" border="0" cellpadding="0" cellspacing="0">{/table_open}

			{heading_row_start}<tr class="dashboardCalendarTopRow">{/heading_row_start}

			{heading_previous_cell}<th id="prevMonth"><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
			{heading_title_cell}<th id="dashboardCalendarActualMonth" colspan="{colspan}">{heading}</th>{/heading_title_cell}
			{heading_next_cell}<th id="nextMonth"><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

			{heading_row_end}</tr>{/heading_row_end}

			{week_row_start}<tr>{/week_row_start}
			{week_day_cell}<td>{week_day}</td>{/week_day_cell}
			{week_row_end}</tr>{/week_row_end}

			{cal_row_start}<tr class="days">{/cal_row_start}
			{cal_cell_start}<td class="calday">{/cal_cell_start}

			{cal_cell_content}<div class="dayNumber highlight">{day}</div><div class="content">{content}</div>{/cal_cell_content}
			
			{cal_cell_content_today}<div class="dayNumber ">{day}</div><div class="content">{content}</div>{/cal_cell_content_today}

			{cal_cell_no_content}<div class="dayNumber">{day}</div>{/cal_cell_no_content}
			
			{cal_cell_no_content_today}<div class="dayNumber">{day}</div>{/cal_cell_no_content_today}

			{cal_cell_blank}{/cal_cell_blank}

			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}

			{table_close}</table>{/table_close}
		';
	}
	
	//Recoge los datos para cada fecha del mes y año seleccionados
	function getData($year, $month){
		$query = $this->db->select('date, data')->from('calendar')->like('date', "$year-$month", 'after')->get();
		$calData = array();
		foreach($query->result() as $row){
			$calData[substr($row->date,8,2)] = $row->data;
		}
		return $calData;
	}
	
	//genera el calendario con los datos y la fecha seleccionada
	public function generaCal($datesArray){
		$this->load->library('calendar', $this->config);
		
		reset($datesArray);
		$firstDate = key($datesArray);
		
		$year = date('Y',strtotime($firstDate));
		$month = date('m',strtotime($firstDate));
		
		$calData = array();
		foreach ($datesArray as $date => $data) {
			if ($month == date('m', strtotime($date))) {
				$date = strtotime($date);
				$date = date('j', $date);
				$calData1[$date] = 'book date';
			}else{
				$month2 = date('m',strtotime($date));
				$date = strtotime($date);
				$date = date('j', $date);
				$calData2[$date] = 'book date';
			}
		}
		$calendars[1] = $this->calendar->generate($year, $month, $calData1);
		if (isset($calData2)) {
			$calendars[2] = $this->calendar->generate($year, $month2, $calData2);
		}else{
			$calendars[2] = $this->calendar->generate($year, $month+1, '');
		}
		return $calendars;
	}


/*==============================================BACKOFFICE CALENDAR FOR BOOKING AND BLOCKING=====================================================*/

	//genera el calendario con los datos y la fecha seleccionada
	public function generateBackofficeCalendar($datesArray){
		$this->load->library('calendar', $this->backofficeConfig);
		reset($datesArray);
		$firstDate = key($datesArray);
		$year = date('Y',strtotime($firstDate));
		$month = date('m',strtotime($firstDate));
		
		foreach ($datesArray as $date => $data) {
			$actualDate = $date;
			if ($month == date('m', strtotime($date))) {
				$date = strtotime($date);
				$date = date('j', $date);
				$calData1[$date] = "";
				foreach ($data as $roomId => $roomData) {
					$calData1[$date] .= "<p class=\"dateAllotment clearfix\"><input class=\"roomTypeId\" type=\"hidden\" value=\"$roomId\">".$roomData['name'].": <span class=\"allotmentNumber\">".$roomData['allotment']."</span></p>";
				}
				$calData1[$date] .= "<input type=\"hidden\" value=\"$actualDate\" class=\"hiddenCalDate\">";
			}
		}
		$calendar = $this->calendar->generate($year, $month, $calData1);
		return $calendar;
	}
}






