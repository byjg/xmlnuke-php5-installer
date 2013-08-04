<?php
require_once("data.php");
require_once("checks.php");
require_once("ui-html.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>XMLNuke PHP5 Installer</title>
		<link rel="stylesheet" type="text/css" href="css/view.css" media="all" />
		<script type="text/javascript" src="js/view.js"></script>

	</head>
	
	<?php
	$curStep = array_key_exists("step", $_REQUEST) ? $_REQUEST["step"] : "0";
	$nextStep = array_key_exists("nextstep", $_REQUEST) ? $_REQUEST["nextstep"] : "";
	$xmlnukePath = getValue("xmlnuke-path");
	$projectPath = getValue("project-path");
	
	$stepConfig = array(
		array("title"=>"Checking Requirements 1/4", "buttonNext" => "Specify XMLNuke and project path >>", "buttonPrevious" => ""),
		array("title"=>"Define XMLNuke paths 2/4", "buttonNext" => "Assign XMLNuke Path >>", "buttonPrevious" => "<< Go back"),
		array("title"=>"Setup Config File 3/4", "buttonNext" => "Write config file >>", "buttonPrevious" => "<< Go back"),
		array("title"=>"Installation Finished 4/4", "buttonNext" => "Check Post Install", "buttonPrevious" => "Review your installation")
	);
	
	if (($nextStep == "") || array_key_exists("nextBtn", $_REQUEST))
	{
		include_once('step' . $curStep . '.php');
		list($step, $message, $listmsg) = call_user_func("validateStep" . $curStep, $nextStep);
	}
	elseif (array_key_exists("previousBtn", $_REQUEST))
	{
		$step = $curStep-1;
		$message = "";
		$listmsg = array();
	}
	$nextStep = $step+1;
	
	include_once('step' . $step . '.php');
	
	?>
	
	<body id="main_body">

		<img id="top" src="imgs/top.png" alt="" />
		<div id="form_container">

			<h1><a>XMLNuke PHP5 Installer</a></h1>
			<form id="form_663469" class="appnitro"  method="post" action="index.php">
				
				<input type="hidden" name="step" value="<?php echo $step ?>" />
				<input type="hidden" name="nextstep" value="<?php echo $nextStep ?>" />
				<input type="hidden" name="xmlnuke-path" value="<?php echo $xmlnukePath ?>" />
				<input type="hidden" name="project-path" value="<?php echo $projectPath ?>" />

				<div class="form_description">
					<center><img src="imgs/logo_xmlnuke.gif" border="0" style="margin-bottom: 20px;" /></center>

					<h2>XMLNuke PHP5 Installer - <?php echo $stepConfig[$step]["title"]; ?></h2>
					<p>This script will aid you to install and setup your XMLNuke copy. Follow the steps to complete the install process.</p>
				</div>
				
				<ul >
					
					<?php
						if (($message != "") || (count($listmsg) > 0))
							writeInfo("Warning - Some errors found", $message, $listmsg);
					
						$result = call_user_func("showStep" . $step);

						writeInputButtons($result, $stepConfig[$step]["buttonNext"], $stepConfig[$step]["buttonPrevious"]);
					?>

				</ul>
			</form>	
			
			<div id="footer">
				XMLNuke installer create <a href="http://www.byjg.com.br/">by JG</a>
			</div>
		</div>
		<img id="bottom" src="imgs/bottom.png" alt="" />
	</body>
</html>
