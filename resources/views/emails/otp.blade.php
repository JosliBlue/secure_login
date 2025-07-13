<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Código de Verificación</title>
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
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .otp-code {
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #1f2937;
            margin: 20px 0;
            border-radius: 8px;
        }

        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .warning-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
        }

        .warning-text {
            color: #78350f;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔐 Secure Login</div>
            <h2>Código de Verificación</h2>
        </div>

        <p>Hemos recibido una solicitud de inicio de sesión para tu cuenta.</p>

        <p>Tu código de verificación es:</p>

        <div class="otp-code">
            {{ $otp }}
        </div>

        <div class="warning">
            <div class="warning-title">⚠️ Importante:</div>
            <div class="warning-text">
                • Este código expira en 10 minutos.<br>
                • No compartas este código con nadie.<br>
                • Si no solicitaste este código, ignora este mensaje.
            </div>
        </div>

        <p>Ingresa este código en la página de inicio de sesión para acceder a tu cuenta.</p>

        <div class="footer">
            Este es un mensaje automático, por favor no respondas a este correo.
        </div>
    </div>
</body>

</html>
