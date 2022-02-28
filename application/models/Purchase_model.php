<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Purchase_model extends CI_Model 
{
    public function suppiler()
    {
        $sql = "SELECT * FROM supplier";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }  
    public function locations(){
        $sql = "SELECT * FROM location";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }            
    
    public function insert_purchase($supplier,$rec_date,$location,$notes,$ref_no,$method,$check_date){
        $data = array(
            'supplier' => $supplier,
            'rec_date' => $rec_date,
            'location' => $location,
            'notes' => $notes,
            'ref_no' => $ref_no,
            'method' => $method,
            'check_date' => $check_date
        );
        $this->db->insert('purchase', $data);
    }

    public function same_item($s_price,$p_price,$ex_date,$item_id){
        $sql = "SELECT * FROM purchase_items WHERE selling_price = $s_price AND purchase_price = $p_price AND ex_date = '$ex_date' AND item_id = '$item_id'";
        $query = $this->db->query($sql);
        $result = $query->num_rows();

        return $result;
    }

    public function item_available($item_id){
        $sql = "SELECT * FROM int_items WHERE item_id = '$item_id'";
        $query = $this->db->query($sql);
        $result = $query->num_rows();

        return $result;
    }

    public function get_qty($s_price,$p_price,$ex_date,$item_id){
        $sql = "SELECT * FROM purchase_items WHERE selling_price = $s_price AND purchase_price = $p_price AND ex_date = '$ex_date' AND item_id = '$item_id'";
        $query = $this->db->query($sql);
        $row = $query->first_row();

        return $row->quantity;
    }

    public function update_qty($s_price,$p_price,$ex_date,$item_id,$qty){
        $last_qty = $this->get_qty($s_price,$p_price,$ex_date,$item_id);
        $new_qty = $last_qty+$qty;
        $sql = "UPDATE purchase_items SET quantity = $new_qty WHERE selling_price = $s_price AND purchase_price = $p_price AND ex_date = '$ex_date' AND item_id = '$item_id'";
        $query = $this->db->query($sql);
    }

    public function insert_purchase_item($item,$quantity,$purchase_id,$s_price,$p_price,$ex_date){
        $data = array(
            'purchase_id' => $purchase_id,
            'item_id' => $item,
            'quantity' => $quantity,
            'selling_price' => $s_price,
            'purchase_price' => $p_price,
            'ex_date' => $ex_date
        );

        $this->db->insert('purchase_items', $data);
    }

    public function last_purchase(){
        $sql = "SELECT * FROM purchase ORDER BY created_at DESC LIMIT 1";
        $query = $this->db->query($sql);
        $row = $query->first_row();

        return $row;
    }

    public function items($id){
        $sql = "SELECT * FROM purchase_items WHERE purchase_id = $id";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function avai_items($id){
        $sql = "SELECT * FROM purchase_items WHERE purchase_id = $id";
        $query = $this->db->query($sql);
        $result = $query->num_rows();

        return $result;
    }

    public function save_items($id){
        $data = array(
            'status' => 1
        );
        
        $this->db->where('purchase_id', $id);
        $this->db->update('purchase_items', $data);
    }

    public function cancel_items($id){
        $sql = "DELETE FROM purchase_items WHERE purchase_id=$id";
        $query = $this->db->query($sql);
    }

    public function purchase_summery(){
        $sql = "SELECT * FROM purchase_items ORDER BY created_at DESC";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function purchase_data($pur_id){
        $sql = "SELECT * FROM purchase WHERE id = $pur_id";
        $query = $this->db->query($sql);
        $row = $query->first_row();

        return $row;
    
    }

    public function supplier($id){
        $sql = "SELECT * FROM supplier WHERE id = $id";
        $query = $this->db->query($sql);
        $row = $query->first_row();

        return $row;
    }

    public function item_list($item){
        $sql = "SELECT * FROM int_items WHERE item_id LIKE '%$item%'";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function get_item_price($item_id){
        $sql = "SELECT * FROM int_items WHERE item_id = '$item_id'";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        return $row->price;
    }

    public function get_item_name($item_id){
        $sql = "SELECT item_name FROM int_items WHERE item_id = '$item_id'";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        return $row->item_name;
    }

    public function delete_purchase($id)
    {
        $sql = "DELETE FROM purchase_items WHERE id=$id";
        $query = $this->db->query($sql);
    }

    public function view_purchase($id)
    {
        $sql = "SELECT * FROM purchase_items WHERE id=$id";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        return $row;
    }

    public function fill_item_name($item_id){
        $sql = "SELECT item_name FROM int_items WHERE item_id = '$item_id' OR barcode = '$item_id'";
        $query = $this->db->query($sql);
        $count = $query->num_rows();
        $row = $query->first_row();

        if ($count == 1) {
            return $row->item_name;
        }
        else{
            return null;
        }
        
    }

    public function last_purchase_id(){
        $sql = "SELECT * FROM purchase_items ORDER BY created_at DESC";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        return $row->id;
    }

    public function insert_item_qty($last_purchase_id,$quantity){
        $data = array(
            'purchase_id' => $last_purchase_id,
            'quantity' => $quantity
        );

        $this->db->insert('int_qty', $data);
    }

    public function update_item_qty($last_purchase_id,$quantity){
        $sql = "UPDATE int_qty SET quantity = quantity + $quantity WHERE purchase_id = $last_purchase_id";
        $query = $this->db->query($sql);
    }

    public function item_purchase_id($s_price,$p_price,$ex_date,$item){
        $sql = "SELECT * FROM purchase_items WHERE selling_price = $s_price AND purchase_price = $p_price AND ex_date = '$ex_date' AND item_id = '$item'";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        return $row->id;
    }

    public function get_item_id($item)
    {
        $sql = "SELECT item_id FROM int_items WHERE item_id = '$item' OR barcode = '$item'";
        $query = $this->db->query($sql);
        $count = $query->num_rows();
        if ($count == 0) {
            return $count;
        }
        else{
            $row = $query->first_row();
            return $row->item_id;
        }
        
    }
    
                        
}


/* End of file Purchase_model.php and path /application/models/Purchase_model.php */

