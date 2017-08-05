<?php
	require_once("assets/class/goldendata.php");
	require_once("assets/class/goldengators.php");
	$GoldenGators = new GoldenGators;
	session_start();


	if (isset($_GET['modal-body'])) {
		$main = "
		<div class='box box-primary'>
        	<div class='box-body'>
          		<form id='pEntry' class='form' method='POST'>
          			<div class='form-group'>
	                  	<label>Player Name:</label>
	                  	<select class='form-control' name='playerNames'>
                        <option value='4300302473'>gg_andre</option><option value='115970401461'>gg_andre</option><option value='197570594374'>Princess A</option><option value='304947981230'>Lexivore</option><option value='304948423504'>James</option><option value='257702214271'>AussieGuy</option><option value='240523994983'>WhityMighty</option><option value='231939872898'>rivers</option><option value='103082493498'>Andres 2</option><option value='240523548195'>Ironman</option><option value='146038965029'>Art2D2</option><option value='262004645700'>moose knuckle</option><option value='7362013'>*! Ruff Drum !*</option><option value='210466104078'>Nand</option><option value='77315069584'>De Facto Mango</option><option value='12885844788'>Tatitatito</option><option value='9542756'>ronaldo_daksh</option><option value='188981024506'>Earl Belshire</option><option value='206162078953'>ZeroCool</option><option value='167504656403'>matt porter</option><option value='85906137295'>sinner</option><option value='12888759235'>your mr boss</option><option value='253406395215'>cell</option><option value='176094124858'>Wild Ginger</option><option value='236225385255'>MsEbomb</option><option value='214750283687'>Dr.J</option><option value='30071932933'>The Legal Black</option><option value='68726343894'>MONKEY D LUFFY!</option><option value='47248572442'>Orijacob</option><option value='107381819855'>PurPle_DraGon75</option><option value='107376953698'>Mini_724</option><option value='197572913329'>MunchieMinions</option><option value='262003290465'>JuanKas</option><option value='21481332836'>MrRubberDuck</option><option value='201876697956'>The Bomberang</option><option value='115973775927'>Balefire</option><option value='21477475721'>OUSooners01</option><option value='77316926501'>lilys</option><option value='188984229912'>Jamie.70</option><option value='266297889686'>ww3</option><option value='223339052447'>Atom</option><option value='210461557021'>zidane</option><option value='214763511462'>creek</option><option value='150331053465'>art</option><option value='210465557393'>Ms. Dazey</option><option value='292064437542'>ZeroCool</option><option value='158918392254'>Happy-Happy</option><option value='270595827787'>Curly</option><option value='103086126224'>RepeaterCreeper</option><option value='219049414273'>Neo</option>
                        </select>
	                </div>
          		</form>
          	</div>
        </div>";

        echo $main;
	} else {
		if (in_array($_SESSION['userTag'], $GoldenGators->admins)) {
			echo "<button class='btn btn-primary' id='pEntrySub' type='submit'>Add Player</button>";
		} else {
			session_destroy();
			session_unset();
			header('Location: /');
		}
	}
?>