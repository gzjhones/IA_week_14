<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción de Imágenes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .prediction {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .image-container {
            margin-top: 20px;
            width: 500px;
            text-align: center;
        }

        img {
            max-width: 100%;
            height: auto;
            border: 2px solid #007bff;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<?php
$predictionMessage = "";
$imageUrl = "";

if (isset($_FILES['image'])) {
    $url = 'https://b3ab-34-106-83-165.ngrok-free.app/predict';
    $filePath = $_FILES['image']['tmp_name'];

    $cFile = new CURLFile($filePath, $_FILES['image']['type'], $_FILES['image']['name']);

    $post = array('file' => $cFile);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response, true);
    if (isset($response['prediction'])) {
        $predictionMessage = 'Predicción: ' . $response['prediction'];
        $imageUrl = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($filePath));
    } else {
        $predictionMessage = 'Error: ' . $response['error'];
    }
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <label for="image">Seleccione una imagen para predecir:</label>
    <input type="file" name="image" accept="image/*" required>
    <input type="submit" value="Enviar">
</form>

<?php if ($predictionMessage): ?>
    <div class="prediction">
        <?php echo $predictionMessage; ?>
    </div>
<?php endif; ?>

<?php if ($imageUrl): ?>
    <div class="image-container">
        <img src="<?php echo $imageUrl; ?>" alt="Imagen predicha">
    </div>
<?php endif; ?>

</body>
</html>
