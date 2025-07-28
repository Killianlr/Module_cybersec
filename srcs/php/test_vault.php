<?php
require_once 'vault.php';

try {
    $secret = get_jwt_secret_from_vault();
    echo "✅ Clé JWT récupérée : $secret";
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
