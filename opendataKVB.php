<?php

// Haltestellen
//		Haltestellenbereich (1)
//		Haltestellenname (Heumarkt)
//		
// Fahrtreppen
//		Haltestellenbereich (1)
//		Bezeichnung (Ausgang Heumarkt)
//		Kennung (081-55) [Die Nr 55 ist meist an der Haltestelle sichtbar.]	


// Sucht für eine bestimmte Haltestelle Fahrtreppen mit Störung und gibt 
// eine Liste mit den Bezeichnungen der Fahrtreppen zurück
// V0.1.1.001 EH

function sucheAlleStoerungen(){
	$liste = array();


	// $KvbFahrteppenStoerungString = file_get_contents('data/fahrtreppen_gestoert.xml');
	$KvbFahrteppenStoerungString = file_get_contents('http://online-service.kvb-koeln.de/geoserver/OPENDATA/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%20ODENDATA%3Afahrtreppen_gestoert');
	$doc = new DOMDocument();
	$doc->loadXML($KvbFahrteppenStoerungString); 


	foreach ($doc->getElementsByTagNameNS('http://www.opengis.net/gml', 'featureMember')  as $featureMember) {
		// print_r($item);
		foreach ($featureMember->getElementsByTagNameNS('*', 'fahrtreppen_gestoert')  as $fahrtreppen_gestoert) {
			// print_r($item2);
			foreach ($fahrtreppen_gestoert->getElementsByTagNameNS('*', 'Haltestellenbereich')  as $haltestellenbereich) {
				foreach ($fahrtreppen_gestoert->getElementsByTagNameNS('*', 'Bezeichnung')  as $bezeichnung) {
					$name = sucheHaltestellenName($haltestellenbereich->nodeValue);
					array_push($liste, $name ."#Rolltreppe: ". $bezeichnung->nodeValue);
				}
			}
		}
	}

	// $KvbAufzuegeStoerungString = file_get_contents('data/aufzuege_gestoert.xml');
	$KvbAufzuegeStoerungString = file_get_contents('http://online-service.kvb-koeln.de/geoserver/OPENDATA/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%20ODENDATA%3Aaufzuege_gestoert');
	$doc = new DOMDocument();
	$doc->loadXML($KvbAufzuegeStoerungString); 

	foreach ($doc->getElementsByTagNameNS('http://www.opengis.net/gml', 'featureMember')  as $featureMember) {
		// print_r($item);
		foreach ($featureMember->getElementsByTagNameNS('*', 'aufzuege_gestoert')  as $aufzuege_gestoert) {
			// print_r($item2);
			foreach ($aufzuege_gestoert->getElementsByTagNameNS('*', 'Haltestellenbereich')  as $haltestellenbereich) {
				foreach ($aufzuege_gestoert->getElementsByTagNameNS('*', 'Bezeichnung')  as $bezeichnung) {
					$name = sucheHaltestellenName($haltestellenbereich->nodeValue);
					array_push($liste, $name ."#Aufzug: ". $bezeichnung->nodeValue);
				}
			}
		}
	}
	sort($liste);
	return($liste);
}

// Sucht alle Haltestellen, die mit Fahrtreppe oder Aufzug ausgestattet 
// sind und gibt eine Liste der Haltestellenbereiche zurück.
// V0.1.1.001 EH

function sucheHaltestellenAuswahl(){
	$liste = array();

	//Fahrtreppen
	$KvbFahrteppen = file_get_contents('data/fahrtreppen.xml');
	$doc = new DOMDocument();
	$doc->loadXML($KvbFahrteppen); 

	foreach ($doc->getElementsByTagNameNS('http://www.opengis.net/gml', 'featureMember')  as $featureMember) {
		// print_r($item);
		foreach ($featureMember->getElementsByTagNameNS('*', 'fahrtreppen')  as $fahrtreppen) {
			// print_r($fahrtreppen);
			foreach ($fahrtreppen->getElementsByTagNameNS('*', 'Haltestellenbereich')  as $haltestellenbereich) {
				array_push($liste,$haltestellenbereich->nodeValue);
			}
		}
	}

	// Aufzüge
	$KvbAufzuege = file_get_contents('data/aufzuege.xml');
	$doc = new DOMDocument();
	$doc->loadXML($KvbAufzuege); 

	foreach ($doc->getElementsByTagNameNS('http://www.opengis.net/gml', 'featureMember')  as $featureMember) {
		// print_r($item);
		foreach ($featureMember->getElementsByTagNameNS('*', 'aufzuege')  as $aufzuege) {
			// print_r($fahrtreppen);
			foreach ($aufzuege->getElementsByTagNameNS('*', 'Haltestellenbereich')  as $haltestellenbereich) {
					array_push($liste,$haltestellenbereich->nodeValue);
			}
		}
	}
	
	// Doppelte entfernen
	return(array_unique($liste));
}

// Sucht nach Haltestellenname für einen gegebenen Haltestellenbereich
// und gibt den Namen zurück.
// V0.1.1.001 EH


function sucheHaltestellenName($haltestellenbereichSuche){
	$KvbHaltestellen = file_get_contents('data/haltestellenbereiche.xml');
	$doc = new DOMDocument();
	$doc->loadXML($KvbHaltestellen); 

	foreach ($doc->getElementsByTagNameNS('http://www.opengis.net/gml', 'featureMember')  as $featureMember) {
		// print_r($item);
		foreach ($featureMember->getElementsByTagNameNS('*', 'haltestellenbereiche')  as $haltestellenbereiche) {
			// print_r($item2);
			foreach ($haltestellenbereiche->getElementsByTagNameNS('*', 'Haltestellenbereich')  as $haltestellenbereich) {
				if ($haltestellenbereichSuche == $haltestellenbereich->nodeValue) {
					foreach ($haltestellenbereiche->getElementsByTagNameNS('*', 'Haltestellenname')  as $bezeichnung) {
						$ergebnis = $bezeichnung->nodeValue;
					}
				}
			}
		}
	}
	return($ergebnis);
	// return("ok");
}


?>
