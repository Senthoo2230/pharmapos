<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Laboratory extends CI_Controller
{
    
  public function __construct()
  {
    parent::__construct();
        //Load Model
        $this->load->model('Dashboard_model');
        $this->load->model('Booking_model');
        $this->load->model('Appoint_model');
        
        $data['username'] = $this->Dashboard_model->username();
        
        //Load Model
        $this->load->model('Laboratory_model');
        //Already logged In
        if (!$this->session->has_userdata('user_id')) {
            redirect('/LoginController/logout');
        }
  }

  public function Add()
  {
        $data['page_title'] = 'Labortary';
        $data['username'] = $this->Dashboard_model->username();
        // Customer List
        $data['pendings'] = $this->Booking_model->pending();

        $data['pending_count'] = $this->Dashboard_model->pending_count();
        $data['confirm_count'] = $this->Dashboard_model->confirm_count();

        $data['invoice_no'] = $this->Laboratory_model->invoice_no();
        $data['locations'] = $this->Laboratory_model->locations();
        $data['services'] = $this->Laboratory_model->services();
        $data['doctors'] = $this->Laboratory_model->doctors();

        $data['nav'] = "Lap Service";
        $data['subnav'] = "Add Services";

        $this->load->view('dashboard/layout/header',$data);
        $this->load->view('dashboard/layout/aside',$data);
        //$this->load->view('aside',$data);
        $this->load->view('Lab/add-service',$data);
        $this->load->view('Lab/footer');
  }

  public function View()
  {
    $data['page_title'] = 'Labortary';
        $data['username'] = $this->Dashboard_model->username();
        // Customer List
        $data['pendings'] = $this->Booking_model->pending();

        $data['pending_count'] = $this->Dashboard_model->pending_count();
        $data['confirm_count'] = $this->Dashboard_model->confirm_count();
        // Get All Services
        $data['services'] = $this->Laboratory_model->view_services(); //63

        $data['nav'] = "Lap Service";
        $data['subnav'] = "View";

        $this->load->view('dashboard/layout/header',$data);
        $this->load->view('dashboard/layout/aside',$data);
        //$this->load->view('aside',$data);
        $this->load->view('Lab/view-service',$data);
        $this->load->view('Lab/footer');
  }

  public function Service_charge(){
      $service = $this->input->post('service');
      $location = $this->input->post('location');
      echo $this->Laboratory_model->service_charge($service,$location);
  }

  public function insert(){
    $this->form_validation->set_rules('nic', 'NIC Number', 'required');
        $this->form_validation->set_rules('pname', 'Patient Name', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required');
        $this->form_validation->set_rules('service', 'Service', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->Add();
        }
        else{
            $id = $this->input->post('invoice_no');
            $location = $this->input->post('location');
            $nic = $this->input->post('nic');
            $pname = $this->input->post('pname');
            $mobile = $this->input->post('mobile');
            $address = $this->input->post('address');
            $service = $this->input->post('service');
            $charge = $this->input->post('charge');
            $doctor = $this->input->post('doctor');
            $comment = $this->input->post('comment');

            $this->Laboratory_model->insert_lab_service($id,$nic,$location,$service,$charge,$doctor,$comment);
            
            if ($this->Appoint_model->patient_available($nic) > 0) {
                $this->Appoint_model->update_patient($nic,$pname,$mobile,$address);
            }
            else{
                $this->Appoint_model->insert_patient($nic,$pname,$mobile,$address);
            }
            $this->session->set_flashdata('labmsg',"<div class='alert alert-success'>Service Added Successfully!</div>");
            redirect('Laboratory/Add');
        }
  }

  public function update(){
    $this->form_validation->set_rules('nic', 'NIC Number', 'required');
        $this->form_validation->set_rules('pname', 'Patient Name', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required');
        $this->form_validation->set_rules('service', 'Service', 'required');

        $service_id = $this->input->post('id');

        if ($this->form_validation->run() == FALSE) {
            $this->view_single($service_id);
        }
        else{
            $location = $this->input->post('location');
            $nic = $this->input->post('nic');
            $pname = $this->input->post('pname');
            $mobile = $this->input->post('mobile');
            $address = $this->input->post('address');
            $service = $this->input->post('service');
            $charge = $this->input->post('charge');
            $doctor = $this->input->post('doctor');
            $comment = $this->input->post('comment');

            $this->Laboratory_model->update_lab_service($service_id,$nic,$location,$service,$charge,$doctor,$comment); //105
            
            if ($this->Appoint_model->patient_available($nic) > 0) {
                $this->Appoint_model->update_patient($nic,$pname,$mobile,$address);
            }
            else{
                $this->Appoint_model->insert_patient($nic,$pname,$mobile,$address);
            }
            $this->session->set_flashdata('updatemsg',"<div class='alert alert-success'>Updated Successfully!</div>");
            redirect('Laboratory/View');
        }
  }
  
  public function view_single($service_id){
        $data['page_title'] = 'View';
        $data['username'] = $this->Dashboard_model->username();
        // Customer List
        $data['pendings'] = $this->Booking_model->pending();

        $data['pending_count'] = $this->Dashboard_model->pending_count();
        $data['confirm_count'] = $this->Dashboard_model->confirm_count();

        $data['invoice_no'] = $this->Laboratory_model->invoice_no();
        $data['locations'] = $this->Laboratory_model->locations();
        $data['services'] = $this->Laboratory_model->services();
        $data['doctors'] = $this->Laboratory_model->doctors();

        //Get Service Details by service id from url
        $data['service_data'] = $this->Laboratory_model->single_service($service_id); //98

        $data['nav'] = "Lap Service";
        $data['subnav'] = "View";

        $this->load->view('dashboard/layout/header',$data);
        $this->load->view('dashboard/layout/aside',$data);
        //$this->load->view('aside',$data);
        $this->load->view('Lab/view-single-service',$data);
        $this->load->view('Lab/footer');
  }

  public function delete_service()
  {
    $id =  $this->input->post('id');
    $this->Laboratory_model->delete_service($id); //120
  }
}


/* End of file Laboratory.php */
/* Location: ./application/controllers/Laboratory.php */