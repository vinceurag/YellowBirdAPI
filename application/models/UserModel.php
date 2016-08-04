<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model
{
     function __construct()
     {
          // Call the Model constructor
          parent::__construct();
     }

     //get the username & password from tbl_usrs
     function get_user($usr, $pwd)
     {
          $sql = "select * from tbl_users where username = '" . $usr . "' and password = '" . $pwd . "'";
          $query = $this->db->query($sql);
          return $query->num_rows();
     }

     function get_userProfile($usr, $pwd)
     {
          $this->db->select('id,full_name,username');
          $this->db->from('tbl_users');
          $this->db->where('username', $usr);
          $this->db->where('password', $pwd);
          return $this->db->get()->result_array();
     }
     function get_reservations()
     {
          $result = $this->db
                ->get('tbl_reservations')->result_array();
          return $result;
     }

     function post_reservations($reservationInfo){
       $this->db->insert('tbl_reservations', $reservationInfo);
       if ($this->db->affected_rows() > 0) {
         return true;
       } else {
         return false;
       }
     }
}
