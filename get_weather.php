<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $latitude = htmlspecialchars($_POST['lat']);
    $longitude = htmlspecialchars($_POST['lon']);
    $apiUrl = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&current_weather=true";

    $wetterDaten = file_get_contents($apiUrl);
    $wetterDaten = json_decode($wetterDaten, true);

    if (isset($wetterDaten['current_weather'])) {
        $temperatur = $wetterDaten['current_weather']['temperature'];
        $windgeschwindigkeit = $wetterDaten['current_weather']['windspeed'];
        $windrichtung = $wetterDaten['current_weather']['winddirection'];
        $wetterCode = $wetterDaten['current_weather']['weathercode'];

        $wetterBeschreibung = [
            0 => 'Klarer Himmel',
            1 => 'Überwiegend klar',
            2 => 'Teilweise bewölkt',
            3 => 'Bewölkt',
            45 => 'Nebel',
            48 => 'Reifnebel',
            51 => 'Leichter Nieselregen',
            53 => 'Mäßiger Nieselregen',
            55 => 'Starker Nieselregen',
            56 => 'Leichter gefrierender Nieselregen',
            57 => 'Starker gefrierender Nieselregen',
            61 => 'Leichter Regen',
            63 => 'Mäßiger Regen',
            65 => 'Starker Regen',
            66 => 'Leichter gefrierender Regen',
            67 => 'Starker gefrierender Regen',
            71 => 'Leichter Schneefall',
            73 => 'Mäßiger Schneefall',
            75 => 'Starker Schneefall',
            77 => 'Schneekörner',
            80 => 'Leichte Regenschauer',
            81 => 'Mäßige Regenschauer',
            82 => 'Starke Regenschauer',
            85 => 'Leichte Schneeschauer',
            86 => 'Starke Schneeschauer',
            95 => 'Gewitter',
            96 => 'Gewitter mit leichtem Hagel',
            99 => 'Gewitter mit starkem Hagel',
        ];

        // Wetter-Emojis
        $wetterEmojis = [
            0 => '☀️',
            1 => '🌤️',
            2 => '⛅',
            3 => '☁️',
            45 => '🌫️',
            48 => '🌫️',
            51 => '🌦️',
            53 => '🌧️',
            55 => '🌧️',
            56 => '🌧️❄️',
            57 => '🌧️❄️',
            61 => '🌦️',
            63 => '🌧️',
            65 => '🌧️',
            66 => '🌧️❄️',
            67 => '🌧️❄️',
            71 => '❄️',
            73 => '❄️',
            75 => '❄️',
            77 => '❄️',
            80 => '🌦️',
            81 => '🌧️',
            82 => '🌧️',
            85 => '❄️',
            86 => '❄️',
            95 => '⛈️',
            96 => '⛈️',
            99 => '⛈️',
        ];


        // Kleidungsempfehlungen basierend auf Wetterbeschreibung
        $kleidungsempfehlungen = [
            'Klarer Himmel' => 'Trage leichte Kleidung und Sonnenschutz.',
            'Überwiegend klar' => 'Trage leichte Kleidung und Sonnenschutz.',
            'Teilweise bewölkt' => 'Leichte Kleidung ist ausreichend.',
            'Bewölkt' => 'Trage eine leichte Jacke oder Pullover.',
            'Nebel' => 'Trage warme Kleidung und sei vorsichtig.',
            'Reifnebel' => 'Trage warme Kleidung und sei vorsichtig.',
            'Leichter Nieselregen' => 'Nehme einen Regenschirm mit.',
            'Mäßiger Nieselregen' => 'Nehme einen Regenschirm mit.',
            'Starker Nieselregen' => 'Nehme einen Regenschirm mit.',
            'Leichter gefrierender Nieselregen' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Starker gefrierender Nieselregen' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Leichter Regen' => 'Nehme einen Regenschirm mit.',
            'Mäßiger Regen' => 'Nehme einen Regenschirm mit.',
            'Starker Regen' => 'Nehme einen Regenschirm mit.',
            'Leichter gefrierender Regen' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Starker gefrierender Regen' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Leichter Schneefall' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Mäßiger Schneefall' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Starker Schneefall' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Schneekörner' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Leichte Regenschauer' => 'Nehme einen Regenschirm mit.',
            'Mäßige Regenschauer' => 'Nehme einen Regenschirm mit.',
            'Starke Regenschauer' => 'Nehme einen Regenschirm mit.',
            'Leichte Schneeschauer' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Starke Schneeschauer' => 'Trage warme Kleidung und sei vorsichtig auf glatten Straßen.',
            'Gewitter' => 'Bleibe drinnen oder suche Schutz.',
            'Gewitter mit leichtem Hagel' => 'Bleibe drinnen oder suche Schutz.',
            'Gewitter mit starkem Hagel' => 'Bleibe drinnen oder suche Schutz.',
        ];

        $beschreibung = isset($wetterBeschreibung[$wetterCode]) ? $wetterBeschreibung[$wetterCode] : 'Unbekannt';
        $emoji = isset($wetterEmojis[$wetterCode]) ? $wetterEmojis[$wetterCode] : '❓';
        $empfehlung = isset($kleidungsempfehlungen[$beschreibung]) ? $kleidungsempfehlungen[$beschreibung] : 'Keine spezifische Empfehlung.';
        $weatherFacts = [
            "Blitze können bis zu einer Milliarde Volt elektrischer Energie freisetzen.",
            "Ein einziger Blitz kann bis zu fünfmal heißer als die Sonnenoberfläche sein.",
            "Ein Tornado kann Geschwindigkeiten von mehr als 300 km/h erreichen.",
            "Es gibt mehr als 100 verschiedene Arten von Wolkenformationen.",
            "Der stärkste je gemessene Regenfall betrug 305 cm in nur 42 Monaten.",
            "Ein Hagelkorn kann die Größe eines Tennisballs erreichen.",
            "Ein Regenbogen kann nur bei bestimmten Wetterbedingungen und einem spezifischen Sonnenstand erscheinen.",
            "Der Wind kann Sandstürme erzeugen, die Tausende Kilometer weit reichen können.",
            "Die höchste jemals gemessene Windgeschwindigkeit betrug über 500 km/h während eines Tornados.",
            "Der Polarwirbel ist ein riesiger Zyklon, der sich jedes Jahr über der Arktis bildet.",
            "Wusstest du, dass es auf anderen Planeten im Sonnensystem auch elektrische Stürme gibt?",
            "Die längste anhaltende Dürre dauerte mehr als 10 Jahre in der Region des Lake Chad in Afrika.",
            "Der stärkste jemals gemessene Hurrikan hatte Winde von mehr als 300 km/h und verursachte enorme Schäden.",
            "Der Donner ist das Geräusch, das durch die schnelle Ausdehnung und Erwärmung der Luft verursacht wird, wenn ein Blitz einschlägt.",
            "Der Sturm 'The Perfect Storm' im Jahr 1991 war ein außergewöhnlich starker Sturm im Nordatlantik.",
            "Meteorologen verwenden spezielle Instrumente namens Radiosonden, um Daten über die Atmosphäre zu sammeln.",
            "Ein Frostbeben oder Kryoseism ist ein seltenes Phänomen, bei dem der Boden bei extrem kaltem Wetter plötzlich aufbricht.",
            "Die höchste jemals gemessene Temperatur auf der Erde betrug etwa 56,7 °C in Death Valley, USA.",
            "In der Antarktis ist der größte jemals gemessene Schneefall in einem Jahr etwa 2.500 cm hoch!",
            "Es gibt einen Ort in Venezuela, wo es fast das ganze Jahr über blitzt. Das Gebiet heißt Catatumbo-Gewitter.",
            "Wusstest du, dass der Begriff 'Blauer Himmel' von der Streuung des blauen Lichts in der Atmosphäre kommt?",
            "Tornados, die über Wasser auftreten, werden als Wasserhosen bezeichnet und können gefährlich sein für Schiffe in der Nähe.",
            "Es gibt auch Feuerwirbel, die durch Feuer oder Vulkanausbrüche erzeugt werden und gefährlich sein können.",
            "Ein Regenbogen ist eine optische Erscheinung, die durch Brechung und Reflexion von Sonnenlicht in Wassertropfen in der Luft entsteht.",
            "Die höchste jemals gemessene Wellenhöhe betrug etwa 30,7 Meter und wurde während eines Sturms im Nordatlantik registriert.",
            "Wusstest du, dass der Begriff 'Schneeflocke' nicht nur für Schnee verwendet wird, sondern auch metaphorisch für eine Person, die sich als einzigartig und speziell betrachtet?",
            "Manche Hagelkörner können so groß wie Softbälle sein und erhebliche Schäden an Gebäuden und Fahrzeugen verursachen.",
            "Der Begriff 'Blitzeinschlag' bezieht sich auf die Stelle, an der ein Blitz in den Boden oder in ein Gebäude einschlägt.",
            "Wusstest du, dass es auch bunte Polarlichter gibt, die durch die Wechselwirkung von Sonnenwinden mit der Erdatmosphäre entstehen?",
            "Es gibt einen Wüstenwind namens 'Haboob', der riesige Staubstürme erzeugen kann und die Sicht stark beeinträchtigt.",
            "Ein Meteorologe ist jemand, der Wetterdaten sammelt und analysiert, um Wettervorhersagen zu erstellen und Wetterphänomene zu studieren.",
            "Die höchste jemals gemessene Regenmenge in einer Minute betrug etwa 30 mm und ereignete sich in Maryland, USA."
        ];
        
        $randomFact = $weatherFacts[array_rand($weatherFacts)];
        

        echo "<div class='card'>
                <div class='card-body'>
                    <h5 class='card-title'>Aktuelles Wetter</h5>
                    <div style='font-size: 200px; height: 260px; display: flex; align-items: center;'>".htmlspecialchars($emoji)."</div>
                    <p class='card-text'><span style='font-weight: bold;'>Beschreibung:</span> " . htmlspecialchars($beschreibung) . "</p>
                    <p class='card-text'><span style='font-weight: bold;'>Temperatur: </span> " . htmlspecialchars($temperatur) . "°C</p>
                    <p class='card-text'><span style='font-weight: bold;'>Windgeschwindigkeit: </span> " . htmlspecialchars($windgeschwindigkeit) . " km/h</p>
                    <p class='card-text'><span style='font-weight: bold;'>Windrichtung: </span> " . htmlspecialchars($windrichtung) . "°</p>
                    <p class='card-text'><span style='font-weight: bold;'>Kleidungsempfehlung: </span> " . htmlspecialchars($empfehlung) . "</p>
                    <hr>
                    <p class='card-text' style='font-weight: bold; margin.bottom: 0;'> Fakten:</p>
                    <p class='card-text'> ".htmlspecialchars($randomFact)."</p>

                </div>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>Fehler beim Abrufen der Wetterdaten.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Ungültige Anforderung.</div>";
}
?>
