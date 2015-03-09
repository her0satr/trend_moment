<?php

class book extends SYGAAS_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$this->load->view( 'book');
	}
	
	function grid() {
		$_POST['column'] = array( 'title', 'code' );
		$_POST['is_custom']  = '<button class="btn btn-xs btn-edit" data-original-title="Edit"><img src="'.base_url('static/img/icons/icon-edit.png').'" /></button> ';
		$_POST['is_custom'] .= '<button class="btn btn-xs btn-trend" data-original-title="Trend Moment"><img src="'.base_url('static/img/icons/icon-edit.png').'" /></button> ';
		$_POST['is_custom'] .= '<button class="btn btn-xs btn-delete" data-original-title="Hapus"><img src="'.base_url('static/img/icons/icon-delete.png').'" /></button> ';
		
		
		$array = $this->book_model->get_array($_POST);
		$count = $this->book_model->get_count();
		$grid = array( 'sEcho' => $_POST['sEcho'], 'aaData' => $array, 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count );
		
		echo json_encode($grid);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->book_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->book_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->book_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
}