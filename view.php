
<?php

include_once ("settings.php");
include_once ("uniba_lib_2.php");
include_once ("uniba_otrs.php");



if (is_numeric($_REQUEST[ci_class])) {
  $ci_class = (int) $_REQUEST[ci_class];
}

function pyhton_sub_1($key, $value) {
	$string .= "\t" . "'" . $key . "':";
	$string .= "\t" . "'" . $value . "',";
	$string .= "\n";

	return $string;
}

foreach ($output_configs as $key) {
	$keys_temp[xml_content_key][] = $key['xml_content_key'];
	$keys_temp[$key['xml_content_key']] = $key['xml_content_key_replacement'];  	
}



$configitems = uniba_otrs :: list_cmdb($conn_data);


$i = -1;
while ($configitem = $configitems->fetch_object()) {
	$i++;

	$keys = uniba_otrs :: get_xml_content_value($conn_data, $keys_temp[xml_content_key], $configitem->id);
	#$keys = uniba_otrs::get_xml_content_value($conn_data, "%", $configitem->id);

	$version_latest = uniba_otrs :: get_config_item_version_latest($conn_data, $configitem->id);

	$string .= "\n";
	$string .= '{' . "\n";
	$string .= '# https://YOUR-OTRS-HOST/otrs/index.pl?Action=AgentITSMConfigItemEdit;ConfigItemID=' . $configitem->id . "\n";
				   

	$cis[$i]['name'] = $version_latest->name;
	$cis[$i]['id'] = $configitem->id;	
	$cis[$i]['class_id'] = $configitem->class_id;	
	
	$cis[$i]['depl_state_id'] = $version_latest->depl_state_id;
	$cis[$i]['inci_state_id'] = $version_latest->inci_state_id;

	$cis[$i]['create_time'] = $version_latest->create_time;
	$cis[$i]['create_by'] = $version_latest->create_by;

	while ($key = $keys->fetch_object()) {
		#$string .= "\n";
		#$string .= pyhton_sub_1($key->xml_content_key, trim($key->xml_content_value));

		$from = Array (
			'%',
			'[',
			']'
		);
		$to = Array (
			')(.*)(',
			'\[',
			'\]'
		);
		$temp_string =  $key->xml_content_key;
		#echo $temp_string;
		#echo $key->xml_content_key."\n";
		
		foreach ($output_configs as $output_config) {
			#echo "\n".' $key->xml_content_key:'.$key->xml_content_key;
			$xml_content_key_regex = str_replace($from, $to, '('.$output_config[xml_content_key].')');
			#echo "\n xml_content_key_regex:".$xml_content_key_regex;			
			if(preg_match("%$xml_content_key_regex%", $key->xml_content_key)){
				#echo "\n drin:".$key->xml_content_key;
				$xml_content_key_sql = str_replace($to, $from, $output_config[xml_content_key]);
				#echo "\n".'$xml_content_key_sql:'.$xml_content_key_sql;
				#echo "\n".'$keys_temp[$xml_content_key_sql]:'.$keys_temp[$xml_content_key_sql];

				#echo "\n".''.$keys_temp[$xml_content_key_sql];				
				#echo " ".trim($key->xml_content_value);
				$cis[$i][$keys_temp[$xml_content_key_sql]] = trim($key->xml_content_value); 				
			}

		}
		
		
		
	
		

	}
	#$string .= '},'."\n";    
}

#print_r($cis);

if($_REQUEST[format] == 'json'){
	echo '/*<br/><a href ="?format=">Ausgabe als Text</a>*/';	
	echo json_encode($cis);
}else{
	$string = '';
	echo '#<br/><a href ="?format=json">Ausgabe als json</a>';
	foreach($cis as $ci){		
		$string .= "\n#uniba:todo link geht nicht############\n".'# <a href="https://helpdesk.rz.uni-bamberg.de/otrs/index.pl?Action=AgentITSMConfigItemZoom;ConfigItemID='.$ci[id].'">Edit</a>"'."\n";		
		foreach($ci as $key => $value){
			$string .= "\n'".$key."': '".$value."'";
		}
			$string .= "\n,";		
	}		
	if($_REQUEST[format] == 'html'){
		echo '#<br/><a href ="?format=">Ausgabe als Text</a>';		
		echo nl2br(	$string);		
	}else{
		echo '#<br/><a href ="?format=html">Ausgabe als html</a>';		
		echo $string;		
	}	
 
	
}
 








?>
