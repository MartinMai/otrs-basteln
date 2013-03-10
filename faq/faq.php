<?php


#uniba.de todo: umstellen auf neue bibliotheken mysql-i
#das ist nicht die aktueleste Version...

$conn_data['host'] 	= '';
$conn_data['user'] 	= '';
$conn_data['pass'] 	= '';
$conn_data['db'] 	= '';


function get_faq_list($conn_data, $category_id, $f_keywords='', $state_id='1'){


        $sql = '
        SELECT * FROM '.$conn_data[db].'.faq_item';

        if (is_int($category_id)){
          $sql .= ' WHERE category_id = '.$category_id.'
          AND state_id = \''.$state_id.'\' 
          AND f_keywords LIKE \'%'.$f_keywords.'%\'
          ORDER BY f_subject ASC';
          }          
          

        #WHERE category_id = '*'
        #echo "<hr>".$sql."<hr>";
        $result = uniba_sys_stat_2::mysql_perform($sql, $conn_data['host'], $conn_data['user'], $conn_data['pass']);

        while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
          foreach($row as $key=>$value){
              #echo "<br>$key  $value";
              $rows[$key][] = $value;     
            }        
        }
return $rows;

}




if($_REQUEST[standalone] == 1){
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title></title>
  <meta name="GENERATOR" content="Quanta Plus" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php 
#847381


?>

<head>

<script src="http://www.uni-bamberg.de/fileadmin/templates/js/jquery/js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="http://www.uni-bamberg.de/fileadmin/templates/js/jquery/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="http://www.uni-bamberg.de/fileadmin/templates/js/css/custom-theme/jquery-ui-1.8.16.custom.css?1327395992" media="all" />


<?php                
/*
"bJQueryUI": true, 
*/
?>
 


</head>
<body>


<?php
}
?>


<?php
include_once("/var/www/tools/sys_stat/sys_stat_lib_2.php");

//include_once("/srv/www/htdocs/typo3/skripte/user_t3_lib.php");


#include_once("/var/www/tools/wau_lib/functions_3.php");
#include_once("/var/www/tools/uniba_typo3lib/classes.php");
#include_once("uniba_otrs.php");



#conn für die die verfügbarkeit von mysql_real_escape_string
$result = uniba_sys_stat_2::mysql_perform("select now()", $conn_data['host'], $conn_data['user'], $conn_data['pass']);

  #todo filter ausreichend?
if(is_numeric($_REQUEST[category_id])){
  $category_id = (int) $_REQUEST[category_id];  
  }
$keywords = mysql_real_escape_string($_REQUEST[keywords]);  
  
$faqs = get_faq_list($conn_data,$category_id, $keywords);

?>

<?php



  $i=0;
foreach($faqs[id] as $id){
  $i++;
  $accordion_id = 'accordion-'.$_REQUEST['nummer'].'-'.$i;
?>  
  <script>
  $(function() {
  $( "#<?php echo $accordion_id;?>" ).accordion({autoHeight:false,collapsible:true,active:false,icons:{'header':'ui-icon-plus','headerSelected':'ui-icon-minus'}})
  });
  </script>
  
<?php  
  echo '<div id="'.$accordion_id.'">';    
                          #nummern zur Sortierung vor Titel entfernen
  echo "\n<h3><a href=#>".preg_replace("%^(\d+)(\.*)%","",current($faqs[f_subject])).'</a></h3>';
  echo "<div><p>";
  echo " ".current($faqs[f_field1]);
  echo " ".current($faqs[f_field2]);
  echo " ".current($faqs[f_field3]);
  echo " ".current($faqs[f_field4]);        
  echo "</p></div>";  
  
  foreach($faqs as $key=>$value){
      next($faqs[$key]);
    } 

  echo '</div>';        
  } 





?>


<?php
if($_REQUEST[standalone] == 1){
?>
</body>
</html>
<?php
}
?>