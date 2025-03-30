<?php
require_once '../db/db.php';
require_once '../src/auth.php';
require_once '../src/controllers/TeamController.php'; 
requireLogin();

$controller = new TeamController($pdo);
$user_id = $_SESSION['user_id'];
$teams = $controller->getTeams($user_id);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Times</title>
</head>
<body>
    <h1>Meus Times</h1>
    
    <?php if (empty($teams)): ?>
        <p>Você ainda não criou nenhum time.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($teams as $team): ?>
                <li>
                    <strong><?php echo htmlspecialchars($team['name']); ?></strong>
                    <a href="teams.php?delete=<?php echo $team['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este time?');">Excluir</a>
                    <ul>
                        <?php
                        $characters = $controller->getTeamCharacters($team['id']);
                        foreach ($characters as $character):
                        ?>
                            <li>
                                <img src="<?php echo htmlspecialchars($character['character_image']); ?>" alt="<?php echo htmlspecialchars($character['character_name']); ?>" width="50">
                                <?php echo htmlspecialchars($character['character_name']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h2>Criar Novo Time</h2>
    <form method="post" action="../src/controllers/TeamController.php">
        <input type="text" name="team_name" placeholder="Nome do Time" required>
        <input type="text" name="characters" placeholder="Pokémons" required>
        <button type="submit">Criar Time</button>
    </form>
</body>
</html>
