<?php
    const TOKEN_ANDERCODE = "ANDERCODEPHPAPIMETA";
    const WEBHOOK_URL = "https://anderson-bastidas.com/webhook.php";

    function verificarToken($req,$res){
        try{
            $token = $req['hub_verify_token'];
            $challenge = $req['hub_challenge'];

            if (isset($challenge) && isset($token) && $token == TOKEN_ANDERCODE){
                $res->send($challenge);
            }else{
                $res ->status(400)->send();
            }

        }catch(Exception $e){
            $res ->status(400)->send();
        }
    }

    function recibirMensajes($req,$res){
        try{
            $entry = $req['entry'][0];
            $changes = $entry['changes'][0];
            $value = $changes['value'];
            $objetomensaje = $value['messages'];
            $mensaje = $objetomensaje[0];

            $comentario = $mensaje['text']['body'];
            $numero = $mensaje['from'];

            EnviarMensajeWhastapp($comentario,$numero);

            $archivo = fopen("log.txt","a");
            $texto = json_encode($numero);
            fwrite($archivo,$texto);
            fclose($archivo);

            $res ->status(200)->send("EVENT_RECEIVED");
        }catch(Exception $e){
            $res ->status(200)->send("EVENT_RECEIVED");
        }
    }

    function EnviarMensajeWhastapp($comentario,$numero){
        $comentario = strtolower($comentario);

        if (strpos($comentario,'hola') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Hola visita mi web andercon-bastidas.com"
                ]
            ]);
        }else if ($comentario=='1') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
                ]
            ]);
        }else if ($comentario=='2') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "location",
                "location"=> [
                    "latitude" => "-12.067158831865067",
                    "longitude" => "-77.03377940839486",
                    "name" => "Estadio Nacional del Perú",
                    "address" => "Cercado de Lima"
                ]
            ]);
        }else if ($comentario=='3') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "document",
                "document"=> [
                    "link" => "http://s29.q4cdn.com/175625835/files/doc_downloads/test.pdf",
                    "caption" => "Temario del Curso #001"
                ]
            ]);
        }else if ($comentario=='4') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "audio",
                "audio"=> [
                    "link" => "https://filesamples.com/samples/audio/mp3/sample1.mp3",
                ]
            ]);
        }else if ($comentario=='5') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "to" => $numero,
                "text" => array(
                    "preview_url" => true,
                    "body" => "Introducción al curso! https://youtu.be/6ULOE2tGlBM"
                )
            ]);
        }else if ($comentario=='6') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "🤝 En breve me pondré en contacto contigo. 🤓"
                )
            ]);
        }else if ($comentario=='7') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "📅 Horario de Atención: Lunes a Viernes. \n🕜 Horario: 9:00 a.m. a 5:00 p.m. 🤓"
                )
            ]);
        }else if (strpos($comentario,'gracias') !== false) {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "Gracias a ti por contactarme. 🤩"
                )
            ]);
        }else if (strpos($comentario,'adios') !== false || strpos($comentario,'bye') !== false || strpos($comentario,'nos vemos') !== false || strpos($comentario,'adiós') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "Hasta luego. 🌟"
                )
            ]);
        }else if (strpos($comentario,'gchatgpt:')!== false){
            $texto_sin_gchatgpt = str_replace("gchatgpt: ", "", $comentario);

            $apiKey = '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';

            $data = [
                'model' => 'text-davinci-003',
                'prompt' => $texto_sin_gchatgpt,
                'temperature' => 0.7,
                'max_tokens' => 300,
                'n' => 1,
                'stop' => ['\n']
            ];

            $ch = curl_init('https://api.openai.com/v1/completions');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ));

            $response = curl_exec($ch);
            $responseArr = json_decode($response, true);

            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => $responseArr['choices'][0]['text']
                )
            ]);
        }else{
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "🚀 Hola, visita mi web anderson-bastidas.com para más información.\n \n📌Por favor, ingresa un número #️⃣ para recibir información.\n \n1️⃣. Información del Curso. ❔\n2️⃣. Ubicación del local. 📍\n3️⃣. Enviar temario en pdf. 📄\n4️⃣. Audio explicando curso. 🎧\n5️⃣. Video de Introducción. ⏯️\n6️⃣. Hablar con AnderCode. 🙋‍♂️\n7️⃣. Horario de Atención. 🕜"
                ]
            ]);
        }

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/json\r\nAuthorization: Bearer EAAHd9K9P3aUBANONXPgRuamKOvoZAV2QHYjOry9ngiWBcfZBPyZCzES5hHwPCemZCvU91xruiXxZCVojFw1ZCCYJiYj7p4pWShu5b6tghaihmCYrd6hHZAqYoCS7OhV1KXZB7pZBOwBKSgyTychwisMjbQhK34YHsXJlXioZBDcWddPMEWNj3x3OBwRQ3nnkU4kzsiks3ovrRAlEEv5bijLucq\r\n",
                'content' => $data,
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents('https://graph.facebook.com/v15.0/113319844996763/messages', false, $context);

        if ($response === false) {
            echo "Error al enviar el mensaje\n";
        } else {
            echo "Mensaje enviado correctamente\n";
        }
    }

    if ($_SERVER['REQUEST_METHOD']==='POST'){
        $input = file_get_contents('php://input');
        $data = json_decode($input,true);

        recibirMensajes($data,http_response_code());
    }else if($_SERVER['REQUEST_METHOD']==='GET'){
        if(isset($_GET['hub_mode']) && isset($_GET['hub_verify_token']) && isset($_GET['hub_challenge']) && $_GET['hub_mode'] === 'subscribe' && $_GET['hub_verify_token'] === TOKEN_ANDERCODE){
            echo $_GET['hub_challenge'];
        }else{
            http_response_code(403);
        }
    }
?>