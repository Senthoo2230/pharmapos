<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Laboratory_model extends CI_Model 
{
    public function invoice_no(){
        $sql = "SELECT id FROM lab_service ORDER BY created DESC";
        $query = $this->db->query($sql);
        $conut = $query->num_rows();
        $row = $query->first_row();

        if ($conut == 0) {
            return 1;
        }
        else{
            return $row->id+1;
        }
    }   
    
    public function locations(){
        $sql = "SELECT * FROM location";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function services(){
        $sql = "SELECT * FROM service";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function doctors(){
        $sql = "SELECT * FROM doctor";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function service_charge($service,$location){
        $sql = "SELECT * FROM service_amount WHERE service_id = $service AND location_id = $location";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        return $row->amount;
    }

    public function insert_lab_service($id,$nic,$location,$service,$charge,$doctor,$comment){
        $data = array(
            'invoice_no' => $id,
            'user_id' => $this->session->user_id,
            'patient_nic' => $nic,
            'lab_service_id' => $service,
            'location_id' => $location,
            'amount' => $charge,
            'doctor_id' => $doctor,
            'comment' => $comment,
        );
    
        $this->db->insert('lab_service', $data);
    }
                        
}


/* End of file Laboratory_model.php and path \application\models\Laboratory_model.php */
