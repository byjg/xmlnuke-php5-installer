<?php

function showStep0()
{
	writeSection("Minimum Environment");
	$result = (version_compare(phpversion(), "5.0.0", ">"));

	writeInputMultipleList('checkbox', "phpversion", "PHP Version", array(array(
			"id"=>'chk-phpversion',
			"label" => phpversion() . ' - ' . ($result ? 'Ok' : 'failed (requires minimun 5.0.0)'),
			"value" => '1',
			"checked" => $result,
			"disabled" => true
		))
	);

	if (!$result)
		exit;
	else
	{					
		writeSection("PHP Extensions Installed");
		$result = checkExtensions(getExtensions()) || $result;

		writeSection("PHP.ini setup");
		$result = checkPHPIni(getPHPIni()) || $result;
	}
	
	return $result;
}

function validateStep0($nextStep)
{
	if ($nextStep == "")
		$step = 0;
	else
		$step = 1;

	return array($step, "", array());
}
?>
