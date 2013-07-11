<?php

function showStep2()
{
	$projectPath = getValue("project-path");
	
	if (file_exists($projectPath . "/config.inc.php"))
		include_once $projectPath . "/config.inc.php";

	if (array_key_exists("xmlnuke_ROOTDIR", $_POST))
	{
		$configValues = array();
		foreach ($_POST as $key=>$value)
		{
			$name =  str_replace('_', '.', $key);

			if (strpos($key, 'xmlnuke') !== false)
			{
				if ($key == 'xmlnuke_LANGUAGESAVAILABLE')
				{
					$qty = intval($_POST[$key]);
					$value = "";
					for($i=0; $i<$qty; $i++)
					{
						if ($_POST[$key . $i] != "") $value .= ($value!="" ? "|" : "") . $_POST[$key . $i];
					}
				}
				elseif ( ($key == 'xmlnuke_EXTERNALSITEDIR') || ($key == 'xmlnuke_PHPLIBDIR') )
				{
					$qty = intval($_POST[$key]);
					$value = "";
					for($i=0; $i<$qty; $i++)
					{
						$siteName = $_POST[$key . $i . "_key"];
						if ($siteName != "")
						{
							$sitePath = $_POST[$key . $i . "_value"];
							if (!file_exists($sitePath))
							{
								$errors[] =  "The config option '$key' has a hey '$siteName' which defines a directory '$sitePath' that does not exists.";
							}
							$value .= ($value!="" ? "|" : "") . $siteName . "=" . $sitePath;
						}
					}
				}

				$configValues[$name] = $value;
			}
		}		
	}
	else if (class_exists("config", false))
	{
		$configValues = config::getValuesConfig();
		$failed = false;
	}
	else
	{
		// Default Values
		$configValues = array();
		$configValues["xmlnuke.ROOTDIR"]="data";
		$configValues["xmlnuke.USEABSOLUTEPATHSROOTDIR"] = false;
		$configValues["xmlnuke.URLMODULE"]="xmlnuke.php";
		$configValues["xmlnuke.URLXMLNUKEADMIN"]="xmlnukeadmin.php";
		$configValues["xmlnuke.URLXMLNUKEENGINE"]="xmlnuke.php";
		$configValues["xmlnuke.DEFAULTSITE"]="default";
		$configValues["xmlnuke.DEFAULTPAGE"]="page";
		$configValues["xmlnuke.LOGINMODULE"]="login";
		$configValues["xmlnuke.URLBASE"]="";
		$configValues["xmlnuke.DETECTMOBILE"]=true;
		$configValues["xmlnuke.XSLCACHE"]='NoCacheEngine';
		$configValues["xmlnuke.SHOWCOMPLETEERRORMESSAGES"]=true;
		$configValues["xmlnuke.LANGUAGESAVAILABLE"]="en-us=English (United States)|pt-br=Português (Brasil)";
		$configValues["xmlnuke.SMTPSERVER"]="";
		$configValues["xmlnuke.USERSDATABASE"]="";
		$configValues["xmlnuke.USERSCLASS"]="";
		$configValues["xmlnuke.DEBUG"] = false;
		$configValues["xmlnuke.CAPTCHACHALLENGE"] = "hard";
		$configValues["xmlnuke.CAPTCHALETTERS"] = 5;
		$configValues["xmlnuke.ENABLEPARAMPROCESSOR"] = true;
		$configValues["xmlnuke.USEFULLPARAMETER"] = true;
		$configValues["xmlnuke.CHECKCONTENTTYPE"] = true;
		$configValues["xmlnuke.CACHESTORAGEMETHOD"] = "PLAIN";
		$configValues["xmlnuke.XMLSTORAGEMETHOD"] = "PLAIN";
		$configValues["xmlnuke.EXTERNALSITEDIR"] = "";
		$configValues["xmlnuke.PHPLIBDIR"] = "";
		$configValues["xmlnuke.PHPXMLNUKEDIR"] = "";

		if (file_exists("$projectPath/config.default.php"))
		{
		        include_once("$projectPath/config.default.php");
		}
	}

	$langs = getLangs();
	$xslCache = getStorageMethods();

	
	writeSection("Minimum Setup");

	
	writeInputData($configValues, "xmlnuke.ROOTDIR", 
		"Path to the xmlnuke-data folder.", 1, 
		"e.g. /opt/xmlnuke/xmlnuke-data");

	writeInputData($configValues, "xmlnuke.PHPXMLNUKEDIR",
		"Path to the xmlnuke-php5 folder", 1, 
		"e.g. /opt/xmlnuke/xmlnuke-php5");
	
	writeInputData($configValues, "xmlnuke.USEABSOLUTEPATHSROOTDIR",
		"(Deprecated, use true) Defines if xmlnuke.ROOTDIR and xmlnuke.PHPXMLNUKEDIR " .
		"and other folder have your paths absolute (true) or relative (false)", 2);

	writeInputData($configValues, "xmlnuke.XSLCACHE",
		"Defines the cache engine used to stored the XSL transformations and snippets.  " .
		"In a development environment set to 'NoCacheEngine' and in production environment set  ".
		"'FileSystemCacheEngine' at least", 3, null, $xslCache);

	writeInputData($configValues, "xmlnuke.SMTPSERVER",
		"Smtpserver. Smtp Server. You can use the format smtp://user:pass@server:port or " . 
		"ssl://user:pass@server:port for sending from an valid SMTP server;  " .
		"define a SERVERNAME or leave blank for use the sendmail PHP method.", 1, 
		"smtp://user:pass@server:port");

	writeInputData($configValues, "xmlnuke.USEFULLPARAMETER",
		"If true, XMLNuke will complete all basic parameters (xml, xsl, site and lang). " .
		"If false, XMLNuke will complete only the values are different from default values ", 2);

	writeInputData($configValues, "xmlnuke.USERSDATABASE",
		"Where XMLNuke look up for the users. Leave empty to store in single XML, or put a value " .
		"for a valid connection string in XMLNuke.", 1);

	writeInputData($configValues, "xmlnuke.USERSCLASS",
		"XMLNuke will use this class for access custom access users. Empty values uses the default class. ", 1);

	writeInputData($configValues, "xmlnuke.LOGINMODULE",
		"Default Login Module", 1);

    writeInputData($configValues, "xmlnuke.EXTERNALSITEDIR",
    	"Sets the path for sites that are not stored within the structure of xmlnuke.ROOTDIR. " .
    	"You need to configure a pair of values in this option. The first value defines the name of the site,  " .
    	"and the second defines the physical path of the site. You can safely leave this option blank.", 4);

    writeInputData($configValues, "xmlnuke.PHPLIBDIR",
    	"Defines the search path directory for USER LIB generated projects. You need to configure a pair of values in this option. " .
    	"The first value defines the namespace prefix and the second defines the physical path of the files. " .
    	"If you are developing your own modules, you should consider to use this option", 4);

	writeSection("Miscelaneous & Personalization");
	
	writeInputData($configValues, "xmlnuke.URLXMLNUKEENGINE",
		"The script name of XMLNuke front controller for run xmlnuke static pages", 1);

	writeInputData($configValues, "xmlnuke.URLMODULE",
		"The script name of XMLNuke front controller for execute modules", 1);

	writeInputData($configValues, "xmlnuke.URLXMLNUKEADMIN",
		"The script name of XMLNukeAdmin front controller", 1);

	writeInputData($configValues, "xmlnuke.DEFAULTSITE",
		"Default site name", 1);

	writeInputData($configValues, "xmlnuke.DEFAULTPAGE",
		"Default XSL Style", 1);

	writeInputData($configValues, "xmlnuke.URLBASE",
		"Define the base URL of XMLNuke installation. For example: " .
		"http://www.somesite.com/xmlnuke-php5/. " .
		"This is optional and you can safely leave blank this parameter.", 1, 
		"e.g. http://www.mysite.com/");

	writeInputData($configValues, "xmlnuke.DETECTMOBILE",
		"Enable/Disable detecting mobile client and the switch the mobile XSL.", 2);

	writeInputData($configValues, "xmlnuke.SHOWCOMPLETEERRORMESSAGES",
		"Show complete and usefull information for debug. Disable this option in production environments", 2);

	writeInputData($configValues, "xmlnuke.LANGUAGESAVAILABLE",
		"Default set of Languages XMLNuke Expected to find. This set may override at admin " .
		"tool CustomConfig", 9999, null, $langs);

	writeInputData($configValues, "xmlnuke.DEBUG",
		"Put XMLNuke in Debug mode", 2);

    writeInputData($configValues, "xmlnuke.CAPTCHACHALLENGE",
    	"How will be the captcha challenge question.", 3, null,
    	array("easy" => "Easy", "hard" => "Hard"));

    writeInputData($configValues, "xmlnuke.CAPTCHALETTERS",
    	"How many letters will be use to build the captcha", 3, null,
    	array("5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10"));

    writeInputData($configValues, "xmlnuke.ENABLEPARAMPROCESSOR",
		"Enable or disable the PARAMPROCESSOR diretive. ".
		"ParamProcessor enable post processing on your XML/XSL transformed looking for [param:....]. " .
		"This feature, if enable, uses resources from your Web Server causing low performance. ".
		"TIP: If high performance is critical, set to false this option. ", 2);

	writeInputData($configValues, "xmlnuke.CHECKCONTENTTYPE",
		"XMLNuke can check if a XSL transformation generate a document with a specific type. " .
		"The relation between XSL and content is located at: setup/content-type.anydata.xml ", 2);

    writeInputData($configValues, "xmlnuke.CACHESTORAGEMETHOD",
    	"How XMLNuke will be store the cache in filesystem", 3, null,
    	array("PLAIN" => "Plain(Flat) directory", "HASHED" => "Hashed Directory Structure"));

    writeInputData($configValues, "xmlnuke.XMLSTORAGEMETHOD",
    	"How XMLNuke will be store XML documents in filesystem", 3, null, 
    	array("PLAIN" => "Plain(Flat) directory", "HASHED" => "Hashed Directory Structure"));

	return true;
}

function validateStep2($nextStep)
{
	$errors = array();
	$projectPath = getValue("project-path");

	$fileContent =
		"## CONFIG FILE AUTO-GENERATED on " . date('c') . "\n" .
		"class config \n" .
		"{ \n" .
		"	public static function getValuesConfig()\n" .
		"	{\n" .
		"		\$values = array();\n";

	foreach ($_POST as $key=>$value)
	{
		if (strpos($key, "xmlnuke")===false)
			continue;
		
		$name =  "'" . str_replace('_', '.', $key) . "'";

		if ($key == 'xmlnuke_ROOTDIR')
		{
			if (!file_exists($value))
			{
				$errors[] = "Directory '$value' defined in 'xmlnuke.ROOTDIR' does not exists";
			}
			elseif (!is_writeable($value))
			{
				$errors[] = "Directory '$value' and its subdirectories must be writeable in order to complete XMLNuke setup. Check this and try again.";
			}
		}
		elseif ($key == 'xmlnuke_LANGUAGESAVAILABLE')
		{
			$qty = intval($_POST[$key]);
			$langs = getLangs();
			$value = "";
			for($i=0; $i<$qty; $i++)
			{
				if ($_POST[$key . $i] != "") $value .= ($value!="" ? "|" : "") . $_POST[$key . $i] . "=" . $langs[$_POST[$key . $i]];
			}
		}
		elseif ( ($key == 'xmlnuke_EXTERNALSITEDIR') || ($key == 'xmlnuke_PHPLIBDIR') )
		{
			$qty = intval($_POST[$key]);
			$value = "";
			for($i=0; $i<$qty; $i++)
			{
				$siteName = $_POST[$key . $i . "_key"];
				if ($siteName != "")
				{
					$sitePath = $_POST[$key . $i . "_value"];
					if (!file_exists($sitePath))
					{
						$errors[] =  "The config option '$key' has a hey '$siteName' which defines a directory '$sitePath' that does not exists.";
					}
					$value .= ($value!="" ? "|" : "") . $siteName . "=" . $sitePath;
				}
			}
		}
		elseif (($key == 'xmlnuke_PHPXMLNUKEDIR') && ($value != ""))
		{
			if (!file_exists($value))
			{
				$errors[] = "Directory '$value' defined in 'xmlnuke.PHPXMLNUKEDIR' does not exists";
			}
		}
		elseif ((strpos($key, 'xmlnuke_LANGUAGESAVAILABLE')!==false) ||
				(strpos($key, 'xmlnuke_EXTERNALSITEDIR')!==false) ||
				(strpos($key, 'xmlnuke_PHPLIBDIR')!==false) )
		{
			continue;
		}

		if ( ($value != "false") && ($value != "true") )
			$value = "'$value'";
		$fileContent .= "		\$values[$name] = $value;\n";
	}

	$fileContent .= "		return \$values;\n";
	$fileContent .= "	}\n" ;
	$fileContent .= "}\n" ;
	$fileContent .= "define('PHPXMLNUKEDIR', '" . ($_POST["xmlnuke_PHPXMLNUKEDIR"] != "" ? $_POST["xmlnuke_PHPXMLNUKEDIR"] . "/" : "") . "');\n";
	$fileContent .= "define('AUTOLOAD', 'AUTOLOAD');\n";
	$fileContent .= "## END-OF-FILE\n" ;

	//echo "<pre>";
	//echo $fileContent;
	//echo "</pre>";

	if (sizeof($errors) == 0)
	{
		if (!is_writeable("$projectPath/config.inc.php"))
		{
			$errors[] = "'config.inc.php' must be write able in order to complete the setup<br/><br/>";
		}
		else
		{
			@file_put_contents("$projectPath/config.inc.php", "<?php\n$fileContent\n?>");
			$err = error_get_last();
			if ((intval($err["type"]) == 1) || (intval($err["type"]) == 2))
			{
				$errors[] = $err["message"];
			}
		}
	}

	if (count($errors) > 0)
		$step = $nextStep - 1;
	else
		$step = $nextStep;

	return array($step, "", $errors);
}
?>
