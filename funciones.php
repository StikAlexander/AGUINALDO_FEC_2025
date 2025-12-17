<?php
function enviarsms($celular,$mensaje)
{
    $apiKey = '72acebf49e4f4944915380ec516e5cbf';   // Reemplaza con tu API Key real si es diferente
    $apiSecret = '4746371202038681'; // Reemplaza con tu API Secret real si es diferente

    // ID de la campaña a la que quieres enviar el mensaje
    // ¡Importante! Reemplaza 'CAMPING_ID' con el ID real de tu campaña existente.
    $campaignId = '688100ebb3bee3000749ea88'; // EJEMPLO: '5c7fe5c86edcf200083b812a' o el ID de tu campaña ya creada

    $messageContent = $mensaje; // El contenido del mensaje SMS
    $priority = 'HIGH'; // Prioridad del mensaje (puede ser 'HIGH', 'MEDIUM', 'LOW')

    // Lista de números de teléfono a los que se enviará el mensaje
    // Asegúrate de que los números estén en formato E.164 (código de país + número, sin "+")
    $destinations = ["57$celular"];

    // URL de la API para enviar mensajes dentro de una campaña
    $url = 'https://cloud.go4clients.com:8580/api/campaigns/sms/v1.0/' . $campaignId;

    // Prepara los datos a enviar en formato JSON
    $data = array(
        'message' => $messageContent,
        'priority' => $priority,
        'destinationsList' => $destinations
    );
    $jsonData = json_encode($data);

    // Inicializa cURL
    $ch = curl_init($url);

    // Configura las opciones de cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Devuelve la respuesta como una cadena
    curl_setopt($ch, CURLOPT_POST, true);           // Establece el método de solicitud a POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Envía los datos JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'apiKey: ' . $apiKey,
        'apiSecret: ' . $apiSecret,
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData) // Es una buena práctica incluir Content-Length
    ));

    // Ejecuta la solicitud cURL
    $response = curl_exec($ch);
    curl_close($ch);
}

function enviarsms2($celular,$mensaje)
{
    $apiKey = '72acebf49e4f4944915380ec516e5cbf';   // Reemplaza con tu API Key real si es diferente
    $apiSecret = '4746371202038681'; // Reemplaza con tu API Secret real si es diferente

    // ID de la campaña a la que quieres enviar el mensaje
    // ¡Importante! Reemplaza 'CAMPING_ID' con el ID real de tu campaña existente.
    $campaignId = '688100ebb3bee3000749ea88'; // EJEMPLO: '5c7fe5c86edcf200083b812a' o el ID de tu campaña ya creada

    $messageContent = $mensaje; // El contenido del mensaje SMS
    $priority = 'HIGH'; // Prioridad del mensaje (puede ser 'HIGH', 'MEDIUM', 'LOW')

    // Lista de números de teléfono a los que se enviará el mensaje
    // Asegúrate de que los números estén en formato E.164 (código de país + número, sin "+")
    $destinations = ["57$celular"];

    // URL de la API para enviar mensajes dentro de una campaña
    $url = 'https://cloud.go4clients.com:8580/api/campaigns/sms/v1.0/' . $campaignId;

    // Prepara los datos a enviar en formato JSON
    $data = array(
        'message' => $messageContent,
        'priority' => $priority,
        'destinationsList' => $destinations
    );
    $jsonData = json_encode($data);

    // Inicializa cURL
    $ch = curl_init($url);

    // Configura las opciones de cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Devuelve la respuesta como una cadena
    curl_setopt($ch, CURLOPT_POST, true);           // Establece el método de solicitud a POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Envía los datos JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'apiKey: ' . $apiKey,
        'apiSecret: ' . $apiSecret,
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData) // Es una buena práctica incluir Content-Length
    ));

    // Ejecuta la solicitud cURL
    $response = curl_exec($ch);
    echo $response;
    curl_close($ch);
}

?>
