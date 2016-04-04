<h4><?php echo t("Push Template New Order")?></h4>

<p class="bg-success inlineblock"><?php echo t("Available tags")?>: {merchant_name} {customer_name} {order_id} {order_status} {total_amount} {trans_type} {payment_type}</p>

<div class="form-group ">
  <label class="col-sm-3 control-label"><?php echo t("Push Title")?></label>
  <div class="col-sm-8">
    <?php 
    echo CHtml::textField('push_tpl_new_order_title',getOptionA('push_tpl_new_order_title'),array(
      'class'=>'form-control'      
    ));
    ?>   
  </div>
</div>

<div class="form-group ">
  <label class="col-sm-3 control-label"><?php echo t("Push Content")?></label>
  <div class="col-sm-8">
    <?php 
    echo CHtml::textArea('push_tpl_new_order_content',getOptionA('push_tpl_new_order_content'),array(
      'class'=>'form-control'       
    ));
    ?>   
  </div>
</div>


<h4><?php echo t("Push Template Booking Table")?></h4>

<p class="bg-success inlineblock"><?php echo t("Available tags")?>: {merchant_name} {booking_name} {booking_date} {booking_time} {number_of_guest} {booking_id} </p>

<div class="form-group ">
  <label class="col-sm-3 control-label"><?php echo t("Push Title")?></label>
  <div class="col-sm-8">
    <?php 
    echo CHtml::textField('push_tpl_booking_title',getOptionA('push_tpl_booking_title'),array(
      'class'=>'form-control'      
    ));
    ?>   
  </div>
</div>

<div class="form-group ">
  <label class="col-sm-3 control-label"><?php echo t("Push Content")?></label>
  <div class="col-sm-8">
    <?php 
    echo CHtml::textArea('push_tpl_booking_content',getOptionA('push_tpl_booking_content'),array(
      'class'=>'form-control'       
    ));
    ?>   
  </div>
</div>