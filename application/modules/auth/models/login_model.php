<?php

class Login_model extends CI_Model
{

  var $id;

  function __construct()
  {
    parent::__construct();
    $this->load->library('encrypt');
  }

  function check_user($username, $password)
  {
    $query = $this->db->get_where('USERS', array('USERNAME'=>$username));
    $username = $this->get_one('USERNAME','USERS',"USERNAME = '".$username."'");

    if($username)
    {

      $result = $query->row_array();
      //jika status user aktif
      if($result["STATUS"] != '0')
      {
        //cek group admin
        $gStatus = $this->get_one('STATUS','GROUPS',"ID = ".$result['GROUP_ID']);
        if($gStatus != '0')
        {
          if($result["PASSWD"] == sha1($result["SALT"].$password))
          {
            return 2;
          }
          else
          {
            return false;
          }
        }
        else return 3;//group tdk aktif

      }
      else return 1;
    }
    else
    {
      return false;//username tdk ada
    }
  }

  function get_nama($username)
  {
    $this->db->select('*');
    $this->db->join('v_skpd s', 's.id_skpd = u.skpd_id', 'left');
    $this->db->where('username', $username);
    $nama = $this->db->get('users u');
    return $nama->row_array();
  }

  function get_status($tahun)
  {
    $this->db->select('coalesce(cast(STATUS_KINI as varchar(20)), cast(STATUS_AWAL as varchar(20)), cast( \'-\' as varchar(20))) status');
    $this->db->where('TAHUN', $tahun);
    $status = $this->db->get('TAHUN_ANGGARAN');
    return $status->row_array();
  }

  function get_thn()
  {
    $this->db->select('TAHUN');
    $this->db->order_by('TAHUN desc');
    $query = $this->db->get('TAHUN_ANGGARAN');

    foreach($query->result_array() as $row)
    {
      $result[$row['TAHUN']] = $row['TAHUN'];
    }
    return $result;
  }

  function get_sts($tahun = 0)
  {
		$result = false;
		$this->db->select ('STATUS');
		$this->db->where('TAHUN', $tahun); 
		$status = $this->db->get('STATUS_ANGGARAN');
		foreach ($status->result_array() as $row)
		{
			$result[$row['STATUS']] = $row['STATUS'];
		}
		return $result;
  }

  function cek_status($tahun)
  {
    $this->db->where('TAHUN',$tahun);
    $cek = $this->db->get('TAHUN_ANGGARAN')->row_array();
    return $cek['STATUS_KINI'];
  }

  function get_pemda()
  {
    $this->db->select('NAMA_PEMDA');
    $pemda = $this->db->get('PEMDA');
    return $pemda->row_array();
  }

  /*function get_level_akses($user)
  {
  $this->db->where('username', $user);
  $akses = $this->db->get('operator')->row_array();
  return $akses;
  }*/

  function get_level_akses($uri)
  {
    $akses = $this->get_all_array('p.AKSI','MENU m, M_PRIVILEGE p',"m.ID = p.ID AND p.GROUP_ID = '".$this->session->userdata('group')."' AND m.LINK ='".$uri."' ");
    //print_r($akses['0']->AKSI);
    if($akses)
    {
      return $akses['0']->AKSI;
    }

  }

  //user proses
  function save_data()
  {
    $this->db->trans_start();
    //$this->id =
    $this->insert_user();
    $this->db->trans_complete();

    if($this->db->trans_status() === FALSE)
    {
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

    //print_r($get['SALT']);
    //untuk proses edit
    if($get)
    {
      //jika password yg d POST sama dengan password d table, maka password tidak diupdate
      if($this->input->post('passwd') == '')
      {
        $passwd = $get['PASSWD'];
        $salt   = $get['SALT'];
      }
      //jika password yg d POST tidak sama dengan password d table, maka password diupdate
      else $passwd = sha1($salt.$this->input->post('passwd'));
    }
    else $passwd = sha1($salt.$this->input->post('passwd'));

    //icon
    //jika mode = new
    if($this->input->post('new'))
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
      else
      {
        $icon = $get['ICON'];//tanpa ubah image
        print_r($get['ICON']);
      }
    }

    $data   = array('GROUP_ID'=> $this->input->post('guser'),
      'NAME'    => $this->input->post('name'),
      'USERNAME'=> $this->input->post('username'),
      'SALT'    => $salt,
      'PASSWD'  => $passwd,
      'EMAIL'   => $this->input->post('email'),
      'SKPD_ID' => $this->input->post('id_skpd'),
      'ICON' => $icon,
      'STATUS'  => '1'
    );

    if($rs)
    {
      $this->db->where('ID', $this->input->post('id'));
      $this->db->update('USERS', $data);
    }
    else
    {
      $insert = $this->db->insert('USERS', $data);
    }
  }

  function cek_old_password($oldpasswd)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    //if ($id) $this->db->where('ID <> ', $id);
    $this->db->where('ID', $id);
    $this->db->select('SALT,PASSWD');
    $rs            = $this->db->get('USERS')->row_array();

    $post_password = sha1($rs['SALT'].$oldpasswd);

    if($post_password == $rs['PASSWD']) return TRUE;
    else return FALSE;

    //return (integer)$rs['DUP'] === 0;
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

  function cek_duplikasi_email($email)
  {
    //cek apakah input email valid
    if(filter_var($email, FILTER_VALIDATE_EMAIL))
    {
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

    if($this->db->trans_status() === FALSE)
    {
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

    if($rs)
    {
      $this->db->where('ID', $this->input->post('id'));
      $this->db->update('GROUPS', $data);
      //delete menu previlage sesuai ID
      $this->delete_group_previlage($id);
    }
    else
    {
      $insert = $this->db->insert('GROUPS', $data);
      //$this->id = $insert->lastInsertId;
    }

    $selected = $this->input->post('Selected') ? $this->input->post('Selected') : NULL;

    if($selected)
    {
      foreach($selected as $key => $parent){

        //insert parent
        if(isset($parent['akses']))
        {
          $akses = $parent['akses'];
        }
        else $akses            = '0';
        $insert_privilage = array('GROUP_ID'=> $id,//id group yg baru dibuat
          'ID'=> $parent['parent_id'],//menu id
          'AKSI'=> $akses
        );
        $this->insert_privilage($insert_privilage);

        //insert menu privilage
        if(isset($parent['child']))
        {
          $p_child = $parent['child'];

          //unset menu yg bernilai false
          foreach($p_child as $child => $keys)
          {
            if($keys['is_checked'] == 'false')
            {
              unset($p_child[$child]);
            }
          }
          //print_r($p_child);

          //insert BEGIN, $p_child baru
          foreach($p_child as $child => $keys)
          {
            if(isset($keys['akses']))
            {
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
    }
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
    /*$this->db->select('MAX(ID) MAX_ID');
    $rs = $this->db->get('GROUPS')->row_array();
    return $rs;*/
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

    $result = $this->db->get()->result_array();
    return $result;
  }

  //menu group
  function get_group_menu($id = "")
	{

		$this->db->from('menu a');
		$this->db->join('m_privilege b','a.id=b.id');
		$this->db->where('a.parent_id','0');
		$this->db->where('a.aktif','1');
		$this->db->where('b.GROUP_ID',$id);
		$this->db->order_by('a.ORDER_BY');
		$result = $this->db->get();
		$x      = 0;
		$menu   = array();
		$is_checked = "0";
		$act        = "0";
		$res_menu   = $result->result();

		foreach($res_menu as $hasil)
		{
			if($id !== "")
			{
				$check      = $this->get_one('GROUP_ID','m_privilege',"GROUP_ID = '".$id."' AND ID = '".$hasil->ID."'");
				$is_checked = $check == ""?"0":"1";
				$act        = $this->get_one('AKSI','m_privilege',"GROUP_ID = '".$id."' AND ID = '".$hasil->ID."'");
			}
			$menu[$x]['ID'] = $hasil->ID;
			$menu[$x]['TITLE'] = $hasil->TITLE;
			$menu[$x]['LINK'] = $hasil->LINK;
			$menu[$x]['is_checked'] = $is_checked;
			$menu[$x]['akses'] = $act;
			$hasil_child = $this->get_child_menu($hasil->ID,$id);//ambil menu anak
			if($hasil_child) $menu[$x]['child'] = $hasil_child;
			else $menu[$x]['child'] = array();
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
    $this->db->where('parent_id',$id);
    $this->db->where('aktif','1');
    $this->db->order_by('ORDER_BY','ASC');
    $result = $this->db->get('menu');

    if($result->num_rows() > 0)
    {
      $row = $result->result_array();
      if($check !== "")
      {
        foreach($row as $sub)
        {
          $check      = $this->get_one('GROUP_ID','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$sub['ID']."'");
          $is_checked = $check == ""?"0":"1";
          $act        = $this->get_one('AKSI','m_privilege',"GROUP_ID = '".$check."' AND ID = '".$sub['ID']."'");
          $data_check = array('is_checked'=> $is_checked,'akses'     => $act);
          $data_final = array_merge($sub,$data_check);
          $embuh[] = $data_final;
        }
        //die(print_r($embuh));
        $row = $embuh;
      }
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
    if($result->num_rows() > 0)
    {
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

    if($aksi == 'delete')
    {
      $proses = $this->delete($id,$type);
    }
    elseif($aksi == 'update')
    {
      $proses = $this->update($id,$type,$status);
    }

    $this->db->trans_start();
    $proses;
    $this->db->trans_complete();

    if($this->db->trans_status() === FALSE)
    {
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
    $this->db->order_by('a.ID');
    $result = $this->db->get();
    $x      = 0;
    $menu   = array();
    $result = $result->result();
    foreach($result as $hasil)
    {
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
    $this->db->order_by('a.ID');
    $result = $this->db->get();
    if($result->num_rows() > 0) return $result->result_array();
    else return false;
  }

  function get_one_join($field = '',$table = '',$where = '')
  {
    $sql    = "SELECT ".$field." FROM ".$table." ".$where." LIMIT 1";
    $result = $this->db->query("SELECT ".$field." FROM ".$table." ".$where);
    if($result->num_rows() > 0)
    {
      $row = $result->row_array();
      return $row[$field];
    }
    else return "";
  }

  function get_all_array($field = '',$table = '',$where = '')
  {
    $sql    = "SELECT ".$field." FROM ".$table." WHERE ".$where;
    $result = $this->db->query("SELECT ".$field." FROM ".$table." WHERE ".$where);
    if($result->num_rows() > 0)        return $result->result();
    else return FALSE;
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