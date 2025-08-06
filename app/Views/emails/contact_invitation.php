<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación de Contacto</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .invitation-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .group-info {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }
        .group-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .highlight {
            color: #667eea;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 ¡Has sido invitado!</h1>
            <p>Te han invitado a unirte como contacto</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Hola <?= !empty($invitation['first_name']) ? esc($invitation['first_name']) : '' ?>!</h2>
            
            <p><strong><?= esc($senderName) ?></strong> te ha enviado una invitación para unirte como contacto en nuestro sistema.</p>

            <?php if (!empty($invitation['message'])): ?>
                <div class="invitation-details">
                    <h3>📝 Mensaje personal:</h3>
                    <p><?= nl2br(esc($invitation['message'])) ?></p>
                </div>
            <?php endif; ?>

            <!-- Invitation Details -->
            <div class="invitation-details">
                <h3>📋 Detalles de tu invitación:</h3>
                
                <?php if (!empty($invitation['client_name'])): ?>
                    <p><strong>Cliente:</strong> <span class="highlight"><?= esc($invitation['client_name']) ?></span></p>
                <?php endif; ?>

                <?php if (!empty($invitation['group_name'])): ?>
                    <div class="group-info">
                        <div class="group-color" style="background-color: <?= esc($invitation['group_color'] ?? '#667eea') ?>;"></div>
                        <div>
                            <strong>Grupo asignado:</strong> <span class="highlight"><?= esc($invitation['group_name']) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <p><strong>Email:</strong> <?= esc($invitation['email']) ?></p>
                <p><strong>Invitación válida hasta:</strong> <span class="highlight"><?= date('d/m/Y H:i', strtotime($invitation['expires_at'])) ?></span></p>
            </div>

            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= esc($invitationUrl) ?>" class="btn">
                    ✅ Aceptar Invitación
                </a>
            </div>

            <!-- Instructions -->
            <div class="warning">
                <strong>⚠️ Importante:</strong>
                <ul>
                    <li>Esta invitación expira el <strong><?= date('d/m/Y a las H:i', strtotime($invitation['expires_at'])) ?></strong></li>
                    <li>Al aceptar, podrás crear tu cuenta y acceder al sistema</li>
                    <li>Si no puedes hacer clic en el botón, copia y pega este enlace en tu navegador:</li>
                </ul>
                <p style="word-break: break-all; background: #fff; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;">
                    <?= esc($invitationUrl) ?>
                </p>
            </div>

            <p>Si tienes alguna pregunta, puedes contactar directamente a <strong><?= esc($senderName) ?></strong> o responder a este email.</p>

            <p>¡Esperamos verte pronto en nuestro sistema! 🚀</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este email fue enviado automáticamente. Por favor, no respondas a esta dirección.</p>
            <p>&copy; <?= date('Y') ?> - Sistema de Gestión de Contactos</p>
        </div>
    </div>
</body>
</html> 