<?php 
foreach($lang_label as $langlbl) 
{ 
    if($langlbl['id'] == '1997')  {  $timer = $langlbl['title'] ;  } 
    if($langlbl['id'] == '365')  {  $subjlbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '243')  {  $submtlbl = $langlbl['title'] ;  }
    if($langlbl['id'] == '337')  {  $clslbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '371')  {  $maxmrklbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '41')  {  $bcklbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1998')  {  $stymlbl = $langlbl['title'] ;  } 
    if($langlbl['id'] == '1999')  {  $etymlbl = $langlbl['title'] ;  }
    
}
    $disable = $status == 1 ? "disabled" : "";
?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <?php
                        $now = time();
                        $enddate = strtotime($exam_details['end_date']);
                        $timediff = $enddate-$now;
                        ?>
                        <input type="hidden" name="subId" id="subId" value="<?= $sub_details['id'] ?>" >
                        <input type="hidden" name="classId" id="classId" value="<?= $cls_details['id'] ?>" >
                        <input type="hidden" name="timenow" id="timenow" value="<?= $now ?>" >
                        <input type="hidden" name="enddate" id="enddate" value="<?= $enddate ?>" >
                        <div class="row text-center ">
                            <h2 class="heading"></h2>
                            
                        </div>
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h2 class="heading" id="countrtimer" style="color: #ff0000 !important;"><?= $timer ?>: <span id="counter"></span> Minutes</h2>
                            </div>
                            <div class="col-md-4">
                                <h2 class="heading"><?= $exam_details['type']." (". $exam_details['exam_type'] .")" ?></h2>
                                <h2 class="heading"> <?= $subjlbl ?>: <?= $sub_details['subject_name'] ?></h2>
                                <h2 class="heading"> <?= $clslbl ?>: <?= $cls_details['c_name']."-".$cls_details['c_section']." (". $cls_details['school_sections'] .")" ?></h2>
                            </div>
                            <div class="col-md-4  align-right">
                                <h2 class="heading"><?= $maxmrklbl ?>: <?= $exam_details['max_marks'] ?></h2>
                                <h2 class="heading"><?= $stymlbl ?>: <?= $exam_details['start_date'] ?></h2>
                                <h2 class="heading"><?= $etymlbl ?>: <?= $exam_details['end_date'] ?></h2>
                            </div>  
                            <ul class="header-dropdown">
                                <li><a href="<?= $baseurl ?>ExamListing?classId=<?= $cls_details['id'] ?>&subId=<?= $sub_details['id'] ?>" class="btn btn-info"><?= $bcklbl ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="body" id="examss">
                        <?php  echo $this->Form->create(false , ['action' => 'submitcustomizeexam', 'id' => "customexamsubmitform", 'enctype' => "multipart/form-data"  ,  'method' => "post"]); ?>
                            <input type="hidden" name="studentid" id="studentid" value="<?= $studentid ?>" >
                            <input type="hidden" name="examid" id="examid" value="<?= $exam_details['id'] ?>" >
                            <input type="hidden" name="totalque" id="totalque" value="<?= count($ques_details) ?>">
                            <?php
                            $n =1;
                    	    $questns = "<table style='width: 100%; '>";
                    	    if(!empty($ans_details))
                    	    {
                    	        $explodeans = explode('~^|*~',$ans_details['answer_submit']);
                    	        //print_r($explodeans);
                    	        foreach($ques_details as $ques)
                        	    {
                        	        $questns .= '<tr>';
                        	        
                                	$questns .= '<th style="width: 100%; text-align:left; margin-top:30px; margin-left:15px;  font-weight:16px !important; margin-bottom:30px !important;">';
                                	
                                	$questns .= "<div class='form-group'  style='margin-left:20px; font-style:normal !important;'>
                                	<span id='ques' style=' width:95%; padding-top:10px;'>Question ".$n.".  ".ucfirst($ques['question'])."</span> 
                                	<span style='float:right; '>(".$ques['marks']." Points)</span> </div>";
                                	
                        	        if($ques['ques_type'] == "objective")
                        	        {
                        	            $questns .= "<div style='margin:7px 0;'>";
                        	            $options = explode("~^", $ques['options']);
                        	            $m = 1;
                        	            
                        	            foreach($options as $opt)
                        	            {
                        	                $questns .= "<div>";
                        	                foreach($explodeans as $key => $val)
                        	                {
                        	                    $ans = $key+1;
                        	                    
                        	                    if($n == $ans)
                        	                    {
                        	                        
                        	                        $checked= '';
                        	                        $checkboxval = explode(",", $val);
                        	                        if(in_array($opt, $checkboxval)) { $checked = "checked"; }
                        	                        $questns .= "<input type='radio' ".$checked." ".$disable." class='submitanswer' name='answers_".$n."[]' style='margin-left:25px; padding-top:5px;' value='".$opt."'>";
                        	                    }
                        	                }
                        	                $questns .= "<span id='options' style='margin-left:5px;'>".$opt."</span></div>";
                        	                $m++;
                        	                
                        	            }
                        	            $questns .= "</div>";
                        	            
                        	        }
                        	        elseif($ques['ques_type'] == "subjective")
                        	        {
                        	            
                        	            $questns .= "<div class='form-group'  style='margin-left:20px; font-style:normal !important;' >";
                        	            if($ques['max_words'] != "" )
                        	            {
                        	                $questns .= "<input type='hidden' id='wordlimitmax' value='".$ques['max_words']."'>";
                        	                $questns .= "<textarea rows='3' ".$disable." class='form-control mb-2 answercount submitanswer' id='countanswer_".$ques['max_words']."' name='answers_".$n."'>";
                        	                foreach($explodeans as $key => $val)
                        	                {
                        	                    $ans = $key+1;
                        	                    if($n == $ans)
                        	                    {
                                	                $questns .= $val ;
                                	            }
                        	                }
                        	                $questns .= "</textarea><small id='fileHelp' class='form-text text-muted mb-3' id='wordlimit'>Total word count: <span id='displaycount_".$ques['max_words']."'>0</span> words. Words left: <span id='wordleft_".$ques['max_words']."'>".$ques['max_words']."</span></small>";
                        	            
                        	            }
                        	            else
                        	            {
                        	                $questns .= "<textarea rows='3' ".$disable." class='form-control mb-2 submitanswer' name='answers_".$n."'>";
                        	                foreach($explodeans as $key => $val)
                        	                {
                        	                    $ans = $key+1;
                        	                    if($n == $ans)
                        	                    {
                        	                        $questns .= $val;
                        	                    }
                        	                }
                        	                $questns .= "</textarea>";
                        	            }
                        	            $questns .= "</div>";
                        	            
                        	        }
                                	$questns .= "</div></th></tr>";
                        	        $n++;
                        	    }
                    	    }
                    	    else
                    	    {
                    	        foreach($ques_details as $ques)
                        	    {
                        	        $questns .= '<tr>';
                        	        
                                	$questns .= '<th style="width: 100%; text-align:left; margin-top:30px; margin-left:15px;  font-weight:16px !important; margin-bottom:30px !important;">';
                                	
                                	$questns .= "<div class='form-group'  style='margin-left:20px; font-style:normal !important;'>
                                	<span id='ques' style=' width:95%; padding-top:10px;'>Question ".$n.".  ".ucfirst($ques['question'])."</span> 
                                	<span style='float:right; '>(".$ques['marks']." Points)</span> </div>";
                                	
                        	        if($ques['ques_type'] == "objective")
                        	        {
                        	            $questns .= "<div style='margin:7px 0;'>";
                        	            $options = explode("~^", $ques['options']);
                        	            $m = 1;
                        	            
                        	            foreach($options as $opt)
                        	            {
                        	                $questns .= "<div><input type='radio' class='submitanswer' name='answers_".$n."[]' style='margin-left:25px; padding-top:5px;' value='".$opt."'> <span id='options' style='margin-left:5px;'>".$opt."</span></div>";
                        	                $m++;
                        	            }
                        	            $questns .= "</div>";
                        	            
                        	        }
                        	        elseif($ques['ques_type'] == "subjective")
                        	        {
                        	            
                        	            $questns .= "<div class='form-group'  style='margin-left:20px; font-style:normal !important;' >";
                        	            if($ques['max_words'] != "" )
                        	            {
                        	                $questns .= "<input type='hidden' id='wordlimitmax' value='".$ques['max_words']."'>";
                        	                $questns .= "<textarea rows='3'  class='form-control mb-2 answercount submitanswer' id='countanswer_".$ques['max_words']."' name='answers_".$n."'></textarea>";
                        	                $questns .= "<small id='fileHelp' class='form-text text-muted mb-3' id='wordlimit'>Total word count: <span id='displaycount_".$ques['max_words']."'>0</span> words. Words left: <span id='wordleft_".$ques['max_words']."'>".$ques['max_words']."</span></small>";
                        	            }
                        	            else
                        	            {
                        	                $questns .= "<textarea rows='3'  class='form-control mb-2 submitanswer' name='answers_".$n."'></textarea>";
                        	            }
                        	            $questns .= "</div>";
                        	            
                        	        }
                                	$questns .= "</div></th></tr>";
                        	        $n++;
                        	    }
                    	    }
                    	    
                    	    $questns .= "</table>";
                    	    echo $questns; ?>
                    	    
                    	    <div class="col-md-12">
                                <div class="error" id="subexamerror"></div>
                                <div class="success" id="subexamsuccess"></div>
                            </div>
                            <div class="button_row" >
                                <hr>
                                <button type="submit" name="submit_passcode" <?= $disable ?> id="submit_exam" class="btn btn-primary mr-2 submit_exam"><?= $submtlbl ?> </button>
                            </div>
                    
                            <?php echo $this->Form->end();  ?>
                    </div>
                </div>
            </div>
        </div>
        <?php   echo $this->Form->create(false , ['method' => "post"  ]);  echo $this->Form->end(); ?>
    </div>
</div>
