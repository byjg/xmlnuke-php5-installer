<?php

/**
 * 
 * @param type $array
 * @return type
 */
function checkExtensions($array)
{
	$error = false;
	
	$count = 1;
	
	foreach ($array as $title=>$items)
	{
		$data = array();
		$countChk = 1;
		$checkTrue = $items[0];
		
		foreach ($items[1] as $key=>$value)
		{
			if (is_null($checkTrue))
				$ok = extension_loaded($key);
			elseif ($checkTrue)
			{
				$ok = extension_loaded($key);
				$error = $error || !$ok;
			}
			elseif (!$checkTrue)
			{
				$ok = !extension_loaded($key);
				$error = $error || !$ok;
			}

			$data[] = array(
				"id"=>'ext-' . $count . '-chk-' . ($countChk++),
				"label" => $key . ' - ' . $value,
				"value" => '1',
				"checked" => $ok,
				"disabled" => true
			);
			
		}

		writeInputMultipleList('checkbox', 'ext' . $count, $title, $data);
		$count++;
	}
	
	return $error;
}


/**
 * 
 * @param type $array
 * @return boolean
 */
function checkPHPIni($array)
{
	$error = false;
	
	foreach ($array as $title=>$items)
	{
		$data = array();
		$checkTrue = $items[0];
		
		foreach ($items[1] as $key=>$value)
		{
			$iniGet = ini_get($key);
			$keyCmp = parseValue($iniGet);
			$valueCmp = parseValue($value);
			if (gettype($valueCmp) == "string" && gettype($keyCmp) == "string")
				$result = $keyCmp == $valueCmp;
			elseif (is_numeric($valueCmp))
				$result = intval($keyCmp) >= intval($valueCmp);
			else
				$result = $keyCmp == $valueCmp;
		
			if (is_null($checkTrue))
				$ok = $result;
			elseif ($checkTrue)
			{
				$ok = $result;
				$error = $error || !$ok;
			}
			elseif (!$checkTrue)
			{
				$ok = !$result;
				$error = $error || !$ok;
			}
			
			if (is_bool($value))
			{
				$value = ($value ? "On" : "Off");
				$iniGet = ($iniGet ? "On" : "Off");
			}

			$data[] = array(
				"id"=>'element',
				"label" => $key . ' - (Required: ' . $value . ', found: ' . $iniGet . ')',
				"value" => '1',
				"checked" => $ok,
				"disabled" => true
			);
		}
		
		writeInputMultipleList('checkbox', 'phpini', $title, $data);
	}
	return $error;
}


function parseValue($value)
{
	if (is_bool($value))
		return $value;

	if (strlen($value) < 2)
		return $value;

	if ($value[strlen($value)-1] == "M")
		$value = intval(substr($value, 0, strlen($value)-1)) * 1024;

	return $value;
}

function getValue($key, $array = null)
{
	if (!is_array($array))
		$array = $_REQUEST;
	
	if (array_key_exists($key, $array))
		return $array[$key];
	else
		return "";
}

function commandExists($cmd)
{
	if (PHP_OS == 'Linux')
	{
		$returnVal = shell_exec("which $cmd");
		return (empty($returnVal) ? false : true);	
	}
	else
		return false;
}

?>
