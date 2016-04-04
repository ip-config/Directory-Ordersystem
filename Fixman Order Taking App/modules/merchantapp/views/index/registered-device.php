
<div class="pad10">

<form id="frm_table" method="POST" class="form-inline" >
<?php echo CHtml::hiddenField('action','registeredDeviceList')?>

<table id="table_list" class="table table-hover">
<thead>
  <tr>
    <th width="5%"><?php echo t("ID")?></th>
    <th><?php echo t("Merchant Name")?></th>
    <th><?php echo t("Platform")?></th>
    <th><?php echo t("Username")?></th>
    <th><?php echo t("User type")?></th>
    <th ><?php echo t("Device ID")?></th>
    <th><?php echo t("Enabled Push")?></th>    
    <th><?php echo t("Date Created")?></th>
    <th><?php echo t("Actions")?></th>
  </tr>
</thead>
<tbody> 
</tbody>
</table>

</form>

</div>