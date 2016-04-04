

<div class="pad10">

<br/>
<p>
Please run the following cron jobs in your server as http.<br/>
set the running of cronjobs every minute<br/>
</p>
<ul>
 <li class="bg-success">
 <a href="<?php echo websiteUrl()."/mobileapp/cron/processpush"?>" target="_blank"><?php echo websiteUrl()."/mobileapp/cron/processpush"?></a>
 </li>
 
  <li class="bg-success">
 <a href="<?php echo websiteUrl()."/mobileapp/cron/processbroadcast"?>" target="_blank"><?php echo websiteUrl()."/mobileapp/cron/processbroadcast"?></a>
 </li>
 
</ul>

<p>Eg. command <br/>
 CURL <?php echo websiteUrl()."/mobileapp/cron/processpush"?><br/>
 CURL <?php echo websiteUrl()."/mobileapp/cron/processbroadcast"?></p>
 </p>

</div>