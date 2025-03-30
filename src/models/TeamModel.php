<?php

require_once '../db/db.php';

function createTeam($user_id, $team_name, $characters_selected) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO teams (user_id, name) VALUES (:user_id, :name)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':name', $team_name);
    $stmt->execute();
    $team_id = $pdo->lastInsertId();
    
    foreach ($characters_selected as $character) {
        $stmt = $pdo->prepare("INSERT INTO team_characters (team_id, character_name, character_image) VALUES (:team_id, :character_name, :character_image)");
        $stmt->bindParam(':team_id', $team_id);
        $stmt->bindParam(':character_name', $character['name']);
        $stmt->bindParam(':character_image', $character['image']);
        $stmt->execute();
    }
}

function getTeamsByUser($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM teams WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTeamCharacters($team_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT character_name, character_image FROM team_characters WHERE team_id = :team_id");
    $stmt->bindParam(':team_id', $team_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteTeam($team_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("DELETE FROM team_characters WHERE team_id = :team_id");
    $stmt->bindParam(':team_id', $team_id);
    $stmt->execute();
    
    $stmt = $pdo->prepare("DELETE FROM teams WHERE id = :team_id");
    $stmt->bindParam(':team_id', $team_id);
    $stmt->execute();
}
