<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Report_model extends CI_Model 
{
    public function is_initail_balance()
    {
        $sql = "SELECT * FROM initial_balance";
    }                        
                        
}


/* End of file Report_model.php and path /application/models/Report_model.php */

