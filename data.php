<?php
// === Parametri ===
$channelID = "2964228"; // ID del canale
$readAPIKey = ""; // Lascia "" se il canale è pubblico
$results = 1; // Quanti risultati vuoi

// === Costruisci URL API ===
$url = "https://api.thingspeak.com/channels/$channelID/feeds.json?results=$results";
if (!empty($readAPIKey)) {
    $url .= "&api_key=$readAPIKey";
}

// === Richiesta GET ===
$response = file_get_contents($url);
if ($response === FALSE) {
    echo "<p>Errore nella richiesta a ThingSpeak.</p>";
    exit;
}

// === Decodifica JSON ===
$data = json_decode($response, true);

// === Visualizza dati ===
if (!empty($data["feeds"])) {
    echo "<table class='responsive-table' border='1' cellpadding='5'>";
    echo "<tr><th>Timestamp</th><th>T(°C)Sensore1</th><th>T(°C)Sensore2</th></tr>";

    foreach ($data["feeds"] as $feed) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($feed["created_at"]) . "</td>";
        echo "<td>" . htmlspecialchars($feed["field2"]) . "</td>";
        echo "<td>" . htmlspecialchars($feed["field4"]) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>Nessun dato disponibile.</p>";
}
