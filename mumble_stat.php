<?php
 
// Log-Auswertung für registrierte Mumble-User
//
// LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
//
// @copyright  2014_neysoftware
// @authors    Neyen, Dirk
//	           Liewer, Rainer 
// @license    http://creativecommons.org/licenses/by-nc/2.0/de/
// @version    $1.0.3
// @link       http://virtueller-stammtisch.de

function intervall($sek) {
    $i = sprintf('%d Tag%s, %d Stunde%s,'.
            ' %d Minute%s und %d Sekunde%s',
            $sek / 86400,
            floor($sek / 86400) != 1 ? 'e':'',
            $sek / 3600 % 24,
            floor($sek / 3600 % 24) != 1 ? 'n':'',
            $sek / 60 % 60,
            floor($sek / 60 % 60) != 1 ? 'n':'',
            $sek % 60,
            floor($sek % 60) != 1 ? 'n':''
         );
    return $i;
}
echo "<center><h3>Online-Statistik f&uuml;r registrierte Mumble-Benutzer</h3></center>";
$neueliste = array();
for($suchbegriff=1; $suchbegriff <=50; $suchbegriff++) 
{
$x=0;
$datei = fopen("http://USER:PASSWORT@DEINE_URL/var/www/_stats/mumble-server.log","r")or die ("Kann Datei nicht lesen.");
$srvstart = fgets($datei,14);		

while(!feof($datei))
   {

   $zeile = fgets($datei,10000);
   
   if(strpos($zeile,chr(40).$suchbegriff.chr(41)) == true)
    {   
    
	// Namen auslesen
	if ($x == 0)
	{
		$var = substr($zeile, 31, 30);
		$start = ':';
		$ende  = '(';
		$search = substr( $var, strpos( $var, $start), strpos( $var, $ende)-strpos( $var, $start) + strlen($ende) + 1 ); 
		$laenge = strlen($search); 	
		$user = substr($search,1,$laenge -3);
		$user_gross = ucfirst($user);
		$x=1;
	// Ende auslesen
	}
	if(strpos($zeile,"Authenticated") == true)
	 {
		$anfang = substr($zeile, 14, 7);
		$startzeit = strtotime($anfang);
	 }
	 if(strpos($zeile,"closed") == true)
	 {
		$ende = substr($zeile, 14, 8);	
		$endzeit = strtotime($ende);	
        if ($startzeit > 0)
		 {
		 $dauerInMinuten = ($endzeit - $startzeit)/60;
         }   
			//Wenn negatives Ergebnis, dann geht es über die Datumsgrenze. Dann addiere 1440 Minuten für das richtige Ergebnis            
            if($dauerInMinuten < 0){            
                $dauerInMinuten = 1440 + $dauerInMinuten;
            }
		$dauerGes = $dauerGes + $dauerInMinuten;	
	 }  	
    }
  }
fclose($datei);

if ($dauerGes >0)
{
$sec = $dauerGes*60;
$zeiten = intervall($sec);
			$neueliste[$suchbegriff][dauerGes]   = $dauerGes;
			$neueliste[$suchbegriff][derRest]    = $user_gross;
			$neueliste[$suchbegriff][derRest_2]  = $zeiten;
}
$dauerGes = "";
$dauerInMinuten = "";
$endzeit = ""; 
$startzeit = "";

}
//aktuelles Datum

echo "<center>";
$datum = date("d.m.Y");
$uhrzeit = date("H:i");

//Ende aktuelles Datum

//$date is in yyyy-mm-dd format
$explode=explode("-",$srvstart);
$new_date=$explode[2].".".$explode[1].".".$explode[0];
echo "Erfassungszeitraum vom ";
echo $new_date;
echo " bis ";
echo $datum," - ",$uhrzeit," Uhr ";
echo "</center>";
echo "<br />";


rsort($neueliste);

$result = count($neueliste);
for($zz=0; $zz <=$result-1; $zz++)
{ 
echo "	<center>
			<table style='text-align: left; height: 32px; width: 600px;' border='1' cellpadding='1' cellspacing='1'>
				<tr>
					<td style=width: 180px; text-align: left;'>&nbsp;&nbsp;";
					echo $neueliste[$zz]['derRest'];
					echo"</td>
					<td style='width: 380px; text-align: left;'>&nbsp;&nbsp;";
					echo $neueliste[$zz]['derRest_2'];
					echo"</td>
				</tr>
			</table>
			<br />
		</center>";
}

?> 
