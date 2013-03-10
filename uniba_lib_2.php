<?php 

class uniba_lib_2 {
  
function mysqli_perform($conn_data, $sql){
#uniba_todo:aufräumen 

$db = @new mysqli( $conn_data['host'], $conn_data['user'], $conn_data['pass'], $conn_data['db'] );
// Pruefen ob die Datenbankverbindung hergestellt werden konnte
if (mysqli_connect_errno() == 0)
{
    // Query vorbereiten und an die DB schicken
    #$sql = 'SELECT `name`, `bereich` FROM `moderatoren`';
    #$sql = 'SELECT * FROM otrs.configitem';
     
    $ergebnis = $db->query( $sql );
    // Anzahl gefunde Datensaetze ausgeben
    #echo "<p>Es wurden " .$ergebnis->num_rows. " Eintr&auml;ge gefunden.</p>";
    // Ergebnisse ausgeben
    #while ($zeile = $ergebnis->fetch_object())
    #{
    #    echo $zeile->id. " ist zust&auml;ndig f&uuml;r " .$zeile->bereich. "<br />";
    #}
    // Resourcen freigeben
    return $ergebnis;
}
else
{
    // Es konnte keine Datenbankverbindung aufgebaut werden
    return 'Die Datenbank konnte nicht erreicht werden. Folgender Fehler trat auf: <span class="hinweis">' .mysqli_connect_errno(). ' : ' .mysqli_connect_error(). '</span>';
}
// Datenbankverbindung schliessen


}  
  
}  

?>