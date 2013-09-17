<?php

function showStep3()
{
	writeSucess("Installation finished with sucess", 
			"The XMLNuke instalation was successfull done. " .
			"Please check the post-install actions you have to do manually.", array());

	
	$xmlnukePath = getValue("xmlnuke-path");
	$projectPath = getValue("project-path");


	writeSection("Required Steps");
	
	$data = array();
	
	$data[] = array(
		"id"=>'element',
		"label" => "Create a symbolic link (GOOD): ln -s \"$xmlnukePath" . DIRECTORY_SEPARATOR . "xmlnuke-common\" \"$projectPath" . DIRECTORY_SEPARATOR . "common\" ",
		"value" => '1',
		"checked" => false,
		"disabled" => true
	);
	
	$data[] = array(
		"id"=>'element',
		"label" => "Setup a virtual directory 'common' pointing to '$xmlnukePath" . DIRECTORY_SEPARATOR . "xmlnuke-common'. (e.g. Alias /common \"$xmlnukePath" . DIRECTORY_SEPARATOR . "xmlnuke-common\"",
		"value" => '1',
		"checked" => false,
		"disabled" => true
	);
	
	$data[] = array(
		"id"=>'element',
		"label" => "Copy ALL contents from '$xmlnukePath" . DIRECTORY_SEPARATOR . "xmlnuke-common\' to '$projectPath" . DIRECTORY_SEPARATOR . "common' (worst scenario) ",
		"value" => '1',
		"checked" => false,
		"disabled" => true
	);

	echo '<img src="common/imgs/logo_xmlnuke.gif" alt="If you dont see the image, please follow the steps bellow" onerror="this.src=\'imgs/error.gif\';"/><br/>';
	
	writeInputMultipleList('radio', 'review', 'Make the folder /common accessible from your web browser (e.g. http://server/yourproject/common) :', $data, 
			"Without this option some xmlnuke components and images will not work as expected. This is very important task. Choose one of the options bellow.");
	
	writeSection("Optional Steps");
	
	$data = array();

	$data[] = array(
		"id"=>'element',
		"label" => "Make the xmlnuke path '$xmlnukePath' readonly",
		"value" => '1',
		"checked" => !is_writable($xmlnukePath),
		"disabled" => true
	);
	$data[] = array(
		"id"=>'element',
		"label" => "Make the xmlnuke data path '$xmlnukePath" . DIRECTORY_SEPARATOR . "xmlnuke-data' writable",
		"value" => '1',
		"checked" => is_writable($xmlnukePath . "/xmlnuke-data"),
		"disabled" => true
	);
	$data[] = array(
		"id"=>'element',
		"label" => "Make the project path '$projectPath' readonly",
		"value" => '1',
		"checked" => !is_writable($projectPath),
		"disabled" => true
	);
	$data[] = array(
		"id"=>'element',
		"label" => "Make the project data path '$projectPath" . DIRECTORY_SEPARATOR . "data' writable",
		"value" => '1',
		"checked" => is_writable($projectPath . "/data"),
		"disabled" => true
	);
	
	writeInputMultipleList('checkbox', 'review', 'Review your post install checks:', $data, 
			"Please, check if ALL steps are checked. If you skip to validate your installation maybe is insecure or missing some final adjstment");


	
	return true;
}

function validateStep3($nextStep)
{
	return array(3, "", array());
}
?>
