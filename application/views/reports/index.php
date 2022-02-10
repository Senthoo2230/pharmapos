

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
  .sec-container{
    background-color:white;
    padding:50px;
  }

  .report-cart{
    font-size:30px;
    padding:50px 0px 50px 0px; 
    color:white;
    font-weight:900;
    border-radius: 18px;
    transition: box-shadow .3s;
  }

  .report-cart:hover {
  box-shadow: 0 0 11px rgba(33,33,33,.2); 
    }

</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">  
        <h3>Reports</h3>

        <div class="sec-container">
            <div class="row">
                <div class="col-md-4">
                    <a href="<?php echo base_url(); ?>Report/Order">
                    <div class="text-center report-cart" style="background-color:#6ECB63;">
                        ORDER
                    </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="text-center report-cart" style="background-color:#1DB9C3;">
                        EXPENSE
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center report-cart" style="background-color:#FF865E;">
                        INVENTORY
                    </div>
                </div>
                
            </div>

            <div class="row" style="margin-top:20px">
                <div class="col-md-4">
                    <div class="text-center report-cart" style="background-color:#7027A0;">
                        CUSTOMER
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center report-cart" style="background-color:#0F52BA;">
                        FINANCE
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="">
                        <div class="text-center report-cart" style="background-color:#F8485E;">
                            PURCHASE
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</section>
    <!-- /MAIN CONTENT -->

