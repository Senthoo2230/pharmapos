<style type="text/css">
  .li-style{
    border-bottom: medium;
    background-color:#f4f9f9;
    padding: 8px;
    color: #314e52;
  }
  .li-style:hover{
    background-color:#e7e6e1;
    color: #f2a154;
  }
</style>
<section id="main-content">
    <section class="wrapper">
        <div class="row mt">
            <div class="col-lg-8">
                <?php echo form_open('Laboratory/update'); ?>
                <input type="text" value="<?php echo $service_data->id; ?>" name="id" hidden >
                    <div class="form-panel" style="padding:25px">
                      <div id="delete_msg">
                        <?php
                          if ($this->session->flashdata('updatemsg')) {
                            echo $this->session->flashdata('updatemsg');
                          }
                        ?>
                      </div>
                        <h4 class="mb">Labortary Service</h4>
                        <div class="form-horizontal style-form">
                        
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Location<span style="color: red;"> *</span></label>
                          <div class="col-sm-8">
                              <select id="location" class="form-control" name="location">
                                <?php
                                foreach ($locations as $location) {
                                    $l_sel = "";
                                    if ($location->id == $service_data->location_id) {
                                        $l_sel = "selected";
                                    }
                                    echo "<option $l_sel value='$location->id'>$location->location</option>";
                                }
                                ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('location'); ?></span>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Invoice No</label>
                            <div class="col-sm-8">
                            <input type="text" value="<?php echo $service_data->invoice_no; ?>" class="form-control" name="invoice_no" id="invoice_no" readonly >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">NIC No<span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                            <input type="text" value="<?php echo $nic = $service_data->patient_nic; ?>"  class="form-control" name="nic" id="nic">
                            <div id="nic_list"></div>
                            <span class="text-danger"><?php echo form_error('nic'); ?></span>
                            </div>
                        </div>

                        <?php
                            $CI =& get_instance();
                            // Get Patient Detail by nic
                            $patient = $CI->Laboratory_model->patient_detail($nic); //70
                        ?>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Patient Name<span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                            <input type="text" value="<?php echo $patient->name; ?>" class="form-control" name="pname" id="pname">
                            <span class="text-danger"><?php echo form_error('pname'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mobile No<span style="color: red;"> *</span></label>
                            <div class="col-sm-8">
                            <input type="text" value="<?php echo $patient->mobile; ?>" class="form-control" name="mobile" id="mobile">
                            <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Address</label>
                            <div class="col-sm-8">
                            <input type="text" value="<?php echo $patient->address; ?>" class="form-control" name="address" id="address">
                            <span class="text-danger"><?php echo form_error('address'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-3 control-label">Service<span style="color: red;"> *</span></label>
                          <div class="col-sm-8">
                              <select id="service" class="form-control" name="service">
                                <option value="">Select Service</option>
                                <?php
                                foreach ($services as $service) {
                                    $s_sel = "";
                                    if ($service->service_id == $service_data->lab_service_id) {
                                        $s_sel = "selected";
                                    }
                                    echo "<option $s_sel value='$service->service_id'>$service->service</option>";
                                }
                                ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('service'); ?></span>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Charge</label>
                            <div class="col-sm-8">
                            <input readonly type="text" value="<?php echo $service_data->amount; ?>" class="form-control" name="charge" id="charge">
                            <span class="text-danger"><?php echo form_error('charge'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-3 control-label">Doctor</label>
                          <div class="col-sm-8">
                              <select id="doctor" class="form-control" name="doctor">
                                <option value="">Select Doctor</option>
                                <?php
                                
                                foreach ($doctors as $doctor) {
                                    $d_sel = "";
                                    if ($doctor->id == $service_data->doctor_id) {
                                        $d_sel = "selected";
                                    }
                                    echo "<option $d_sel value='$doctor->id'>$doctor->name</option>";
                                }
                                ?>
                              </select>
                              <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                          </div>
                        </div>

                        <?php
                          $month = date('m');
                          $day = date('d');
                          $year = date('Y');
                          
                          $today = $year . '-' . $month . '-' . $day;
                        ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Comments</label>
                            <div class="col-sm-8">
                            <input type="text" value="<?php echo $service_data->comment; ?>" class="form-control" name="comment" id="comment">
                            <span class="text-danger"><?php echo form_error('comment'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-3"></div>
                          <div class="col-sm-8">
                            <input type="submit" class="btn btn-primary pull-right mr-5" value="Update" name="save_item">
                            <a style="margin-right: 15px;" href="" class="pull-right btn btn-danger">Cancel</a>
                          </div>
                        </div>

                      </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</section>


