<?php
require_once '../db/db.php';
require_once '../src/auth.php';
require_once '../src/services/PokeApiService.php';
require_once '../src/controllers/TeamController.php';
requireLogin();

$controller = new TeamController($pdo);
$pokemons = $controller->getPokemonsForSelection();  

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['team_name'])) {
    $error = $controller->createTeam($_POST['team_name'], $_POST['characters']);
    header('Location: teams.php');
    exit();
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

        <form action="teams.php" method="POST">
            <label for="team_name">Nome do Time:</label>
            <input type="text" name="team_name" id="team_name" required>

            <label for="characters">Selecione até 5 personagens:</label>
            <select name="characters[]" id="characters" multiple>
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
            $stmt = $pdo->prepare("SELECT character_name, character_image FROM team_characters WHERE team_id = :team_id");
            $stmt->bindParam(':team_id', $team['id']);
            $stmt->execute();
            $team_characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <div class="team">
                <h4><?= $team['name'] ?></h4>
                <p>Personagens:</p>
                <ul>
                    <?php foreach ($team_characters as $character): ?>
                        <li>
                            <img src="<?= $character['character_image'] ?>" alt="<?= $character['character_name'] ?>" style="width: 50px; height: 50px;">
                            <?= $character['character_name'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="?delete=<?= $team['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este time?')">Excluir</a>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="../assets/js/selecionarPokemon.js"></script>
</body>
</html>
