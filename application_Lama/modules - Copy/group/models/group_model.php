<?php

class Group_model extends CI_Model
{
  var $id;

  function __construct()
  {
    parent::__construct();
  }

  //user proses
  function save_data()
  {
    $this->db->trans_start();
    $this->insert_user();
    $this->db->trans_complete();

    if($this->db->trans_status() === FALSE){
      return FALSE;
    }
  }

  function insert_user()
  {
    $this->db->select('1')->from('USERS')->where('ID', $this->input->post('id'));
    $rs   = $this->db->get()->row_array();

    $this->db->select('PASSWD,SALT,ICON')->from('USERS')->where('ID', $this->input->post('id'));
    $get  = $this->db->get()->row_array();

    $salt = strtotime(date('Y-m-d H:i:s'));

    //untuk proses edit
    if($get){
      //jika password yg d POST sama dengan password d table, maka password tidak diupdate
      //if($this->input->post('passwd') == '')
      $password = $this->input->post('passwd');
      if(isset($password)){
        $passwd = $get['PASSWD'];
        $salt   = $get['SALT'];
      }
      //jika password yg d POST tidak sama dengan password d table, maka password diupdate
      else $passwd = sha1($salt.$this->input->post('passwd'));
    }
    else $passwd = sha1($salt.$this->input->post('passwd'));
    if($this->input->post('guser') == ''){
      $group = '2';
    }
    else $group = $this->input->post('guser');
    if($this->input->post('id_skpd') == ''){
      $id_skpd = '0';
    }
    else $id_skpd = $this->input->post('id_skpd');
    
    //icon
    //jika mode = new
    if($this->input->post('mode') == 'new')
    {
      if(isset($_POST['icon'])){//ada image
        $icon = $this->input->post('icon');
      }
      else $icon = NULL;//g ada image
    }
    //jika mode = edit
    else
    {
      if(isset($_POST['icon'])){//ganti image
        $icon = $this->input->post('icon');//image baru
        $path = realpath( FCPATH.'assets/img/user/');//hapus image lama dr Directory
        @unlink($path."/".$get['ICON']);
      }
      else $icon = $get['ICON'];//tanpa ubah image
    }
    

    $data    = array('GROUP_ID'=> $group,
      'NAME'    => $this->input->post('name'),
      'USERNAME'=> $this->input->post('username'),
      'SALT'    => $salt,
      'PASSWD'  => $passwd,
      'EMAIL'   => $this->input->post('email'),
      'SKPD_ID' => $id_skpd,
      'ICON' => $icon,
      'STATUS'  => '1'
    );

    if($rs){
      $this->db->where('ID', $this->input->post('id'));
      $this->db->update('USERS', $data);
    }
    else
    {
      $insert = $this->db->insert('USERS', $data);
      /*if(isset($_SERVER['HTTP_X_FILE_NAME'])){
      print_r($_SERVER['HTTP_X_FILE_NAME']);*/
      /*$path = realpath( FCPATH.'upload/');
        $config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|png|jpeg|ico';
				$config['max_size']	= '1000';
                $config['remove_spaces']	= TRUE;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('image'))
                {
                    $this->upload->display_errors();
                    return FALSE;
                }
                else
                {
                   $dataz = $this->upload->data();
                   $var = array('ICON' => $dataz['file_name']);
                   $merge = array_merge($data,$var);
                   $insert = $this->db->insert('USERS', $merge);
                   //return TRUE;
                }*/
    }
  }

  function cek_duplikasi_username($username)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    if($id) $this->db->where('ID <>', $id);
    $this->db->where('USERNAME', $username);
    $this->db->select('COUNT(ID) DUP');
    $rs = $this->db->get('USERS')->row_array();

    return (integer)$rs['DUP'] === 0;
  }

  function cek_old_password($oldpasswd)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    $this->db->where('ID', $id);
    $this->db->select('SALT,PASSWD');
    $rs            = $this->db->get('USERS')->row_array();

    $post_password = sha1($rs['SALT'].$oldpasswd);

    if($post_password == $rs['PASSWD']) return TRUE;
    else return FALSE;

  }

  function cek_duplikasi_email($email)
  {
    //cek apakah input email valid
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $id = $this->input->post('id') ? $this->input->post('id') : NULL;

      if($id) $this->db->where('ID <>', $id);
      $this->db->where('EMAIL', $email);
      $this->db->select('COUNT(ID) DUP');
      $rs = $this->db->get('USERS')->row_array();

      return (integer)$rs['DUP'] === 0;
    }
    else return (integer)$email === 1;
  }

  function get_data_by_id($id)
  {
    $this->db->select('a.id, a.group_id, a.name, a.username, a.salt, a.passwd, a.email,
      a.skpd_id, a.status, a.icon, s.nama_skpd');
    $this->db->from('users a');
    $this->db->join('v_skpd s', 's.id_skpd = a.skpd_id','left');
    $this->db->where('a.id', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_daftar()
  {

    $this->db->select('a.id, a.group_id, a.name, a.username, a.email, a.skpd_id, a.status, s.nama_skpd, g.name as gname');
    $this->db->from('users a');
    $this->db->join('v_skpd s', 's.id_skpd = a.skpd_id','left');
    $this->db->join('groups g', 'g.id = a.group_id','left');
    $this->db->order_by('a.username');

    $result = $this->db->get()->result_array();
    return $result;

  }

  //group proses
  function save_group()
  {
    $this->db->trans_start();
    //$this->id =
    $this->insert_group();
    $this->db->trans_complete();

    if($this->db->trans_status() === FALSE){
      return FALSE;
    }
  }

  function insert_group()
  {
    $this->db->select('1')->from('GROUPS')->where('ID', $this->input->post('id'));
    $rs      = $this->db->get()->row_array();

    //id group
    $idg     = $this->max_id_group();
    $last_id = $idg['GEN_ID'];
    //print_r($last_id);
    if($rs) $id = $this->input->post('id');
    else $id   = $last_id;

    $data = array('ID'         => $id,
      'NAME'       => $this->input->post('gname'),
      'DESCRIPTION'=> $this->input->post('gdesc'),
      'STATUS'     => 1
    );

    if($rs){
      $this->db->where('ID', $this->input->post('id'));
      $this->db->update('GROUPS', $data);
      //delete menu previlage sesuai ID
      $this->delete_group_previlage($id);
    }
    else
    {
      $insert = $this->db->insert('GROUPS', $data);
    }

    foreach ($this->input->post('parent') as $v)
	{
		$insert_privilage = array('GROUP_ID'=> $id,
		  'ID'=> $v,
		  'AKSI'=> '3'
		);
		$this->insert_privilage($insert_privilage);
	}
  
  /* $anak 	= array_diff($this->input->post('child'),array(''));
	$isi 	= array_diff($this->input->post('val'),array('')); */
	
	$anak 	= $this->input->post('child');
	$isi 	= $this->input->post('val');
		
	for($i=0;$i<count($isi);$i++){
		if($isi[$i]!=''){
			$insert_privilage = array('GROUP_ID'=> $id,
			  'ID'=> $anak[$i],
			  'AKSI'=> $isi[$i]
			);
			$this->insert_privilage($insert_privilage);
		}
	}
    
    /*$selected = $this->input->post('Selected') ? $this->input->post('Selected') : NULL;

    if($selected){
      foreach($selected as $key => $parent){

        //insert parent
        if(isset($parent['akses'])){
          $akses = $parent['akses'];
        }
        else $akses            = '0';

        $insert_privilage = array('GROUP_ID'=> $id,//id group yg baru dibuat
          'ID'=> $parent['parent_id'],//menu id
          'AKSI'=> $akses
        );

        $this->insert_privilage($insert_privilage);

        //insert menu privilage
        if(isset($parent['child'])){
          $p_child = $parent['child'];

          //unset menu yg bernilai false
          foreach($p_child as $child => $keys){
            if($keys['is_checked'] == 'false'){
              unset($p_child[$child]);
            }
          }
          //print_r($p_child);

          //insert BEGIN, $p_child baru
          foreach($p_child as $child => $keys){
            if(isset($keys['akses'])){
              $akses = $keys['akses'];
            }
            else $akses            = '0';

            $insert_privilage = array('GROUP_ID'=> $id,//id group yg baru dibuat
              'ID'=> $keys['child_id'],//menu id
              'AKSI'=> $akses
            );

            $this->insert_privilage($insert_privilage);

          }
        }
      }
    }*/
  }

  function insert_privilage($data)
  {
    $this->db->trans_start();
    $this->db->insert('M_PRIVILEGE',$data);
    $this->db->trans_complete();
  }

  function delete_group_previlage($id)
  {
    $this->db->where('GROUP_ID',$id);
    $this->db->delete('M_PRIVILEGE');
  }

  function cek_duplikasi_groupname($groupname)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    if($id) $this->db->where('ID <>', $id);
    $this->db->where('NAME', $groupname);
    $this->db->select('COUNT(ID) DUP');
    $rs = $this->db->get('GROUPS')->row_array();

    return (integer)$rs['DUP'] === 0;
    return TRUE;
  }

  function max_id_group()
  {

    $sql    = "SELECT NEXT VALUE FOR G_ID_GROUPS FROM RDB\$DATABASE";
    $result = $this->db->query($sql)->row_array();

    return $result;

  }

  function get_group_by_id($id)
  {
    $this->db->from('groups a');
    $this->db->where('a.ID', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_daftar_group()
  {

    $this->db->select('*');
    $this->db->from('groups');
    $this->db->order_by('NAME');

    $result = $this->db->get()->result_array();
    return $result;

  }

  //menu group
  //menu group
  function get_group_menu($id = "")
  {
    $this->db->where('parent_id','0');
    $this->db->where('ID !=','1');
    $this->db->order_by('ORDER_BY','ASC');
    //$this->db->where('aktif','1');
    $result = $this->db->get('menu');
    $x      = 0;
    $menu   = array();
    $is_checked = "0";
    $act        = "0";
    $res_menu   = $result->result();
    foreach($res_menu as $hasil){
      if($id !== ""){
        $check      = $this->get_one('GROUP_ID','m_privilege',"GROUP_ID = '".$id."' AND ID = '".$hasil->ID."'");
        $is_checked = $check == ""?"0":"1";
        $act        = $this->get_one('AKSI','m_privilege',"GROUP_ID = '".$id."' AND ID = '".$hasil->ID."'");
      }
      $menu[$x]['ID'] = $hasil->ID;
      $menu[$x]['TITLE'] = $hasil->TITLE;
      $menu[$x]['LINK'] = $hasil->LINK;
      $menu[$x]['AKTIF'] = $hasil->AKTIF;
      $menu[$x]['is_checked'] = $is_checked;
      $menu[$x]['akses'] = $act;
      $hasil_child = $this->get_child_menu($hasil->ID,$id,$this->uri->segment('2'));//ambil menu anak
      if($hasil_child){ 
		$menu[$x]['child'] = $hasil_child;
	  }	
      else{ 
		$menu[$x]['child'] = array();
	  }	
      $x++;
    }
    return $menu;
  }

	function get_child_menu($id,$check)
	{
		$is_checked = "0";
		$act        = "0";
		$embuh      = array();
		$data_final = array();
		$x      = 0;
		$menu   = array();

		$this->db->where('parent_id',$id);
		$this->db->order_by('ORDER_BY','ASC');
		$result = $this->db->get('menu');
		if($result->num_rows() > 0){
			$row = $result->result();
			foreach($row as $hasil){
				if($check !== ""){
					$checked	= $this->get_one('GROUP_ID','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$hasil->ID."'");
					$is_checked = $checked == ""?"0":"1";
					$act        = $this->get_one('AKSI','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$hasil->ID."'");
					$data_check = array('is_checked'=> $is_checked,'akses'     => $act);

					$data_final = array_merge($row,$data_check);
					$embuh[] 	= $data_final;
					$row 		= $embuh;
				}

				$menu[$x]['ID'] = $hasil->ID;
				$menu[$x]['TITLE'] = $hasil->TITLE;
				$menu[$x]['LINK'] = $hasil->LINK;
				$menu[$x]['AKTIF'] = $hasil->AKTIF;
				$menu[$x]['PARENT_ID'] = $hasil->PARENT_ID;
				$menu[$x]['ORDER_BY'] = $hasil->ORDER_BY;
        $menu[$x]['is_checked'] = $is_checked;
        $menu[$x]['akses'] = $act;
				$hasil_child = $this->get_child_menu2($hasil->ID,$id,$this->uri->segment('2'));//ambil menu anak
				if($hasil_child){ 
					$menu[$x]['child'] = $hasil_child;
				}	
				else{ 
					$menu[$x]['child'] = array();
				}	
				$x++;
			}
		  return $menu;
		}
		else return false;
	}
	
	function get_child_menu2($id,$check)
	{
		$is_checked = "0";
		$act        = "0";
		$embuh      = array();
		$data_final = array();
		$x      = 0;
		$menu   = array();

		$this->db->where('parent_id',$id);
		$this->db->order_by('ORDER_BY','ASC');
		$result = $this->db->get('menu');
		if($result->num_rows() > 0){
			$row = $result->result();
			foreach($row as $hasil){
				if($check !== ""){
					$checked	= $this->get_one('GROUP_ID','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$hasil->ID."'");
					$is_checked = $checked == ""?"0":"1";
					$act        = $this->get_one('AKSI','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$hasil->ID."'");
					$data_check = array('is_checked'=> $is_checked,'akses'     => $act);

					$data_final = array_merge($row,$data_check);
					$embuh[] 	= $data_final;
					$row 		= $embuh;
				}

				$menu[$x]['ID'] = $hasil->ID;
				$menu[$x]['TITLE'] = $hasil->TITLE;
				$menu[$x]['LINK'] = $hasil->LINK;
				$menu[$x]['AKTIF'] = $hasil->AKTIF;
				$menu[$x]['PARENT_ID'] = $hasil->PARENT_ID;
				$menu[$x]['ORDER_BY'] = $hasil->ORDER_BY;
        $menu[$x]['is_checked'] = $is_checked;
        $menu[$x]['akses'] = $act;
				$hasil_child = $this->get_child_menu3($hasil->ID,$id,$this->uri->segment('2'));//ambil menu anak
				if($hasil_child){ 
					$menu[$x]['child'] = $hasil_child;
				}	
				else{ 
					$menu[$x]['child'] = array();
				}	
				$x++;
			}
		  return $menu;
		}
		else return false;
	}
	
	function get_child_menu3($id,$check)
	{
		$is_checked = "0";
		$act        = "0";
		$embuh      = array();
		$data_final = array();
		$x      = 0;
		$menu   = array();

		$this->db->where('parent_id',$id);
		$this->db->order_by('ORDER_BY','ASC');
		$result = $this->db->get('menu');
		if($result->num_rows() > 0){
			$row = $result->result();
			foreach($row as $hasil){
				if($check !== ""){
					$checked	= $this->get_one('GROUP_ID','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$hasil->ID."'");
					$is_checked = $checked == ""?"0":"1";
					$act        = $this->get_one('AKSI','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$hasil->ID."'");
					$data_check = array('is_checked'=> $is_checked,'akses'     => $act);

					$data_final = array_merge($row,$data_check);
					$embuh[] 	= $data_final;
					$row 		= $embuh;
				}

				$menu[$x]['ID'] = $hasil->ID;
				$menu[$x]['TITLE'] = $hasil->TITLE;
				$menu[$x]['LINK'] = $hasil->LINK;
				$menu[$x]['AKTIF'] = $hasil->AKTIF;
				$menu[$x]['PARENT_ID'] = $hasil->PARENT_ID;
				$menu[$x]['ORDER_BY'] = $hasil->ORDER_BY;
        $menu[$x]['is_checked'] = $is_checked;
        $menu[$x]['akses'] = $act;
				$hasil_child = $this->get_child_menu4($hasil->ID,$id,$this->uri->segment('2'));//ambil menu anak
				if($hasil_child){ 
					$menu[$x]['child'] = $hasil_child;
				}	
				else{ 
					$menu[$x]['child'] = array();
				}	
				$x++;
			}
		  return $menu;
		}
		else return false;
	}
	
	function get_child_menu4($id,$check)
	{
		$is_checked = "0";
		$act        = "0";
		$embuh      = array();
		$data_final = array();
		$x      = 0;
		$menu   = array();

		$this->db->where('parent_id',$id);
		$this->db->order_by('ORDER_BY','ASC');
		$result = $this->db->get('menu');
		if($result->num_rows() > 0){
			$row = $result->result();
			foreach($row as $hasil){
				if($check !== ""){
					$checked	= $this->get_one('GROUP_ID','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$hasil->ID."'");
					$is_checked = $checked == ""?"0":"1";
					$act        = $this->get_one('AKSI','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$hasil->ID."'");
					$data_check = array('is_checked'=> $is_checked,'akses'     => $act);

					$data_final = array_merge($row,$data_check);
					$embuh[] 	= $data_final;
					$row 		= $embuh;
				}

				$menu[$x]['ID'] = $hasil->ID;
				$menu[$x]['TITLE'] = $hasil->TITLE;
				$menu[$x]['LINK'] = $hasil->LINK;
				$menu[$x]['AKTIF'] = $hasil->AKTIF;
				$menu[$x]['PARENT_ID'] = $hasil->PARENT_ID;
				$menu[$x]['ORDER_BY'] = $hasil->ORDER_BY;
        $menu[$x]['is_checked'] = $is_checked;
        $menu[$x]['akses'] = $act;
				$hasil_child = $this->get_child_menu5($hasil->ID,$id,$this->uri->segment('2'));//ambil menu anak
				if($hasil_child){ 
					$menu[$x]['child'] = $hasil_child;
				}	
				else{ 
					$menu[$x]['child'] = array();
				}	
				$x++;
			}
		  return $menu;
		}
		else return false;
	}
	
  
  function get_child_menu5($id,$check)
  {
    $is_checked = "0";
    $act        = "0";
    $embuh      = array();
    $data_final = array();
    $this->db->where('parent_id',$id);
    $this->db->order_by('ORDER_BY','ASC');
    $result = $this->db->get('menu');
    if($result->num_rows() > 0){
      $row = $result->result_array();      
      return $row;
    }
    else return false;
  }

  function is_checked($id)
  {
    $menu = $this->get_group_menu($id);
    return $menu;
  }

  function get_one($field = '',$table = '',$where = '')
  {
    $sql    = "SELECT ".$field." FROM ".$table." WHERE ".$where;
    $result = $this->db->query("SELECT ".$field." FROM ".$table." WHERE ".$where);
    if($result->num_rows() > 0){
      $row = $result->row_array();
      if($row == false) return 0;
      else return $row[$field];
    }
    else return 0;
  }

  function update_passlost($data_update,$uid)
  {
    $this->db->where('id',$uid);
    $result = $this->db->update('USERS',$data_update);
    return $result;
  }

  function act()
  {
    $aksi   = $this->input->post('act');
    $status = $this->input->post('status');
    $type   = $this->input->post('type');
    $id     = $this->input->post('id');

    if($aksi == 'delete'){
      $proses = $this->delete($id,$type);
    }
    elseif($aksi == 'update'){
      $proses = $this->update($id,$type,$status);
    }

    $this->db->trans_start();
    $proses;
    $this->db->trans_complete();
    //print_r($proses);
    if($this->db->trans_status() === FALSE){
      return FALSE;
    }
  }

  function delete($id,$type)
  {
    $this->db->where('ID', $id);
    $this->db->delete($type);
  }

  function update($id,$type,$status)
  {
    if($status == '1') $newstatus = '0';
    elseif($status == '0') $newstatus = '1';
    $data      = array(
      'STATUS'=> $newstatus,
    );

    $this->db->where('ID', $id);
    $this->db->update($type, $data);
  }

  //menu backend
  function get_backend_menu()
  {
    //ambil semua menuid bersama hasil dari menu anaknya
    $this->db->from('MENU a');
    $this->db->from('M_PRIVILEGE b');
    $this->db->where('a.AKTIF','1');
    $this->db->where('a.PARENT_ID','0');
    $this->db->where('b.GROUP_ID',$this->session->userdata('group'));
    $this->db->where('a.ID','b.ID',false);
    $this->db->order_by('ORDER_BY','ASC');
    $result = $this->db->get();
    $x      = 0;
    $menu   = array();
    $result = $result->result();
    foreach($result as $hasil){
      $menu[$x]['menu_id'] = $hasil->ID;
      $menu[$x]['menu_title'] = $hasil->TITLE;
      $menu[$x]['menu_link'] = $hasil->LINK;
      $hasil_child = $this->get_child_menu_admin($hasil->ID);//ambil menu anak
      if($hasil_child) $menu[$x]['child'] = $hasil_child;
      else $menu[$x]['child'] = array();
      $x++;
    }
    return $menu;
  }

  function get_child_menu_admin($id)
  {
    //ambil menu child dari table site menu
    $this->db->from('MENU a');
    $this->db->from('M_PRIVILEGE b');
    $this->db->where('a.AKTIF','1');
    $this->db->where('a.PARENT_ID',$id);
    $this->db->where('b.GROUP_ID',$this->session->userdata('group'));
    $this->db->where('a.ID','b.ID',false);
    $this->db->order_by('ORDER_BY','ASC');
    $result = $this->db->get();
    if($result->num_rows() > 0) return $result->result_array();
    else return false;
  }

  function count_($type)
  {
    $result = $this->db->query("SELECT COUNT(*) FROM ".$type);
    if($result->num_rows() > 0){
      $row = $result->row_array();
      return $row['COUNT'];
    }
    else return "";
  }

  function _cek_member_group($id)
  {
    $this->db->where('GROUP_ID', $id);
    $this->db->select('COUNT(ID) UG');
    $rs = $this->db->get('USERS')->row_array();

    return $rs['UG'];
    //print_r($rs);
  }

  //Menu
  function get_menu_by_id($id)
  {
    $this->db->from('MENU');
    $this->db->where('ID', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_parent_menu()
  {
    $this->db->from('MENU');
    $this->db->where('PARENT_ID', '0');
    $result = $this->db->get()->result_array();

    return $result;
  }

  function update_arr($id,$data)
  {
    $result = $this->db->query("UPDATE MENU a SET a.ORDER_BY = '".$id."' WHERE a.ID = '".$data."'");
  }
  //menu proses
	function save_menu()
	{
		$this->db->trans_start();
		//$this->id =
		$this->insert_menu();
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			return FALSE;
		}
	}

	function insert_menu()
	{
		if($this->input->post('mode') == 'new') 
			$parent = $this->input->post('ids');//add child
		else 
			$parent = $this->input->post('parent'); //child

		$this->db->select('1')->from('MENU')->where('ID', $this->input->post('ids'));
		$rs     = $this->db->get()->row_array();
		
		$data   = array('PARENT_ID'=> $parent,
			'TITLE'    => $this->input->post('n_menu'),
			'LINK'     => $this->input->post('links'),
			'AKTIF'    => $this->input->post('aktif')/*,
			'ORDER_BY'    => '0'*/
		);
		
		if($rs && $this->input->post('mode') == 'edit'){
			//print_r($data);
			$this->db->where('ID', $this->input->post('ids'));
			$this->db->update('MENU', $data);
		}
		else
		{
			//print_r($data);
			$insert = $this->db->insert('MENU', $data);
		}
	}
  
  function get_data_parent_by_id($id)
  {
    $this->db->from('MENU');
    $this->db->where('ID', $id);
    $result = $this->db->get()->result_array();

    return $result;
  }
  
  //Parent
  function get_parent_by_id($id)
  {
    $this->db->from('MENU');
    $this->db->where('PARENT_ID', $id);
    $this->db->where('ID !=', '1');
    $this->db->order_by('ORDER_BY');
    $result = $this->db->get()->result_array();

    return $result;
  }

 
  function del($id)
  {
    //
    $this->db->trans_start();
    $this->deleted($id);
    $this->db->trans_complete();

    if($this->db->trans_status() === FALSE){
      return FALSE;
    }
  }
  
  function simpan_menu_form()
	{
		$this->db->trans_start();
		if(($this->input->post('id'))) {
			$d_array = $this->input->post('id');
			$result = json_decode($d_array);
			$a=1;
			foreach($result as $var => $value) {
				$update_id = $value->id;
				//mysql_query("UPDATE MENU SET PARENT_ID = 0 WHERE ID =  '$update_id'");
				$this->db->query("UPDATE MENU a SET a.PARENT_ID = '0',a.ORDER_BY = '".$a."' WHERE a.ID = '".$update_id."'");
				if(!empty($value->children)){
					$b=1;
					foreach ($value->children as $vchild)
					{
						$update_id = $vchild->id;
						$parent_id = $value->id;
						//mysql_query("UPDATE MENU SET PARENT_ID ='$parent_id' WHERE ID =  '$update_id'");
						$this->db->query("UPDATE MENU a SET a.PARENT_ID = '".$parent_id."',a.ORDER_BY = '".$b."' WHERE a.ID = '".$update_id."'");
						if(!empty($vchild->children)){
							$c=1;
							foreach ($vchild->children as $vchild1)
							{
								$update_id = $vchild1->id;
								$parent_id = $vchild->id;
								//mysql_query("UPDATE MENU SET PARENT_ID ='$parent_id' WHERE ID =  '$update_id'");
								$this->db->query("UPDATE MENU a SET a.PARENT_ID = '".$parent_id."',a.ORDER_BY = '".$c."' WHERE a.ID = '".$update_id."'");
								if(!empty($vchild1->children))
								{
									$d=1;
									foreach ($vchild1->children as $vchild2)
									{
										$update_id = $vchild2->id;
										$parent_id = $vchild1->id;
										//mysql_query("UPDATE MENU SET PARENT_ID ='$parent_id' WHERE ID =  '$update_id'");
										$this->db->query("UPDATE MENU a SET a.PARENT_ID = '".$parent_id."',a.ORDER_BY = '".$d."' WHERE a.ID = '".$update_id."'");
										if(!empty($vchild2->children)){
											$e=1;
											foreach ($vchild2->children as $vchild3)
											{
												$update_id = $vchild3->id;
												$parent_id = $vchild2->id;
												$this->db->query("UPDATE MENU a SET a.PARENT_ID = '".$parent_id."',a.ORDER_BY = '".$e."' WHERE a.ID = '".$update_id."'");
												//mysql_query("UPDATE MENU SET PARENT_ID ='$parent_id' WHERE ID =  '$update_id'");
												$e++;
											}
											$d++;
										}
									}
								}
								$c++;
							}
						}
						$b++;
					}
				}
				$a++;
			}
		}
		$this->db->trans_complete();
	}

  function deleted($id)
  {
    
    $this->db->select('PARENT_ID');
		$this->db->from('MENU');
		$this->db->where('PARENT_ID', $id);
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['PARENT_ID'];
			}
		}
    if($hasil)
		{
			$this->db->delete('MENU', array('PARENT_ID'=> $id));
		}
    
    $this->db->delete('MENU', array('ID'=> $id));
  }

  function separator($id)
  {
    //insert separator ke menu
    $this->db->insert('MENU', array('PARENT_ID'=> $id,'TITLE'    => '---','AKTIF'    => '1'));

    //get last insert id menu
    $sql    = "SELECT GEN_ID( G_ID_MENU, 0 ) FROM RDB\$DATABASE";
    $result = $this->db->query($sql)->row_array();
    //print_r($result);
    $last_id= $result['GEN_ID'];

    //get id group
    $this->db->select('ID');
    $this->db->from('GROUPS');

    $group  = $this->db->get()->result_array();
    //print_r($group);
    foreach($group as $group){
      //print_r($group['ID']);
      $insert_privilage = array('GROUP_ID'=> $group['ID'],//id group
        'ID'=> $last_id,//menu id separator
        'AKSI'=> '0'
      );
      $this->insert_privilage($insert_privilage);
    }
  }
  
  function update_icon()
  {
    $this->db->select('ICON')->from('USERS')->where('ID', $this->input->post('id'));
    $get  = $this->db->get()->row_array();
    
    if($get)
    {
      $path = realpath( FCPATH.'assets/img/user/');
      @unlink($path."/".$get['ICON']);
      $this->db->where('ID', $this->input->post('id'));
      $this->db->update('USERS', array('ICON' => NULL));
    }
  }

}