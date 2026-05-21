#!/bin/bash
set -e

REMOTE="ubuntu@146.235.224.99"
KEY="/home/fdev-ms/.ssh/ssh-key-2025-10-20.key"
PROJECT_DIR="/var/www/html/sigesaude"

echo "==> Verificando arquivos modificados..."
git status --short

echo "==> Adicionando arquivos ao stage..."
git add -A

if git diff --cached --quiet; then
    echo "==> Nenhuma mudanca para commitar."
else
    echo "==> Criando commit..."
    git diff --cached --stat
    git commit -m "deploy: $(date '+%Y-%m-%d %H:%M') — atualizacao automatica"
fi

echo "==> Enviando para o GitHub..."
git push origin main

echo "==> Fazendo deploy no servidor..."
ssh -i "$KEY" -o StrictHostKeyChecking=no "$REMOTE" "cd $PROJECT_DIR && git pull origin main && php artisan optimize:clear" 2>&1 || true

echo "==> Deploy concluido!"
echo ">> Para alterar o IP do servidor edite este arquivo (variavel REMOTE)."