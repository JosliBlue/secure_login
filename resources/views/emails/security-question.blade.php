<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregunta de Seguridad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .question-box {
            background-color: #f8f9fa;
            border: 2px solid #dc2626;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            color: #dc2626;
        }
        .instruction {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .instruction-title {
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 5px;
        }
        .instruction-text {
            color: #0d47a1;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .security-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="security-icon">🔐</div>
            <h1>Secure Login</h1>
            <p>Verificación de Seguridad Final</p>
        </div>

        <div class="content">
            <h2>¡Último Paso!</h2>
            <p>Para completar tu inicio de sesión, por favor responde la siguiente pregunta de seguridad:</p>

            <div class="question-box">
                {{ $question }}
            </div>

            <div class="instruction">
                <div class="instruction-title">📝 Instrucciones:</div>
                <div class="instruction-text">
                    Regresa a la página de inicio de sesión y responde esta pregunta exactamente como la configuraste.
                </div>
            </div>

            <div class="warning">
                <strong>⚠️ Importante:</strong>
                <ul style="text-align: left; margin: 10px 0;">
                    <li>La respuesta debe ser <strong>exactamente</strong> igual (sí importan mayúsculas/minúsculas)</li>
                    <li>Si no recuerdas la respuesta, contacta al administrador</li>
                    <li>Esta es una medida adicional de seguridad</li>
                </ul>
            </div>

            <p style="margin-top: 30px; color: #6c757d;">
                Una vez que respondas correctamente, podrás acceder a tu cuenta.
            </p>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
            <p>&copy; {{ date('Y') }} Secure Login. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
