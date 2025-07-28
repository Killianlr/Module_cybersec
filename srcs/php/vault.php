<?php
function get_jwt_secret_from_vault(): string {
    $vault_addr = 'http://vault:8200'; // URL du container Vault depuis PHP
    $vault_token = 'root';            // Token de dev (à ne pas utiliser en prod)
    $secret_path = '/v1/secret/data/jwt';

    $ch = curl_init($vault_addr . $secret_path);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-Vault-Token: $vault_token"
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception("Erreur Vault: code $httpCode");
    }

    $data = json_decode($response, true);
    return $data['data']['data']['secret'] ?? throw new Exception("Clé JWT non trouvée dans Vault");
}
