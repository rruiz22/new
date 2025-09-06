#!/bin/bash

# SiteGround Deployment Script para MDA
# Este script resuelve conflictos de ramas divergentes y despliega a SiteGround

set -e

echo "🚀 Iniciando deployment a SiteGround..."
echo "📅 $(date)"

# Configurar Git para manejar ramas divergentes
echo "⚙️  Configurando Git..."
git config pull.rebase false

# Verificar el estado actual
echo "📊 Estado actual del repositorio:"
git status --porcelain

# Agregar y commitear cambios locales si existen
if [ -n "$(git status --porcelain)" ]; then
    echo "💾 Commitando cambios locales..."
    git add .
    git commit -m "Local changes before SiteGround deploy - $(date +%Y-%m-%d_%H-%M-%S)"
fi

# Hacer pull con merge para resolver divergencias
echo "🔄 Sincronizando con repositorio remoto..."
git pull origin master --no-rebase || {
    echo "❌ Error en pull. Intentando resolver conflictos..."
    
    # Si hay conflictos, mostrar archivos en conflicto
    echo "📋 Archivos en conflicto:"
    git status --porcelain | grep "^UU"
    
    echo "⚠️  Resuelve los conflictos manualmente y ejecuta:"
    echo "   git add ."
    echo "   git commit -m 'Resolve merge conflicts'"
    echo "   git push origin master"
    exit 1
}

# Push de los cambios fusionados
echo "📤 Enviando cambios al repositorio..."
git push origin master

# Verificar que la sincronización fue exitosa
echo "✅ Verificando sincronización..."
git status

echo "🎉 Deployment completado exitosamente!"
echo "📅 $(date)"

# Instrucciones para SiteGround
echo ""
echo "📝 Próximos pasos en SiteGround:"
echo "1. Conectar via SSH o File Manager"
echo "2. Navegar a public_html"
echo "3. Ejecutar: git pull origin master"
echo "4. Verificar permisos de archivos"
echo "5. Probar la aplicación"