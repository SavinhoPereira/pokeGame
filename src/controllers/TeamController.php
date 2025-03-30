<?php
require_once '../db/db.php';
require_once '../src/auth.php';
require_once '../src/services/PokeApiService.php';
requireLogin();

class TeamController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getTeams($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM teams WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamCharacters($team_id) {
        $stmt = $this->pdo->prepare("SELECT character_name, character_image FROM team_characters WHERE team_id = :team_id");
        $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPokemonsForSelection() {
        return PokeApiService::getPokemons(20, 0);
    }

    public function createTeam($team_name, $characters_selected) {
        if (count($characters_selected) > 5) {
            return "Você pode adicionar no máximo 5 personagens ao seu time.";
        }

        $stmt = $this->pdo->prepare("INSERT INTO teams (user_id, name) VALUES (:user_id, :name)");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $team_name, PDO::PARAM_STR);
        $stmt->execute();
        $team_id = $this->pdo->lastInsertId();

        foreach ($characters_selected as $character_name) {
            $pokemonData = PokeApiService::getPokemonByName($character_name);
            if ($pokemonData) {
                $image_url = $pokemonData['sprites']['front_default'] ?? '';

                $stmt = $this->pdo->prepare("INSERT INTO team_characters (team_id, character_name, character_image) VALUES (:team_id, :character_name, :character_image)");
                $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
                $stmt->bindParam(':character_name', $character_name, PDO::PARAM_STR);
                $stmt->bindParam(':character_image', $image_url, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
        return null;
    }

    public function deleteTeam($team_id) {

        $stmt = $this->pdo->prepare("DELETE FROM team_characters WHERE team_id = :team_id");
        $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->pdo->prepare("DELETE FROM teams WHERE id = :team_id");
        $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

$controller = new TeamController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['team_name'])) {
    $characters_selected = isset($_POST['characters']) ? explode(",", $_POST['characters']) : [];
    $error = $controller->createTeam($_POST['team_name'], $characters_selected);
    header('Location: ../public/teams.php');
    exit();
}

if (isset($_GET['delete'])) {
    $controller->deleteTeam($_GET['delete']);
    header('Location: ../public/teams.php');
    exit();
}

?>
