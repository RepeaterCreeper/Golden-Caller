<div style='margin: 16px;' class='row'>
	<div class='box box-primary'>
		<div class='box-header'>
			<h6 style='font-family: Clash'>June 19, 2016 17:30:00</h6>
			<small>Golden Caller v2.0.0</small>
		</div>
		<div class='box-body'>
			<ul style='margin-left: 16px;'>
				<li><label class='label label-primary'>[NEW]</label> Kingdom Ranking</li>
				<li><label class='label label-primary'>[NEW]</label> Player Statistics</li>
				<li><label class='label label-primary'>[NEW]</label> Mini Accounts</li>
				<br>
				<li><label class='label label-success'>[IMPROVEMENT]</label> Setting stars has been improved</li>
				<li><label class='label label-success'>[IMPROVEMENT]</label> Entire system has been rewritten.</li>
				<hr>
				<li><label class='label label-default'>Release Note: </label><br>
					Seems really small but it should have drastically improved the performance and speed of the caller. Along with some new features as stated above and also Golden Gators 2.0 main website is coming soon! </li>
			</ul> 
		</div>
	</div>

	<div class='box box-danger'>
		<div class='box-header'>
			<h6 style='font-family: Clash'>May 21, 2016 12:00:00</h6>
			<small>Golden Caller v1.0.8</small>
		</div>
		<div class='box-body'>
			<ul style='margin-left: 16px;'>
				<li><label class='label label-primary'>[NEW]</label> Notification System</li>
				<li><label class='label label-primary'>[NEW]</label> Leadership can now call for members.</li>
				<li><label class='label label-primary'>[NEW]</label> Leadership now has unlimited calls.</li>
				<br>
				<li><label class='label label-success'>[IMPROVEMENT]</label> Little code cleanup</li>
			</ul> 
		</div>
	</div>
	<div class='box box-danger'>
		<div class='box-header'>
			<h6 style='font-family: Clash'>April 6, 2016 18:41:00</h6>
			<small>Golden Caller v1.0.7</small>
		</div>
		<div class='box-body'>
			<ul style='margin-left: 16px;'>
				<li><label class='label label-warning'>[FIXED]</label> TH Designation SELECT</li>
				<li><label class='label label-warning'>[FIXED]</label> Incorrect Calling Logic causing 'Already called' or '2 Entries'.</li>
				<li><label class='label label-warning'>[FIXED]</label> Calling causes 'DELETE', 'SET STAR', and more to not work.</li>
				<li><label class='label label-warning'>[FIXED]</label> Timer variables were incorrect causing incorrect expiration.</li>
				<li><label class='label label-warning'>[FIXEd]</label> New call not displaying timer, and cannot be deleted without refresh.</li>
				<br>
				<li><label class='label label-success'>[IMPROVEMENT]</label> Settings stars is now displayed INSTANTLY.</li>
			</ul> 
		</div>
	</div>

	<div class='box box-danger'>
		<div class='box-header'>
			<h6 style='font-family: Clash'>April 28, 2016 18:41:00</h6>
			<small>Golden Caller v1.0.6 - MINOR</small>
		</div>
		<div class='box-body'>
			<ul style='margin-left: 16px;'>
				<li><label class='label label-primary'>[NEW]</label> Distinct 'prep day' and 'war day'</li>
				<br>
				<li><label class='label label-danger'>[REMOVED]</label> Timer causes failure in system.</li>
			</ul>
		</div>
	</div>
	<div class='box box-danger'>
		<div class='box-header'>
			<h6 style='font-family: Clash'>April 16, 2016 18:28:00</h6>
			<small>Golden Caller v1.0.5</small>
		</div>
		<div class='box-body'>
			<ul style='margin-left: 16px;'>
				<li><label class='label label-primary'>[NEW]</label> Townhall Designation (IMAGE)</li>
				<li><label class='label label-primary'>[NEW]</label> War Timer</li>
				<br>
				<li><label class='label label-warning'>[FIXED]</label> Infinite amount of calls</li>
				<li><label class='label label-warning'>[FIXED]</label> Certain variables were not being used.</li>
			</ul>
		</div>
	</div>
	<div class='box box-danger'>
		<div class='box-header'>
			<h6 style='font-family: Clash'>April 14, 2016 18:28:00</h6>
			<small>Golden Caller v1.0.4</small>
		</div>
		<div class='box-body'>
			<ul style='margin-left: 16px;'>
				<li><label class='label label-primary'>[NEW]</label> Login/Signup has been redesigned</li>
				<li><label class='label label-primary'>[NEW]</label> Permissions has been added</li>
				<br>
				<li><label class='label label-success'>[IMPROVEMENT]</label> Pressing Delete call now closes the modal.</li>
				<br>
				<li><label class='label label-warning'>[FIXED]</label> Caller is now ordered by status ASCENDING.</li>
				<li><label class='label label-warning'>[FIXED]</label> Notes now save</li>
			</ul>
		</div>
	</div>

	<div class='box box-danger'>
		<div class='box-header'>
			<h6 style='font-family: Clash'>April 07, 2016 19:48:00</h6>
			<small>Golden Caller v1.0.3</small>
		</div>
		<div class='box-body'>
			<ul style='margin-left: 16px;'>
				<li><label class='label label-primary'>[NEW]</label> Synchronized calls. (No need to refresh.. updates every 5 seconds) [CONFIGURABLE]</li>
				<li><label class='label label-primary'>[NEW]</label> Added 3 starred base indication (Red - 3 starred | Green - Has not been 3 starred)</li>
				<li><label class='label label-primary'>[NEW]</label> Expired calls are no longer removed. They are just placed at back</li>
				<li><label class='label label-primary'>[NEW]</label> Notes has been added</li>
				<li><label class='label label-primary'>[NEW]</label> Stars has been added</li>
				<li><label class='label label-primary'>[NEW]</label> Signup will only accept players in clan.</li>
				<li><label class='label label-primary'>[NEW]</label> Icons to signify notes and/or infractions of player.</li>
				<br>
				<li><label class='label label-primary'>[NEW]</label> Changelog Page</li>
				<br>
				<li><label class='label label-success'>[IMPROVEMENT]</label> Refresh will now ONLY occur when changes are made.</li>
			</ul>
			<?php
				Pushpad\Pushpad::$auth_token = '74d05f18756a4ac4e17b69a92483d6f8';
				Pushpad\Pushpad::$project_id = 838; # set it here or pass it as a param to methods later
			?>
			<a href="<?= Pushpad\Pushpad::path_for('28JVGV92L') ?>">*</a>
		</div>
	</div>
</div>