<?php
const API_URL = "https://whenisthenextmcufilm.com/api";

function get_data(string $url): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    return json_decode($result, true) ?? [];
}

$data = get_data(API_URL);
function get_release_message(array $data): string {
    if(empty($data)) return "No se pudo obtener la información";

    $date = new DateTime();
    $eventDate = new DateTime($data["release_date"]);
    $diff = $date->diff($eventDate);
    
    $days = $diff->format("%a");
    
    return match (true) {
        $days == "0" => "¡Se estrena hoy! 🍿",
        $days == "1" => "Se estrena mañana 🎥",
        default      => "$days días para el estreno 📅",
    };
}
$releaseMessage = get_release_message($data);
?>

<head>
    <meta charset="UTF-8"/>
    <title>La próxima pelicula de Marvel</title>
    <meta name="description" content="La próxima pelicula de Marvel"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="./img/myIcon.svg" rel="icon" type="image/x-icon"/> 
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
    >
</head>
<main class="container" style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; text-align: center; overflow-y: auto">
    <h1>La próxima pelicula de Marvel</h1>
    <?php if ($data) : ?>
        <section>
            <img src="<?= htmlspecialchars($data["poster_url"])?>" alt="<?= htmlspecialchars($data["title"]) ?>" width="300" style="border-radius: 16px" />
        </section>
        <hgroup>
            <h3><?= htmlspecialchars($data["title"]) ?></h3>
            <p style="font-size: 14px; "><strong style="text-transform: uppercase"><?= $releaseMessage ?> </strong></p>
            <p style="margin: 1em 0; text-transform: uppercase; font-size: 16px">Fecha de estreno: <?= htmlspecialchars($data["release_date"]) ?></p>
            <p><strong><?= htmlspecialchars($data["following_production"]["title"]) ?></strong> es la siguiente pelicula</p>
        </hgroup>
        <footer style="font-size: 14px">Hecho con ❤ by <a href="https://github.com/emamaz">Santiago Maza</a></footer>
    <?php else: ?>
        <p>Hubo un error al conectar con Marvel. Inténtalo más tarde.</p>
    <?php endif; ?>
</main>