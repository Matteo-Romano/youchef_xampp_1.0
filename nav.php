<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Lettura dati ThingSpeak</title>
</head>
<body>
    <div class="card">
        <h1>Dati da ThingSpeak</h1>
        <!-- Contenitore da aggiornare dinamicamente -->
        <div id="contenitore-dati">Caricamento dati...</div>
    </div>

    <script>
        function aggiornaDatiThingSpeak() {
            fetch('data.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('contenitore-dati').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('contenitore-dati').innerHTML = "Errore nel caricamento dei dati.";
                    console.error("Errore:", error);
                });
        }

        // Aggiorna subito al caricamento
        window.onload = aggiornaDatiThingSpeak;

        // Poi ogni 10 secondi (10000 ms)
        setInterval(aggiornaDatiThingSpeak, 1000);
    </script>
</body>
</html>
