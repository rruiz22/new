<?php

namespace Config;

/**
 * Configuración personalizada para MySQL
 */
class MySQL
{
    /**
     * Aplicar configuraciones de sesión para optimizar las conexiones persistentes
     */
    public static function optimizeConnections($db = null)
    {
        if (!$db) {
            $db = \Config\Database::connect();
        }
        
        try {
            // Aumentar tiempo de espera antes de desconectar conexiones inactivas
            $db->query("SET SESSION wait_timeout = 28800"); // 8 horas
            
            // Aumentar tiempo de espera para conexiones interactivas
            $db->query("SET SESSION interactive_timeout = 28800"); // 8 horas
            
            // Configurar un keep-alive para la conexión
            $db->query("SET SESSION net_read_timeout = 360"); // 6 minutos
            $db->query("SET SESSION net_write_timeout = 360"); // 6 minutos
            
            // Maximizar los buffers para reducir problemas de desconexión
            $db->query("SET SESSION max_allowed_packet = 16777216"); // 16MB
            
            // Configurar reconexión automática para la versión del servidor que lo soporte
            $db->query("SET SESSION session_track_system_variables = 'autocommit,character_set_client,character_set_connection,character_set_results,time_zone'");
            
            // Registrar la configuración aplicada
            log_message('debug', 'MySQL: Conexión optimizada con parámetros extendidos');
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'MySQL: Error al configurar parámetros de conexión: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verificar si una conexión a la base de datos está activa
     */
    public static function isConnectionAlive($db = null)
    {
        if (!$db) {
            $db = \Config\Database::connect();
        }
        
        try {
            $db->query("SELECT 1");
            return true;
        } catch (\Exception $e) {
            log_message('error', 'MySQL: Conexión a la base de datos perdida: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Intentar reconectar a la base de datos si la conexión se ha perdido
     */
    public static function reconnectIfNeeded($db = null)
    {
        if (!$db) {
            $db = \Config\Database::connect();
        }
        
        if (!self::isConnectionAlive($db)) {
            log_message('debug', 'MySQL: Intentando reconexión a la base de datos...');
            
            try {
                // Cerrar todas las conexiones existentes
                \Config\Database::closeConnections();
                
                // Obtener una nueva conexión
                $db = \Config\Database::connect();
                
                // Verificar que la nueva conexión funciona
                $db->query("SELECT 1");
                
                // Optimizar la conexión
                self::optimizeConnections($db);
                
                log_message('debug', 'MySQL: Reconexión exitosa a la base de datos');
                return $db;
            } catch (\Exception $e) {
                log_message('error', 'MySQL: Error al reconectar a la base de datos: ' . $e->getMessage());
                throw $e;
            }
        }
        
        return $db;
    }
} 