#!/bin/sh

# Attendre que Vault soit prêt à répondre
echo "⏳ Attente de Vault..."
while ! curl -s http://vault:8200/v1/sys/health >/dev/null; do
  sleep 1
done

echo "✅ Vault est prêt. Injection du secret..."

# Crée le secret JWT
curl -s --request POST \
  --header "X-Vault-Token: root" \
  --data "{\"data\": {\"secret\": \"${JWT_SECRET}\"}}" \
  http://vault:8200/v1/secret/data/jwt

echo "✅ Secret JWT injecté dans Vault."
