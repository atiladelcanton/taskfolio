<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Convite para se cadastrar</h2>
    </div>

    <div class="content">
        <p>Olá,</p>

        <p>Você foi convidado para se cadastrar e participar de um projeto da equipe <strong>{{ $ownerTeamName }}</strong>!</p>

        <p>Para completar seu cadastro, clique no botão abaixo:</p>

        <div style="text-align: center;">
            <a href="{{ $urlRegister }}" class="button">Cadastrar-se agora</a>
        </div>

        <p>Ou copie e cole o seguinte link no seu navegador:</p>
        <p>{{ $urlRegister }}</p>

        <p>Este convite é válido ate {{ $expirationDays }}.</p>

        <p>Caso você não esteja esperando este convite, por favor ignore este email.</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} {{ $ownerTeamName }} - Todos os direitos reservados.</p>
    </div>
</div>
</body>
</html>
