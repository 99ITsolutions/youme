
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="heading"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '909') { echo $langlbl['title'] ; } } ?></h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#addsession"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '910') { echo $langlbl['title'] ; } } ?></a></li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover js-basic-example dataTable table-custom table-striped m-b-0 c_list default_pageitem session_table" id="session_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <label class="fancy-checkbox">
                                                    <input class="select-all" type="checkbox" name="checkbox">
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '913') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '914') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '915') { echo $langlbl['title'] ; } } ?></th>
                                            <th><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '916') { echo $langlbl['title'] ; } } ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="sessionbody" class="modalrecdel">
                                    
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>



    <!------------------ Extra HTML --------------------->

    
<div class="modal animated zoomIn" id="addsession"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '919') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body ">
            <?php	echo $this->Form->create(false , ['url' => ['action' => 'addsession'] , 'id' => "addsessionform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">     
			   <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '920') { echo $langlbl['title'] ; } } ?></small>
			   <select class="form-control js-states addsession" name="startmonth" required>
				<option value="">Choose One</option>
				<option value="january">January</option>
				<option value="february">February</option>
				<option value="march">March</option>
				<option value="april">April</option>
				<option value="may">May</option>
				<option value="june">June</option>
				<option value="july">July</option>
				<option value="august">August</option>
				<option value="september">September</option>
				<option value="october">October</option>
				<option value="november">November</option>
				<option value="december">December</option>
			     </select>
 		                     
                       </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">     
			   <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '921') { echo $langlbl['title'] ; } } ?></small>	 
			   <select name="startyear" class="form-control addsession" required>
			        <option value="">Choose One</option>
			        <option value="2010">2010</option>
			        <option value="2011">2011</option>
			        <option value="2012">2012</option>
			        <option value="2013">2013</option>
			        <option value="2014">2014</option>
			        <option value="2015">2015</option>
			        <option value="2016">2016</option>
			        <option value="2017">2017</option>
			        <option value="2018">2018</option>
			        <option value="2019">2019</option>
			        <option value="2020">2020</option>
			        <option value="2021">2021</option>
			        <option value="2022">2022</option>
			        <option value="2023">2023</option>
			        <option value="2024">2024</option>
			        <option value="2025">2025</option>
			   </select>
			   </div>
        </div>

		   <div class="col-md-6">
                        <div class="form-group">     
			   <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '922') { echo $langlbl['title'] ; } } ?></small>
			   <select class="form-control addsession" name="endmonth" required>
				<option value="">Choose One</option>
				<option value="january">January</option>
				<option value="february">February</option>
				<option value="march">March</option>
				<option value="april">April</option>
				<option value="may">May</option>
				<option value="june">June</option>
				<option value="july">July</option>
				<option value="august">August</option>
				<option value="september">September</option>
				<option value="october">October</option>
				<option value="november">November</option>
				<option value="december">December</option>
	         	 </select>
 		                     
                       </div>
                    </div>
		
		    <div class="col-md-6">
                        <div class="form-group">     
			   <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '923') { echo $langlbl['title'] ; } } ?></small>
			   <select name="endyear" class="form-control addsession" required>
			        <option value="">Choose One</option>
			        <option value="2011">2011</option>
			        <option value="2012">2012</option>
			        <option value="2013">2013</option>
			        <option value="2014">2014</option>
			        <option value="2015">2015</option>
			        <option value="2016">2016</option>
			        <option value="2017">2017</option>
			        <option value="2018">2018</option>
			        <option value="2019">2019</option>
			        <option value="2020">2020</option>
			        <option value="2021">2021</option>
			        <option value="2022">2022</option>
			        <option value="2023">2023</option>
			        <option value="2024">2024</option>
			        <option value="2025">2025</option>
			        <option value="2026">2026</option>
			        
			   </select>
			                                  
                       </div>
                    </div>
	                   
                    <div class="col-md-12">
                        <div class="error" id="sesserror"></div>
                        <div class="success" id="sesssuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary addsessbtn" id="addsessbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '925') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '926') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>




    <!------------------ Edit Session --------------------->

    
<div class="modal animated zoomIn" id="editsession"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header header">
                <h6 class="title" id="defaultModalLabel"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1411') { echo $langlbl['title'] ; } } ?></h6>
            </div>
            <div class="modal-body ">
            <?php   echo $this->Form->create(false , ['url' => ['action' => 'editsession'] , 'id' => "editsessionform" , 'method' => "post"  ]); ?>

                <div class="row clearfix">
                    <input type="hidden" id="id"  name="id" >
		    <div class="col-md-6">
                        <div class="form-group">     
			   <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '920') { echo $langlbl['title'] ; } } ?></small>
			   <select class="form-control js-states addsession" id="startmonth" name="startmonth" required>
				<option value="">Choose One</option>
				<option value="january">January</option>
				<option value="february">February</option>
				<option value="march">March</option>
				<option value="april">April</option>
				<option value="may">May</option>
				<option value="june">June</option>
				<option value="july">July</option>
				<option value="august">August</option>
				<option value="september">September</option>
				<option value="october">October</option>
				<option value="november">November</option>
				<option value="december">December</option>
			     </select>
 		                     
                       </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">     
			   <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '921') { echo $langlbl['title'] ; } } ?></small>	
			   <select name="startyear" id="startyear" class="form-control addsession" required>
			       <option value="">Choose One</option>
			        <option value="2010">2010</option>
			        <option value="2011">2011</option>
			        <option value="2012">2012</option>
			        <option value="2013">2013</option>
			        <option value="2014">2014</option>
			        <option value="2015">2015</option>
			        <option value="2016">2016</option>
			        <option value="2017">2017</option>
			        <option value="2018">2018</option>
			        <option value="2019">2019</option>
			        <option value="2020">2020</option>
			        <option value="2021">2021</option>
			        <option value="2022">2022</option>
			        <option value="2023">2023</option>
			        <option value="2024">2024</option>
			        <option value="2025">2025</option>
			    </select>
			                <?php
                                /*$starting_year = 2010;
                                $current_year = date('Y')*1;
                                $nxt_year = 1 + $starting_year;
                                
                                echo '<select name="startyear" id="startyear" class="form-control addsession" required>';
                                echo '<option value="">Choose One</option>';
                                do {
                                    echo '<option value="'.$starting_year.'">'.$starting_year.'</option>';
                                    $starting_year++;
                                    $nxt_year++;
                                }
                                while ($current_year >= $starting_year);
                                echo '</select>';*/
                            ?> 	                            
                       </div>
                    </div>

		   <div class="col-md-6">
                        <div class="form-group">     
			   <small id="fileHelp" class="form-text text-muted"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '922') { echo $langlbl['title'] ; } } ?></small>
			   <select class="form-control addsession" id="endmonth" name="endmonth" required >
				<option value="">Choose One</option>
				<option value="january">January</option>
				<option value="february">February</option>
				<option value="march">March</option>
				<option value="april">April</option>
				<option value="may">May</option>
				<option value="june">June</option>
				<option value="july">July</option>
				<option value="august">August</option>
				<option value="september">September</option>
				<option value="october">October</option>
				<option value="november">November</option>
				<option value="december">December</option>
	         	 </select>
 		                     
                       </div>
                    </div>
		
		    <div class="col-md-6">
                        <div class="form-group">     
			   <small id="fileHelp" class="form-text text-muted"> <?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '923') { echo $langlbl['title'] ; } } ?></small>	           
			   <select name="endyear" id="endyear" class="form-control addsession" required>
			        <option value="">Choose One</option>
			        <option value="2011">2011</option>
			        <option value="2012">2012</option>
			        <option value="2013">2013</option>
			        <option value="2014">2014</option>
			        <option value="2015">2015</option>
			        <option value="2016">2016</option>
			        <option value="2017">2017</option>
			        <option value="2018">2018</option>
			        <option value="2019">2019</option>
			        <option value="2020">2020</option>
			        <option value="2021">2021</option>
			        <option value="2022">2022</option>
			        <option value="2023">2023</option>
			        <option value="2024">2024</option>
			        <option value="2025">2025</option>
			        <option value="2026">2026</option>
			   </select>
			                <?php
                               /* $starting_year = 2010;
                                $current_year = date('Y')*1;
                                $nxt_year = 1 + $starting_year;
                                
                                echo '<select name="endyear" id="endyear" class="form-control addsession" required>';
                                echo '<option value="">Choose One</option>';
                                do {
                                    echo '<option value="'.$nxt_year.'">'.$nxt_year.'</option>';
                                    $starting_year++;
                                    $nxt_year++;
                                }
                                while ($current_year >= $starting_year);
                                echo '</select>';*/
                            ?> 	                            
                       </div>
                    </div>
			
		
                    <div class="col-md-12">
                        <div class="error" id="editsesserror"></div>
                        <div class="success" id="editsesssuccess"></div>
                    </div>
                    <div class="button_row" >
                    <hr>
                    <button type="submit" class="btn btn-primary editsessbtn" id="editsessbtn"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '1412') { echo $langlbl['title'] ; } } ?></button>
                    <button type="button" class="btn btn-secondary" style="margin-right: 10px;" data-dismiss="modal"><?php foreach($lang_label as $langlbl) { if($langlbl['id'] == '926') { echo $langlbl['title'] ; } } ?></button>
                    </div>
                    
                   <?php echo $this->Form->end(); ?>
                   
                </div>
            </div>
             
        </div>
    </div>
</div>