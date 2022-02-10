<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Doctor extends CI_Controller
{
    
  public function __construct()
  {
    parent::__construct();
    //Load Model
    $this->load->model('Dashboard_model');
    $data['username'] = $this->Dashboard_model->username();
    //Load Model
    $this->load->model('Doctor_model');
    //Load Model
    $this->load->model('Booking_model');
    //Load Model
    $this->load->model('Appoint_model');
    //Already logged In
    if (!$this->session->has_userdata('user_id')) {
        redirect('/LoginController/logout');
    }
  }

  public function Add()
  {
        $data['page_title'] = 'Add Doctor';
        $data['username'] = $this->Dashboard_model->username();
        // Customer List
        $data['pendings'] = $this->Booking_model->pending();

        $data['pending_count'] = $this->Dashboard_model->pending_count();
        $data['confirm_count'] = $this->Dashboard_model->confirm_count();

        //Show Spelizations
        $data['specials'] = $this->Appoint_model->specials(); //28

        
        $data['nav'] = "Doctor";
        $data['subnav'] = "Add Doctor";

        $this->load->view('dashboard/layout/header',$data);
        $this->load->view('dashboard/layout/aside',$data);
        //$this->load->view('aside',$data);
        $this->load->view('Doctor/add',$data);
        $this->load->view('Doctor/footer');
  }

}


/* End of file Doctor.php */
/* Location: ./application/controllers/Doctor.php */