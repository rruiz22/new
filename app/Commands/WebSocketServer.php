<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Libraries\ChatServer;

class WebSocketServer extends BaseCommand
{
    protected $group       = 'WebSocket';
    protected $name        = 'websocket:start';
    protected $description = 'Inicia el servidor WebSocket para el chat';

    public function run(array $params)
    {
        $port = 8080; // Puerto por defecto
        
        // Permitir pasar un puerto personalizado
        if (isset($params[0]) && is_numeric($params[0])) {
            $port = (int) $params[0];
        }
        
        // Verificar la conexión a la base de datos antes de iniciar el servidor
        try {
            $db = \Config\Database::connect();
            
            // Usar la clase MySQL personalizada para verificar y optimizar la conexión
            if (!\Config\MySQL::isConnectionAlive($db)) {
                throw new \Exception("No se pudo conectar a la base de datos");
            }
            
            // Aplicar configuraciones optimizadas para MySQL
            \Config\MySQL::optimizeConnections($db);
            
            CLI::write("Conexión a la base de datos verificada correctamente.", 'green');
        } catch (\Exception $e) {
            CLI::error("Error de conexión a la base de datos: " . $e->getMessage());
            CLI::write("Asegúrese de que el servidor MySQL esté en ejecución y las credenciales sean correctas.", 'yellow');
            return;
        }
        
        CLI::write("Iniciando servidor WebSocket en el puerto {$port}...", 'green');
        
        // Instanciar el servidor de chat
        $chatServer = new ChatServer();
        
        $server = IoServer::factory(
            new HttpServer(
                new WsServer($chatServer)
            ),
            $port
        );

        CLI::write("Servidor WebSocket iniciado. Escuchando en el puerto {$port}.", 'green');
        CLI::write("Presiona Ctrl+C para detener el servidor.", 'yellow');
        
        $server->run();
    }
    
    /**
     * Configura opciones de MySQL para optimizar conexiones de larga duración
     */
    private function optimizeMySQLConfiguration()
    {
        // Este método está obsoleto, ahora usamos la clase Config\MySQL
        try {
            $db = \Config\Database::connect();
            \Config\MySQL::optimizeConnections($db);
            CLI::write("Configuración de MySQL optimizada para conexiones de larga duración.", 'green');
        } catch (\Exception $e) {
            CLI::error("Error al configurar las opciones de MySQL: " . $e->getMessage());
        }
    }
} 