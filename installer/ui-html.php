<?php

function writeSection($title)
{
	echo '<li class="section_break">';
	echo '<h3>' . $title . '</h3>';
	echo '<p></p>';
	echo '</li>';
}

function writeInfo($title, $message, $listMsg)
{
	echo '<li class="section_break" style="background-color: yellow">';
	echo '<h3>' . $title . '</h3>';
	echo '<p>' . $message;
	echo '<ul>';
	foreach ($listMsg as $li)
		echo '<li>' . $li . '</li>'; 
	echo '</ul>';
	echo '</p>';
	echo '</li>';
}

function writeSucess($title, $message, $listMsg)
{
	echo '<li class="section_break" style="background-color: lightgreen">';
	echo '<h3>' . $title . '</h3>';
	echo '<p>' . $message;
	echo '<ul>';
	foreach ($listMsg as $li)
		echo '<li>' . $li . '</li>'; 
	echo '</ul>';
	echo '</p>';
	echo '</li>';
}


function writeInputButtons($result, $next, $previous)
{
	echo '<li class="buttons">';
	
	if ($previous != "")
		echo '<input id="saveForm" class="button_text" type="submit" name="previousBtn" value="' . $previous . '" />';
	
	if ($result)
		echo '<input id="saveForm" class="button_text" type="submit" name="nextBtn" value="' . $next . '" />';
	else
		echo '<input id="saveForm" class="button_text" type="submit" name="nope" value="You cannot go on. Fix this and then click here" />';
	
	echo '</li>';
}


function writeInputMultipleList($type, $id, $title, $data, $help = "")
{
	echo '<li id="' . $id . '" >';
	echo '<label class="description" for="' . $id . '">' . $title . '</label>';
	echo '<span>';
	
	foreach($data as $a)
	{	
		echo '<input id="' . $a['id'] . '" name="' . $a['id'] . '" class="element ' . $type . '" type="' . $type . '" value="' . $a['value'] . '" ' . ($a['checked'] ? ' checked="true" ' : '') . ($a['disabled'] ? ' disabled="true" ' : '') . '/>';
		echo '<label class="choice" for="' . $a['id'] . '">' . $a['label'] . '</label>';	
	}
	
	if ($help != "")
	{
		echo '<p class="guidelines" id="guide"><small>' . $help . '</small></p>';
	}
	
	echo '</span>';
	echo '</li>';	
}

function writeInputText($id, $label, $value, $placeHolder, $help = "")
{
	echo '<li id="li_text" >';
	echo '<label class="description" for="' . $id . '">' . $label . '</label>';
	echo '<div>';
	echo '<input id="' . $id . '" name="' . $id . '" class="element text large" type="text" maxlength="255" value="' . $value . '" placeholder="' . $placeHolder . '"/>';
	echo '</div>';
	
	if ($help != "")
		echo '<p class="guidelines" id="guide_' . $id . '"><small>' . $help . '</small></p>';
	
	echo '</li>';
}

function writeInputHidden($id, $value)
{
	echo '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '"/>';
}

function writeInputSelect($id, $label, $values, $help = "", $cur = "")
{

	echo '<li id="li_select" >';
	echo '<label class="description" for="' . $id . '">' . $label . '</label>';
	echo '<div>';
	
	if ($cur == "")
		$curArray = array(getValue($id));
	elseif (!is_array($cur))
		$curArray = array($cur);
	else
		$curArray = $cur;

	$cnt = 0;
	foreach($curArray as $keyCur=>$valueCur)
	{
		$idItem = $id . (count($curArray) > 1 ? $cnt++ : "");
		echo '<select class="element select large" id="' . $idItem . '" name="' . $idItem . '">';

		foreach ($values as $key=>$value)
		{
			if ((strlen($key) > 1) && ($key[0] == '-')) $key = "";

			echo '<option value="' . $key . '" ' . ($key == $valueCur ? 'selected="selected"' : '') . '>' . $value . '</option>';
		}

		echo '</select>';
	}
	
	echo '</div>' ;

	if (is_array($cur))
		writeInputHidden($id, count($cur));
	
	if ($help != "")
		echo '<p class="guidelines" id="guide_' . $id . '"><small>' . $help . '</small></p>';
	
	echo '</li>';
}

function writeInputKeyValue($id, $label, $values, $help = "")
{
	echo '<li id="li_keyvalue" >';
	echo '<label class="description" for="' . $id . '">' . $label . '</label>';
	
	$count = 0;
	foreach ($values as $key=>$value)
	{
		$key = "" . $key;
		if ((strlen($key) > 0) && ($key[0] == '-'))	$key = "";
		
		echo '<span>';
		echo '<input id="' . $id . $count . '_key" name="' . $id . $count . '_key" class="element text" size="10" value="' . $key . '" type="text" /> = ';
		if ($count == count($values)-1)
			echo '<label for="' . $id . '">Key</label>';
		echo '</span>';
		echo '<span style="width: 68%">';
		echo '<input id="' . $id . $count . '_value" name="' . $id . $count . '_value" class="element text large" value="' . $value . '" type="text" />';
		if ($count == count($values)-1)
			echo '<label for="' . $id . '">Value</label>';
		echo '</span>';
		
		$count++;
	}

	echo '<p class="guidelines" id="guide_4"><small>' . $help . '</small></p>';
	echo '</li>';

	writeInputHidden($id, count($values));
}

function writeInputData($configValues, $name, $desc, $type, $placeHolder = null, $list = null)
{
	$curValue = getValue($name, $configValues);

	$id = str_replace(".", "_", $name);

	if ($type == 1) // Text
	{
		writeInputText($id, $name, $curValue, $placeHolder, $desc);
	}
	elseif ($type == 2)
	{
		writeInputSelect($id, $name, array("true" => "True", "false" => "False"), $desc, $curValue ? "true" : "false");
	}
	elseif ($type == 3)
	{
		writeInputSelect($id, $name, $list, $desc, $curValue);
	}
	elseif ($type == 4)
	{
		$values = array();
		if ($curValue != "")
		{
			$pairItemArray = explode("|", $curValue);
			foreach ($pairItemArray as $pairItem)
			{
				$pair = explode("=", $pairItem);
				$values[$pair[0]] = $pair[1];
			}
		}

		for ($j=0; $j<3; $j++)
		{
			$values['-' . $j] = "";
		}

		writeInputKeyValue($id, $name, $values, $desc);
	}
	elseif ($type == 9999)
	{
		$curValueArrayTmp = explode("|", $curValue);
		$curValueArray = array();
		foreach ($curValueArrayTmp as $value)
		{
			$item = explode("=", $value);
			$curValueArray[] = $item[0];
		}

		$curValueArray[] = "";
		$curValueArray[] = "";

		writeInputSelect($id, $name, $list, $desc, $curValueArray);
	}
}


?>

