<?php

class PokeApiService {
    private const API_BASE_URL = "https://pokeapi.co/api/v2/pokemon/";

    public static function getPokemons($limit = 20, $offset = 0) {
        $url = self::API_BASE_URL . "?limit=$limit&offset=$offset";
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public static function getPokemonDetails($name) {
        $url = self::API_BASE_URL . $name;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}
