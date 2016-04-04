

<div class="pad10">

<br/>
<p>
<?php echo t("Please run the following cron jobs in your server as http.")?><br/>
<?php echo t("set the running of cronjobs every minute")?><br/>
</p>
<ul>

<li class="bg-success">
 <a href="<?php echo websiteUrl()."/merchantapp/cron/getneworder"?>" target="_blank"><?php echo websiteUrl()."/merchantapp/cron/getneworder"?></a>
 </li>
 
 <li class="bg-success">
 <a href="<?php echo websiteUrl()."/merchantapp/cron/processpush"?>" target="_blank"><?php echo websiteUrl()."/merchantapp/cron/processpush"?></a>
 </li>
 
 <li class="bg-success">
 <a href="<?php echo websiteUrl()."/merchantapp/cron/getnewtablebooking"?>" target="_blank"><?php echo websiteUrl()."/merchantapp/cron/getnewtablebooking"?></a>
 </li>
 
 
</ul>

<p><?php echo t("Eg. command")?> <br/>
 CURL <?php echo websiteUrl()."/merchantapp/cron/getneworder"?><br/>
 CURL <?php echo websiteUrl()."/merchantapp/cron/processpush"?><br/>
 CURL <?php echo websiteUrl()."/merchantapp/cron/getnewtablebooking"?><br/>
 </p>
 </p>
 
 <p><?php echo t("OR")?></p>
 
 <p><?php echo t("Eg. command")?> <br/>
 WGET <?php echo websiteUrl()."/merchantapp/cron/getneworder"?><br/>
 WGET <?php echo websiteUrl()."/merchantapp/cron/processpush"?><br/>
 WGET <?php echo websiteUrl()."/merchantapp/cron/getnewtablebooking"?><br/>
 </p>
 </p>

<p><?php echo t("example bluehost server toturial")?> 
<a target="_blank" href="https://my.bluehost.com/cgi/help/411">https://my.bluehost.com/cgi/help/411</a>
</p>
 
</div>