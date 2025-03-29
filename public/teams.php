<?php
require_once '../db/db.php';
require_once '../src/auth.php';
requireLogin();
session_start();
require_once '../src/pokeapi.php';

$pokemons = getPokemons(20, 0)['results'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_name = $_POST['team_name'];
    $characters_selected = $_POST['characters'];

    if (count($characters_selected) <= 5) {
        $stmt = $pdo->prepare("INSERT INTO teams (user_id, name) VALUES (:user_id, :name)");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':name', $team_name);
        $stmt->execute();

        $team_id = $pdo->lastInsertId();

        foreach ($characters_selected as $character_id) {
            $stmt = $pdo->prepare("INSERT INTO team_characters (team_id, character_id) VALUES (:team_id, :character_id)");
            $stmt->bindParam(':team_id', $team_id);
            $stmt->bindParam(':character_id', $character_id);
            $stmt->execute();
        }

        header('Location: teams.php');
        exit();
    } else {
        $error = "Você pode adicionar no máximo 5 personagens ao seu time.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Times</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="teams-container">
        <h2>Gerenciar Times</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form action="teams.php" method="POST">
            <label for="team_name">Nome do Time:</label>
            <input type="text" name="team_name" id="team_name" required>

            <label for="characters">Selecione até 5 personagens:</label>
            <select name="characters[]" id="characters" multiple required>
                <?php foreach ($pokemons as $pokemon): ?>
                    <option value="<?= $pokemon['name'] ?>"><?= ucfirst($pokemon['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Criar Time</button>
        </form>

        <h3>Meus Times:</h3>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM teams WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($teams as $team):
            $stmt = $pdo->prepare("SELECT c.name FROM team_characters tc INNER JOIN characters c ON tc.character_id = c.id WHERE tc.team_id = :team_id");
            $stmt->bindParam(':team_id', $team['id']);
            $stmt->execute();
            $team_characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <div class="team">
                <h4><?= $team['name'] ?></h4>
                <p>Personagens:</p>
                <ul>
                    <?php foreach ($team_characters as $character): ?>
                        <li><?= $character['name'] ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="#">Excluir</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
