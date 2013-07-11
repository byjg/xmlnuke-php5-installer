<?php

function showStep1()
{

	$projectPath = dirname(dirname($_SERVER['SCRIPT_FILENAME']));
	if (file_exists($projectPath . '/config.inc.php') && (getValue("xmlnuke-path") == ""))
	{
		include_once $projectPath . '/config.inc.php';
		$config = new config();
		$configValue = $config->getValuesConfig();
		$xmlnukePath = dirname($configValue["xmlnuke.PHPXMLNUKEDIR"]);
		$xmlnukeDownload = "";
	}
	else
	{
		$configValue = null;
		$xmlnukePath = getValue("xmlnuke-path");
		$projectPath = getValue("project-path");
		$xmlnukeDownload = getValue("xmlnuke-download-zip");
	}
	
	writeSection("Define XMLNuke Path");

	writeInputText("xmlnuke-path", "XMLNuke Path", $xmlnukePath, 
			(!isWindows() ? "e.g. /opt/xmlnuke" : "e.g. d:\xmlnuke"), 
			"Define here the main path of XMLNuke project. You can get this" .
			"path from SCM repository or by downloading the ZIP package.");

	$options = array();
	$options["no"] = 'Do not try to create the path';

	$releasesJson = downloadFile(XMLNUKE_RELEASES);
	if ($releasesJson !== false)
	{
		$releases = json_decode(loadFile($releasesJson));
		foreach ($releases->releases as $release)
		{
			foreach ($release->sources as $source)
			{
				switch ($source->type)
				{
					case "from-repo":
						if (commandExists('svn'))
							$options[$release->name . ":from-repo"] = $source->description;
						break;
					
					case "download":
						if (extension_loaded('curl') && extension_loaded('zip'))
							$options[$release->name . ":download"] = $source->description;
						break;
				}
			}
		}
	}
	
	writeInputSelect('xmlnuke-create', 'Try to create path if not exists?', 
			$options,
			"Select an option to install the XMLNuke project. Some options are " . 
			"available only in Linux and requires some extra packages installed "
	);
	
	//writeInputText("xmlnuke-download-zip", "XMLNuke Download", $xmlnukeDownload, 
	//		"Define here the main path of XMLNuke project. You can get this" .
	//		"path from SCM repository or by downloading the ZIP package.");
	
	
	writeSection("Define Project Settings");

	writeInputText("project-path", "Project Path", $projectPath, 
			(!isWindows() ? "e.g. /var/www" : "e.g. c:\Inetpub\wwwroot"), 
			"Define the path of your project. You can create and " . 
			"configure a new project after.");
	
	writeInputSelect('project-create', 'Try to create path if not exists?', 
			array(
				"no"=>'Do not try to create the path',
				"yes"=>'Try to create and run create-php-project',
			),
			"Select an option to create a project. Some options are " . 
			"available only in Linux "
	);
	
	writeInputText("project-lib-name", "Default project root namespace (lib)", "", "e.g. default", 
			"Defines what is the default project root namespace for the folder lib of your project");
	
	writeInputSelect('project-langs', 'Select the languages available to project', 
			getLangs(),
			"You have to select in the list bellow all the languages available to your project. " .
			"This languages need to be the same in the next step.", 
			array("en-us", "pt-br", "", "")
	);
	
	return true;
}

function validateStep1($nextStep)
{
	$xmlnukePath = getValue("xmlnuke-path");
	$projectPath = getValue("project-path");
	
	$message = "";
	$errorList = array();
	$step = $nextStep;
	
	if ($xmlnukePath != "")
	{
		if ((getValue("xmlnuke-create") != "no") || file_exists($xmlnukePath))
		{
			$releasesJson = downloadFile(XMLNUKE_RELEASES);
			if ($releasesJson !== false)
			{
				$releases = json_decode(loadFile($releasesJson));
				foreach ($releases->releases as $release)
				{
					foreach ($release->sources as $source)
					{
						if (getValue("xmlnuke-create") == $release->name . ":" . $source->type)
						{
							if (!file_exists($xmlnukePath))
								$result = @mkdir($xmlnukePath, 0777, true);
							else
								$result = true;
								
							if ($result)
							{
								switch ($source->type)
								{
									case "from-repo":
										shell_exec ("svn checkout " . $source->url . " '$xmlnukePath'");
										shell_exec ("$xmlnukePath/copy-dist-files.sh link");
										break;
									
									case "download":
										$zipFile = downloadFile($source->url);
										if (!$zipFile)
											$errorList[] = "I cannot download the package in '" . $source->url . "";
										else
											unzipFile($zipFile, $xmlnukePath);
										break;
									default:
										$errorList[] = "I cannot find the type '" . $source->type . "' in the releases.json";
										break;
								}
							}
							else
								$errorList[] = "I cannot create the XMLNuke path '" . $xmlnukePath . "'";
						}
					}
				}
			}
			else
				$errorList[] = "I cannot read the XMLNuke releases.json";			
		}
		else
			$errorList[] = "The XMLNuke path does not exists and you do not select an alternate install method. Check if the directory is correct;";
		
		if (count($errorList) > 0)
			$step = $nextStep - 1;
	}
	
	if ((count($errorList) == 0) && (!file_exists($xmlnukePath . "/xmlnuke-php5") || !file_exists($xmlnukePath . "/xmlnuke-common") || !file_exists($xmlnukePath . "/xmlnuke-data")))
	{
		$errorList[] = "The path provided does not appear to be a valid XMLNuke library;";
		$step = $nextStep - 1;
	}
	
	if (($projectPath != "") && !file_exists($projectPath))
	{
		if (getValue("project-create") == "yes")
		{
			if ($xmlnukePath == "")
			{
				$errorList[] = "The XMLNuke path is empty. Cannot create a project.;";
				$step = $nextStep - 1;				
			}
			elseif  (!commandExists("$xmlnukePath/create-php5-project.sh"))
			{
				$errorList[] = "The XMLNuke path '$xmlnukePath' you provided does not contain a valid XMLNuke project or it does not complete. Please check it and try again. ";
				$step = $nextStep - 1;				
			}
			else
			{
				$result = @mkdir($projectPath, 0777, true);
				if ($result)
				{
					$rootNamespace = getValue("project-lib-name");
					// Fix the name 
					$rootNamespace =  preg_replace('/\.+$/', '', 
										preg_replace('/^\.+/', '', 
											preg_replace('/(\.)\1+/', '.', 
												preg_replace('/[^\w]/', '.', 
													strtolower($rootNamespace)))));
					if ($rootNamespace == "") $rootNamespace = "default";

					$key = "project-langs";
					$qty = intval($_POST[$key]);
					$langs = "";
					for($i=0; $i<$qty; $i++)
					{
						if ($_POST[$key . $i] != "") $langs .= ($langs!="" ? " " : "") . $_POST[$key . $i];
					}

					shell_exec ("$xmlnukePath/create-php5-project.sh '$projectPath' default $rootNamespace $langs");
				}
				else
				{
					$errorList[] = "The Project path does not exists and I cannot create one; You have to give write permissions to this script in order to enable the installation.";
					$step = $nextStep - 1;
				}
			}
		}
		else
		{
			$errorList[] = "The project path does not exists, please choose another directory or try to create using this script; ";
			$step = $nextStep - 1;
		}
	}

	return array($step, $message, $errorList);
}
?>
