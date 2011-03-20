<?php 
/** 
 * dailypennsylvanian.com GA Story List Package
 *
 * tools.php: Toolkit for GA Stories.
 * @version  1.0 (1/05)
 * @author   Matthew Jones (mrjones@seas.upenn.edu)
 * 
 */
function meridian_dropdown($name="meridian", $selected=null){
        $dd = '<select name="'.$name.'" id="'.$name.'">';
        $selected = is_null($selected) ? date('A', time()) : $selected;
		
		$dd .= '<option value="AM"';
		if ('AM' == $selected) {$dd .= ' selected';}
		$dd .= '>AM</option>';
		
		$dd .= '<option value="PM"';
		if ('PM' == $selected) {$dd .= ' selected';}
		$dd .= '>PM</option>';
		
        $dd .= '</select>';
        return $dd;
}

function hour_dropdown($name="hour", $selected=null){
        $dd = '<select name="'.$name.'" id="'.$name.'">';
        $selected = is_null($selected) ? date('g', time()) : $selected;
        for ($i = 1; $i < 13; $i++){
                $dd .= '<option value="'.$i.'"';
                if ($i == $selected) {
                        $dd .= ' selected';
                }
                $hour = date("g", mktime($i, 0, 0, 0, 0, 0));
                $dd .= '>'.$hour.'</option>';
        }
        $dd .= '</select>';
        return $dd;
}

function minute_dropdown($name="minute", $selected=null){
        $dd = '<select name="'.$name.'" id="'.$name.'">';
        $selected = is_null($selected) ? date('i', time()) : $selected;
        for ($i = 0; $i < 60; $i++){
                $dd .= '<option value="'.$i.'"';
                if ($i == $selected) {
                        $dd .= ' selected';
                }
                $minute = date("i", mktime(0, $i, 0, 0, 0, 0));
                $dd .= '>'.$minute.'</option>';
        }
        $dd .= '</select>';
        return $dd;
}

function month_dropdown($name="month", $selected=null){
        $dd = '<select name="'.$name.'" id="'.$name.'">';
        $selected = is_null($selected) ? date('F', time()) : $selected;
        for ($i = 1; $i <= 12; $i++){
                $dd .= '<option value="'.$i.'"';
                if ($i == $selected) {
                        $dd .= ' selected';
                }
                $mon = date("F", mktime(0, 0, 0, $i+1, 0, 0));
                $dd .= '>'.$mon.'</option>';
        }
        $dd .= '</select>';
        return $dd;
}

function day_dropdown($name="day", $selected=null){
        $dd = '<select name="'.$name.'" id="'.$name.'">';
        $selected = is_null($selected) ? date('j', time()) : $selected;
        for ($i = 0; $i < 32; $i++){
                $dd .= '<option value="'.$i.'"';
                if ($i == $selected) {
                        $dd .= ' selected';
                }
                $day = date("j", mktime(0, 0, 0, 0, $i+1, 0));
                $dd .= '>'.$day.'</option>';
        }
        $dd .= '</select>';
        return $dd;
}

function year_dropdown($name="day", $selected=null){
        $dd = '<select name="'.$name.'" id="'.$name.'">';
        $selected = is_null($selected) ? date('Y', time()) : $selected;
		
		$currentyear = date('Y');
		$dd .= '<option value="'.$currentyear.'"';
		if ($currentyear == $selected) {$dd .= ' selected';}
		$dd .= '>'.$currentyear.'</option>';
		
		$nextyear = date('Y')+1;
		$dd .= '<option value="'.$nextyear.'"';
		if ($nextyear == $selected) {$dd .= ' selected';}
		$dd .= '>'.$nextyear.'</option>';
		
        $dd .= '</select>';
        return $dd;
}
?>
