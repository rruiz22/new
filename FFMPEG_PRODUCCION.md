# 🚀 Guía de FFmpeg para Producción

## 📋 **Resumen del Problema**
FFmpeg instalado localmente en `C:\ffmpeg\...` NO estará disponible en el servidor de producción. Necesitas configurar FFmpeg en tu servidor.

## 🛠️ **Opciones de Hosting**

### 1. **VPS/Servidor Dedicado** ⭐ **RECOMENDADO**
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install ffmpeg

# CentOS/RHEL
sudo dnf install ffmpeg
# o
sudo yum install epel-release
sudo yum install ffmpeg

# Verificar instalación
ffmpeg -version
which ffmpeg  # Debería mostrar: /usr/bin/ffmpeg
```

### 2. **Hosting Compartido** ⚠️ **LIMITADO**
- **Problema**: No puedes instalar software
- **Solución**: Contacta al soporte técnico
- **Alternativa**: Cambiar a VPS

### 3. **Servicios en la Nube** 🌐 **ESCALABLE**
```php
// Ejemplo con Cloudinary
$cloudinary = new \Cloudinary\Cloudinary([
    'cloud_name' => 'tu-cloud-name',
    'api_key' => 'tu-api-key',
    'api_secret' => 'tu-api-secret'
]);

$result = $cloudinary->video()->upload($videoPath, [
    'resource_type' => 'video',
    'eager' => [
        ['quality' => 'auto', 'format' => 'mp4'],
        ['width' => 1280, 'height' => 720, 'crop' => 'scale']
    ]
]);
```

## 🔧 **Configuración Automática**

### **Helper Creado** ✅
El helper `ffmpeg_helper.php` que acabamos de crear detecta automáticamente:

```php
// Desarrollo (Windows)
C:\ffmpeg\ffmpeg-7.1.1-essentials_build\bin\ffmpeg.exe

// Producción (Linux) 
/usr/bin/ffmpeg           // Ubuntu/Debian
/usr/local/bin/ffmpeg     // Compilado desde código
/opt/ffmpeg/bin/ffmpeg    // Instalación personalizada
```

### **Uso en tu Código**
```php
// Cargar helper (en tu controller o donde necesites)
helper('ffmpeg');

// Obtener información de FFmpeg
$info = get_ffmpeg_info();
echo "FFmpeg version: " . $info['version'];
echo "Path: " . $info['path'];
echo "Environment: " . $info['environment'];

// Procesar video
$result = process_video($inputPath, $outputPath, [
    'quality' => 'high',
    'resolution' => '1080p'
]);

if ($result['success']) {
    echo "Video procesado exitosamente";
} else {
    echo "Error: " . implode("\n", $result['output']);
}
```

## 📝 **Pasos para Producción**

### **1. Preparar el Servidor**
```bash
# Conectar por SSH
ssh usuario@tu-servidor.com

# Instalar FFmpeg
sudo apt update
sudo apt install ffmpeg

# Verificar
ffmpeg -version
```

### **2. Configurar Permisos**
```bash
# Crear directorio para uploads
sudo mkdir -p /var/www/uploads/videos
sudo chown www-data:www-data /var/www/uploads/videos
sudo chmod 755 /var/www/uploads/videos

# Dar permisos a PHP para ejecutar FFmpeg
sudo usermod -a -G video www-data
```

### **3. Configurar PHP**
```php
// En tu .env de producción
ffmpeg.path = /usr/bin/ffmpeg
ffmpeg.timeout = 300
ffmpeg.memory_limit = 512M
```

### **4. Verificar en Producción**
```php
// Crear archivo temporal: test_ffmpeg_prod.php
<?php
require_once 'app/Helpers/ffmpeg_helper.php';

$info = get_ffmpeg_info();
print_r($info);

// Eliminar después de verificar
```

## 🐳 **Opción Docker** (Avanzado)

### **Dockerfile**
```dockerfile
FROM php:8.2-fpm

# Instalar FFmpeg
RUN apt-get update && apt-get install -y \
    ffmpeg \
    && rm -rf /var/lib/apt/lists/*

# Verificar instalación
RUN ffmpeg -version
```

### **Docker Compose**
```yaml
version: '3.8'
services:
  app:
    build: .
    volumes:
      - ./uploads:/var/www/uploads
    environment:
      - FFMPEG_PATH=/usr/bin/ffmpeg
```

## 🔍 **Verificación de Funcionamiento**

### **1. Prueba Simple**
```bash
# En el servidor
ffmpeg -version
```

### **2. Prueba desde PHP**
```php
// En tu controlador
public function testFFmpegProduction()
{
    helper('ffmpeg');
    
    $info = get_ffmpeg_info();
    
    return $this->response->setJSON([
        'status' => $info['installed'] ? 'success' : 'error',
        'data' => $info
    ]);
}
```

### **3. Prueba Completa**
```php
// Procesar video de prueba
$testInput = 'uploads/test.mp4';
$testOutput = 'uploads/test_processed.mp4';

if (file_exists($testInput)) {
    $result = process_video($testInput, $testOutput);
    
    if ($result['success']) {
        echo "✅ FFmpeg funciona correctamente";
    } else {
        echo "❌ Error: " . implode("\n", $result['output']);
    }
}
```

## 📊 **Monitoreo y Logs**

### **Agregar Logs**
```php
// En tu función de procesamiento
log_message('info', 'FFmpeg command: ' . $command);
log_message('info', 'FFmpeg result: ' . json_encode($result));
```

### **Verificar Logs**
```bash
# En el servidor
tail -f /var/log/apache2/error.log
# o
tail -f /var/log/nginx/error.log
```

## 🚨 **Solución de Problemas**

### **Error: FFmpeg no encontrado**
```bash
# Verificar instalación
which ffmpeg
dpkg -l | grep ffmpeg

# Reinstalar si es necesario
sudo apt remove ffmpeg
sudo apt install ffmpeg
```

### **Error: Permisos**
```bash
# Verificar permisos
ls -la /usr/bin/ffmpeg
# Debería mostrar: -rwxr-xr-x

# Agregar usuario web al grupo video
sudo usermod -a -G video www-data
sudo systemctl restart apache2
```

### **Error: Memoria insuficiente**
```php
// Aumentar límites en php.ini
memory_limit = 512M
max_execution_time = 300
```

## 💡 **Mejores Prácticas**

### **1. Usar Colas de Trabajo**
```php
// Para videos grandes, usar procesamiento en segundo plano
$job = new ProcessVideoJob($inputPath, $outputPath, $options);
$job->dispatch();
```

### **2. Validar Archivos**
```php
// Antes de procesar
$allowedTypes = ['video/mp4', 'video/avi', 'video/mov'];
if (!in_array($file->getClientMimeType(), $allowedTypes)) {
    throw new Exception('Tipo de archivo no permitido');
}
```

### **3. Limpiar Archivos Temporales**
```php
// Eliminar archivos después de procesamiento
if (file_exists($tempFile)) {
    unlink($tempFile);
}
```

## 🏆 **Resultado Final**

Con esta configuración, tu proyecto:
- ✅ Detecta automáticamente FFmpeg en cualquier entorno
- ✅ Funciona en desarrollo (Windows) y producción (Linux)
- ✅ Maneja errores graciosamente
- ✅ Incluye funciones completas de procesamiento
- ✅ Es escalable y mantenible

## 📞 **Siguiente Paso**

1. **Subir tu proyecto al servidor**
2. **Instalar FFmpeg en el servidor**
3. **Verificar que funcione con las funciones del helper**
4. **Configurar procesamiento en segundo plano si es necesario**

¿Necesitas ayuda con algún paso específico? 🚀 