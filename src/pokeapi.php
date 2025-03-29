<?php
function getPokemons($limit = 20, $offset = 0) {
    $url = "https://pokeapi.co/api/v2/pokemon?limit=$limit&offset=$offset";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function getPokemonDetails($pokemonId) {
    $url = "https://pokeapi.co/api/v2/pokemon/$pokemonId/";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function savePokemonsToDatabase($limit = 20, $offset = 0) {
    global $pdo;
    $pokemons = getPokemons($limit, $offset)['results'];

    foreach ($pokemons as $pokemon) {
        $details = getPokemonDetails($pokemon['name']);
        $name = $details['name'];
        $image = $details['sprites']['front_default'];
        $type = $details['types'][0]['type']['name'];

        $stmt = $pdo->prepare("INSERT INTO characters (name, image, type) VALUES (:name, :image, :type)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
    }
}

?>
