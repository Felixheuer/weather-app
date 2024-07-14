<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wetter App</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Arial', sans-serif;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #007bff; 
}

.form-group {
    margin-bottom: 20px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.alert-danger {
    margin-top: 10px;
    padding: 10px;
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.card {
    margin-top: 20px;
    border: none;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.card-body {
    padding: 20px;
}

.card-title {
    font-size: 24px;
    margin-bottom: 15px;
    color: #007bff;
}

.card-text {
    font-size: 16px;
    margin-bottom: 10px;
}

hr {
    margin-top: 15px;
    margin-bottom: 15px;
}

.weather-emoji {
    font-size: 100px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.clothing-recommendation {
    font-style: italic;
    color: #6c757d;
}

.weather-fact {
    font-size: 14px;
    margin-top: 15px;
    font-weight: bold;
    color: #007bff;
}

</style>
</head>
<body>
<div class="container">
        <h1 class="mt-5">Wetter App</h1>
        <form id="form-api" class="mt-3">
            <div class="form-group">
                <label for="ort">Ort:</label>
                <input type="text" class="form-control" name="ort" id="ort" placeholder="Gib einen Ort ein">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Suchen</button>
        </form>
        <div id="wetterInfo" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#form-api').on('submit', function(e) {
                e.preventDefault();
                var ort = $('input[name=ort]').val();
                if (ort != '') {
                    $.ajax({
                        url: 'api.php',
                        method: 'POST',
                        data: { ort: ort },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.status === 'success') {
                                var latitude = data.latitude;
                                var longitude = data.longitude;
                                
                                $.ajax({
                                    url: 'get_weather.php',
                                    method: 'POST',
                                    data: { lat: latitude, lon: longitude },
                                    success: function(response) {
                                        $('#wetterInfo').html(response);
                                    },
                                    error: function(error) {
                                        $('#wetterInfo').html("<div class='alert alert-danger'>Fehler beim Abrufen der Wetterdaten.</div>");
                                    }
                                });
                            } else {
                                $('#wetterInfo').html("<div class='alert alert-danger'>" + data.message + "</div>");
                            }
                        },
                        error: function(error) {
                            $('#wetterInfo').html("<div class='alert alert-danger'>Fehler beim Senden der Daten.</div>");
                        }
                    });
                } else {
                    alert('Bitte Ort eingeben');
                }
            });
        });
    </script>
</body>
</html>
