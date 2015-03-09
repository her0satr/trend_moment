<?php

class trend_moment extends SYGAAS_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$this->load->view( 'trend_moment');
	}
	
	function grid() {
		$_POST['is_edit'] = 1;
		$_POST['column'] = array( 'tanggal_swap', 'penjualan', 'waktu', 'xy', 'x2' );
		
		$array = $this->trend_moment_model->get_array($_POST);
		$count = $this->trend_moment_model->get_count();
		$grid = array( 'sEcho' => $_POST['sEcho'], 'aaData' => $array, 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count );
		
		echo json_encode($grid);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->trend_moment_model->update($_POST);
		} else if ($action == 'generate') {
			$result = $this->trend_moment_model->calculate(array( 'book_id' => $_POST['book_id'] ));
		} else if ($action == 'get_by_id') {
			$result = $this->trend_moment_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->trend_moment_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function get_view() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'variable') {
			$this->load->view( 'trend_moment_variable');
		} else if ($action == 'table') {
			$this->load->view( 'trend_moment_table');
		}
	}
}
