<?php
	if (isset($_GET['modal-body'])) {
		echo "
			<div class='box box-primary'>
				<div class='box box-body'>
					<center>
						<h3 style='font-family: Clash;'>Select Result:</h3>
						<ul style='list-style-type: none;'>
							<li style='cursor: pointer;' onclick=\"saveResult('{$_GET['player']}', {$_GET['callNum']}, 0)\"><img src='assets/img/0starbig.png' height=30/></li>
							<li style='cursor: pointer;' onclick=\"saveResult('{$_GET['player']}', {$_GET['callNum']}, 1)\"><img src='assets/img/1starbig.png' height=30/></li>
							<li style='cursor: pointer;' onclick=\"saveResult('{$_GET['player']}', {$_GET['callNum']}, 2)\"><img src='assets/img/2starbig.png' height=30/></li>
							<li style='cursor: pointer;' onclick=\"saveResult('{$_GET['player']}', {$_GET['callNum']}, 3)\"><img src='assets/img/3starbig.png' height=30/></li>
						</ul>
					</center>
				</div>
			</div>
		";
	}	
?>