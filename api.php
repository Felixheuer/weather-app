<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ort = htmlspecialchars($_POST['ort']);
    $apiUrl = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($ort);

    $geocodingDaten = file_get_contents($apiUrl);
    $geocodingDaten = json_decode($geocodingDaten, true);

    if (empty($geocodingDaten['results'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ort nicht gefunden'
        ]);
    } else {
        $breitengrad = $geocodingDaten['results'][0]['latitude'];
        $laengengrad = $geocodingDaten['results'][0]['longitude'];

        echo json_encode([
            'status' => 'success',
            'latitude' => $breitengrad,
            'longitude' => $laengengrad
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Ungültige Anforderung'
    ]);
}

