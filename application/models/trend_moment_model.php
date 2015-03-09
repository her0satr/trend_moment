<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class trend_moment_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'book_id', 'waktu', 'tanggal', 'penjualan' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, TREND_MOMENT);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, TREND_MOMENT);
            $update_result = mysql_query($update_query) or die(mysql_error());
           
            $result['id'] = $param['id'];
            $result['status'] = '1';
            $result['message'] = 'Data berhasil diperbaharui.';
        }
       
        return $result;
    }

    function get_by_id($param) {
        $array = array();
       
        if (isset($param['id'])) {
            $select_query  = "
				SELECT trend_moment.*
				FROM ".TREND_MOMENT." trend_moment
				WHERE trend_moment.id = '".$param['id']."'
				LIMIT 1
			";
		}
		
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$param['field_replace']['x2'] = '';
		$param['field_replace']['xy'] = '';
		$param['field_replace']['tanggal_swap'] = 'trend_moment.tanggal';
		
		$string_book = (isset($param['book_id'])) ? "AND trend_moment.book_id = '".$param['book_id']."'" : "";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'waktu ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS trend_moment.*
			FROM ".TREND_MOMENT." trend_moment
			WHERE 1 $string_book $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
		
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, $param);
		}
		
        return $array;
    }

    function get_count($param = array()) {
		if (isset($param['query']) && isset($param['book_id'])) {
			$select_query = "SELECT COUNT(*) total FROM ".TREND_MOMENT." WHERE book_id = '".$param['book_id']."'";
		} else {
			$select_query = "SELECT FOUND_ROWS() total";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		$row = mysql_fetch_assoc($select_result);
		$total = $row['total'];
		
		return $total;
    }
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".TREND_MOMENT." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $param = array()) {
		$row = StripArray($row);
		
		// date
		if (isset($row['tanggal'])) {
			$row['tanggal_swap'] = ExchangeFormatDate($row['tanggal']);
		}
		
		// calculate
		$row['xy'] = $row['x2'] = 0;
		if (isset($row['penjualan']) && isset($row['waktu'])) {
			$row['xy'] = $row['penjualan'] * $row['waktu'];
		}
		if (isset($row['waktu'])) {
			$row['x2'] = $row['waktu'] * $row['waktu'];
		}
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}
		
		return $row;
	}
	
	function calculate($param = array()) {
		$array_data = $this->trend_moment_model->get_array(array( 'book_id' => $param['book_id'] ));
		
		// check waktu
		foreach ($array_data as $key => $row) {
			if ($key != $row['waktu']) {
				$result['status'] = 0;
				$result['message'] = 'Waktu tidak sesuai.';
				return $result;
			}
		}
		
		// get summary
		$result['summary_penjualan'] = $result['summary_waktu'] = $result['summary_xy'] = $result['summary_x2'] = 0;
		foreach ($array_data as $key => $row) {
			$result['summary_xy'] += $row['xy'];
			$result['summary_x2'] += $row['x2'];
			$result['summary_waktu'] += $row['waktu'];
			$result['summary_penjualan'] += $row['penjualan'];
		}
		
		// get average
		$result['average_waktu'] = $result['summary_waktu'] / count($array_data);
		$result['average_penjualan'] = $result['summary_penjualan'] / count($array_data);
		
		// get b
		$b_upper = ($result['average_penjualan'] * $result['summary_xy']) - ($result['summary_waktu'] * $result['summary_penjualan']);
		$b_lower = ($result['average_penjualan'] * $result['summary_x2']) - $result['summary_x2'];
		$result['b'] = $b_upper / $b_lower;
		
		// get a
		$result['a'] = ($result['summary_penjualan'] - ($result['b'] * $result['summary_waktu'])) / $result['average_penjualan'];
		
		// set status result
		$result['status'] = 1;
		
		return $result;
	}
}
