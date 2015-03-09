<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'user_type_id', 'email', 'fullname', 'passwd', 'address', 'thumbnail', 'is_active' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
			// default value
			$param['register_date'] = (isset($param['register_date'])) ? $param['register_date'] : $this->config->item('current_datetime');
			
            $insert_query  = GenerateInsertQuery($this->field, $param, USER);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, USER);
            $update_result = mysql_query($update_query) or die(mysql_error());
           
            $result['id'] = $param['id'];
            $result['status'] = '1';
            $result['message'] = 'Data berhasil diperbaharui.';
        }
       
        return $result;
    }

    function get_by_id($param) {
        $array = array();
		$param['auto_insert'] = (isset($param['auto_insert'])) ? $param['auto_insert'] : false;
       
        if (isset($param['id'])) {
            $select_query  = "SELECT * FROM ".USER." WHERE id = '".$param['id']."' LIMIT 1";
        } else if (isset($param['user_name'])) {
            $select_query  = "
				SELECT user.*
				FROM ".USER." user
				WHERE user_name = '".$param['user_name']."'
				LIMIT 1
			";
        } 
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
		if (count($array) == 0 && $param['auto_insert']) {
			$result = $this->update($param);
			$array = $this->get_by_id($result);
		}
		
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$param['field_replace']['user_type_title'] = 'UserType.title';
		
		$string_namelike = (!empty($param['namelike'])) ? "AND User.email LIKE '%".$param['namelike']."%'" : '';
		$string_user_type = (!empty($param['user_type_id'])) ? "AND User.user_type_id = '".$param['user_type_id']."'" : '';
		$string_koordinator = (isset($param['koordinator_id'])) ? "AND User.koordinator_id = '".$param['koordinator_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'fullname ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS User.*, UserType.title user_type_title
			FROM ".USER." User
			LEFT JOIN ".USER_TYPE." UserType ON UserType.id = User.user_type_id
			WHERE 1 $string_namelike $string_user_type $string_koordinator $string_filter
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
		$select_query = "SELECT FOUND_ROWS() TotalRecord";
		$select_result = mysql_query($select_query) or die(mysql_error());
		$row = mysql_fetch_assoc($select_result);
		$TotalRecord = $row['TotalRecord'];
		
		return $TotalRecord;
    }
	
	function get_array_koordinator_sales($param = array()) {
		$result = $this->get_array(array( 'user_type_id' => USER_ID_SALES ));
		return $result;
	}
	
	function get_tree($param = array()) {
		$param['koordinator_id'] = (isset($param['koordinator_id'])) ? $param['koordinator_id'] : 0;
		
		$result = array();
		$array_input = $this->get_array($param);
		foreach ($array_input as $key => $row) {
			$param_child['koordinator_id'] = $row['id'];
			$array_child = $this->get_tree($param_child);
			
			$array = array(
				'id' => $row['id'],
				'title' => $row['fullname']
			);
			if (count($array_child) > 0) {
				$array['child'] = $array_child;
			}
			$result[] = $array;
		}
		
		return $result;
	}
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".USER." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $param = array()) {
		$row = StripArray($row);
		
		if (!empty($row['thumbnail'])) {
			$row['thumbnail_link'] = base_url('static/upload/'.$row['thumbnail']);
		} else {
			$row['thumbnail_link'] = base_url('static/img/user1.png');
		}
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}
		
		return $row;
	}
	
	/*	Region Session */
	
	function is_login($param = array()) {
		$user = $this->get_session();
		$result = (count($user) > 0 && @$user['is_login']) ? true : false;
		
		if ($result && !empty($param['user_type_id'])) {
			if ($user['user_type_id'] != $param['user_type_id']) {
				$result = false;
			}
		}
		
		return $result;
	}
	
	// required_login(array( 'user_type_id' => 1 ))
	function required_login($param = array()) {
		$is_login = $this->is_login($param);
		if (!$is_login) {
			header("Location: ".base_url());
			exit;
		}
	}
	
	function set_session($user) {
		$user['is_login'] = true;
		
		// set session
		$_SESSION['user_login'] = $user;
		
		// set cookie
		$cookie_value = mcrypt_encode(json_encode($user));
		setcookie("user_login", $cookie_value, time() + (60 * 60 * 5), '/');
	}
	
	function get_session() {
		$user = (isset($_SESSION['user_login'])) ? $_SESSION['user_login'] : array();
		if (! is_array($user)) {
			$user = array();
		}
		
		// check from cookie
		if (count($user) == 0) {
			$user = $this->get_cookies();
		}
		
		// renew session if user already login
		if (count($user) > 0 && isset($user['is_login']) && $user['is_login']) {
			// set session
			$_SESSION['user_login'] = $user;
			
			// set cookie
			$cookie_value = mcrypt_encode(json_encode($user));
			setcookie("user_login", $cookie_value, time() + (60 * 60 * 5), '/');
		}
		
		return $user;
	}
	
	function get_cookies() {
		$user = array( 'is_login' => false );
		if (isset($_COOKIE["user_login"])) {
			$user = json_decode(mcrypt_decode($_COOKIE["user_login"]));
			$user = object_to_array($user);
			$user['is_login'] = true;
		}
		
		return $user;
	}
	
	function del_session() {
		// delete session
		if (isset($_SESSION['user_login'])) {
			unset($_SESSION['user_login']);
		}
		
		// delete cookie
		setcookie("user_login", '', time() + 0, '/');
	}
	
	/*	End Region Session */
}