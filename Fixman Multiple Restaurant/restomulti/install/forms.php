<?php
class forms {

    public $data;

    /** 	 
     */
    public function dropDownList($name='', $select='', $data='', $attr=array()) {
        $htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }

        $htm.="<select name=\"$name\" $htmloptions >";
        if (is_array($data) && count($data) >= 1) {
            foreach ($data as $key => $value) {
                if ($key == $select) {
                    $htm.="<option value=\"$key\" selected=\"selected\" >$value</option>";
                } else
                    $htm.="<option value=\"$key\"  >$value</option>";
            }
        }
        $htm.="</select>";
        return $htm;
    }

    public function radioButton($name='', $checked=false, $attr=array(),$value='') {
        $htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }

        if ($checked) {
            $htm.="<input name=\"$name\" type=\"radio\" value=\"$value\"  checked   $htmloptions />";
        } else
            $htm.="<input name=\"$name\" type=\"radio\"  value=\"$value\" $htmloptions />";

        return $htm;
    }
    
    
    public function textold($name='',$value='',$attr=array())
    {
    	$htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }        
        $htm="<input  type=\"text\" value=\"$value\" name=\"$name\"  $htmloptions >";
        return $htm;
    }
    
    
    public function text($name='',$value='',$attr=array() ,$type="text")
    {
    	$htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }        
        $htm="<input  type=\"$type\" value=\"$value\" name=\"$name\"  $htmloptions >";
        return $htm;
    }
        
    
    
    public function hidden($name='',$value='',$attr=array())
    {
    	$htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }        
        $htm="<input  type=\"hidden\" value=\"$value\" name=\"$name\"  $htmloptions >";
        return $htm;
    }
    
    public function checkbox($name='',$value='',$iselected=false,$attr=array())
    {
        $htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }    
        if ($iselected){
        	$selected='checked';
        } else $selected='';
    	$htm="<input name=\"$name\" type=\"checkbox\" value=\"$value\" $htmloptions  $selected >";
    	return $htm;
    }
    
    public function checkboxs($name='',$value='',$iselected=false,$attr=array())
    {
        $htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }    
        if ($iselected){
        	$selected='checked';
        } else $selected='';
    	$htm="<input name=\"$name\" type=\"checkbox\" value=\"$value\" $htmloptions  $selected >";
    	return $htm;
    }
    
    
    
    public function radioButton2($name='', $value='', $value2='', $attr=array()) {
        $htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }
        
        if ($value==$value2){
        	$ischecked="checked=\"checked\"";
        } else $ischecked='';

        /*if ($checked) {
            $htm.="<input name=\"$name\" type=\"radio\"   checked=\"checked\"   $htmloptions />";
        } else*/
        
        $htm.="<input name=\"$name\" value=\"$value\" type=\"radio\" $htmloptions $ischecked />";    

        return $htm;
    }    
    
    
    
       
    public function textarea($name='',$value='',$attr=array() ,$type="text")
    {
    	$htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }                
        $htm="<textarea name=\"$name\" $htmloptions >$value</textarea>";
        return $htm;
    }
     
    public function checkboxes($name='',$value='',$data=array(),$attr=array())
    {
        $htm = '';
        $htmloptions = '';

        if (is_array($attr) && count($attr)) {
            foreach ($attr as $keys => $val) {
                $htmloptions.=" $keys=\"$val\" ";
            }
        }            
        if (in_array($value,$data)){
        	$selected='checked';
        } else $selected='';
    	$htm="<input name=\"$name\" type=\"checkbox\" value=\"$value\" $htmloptions  $selected >";
    	return $htm;
    }
    
}
/*END:Cform*/