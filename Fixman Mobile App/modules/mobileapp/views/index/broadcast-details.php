
<a href="<?php echo Yii::app()->createUrl('/mobileapp/index/broadcast')?>" class="pad5 block">
<i class="fa fa-long-arrow-left"></i> <?php echo t("Back")?></a>

<div class="pad10">

<h3><?php echo t("Broadcast details")?> #<?php echo $_GET['id']?></h3>

<form id="frm_table" method="POST" class="form-inline" >
<?php echo CHtml::hiddenField('action','broadcastdetails')?>
<?php echo CHtml::hiddenField('broadcast_id',$_GET['id'])?>


<table id="table_list" class="table table-hover">
<thead>
  <tr>
    <th width="5%"><?php echo t("ID")?></th>
    <th><?php echo t("PushType")?></th>
    <th><?php echo t("Name")?></th>
    <th><?php echo t("Platform")?></th>    
    <th><?php echo t("Push Title")?></th>
    <th><?php echo t("Push Message")?></th>    
    <th><?php echo t("Date")?></th>
  </tr>
</thead>
<tbody> 
</tbody>
</table>

</form>

</div>