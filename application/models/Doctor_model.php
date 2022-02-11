<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Doctor_model extends CI_Model 
{
    public function insert_doctor($d_name,$d_mobile,$d_special,$d_charge,$d_commision,$com_type){
        $data = array(
            'name' => "Dr.".$d_name,
            'mobile' => $d_mobile,
            'specialization' => $d_special,
            'charge' => $d_charge,
            'commision' => $d_commision,
            'commision_type' => $com_type,
        );
    
        $this->db->insert('doctor', $data);
    }                      
                        
}


/* End of file Doctor_model.php and path \application\models\Doctor_model.php */
