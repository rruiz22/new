<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatAttachmentModel extends Model
{
    protected $table = 'chat_attachments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['message_id', 'file_name', 'file_type', 'file_size'];

    // Dates
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    /**
     * Obtener adjuntos de un mensaje
     */
    public function getMessageAttachments($messageId)
    {
        return $this->where('message_id', $messageId)
                    ->findAll();
    }
    
    /**
     * Almacenar un archivo adjunto
     */
    public function storeAttachment($messageId, $file)
    {
        $fileData = [
            'message_id' => $messageId,
            'file_name' => $file['name'],
            'file_type' => $file['type'],
            'file_size' => $file['size'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($fileData);
    }
    
    /**
     * Eliminar adjuntos de un mensaje
     */
    public function deleteMessageAttachments($messageId)
    {
        // Primero obtener todos los archivos para eliminarlos físicamente
        $attachments = $this->getMessageAttachments($messageId);
        
        foreach ($attachments as $attachment) {
            $filePath = WRITEPATH . 'uploads/chat/' . $attachment['file_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Luego eliminar registros de la base de datos
        return $this->where('message_id', $messageId)
                    ->delete();
    }
} 