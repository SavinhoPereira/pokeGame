<?php
class PokeApiService {
    private static $baseUrl = "https://pokeapi.co/api/v2/";

    public static function getPokemons($limit = 20, $offset = 0) {
        $url = self::$baseUrl . "pokemon?limit=" . $limit . "&offset=" . $offset;
        $response = file_get_contents($url);
        if ($response) {
            $data = json_decode($response, true);
            return $data['results'];
        }
        return [];
    }

    public static function getPokemonDetails($name) {
        $url = self::$baseUrl . "pokemon/" . strtolower($name);
        $response = file_get_contents($url);
        if ($response) {
            $data = json_decode($response, true);
            return [
                'name' => $data['name'],
                'image' => $data['sprites']['front_default']
            ];
        }
        return null;
    }
}
?>
