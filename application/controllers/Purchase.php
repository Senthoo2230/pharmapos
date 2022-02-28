<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends CI_Controller
{
    
  public function __construct()
  {
      parent::__construct();
      //Load Model
      $this->load->model('Dashboard_model');
      $data['username'] = $this->Dashboard_model->username();
      //Load Model
      $this->load->model('Purchase_model');
      //Already logged In
      if (!$this->session->has_userdata('user_id')) {
          redirect('/LoginController/logout');
      }
  }

  public function AddNew()
  {
        $data['page_title'] = 'New Purchase';
        $data['username'] = $this->Dashboard_model->username();

        $data['suppliers'] = $this->Purchase_model->suppiler();
        $data['location'] = $this->Purchase_model->locations();
        //$data['bill_years'] = $this->Orders_model->get_bill_years();

        $data['pending_count'] = $this->Dashboard_model->pending_count();
        $data['confirm_count'] = $this->Dashboard_model->confirm_count();

        $data['nav'] = "Purchase";
        $data['subnav'] = "New Purchase";

        $this->load->view('dashboard/layout/header',$data);
        $this->load->view('dashboard/layout/aside',$data);
        //$this->load->view('aside',$data);
        $this->load->view('purchase/addnew',$data);
        $this->load->view('purchase/footer');
  }

  public function AddItems()
  {
        $data['page_title'] = 'Add Items';
        $data['username'] = $this->Dashboard_model->username();

        $data['pending_count'] = $this->Dashboard_model->pending_count();
        $data['confirm_count'] = $this->Dashboard_model->confirm_count();

        $data['suppliers'] = $this->Purchase_model->last_purchase();

        $data['nav'] = "Purchase";
        $data['subnav'] = "New Purchase";

        $this->load->view('dashboard/layout/header',$data);
        $this->load->view('dashboard/layout/aside',$data);
        //$this->load->view('aside',$data);
        $this->load->view('purchase/add-items',$data);
        $this->load->view('purchase/footer');
  }

  public function AddItem()
  {
      $this->form_validation->set_rules('supplier', 'Supplier', 'required');
      $this->form_validation->set_rules('rec_date', 'Date', 'required');
      $this->form_validation->set_rules('ref_no', 'Ref_No', 'required|is_unique[purchase.ref_no]');
      $this->form_validation->set_rules('method', 'Payment Method', 'required');
      $method = $this->input->post('method');
      if ($method == "cheque") {
        $this->form_validation->set_rules('check_date', 'Cheque Date', 'required');
      }

      if ($this->form_validation->run() == FALSE) {
          $this->AddNew();
      }
      else{
            $supplier = $this->input->post('supplier');
            $rec_date = $this->input->post('rec_date');
            $location = $this->input->post('location');
            $notes = $this->input->post('notes');
            $ref_no = $this->input->post('ref_no');
            $method = $this->input->post('method');
            $check_date = $this->input->post('check_date');


            $this->Purchase_model->insert_purchase($supplier,$rec_date,$location,$notes,$ref_no,$method,$check_date); //23

            redirect('Purchase/AddItems');
      }
  }

  public function insert_items(){
            $item_txt = $this->input->post('item');
            $quantity = $this->input->post('quantity');
            $purchase_id = $this->input->post('purchase_id');
            $s_price = $this->input->post('s_price');
            $p_price = $this->input->post('p_price');
            $ex_date = $this->input->post('ex_date');

            //get item_id is barcode or item_id
            $item = $this->Purchase_model->get_item_id($item_txt);
            if ($this->Purchase_model->item_available($item) == 0) {
              $error =  "<div class='alert alert-danger'>Please Select Available Item. Add new item <a href='../Inventory/Add'>Click Here</a></div>";
            }
            else{
              $error = "";
              // If Different in one featre new item
              if ($this->Purchase_model->same_item($s_price,$p_price,$ex_date,$item) == 0) {
                //Insert Item
                $this->Purchase_model->insert_purchase_item($item,$quantity,$purchase_id,$s_price,$p_price,$ex_date); //36
                $last_purchase_id = $this->Purchase_model->last_purchase_id();
                //Insert qty
                $this->Purchase_model->insert_item_qty($last_purchase_id,$quantity);
              }
              else{
                $item_purchase_id = $this->Purchase_model->item_purchase_id($s_price,$p_price,$ex_date,$item);
                $this->Purchase_model->update_qty($s_price,$p_price,$ex_date,$item,$quantity);
                //Insert qty
                $this->Purchase_model->update_item_qty($item_purchase_id,$quantity);
              }
            }

            
            $items = $this->Purchase_model->items($purchase_id);
            
            ?>
              <h4>Purchase Items</h4>     
              <?php
                echo $error;
              ?>
                     
                <table style="width:80%; margin:auto" class="table table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">Item</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Purchase Price</th>
                        <th class="text-center">Selling Price</th>
                        <th class="text-center">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        $total = 0;
                        foreach ($items as $item) {
                          ?>
                          <tr>
                              <td><?php echo $item->item_id; ?></td>
                              <td class="text-center"><?php echo $item->quantity; ?></td>
                              <td class="text-right"><?php echo $item->purchase_price; ?>.00</td>
                              <td class="text-right"><?php echo $item->selling_price; ?>.00</td>
                              <td class="text-right"><?php echo $tot = $item->quantity*$item->purchase_price; ?>.00</td>
                          </tr>
                          <?php $total = $total + $tot; ?>
                          <?php
                        }
                      ?>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right" style="font-size:16px;">Sub Total </td>
                        <td class="text-right" style="font-size:16px;"><?php echo $total; ?>.00</td>
                      </tr>
                    </tbody>
                </table>

                <div>
                    <a  class="btn btn-primary" href="<?php echo base_url(); ?>Purchase/save_purchase/<?php echo $purchase_id; ?>">Save</a>
                    <a  class="btn btn-danger" href="<?php echo base_url(); ?>Purchase/cancel_purchase/<?php echo $purchase_id; ?>">Cancel</a>
                </div>
            <?php
  }

  public function show_purchase_items(){
    $purchase_id = $this->input->post('purchase_id');

    if ($this->Purchase_model->avai_items($purchase_id) > 0){
      $items = $this->Purchase_model->items($purchase_id);
    
    ?>
      <h4>Purchase Items</h4>            
        <table style="width:80%; margin:auto" class="table table-hover">
            <thead>
            <tr>
                <th class="text-center">Item</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Purchase Price</th>
                <th class="text-center">Selling Price</th>
                <th class="text-center">Total</th>
            </tr>
            </thead>
            <tbody>
              <?php
                $total = 0;
                foreach ($items as $item) {
                  ?>
                  <tr>
                      <td><?php echo $item->item_id; ?></td>
                      <td class="text-center"><?php echo $item->quantity; ?></td>
                      <td class="text-right"><?php echo $item->purchase_price; ?>.00</td>
                      <td class="text-right"><?php echo $item->selling_price; ?>.00</td>
                      <td class="text-right"><?php echo $tot = $item->quantity*$item->purchase_price; ?>.00</td>
                  </tr>
                  <?php $total = $total + $tot; ?>
                  <?php
                }
              ?>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right" style="font-size:16px;">Sub Total </td>
                <td class="text-right" style="font-size:16px;"><?php echo $total; ?>.00</td>
              </tr>
            </tbody>
        </table>

        <div>
            <a  class="btn btn-primary" href="<?php echo base_url(); ?>Purchase/save_purchase/<?php echo $purchase_id; ?>">Save</a>
            <a  class="btn btn-danger" href="<?php echo base_url(); ?>Purchase/cancel_purchase/<?php echo $purchase_id; ?>">Cancel</a>
        </div>
    <?php
    }
    else{
      echo "";
    }
    
    
}

  

  public function save_purchase(){
    $pur_id =  $this->uri->segment('3');
    $this->Purchase_model->save_items($pur_id);
    redirect('Purchase/AddNew');
  }

  public function cancel_purchase(){
    $pur_id =  $this->uri->segment('3');
    $this->Purchase_model->cancel_items($pur_id);
    redirect('Purchase/AddNew');
  }

  public function Summery(){
    $data['page_title'] = 'Purchase Summery';
    $data['username'] = $this->Dashboard_model->username();
    $data['pending_count'] = $this->Dashboard_model->pending_count();
    $data['confirm_count'] = $this->Dashboard_model->confirm_count();

    $data['purchases'] = $this->Purchase_model->purchase_summery();

        $data['nav'] = "Purchase";
        $data['subnav'] = "Purchases";

    $this->load->view('dashboard/layout/header',$data);
    $this->load->view('dashboard/layout/aside',$data);
    $this->load->view('purchase/purchase-summery',$data);
    $this->load->view('purchase/footer');
  }

  public function item_search(){

    if ($this->input->post('item')) {
        
        $output = "";
        $item = $this->input->post('item');
        $result = $this->Purchase_model->item_list($item);
        $output = '<ul class="list-unstyled">';	
        foreach ($result as $row)
        {
                $output .= '<li class="li-style">'.$row->item_id.'</li>';
        }
        $output .= '</ul>';
        echo $output;
    }
  }

  public function item_price(){
    $item_id = $this->input->post('item_id');
    echo $price = $this->Purchase_model->get_item_price($item_id);
  }

  public function item_name(){
    $item_id = $this->input->post('item_id');
    echo $price = $this->Purchase_model->get_item_name($item_id); //116
  }

  public function delete(){
    $id =  $this->input->post('id');
    $this->Purchase_model->delete_purchase($id); //115
  }

  public function view(){

    $puritem_id =  $this->uri->segment('3');
    $data['page_title'] = 'View';
    $data['username'] = $this->Dashboard_model->username();
    $data['pending_count'] = $this->Dashboard_model->pending_count();
    $data['confirm_count'] = $this->Dashboard_model->confirm_count();

    //purchase data
    $data['purchase_item'] = $this->Purchase_model->view_purchase($puritem_id); //122

    $data['nav'] = "Purchase";
    $data['subnav'] = "Purchases";

    $this->load->view('dashboard/layout/header',$data);
    $this->load->view('dashboard/layout/aside',$data);
    $this->load->view('purchase/view-purchase',$data);
    $this->load->view('purchase/footer',$data);
  }

  public function item_name_fill(){
    $item_id = $this->input->post('item_id');

    echo $this->Purchase_model->fill_item_name($item_id); //116
  }


}


/* End of file Purchase.php */
/* Location: ./application/controllers/Purchase.php */