@echo off
REM SiteGround Deployment Script para MDA (Windows)
REM Este script resuelve conflictos de ramas divergentes y despliega a SiteGround

echo 🚀 Iniciando deployment a SiteGround...
echo 📅 %DATE% %TIME%

REM Cambiar al directorio del proyecto
cd /d "C:\xampp\htdocs\mda_nuevo"

echo ⚙️  Configurando Git...
git config pull.rebase false

echo 📊 Estado actual del repositorio:
git status --porcelain

REM Verificar si hay cambios locales
git diff-index --quiet HEAD -- || (
    echo 💾 Commitando cambios locales...
    git add .
    git commit -m "Local changes before SiteGround deploy - %DATE%_%TIME:~0,8%"
)

echo 🔄 Sincronizando con repositorio remoto...
git pull origin master --no-rebase
if %ERRORLEVEL% neq 0 (
    echo ❌ Error en pull. Verificar conflictos manualmente.
    echo 📋 Archivos en conflicto:
    git status
    echo.
    echo ⚠️  Para resolver:
    echo    1. Resolver conflictos en archivos marcados
    echo    2. git add .
    echo    3. git commit -m "Resolve merge conflicts"
    echo    4. git push origin master
    pause
    exit /b 1
)

echo 📤 Enviando cambios al repositorio...
git push origin master

echo ✅ Verificando sincronización...
git status

echo 🎉 Deployment completado exitosamente!
echo 📅 %DATE% %TIME%

echo.
echo 📝 Próximos pasos en SiteGround:
echo 1. Conectar via SSH o File Manager
echo 2. Navegar a public_html  
echo 3. Ejecutar: git pull origin master
echo 4. Verificar permisos de archivos
echo 5. Probar la aplicación

pause