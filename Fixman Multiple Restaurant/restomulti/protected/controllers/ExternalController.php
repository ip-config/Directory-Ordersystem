<?php
/**
 * ExternalController
 *
 */
if (!isset($_SESSION)) { session_start(); }

class ExternalController extends CController
{	
	public function actionIndex()
	{
		$data=$_REQUEST;										
		$class=new ExternalServices;
	    $class->data=$data;
	    $class->$data['action']();	    
	    echo $class->output();
	    yii::app()->end();
	}
	
	public function output($debug=FALSE)
	{
	    $resp=array('code'=>$this->code,'msg'=>$this->msg,'details'=>$this->details);
	    if ($debug){
		    dump($resp);
	    }
	    return json_encode($resp);    	    
	}
		
} /*END CLASS*/