<!DOCTYPE html>
<html lang="de">
	<head>
		<link rel="icon" href="./favicon.ico" type="image/x-icon">
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>KVB-Störungen</title>
		<?php
			include 'opendataKVB.php';
		?>
	</head>
	<body>
		<h1>KVB-Störungen</h1>
		<dl>
			<?php
				$liste = array();
				$haltestelleAlt = "";				
				foreach(sucheAlleStoerungen() as $item){
					list($haltestelle,$stoerung) = explode("#", $item);
					if ($haltestelleAlt != $haltestelle){
						echo "<dt><b>",$haltestelle,"</b></dt>",PHP_EOL;
						$haltestelleAlt = $haltestelle;
					}
					echo "<dd>",$stoerung,"</dd>",PHP_EOL;
				}
			?>
		</dl>
		<hr>
		<p>Verbrochen hat es <a href="https://twitter.com/Eich_Hoern">Eichhörn</a></p>
		<p>Quelle: <a href="https://www.offenedaten-koeln.de/blog/kvb-koeln-erweitert-open-data-portfolio">Offene Daten Köln</a></p>
	</body>
</html>
