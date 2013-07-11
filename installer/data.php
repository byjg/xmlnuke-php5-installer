<?php

define('XMLNUKE_RELEASES', 'http://www.xmlnuke.com/site/releases/releases.json');

function getExtensions()
{

	return
		array(
			"Required Extensions" => 
				array (
					true, 
					array(
						"session"=>"Core PHP",
						"Reflection"=>"Core PHP",
						"date"=>"Core PHP",
						"standard"=>"Core PHP",
						"libxml"=>"Core Xmlnuke",
						"dom"=>"Core Xmlnuke",
						"xml"=>"Core Xmlnuke",
						"xmlreader"=>"Core Xmlnuke",
						"xmlwriter"=>"Core Xmlnuke",
						"xsl"=>"Core Xmlnuke",
						"PDO"=>"Database Access (you must select one driver below)",
						"gd" => "For Captcha messages"
					)
				),
			"Conflicted Extensions (require uninstall)" => 
				array (
					false, 
					array(
						"domxml"=>"PHP Dom XML"
					)
				),
			"Optional Extensions" => 
				array (
					true, 
					array(
						"mbstring" => "Charset related. ",
						"memcached" => "Required for advanced cache XSL/XML page ",
						"curl" => "Required for RESTFul and HTTP Requests. ",
						"soap" => "Required for SOAP Requests and SOAP Server ",
						"zlib" => "For backup and install modules",
						"pdo_dblib"=>"FreeTDS/Sybase/MSSQL driver for PDO",
						"pdo_firebird "=>"Firebird/InterBase 6 driver for PDO",
						"pdo_ibm"=>"PDO driver for IBM databases",
						"pdo_informix"=>"PDO driver for IBM Informix INFORMIX databases",
						"pdo_mysql"=>"MySQL driver for PDO",
						"pdo_oci"=>"Oracle Call Interface driver for PDO",
						"pdo_odbc"=>"ODBC v3 Interface driver for PDO",
						"pdo_pgsql"=>"PostgreSQL driver for PDO",
						"pdo_sqlite"=>"SQLite v3 Interface driver for PDO"
					)
				)
		);


}


function getPHPIni()
{

	return
		array(
			"Required Configuration" => 
				array (
					true, 
					array(
						"memory_limit" => "64M"
					)
				),
			"Optional Configurarion" => 
				array (
					null, 
					array(
						"file_uploads" => true, 
						"post_max_size"=>"5M", 
						"register_globals" => false, 
						"magic_quotes_gpc" => false, 
						"register_long_arrays" => false, 
						"register_argc_argv"=>false, 
						"implicit_flush"=>false
					)
				)
		);


}

function getLangs()
{
	return 
		array(
			'-a' => '',
			'pt-br' => 'Português (Brasil)',
			'en-us' => 'English (United States)',
			'fr-fr' => 'Français',
			'it-it' => 'Italiano',
			'-b' => '',
			'ar-dz' => 'جزائري عربي',
			'bg-bg' => 'Български',
			'ca-es' => 'Català',
			'cs-cz' => 'Čeština',
			'da-dk' => 'Dansk',
			'de-de' => 'Deutsch',
			'el-gr' => 'Ελληνικά',
			'en-gb' => 'English (Great Britain)',
			'es-es' => 'Español',
			'et-ee' => 'Eesti',
			'fi-fi' => 'Suomi',
			'gl-gz' => 'Galego',
			'he-il' => 'עברית',
			'hu-hu' => 'Magyar',
			'id-id' => 'Bahasa Indonesia',
			'is-is' => 'Íslenska',
			'ja-jp' => 'Japanese',
			'lv-lv' => 'Latviešu',
			'nl-nl' => 'Nederlands',
			'no-no' => 'Norsk',
			'pl-pl' => 'Polski',
			'pt-pt' => 'Português (Portugal)',
			'ro-ro' => 'Română',
			'ru-ru' => 'Русский',
			'sk-sk' => 'Slovenčina',
			'sv-se' => 'Svenska',
			'th-th' => 'Thai',
			'uk-ua' => 'Українська',
			'zh-cn' => 'Chinese (Simplified)',
			'zh-tw' => 'Chinese (Traditional)',
		);	
}

function getStorageMethods()
{
	return array (
		"NoCacheEngine"=>'NoCacheEngine -> Do Not use cache',
		"ArrayCacheEngine"=>'ArrayCacheEngine -> Use array as cvalueache (very basic)',
		"FileSystemCacheEngine"=>'FileSystemCacheEngine -> Use the File System as cache (need write permission)',
		"MemCachedEngine"=>'MemCachedEngine -> Use the MemCached as cache (requires install memcached)'
	);
}

?>
