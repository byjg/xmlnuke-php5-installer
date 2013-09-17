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
	if (!isWindows())
	{
		$returnVal = shell_exec("which $cmd");
		return (empty($returnVal) ? false : true);	
	}
	else
		return false;
}

function isWindows()
{
	return (strpos(strtolower(PHP_OS), 'win') !== false) && (strpos(strtolower(PHP_OS), 'cyg') === false);
}

function downloadFile($url, $checkMd5 = true)
{
	$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';  
	$file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($url);
	
	// File already download
	if (file_exists($file))
		return $file;

	// make the cURL request to $url  
	$ch = curl_init();  
	$fp = fopen($file . '.download', "w");  
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);  
	curl_setopt($ch, CURLOPT_URL,$url);  
	curl_setopt($ch, CURLOPT_FAILONERROR, true);  
	curl_setopt($ch, CURLOPT_HEADER,0);  
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);  
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);  
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);   
	curl_setopt($ch, CURLOPT_FILE, $fp);  
	$page = curl_exec($ch);  
	if (!$page) 
	{
		//echo 'Curl error: ' . curl_error($ch);
		return false;
	}  
	curl_close($ch); 
	
	if ($fp)
		fclose($fp);
	
	rename($file . '.download', $file);
	
	return $file;
}

function loadFile($filename)
{
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	
	return $contents;
}

function unzipFile($file, $extractTo)
{
	$zip = new ZipArchive;  
	if (!$zip) 
	{  
		return false;
	}  
	
	if($zip->open($file) != true) 
	{  
		return false;
	}  
	$zip->extractTo($extractTo); 
	$zip->close(); 								
}

?>
