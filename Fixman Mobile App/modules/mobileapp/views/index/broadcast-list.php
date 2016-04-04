
<div class="pad10">

<form id="frm_table" method="POST" class="form-inline" >
<?php echo CHtml::hiddenField('action','broadcastList')?>

<a href="<?php echo Yii::app()->createUrl('/mobileapp/index/broadcastnew')?>"
 class="btn btn-primary"><?php echo t("Add new")?> <i class="fa fa-plus"></i>
</a>

<table id="table_list" class="table table-hover">
<thead>
  <tr>
    <th width="2%"><?php echo t("Broadcast ID")?></th>
    <th width="10%"><?php echo t("Push Title")?></th>
    <th width="10%"><?php echo t("Push Message")?></th>
    <th width="10%"><?php echo t("Platform")?></th>        
    <th width="10%"><?php echo t("Date")?></th>
    <th width="10%"><?php echo t("Actions")?></th>   
  </tr>
</thead>
<tbody> 
</tbody>
</table>

</form>

</div>