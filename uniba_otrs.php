<?php


class uniba_otrs{


function list_cmdb($conn_data, $class_id=''){

        $sql = '
        SELECT * FROM otrs.configitem';
        if (is_int($class_id)){        
          $sql .= ' WHERE class_id='.$class_id;
          }
                       
return uniba_lib_2::mysqli_perform($conn_data, $sql);

}

function get_config_item_version_latest($conn_data, $id){

#todo filter
        $sql = "
        SELECT * FROM otrs.configitem_version 
        WHERE configitem_id = $id     
        ORDER BY id DESC
        LIMIT 1

        ";
$keys = uniba_lib_2::mysqli_perform($conn_data, $sql);     

return  $keys->fetch_object();

}

function get_xml_content_value($conn_data, $xml_content_keys, $configitem_id=''){
             
        if(!is_array($xml_content_keys)){
          $xml_content_keys_temp[] = $xml_content_keys;
          $xml_content_keys = $xml_content_keys_temp;
          }
        #$xml_content_key="[1]{'Version'}[1]{'Description'}[1]{'Content'}";
        
        $sql_part = ' ';  
        foreach($xml_content_keys as $xml_content_key){
          $sql_part .= 'xml_content_key LIKE "'.$xml_content_key.'" OR ';
          }
        $sql_part = preg_replace("%...$%", ' ', $sql_part);

        $sql = '
        SELECT * FROM otrs.xml_storage 
        WHERE
        ('.$sql_part.')
        AND xml_key IN 
                (
                SELECT MAX(id) FROM otrs.configitem_version 
                WHERE configitem_id = '.$configitem_id.'                
                GROUP BY configitem_id
                )
        ';
return uniba_lib_2::mysqli_perform($conn_data, $sql);        


}


}





?>
