<?php
require_once '../vendor/autoload.php';
require_once '../auth.php';

header('Content-Type: application/json');

$payload = require_auth(); // 🔐 Authentifie via JWT

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || (!isset($data['email']) && !isset($data['password']))) {
        http_response_code(400);
        echo json_encode(['error' => 'Aucune donnée à modifier.']);
        exit;
    }

    $fields = [];
    $params = [':id' => $payload['id']];

    if (isset($data['email'])) {
        $fields[] = 'email = :email';
        $params[':email'] = $data['email'];
    }

    if (isset($data['password'])) {
        $fields[] = 'password = :password';
        $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    $db = new PDO('sqlite:/var/www/html/data/users.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['message' => 'Modifications enregistrées avec succès.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
}
