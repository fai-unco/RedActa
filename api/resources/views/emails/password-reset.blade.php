<html>
  <body>
    <p>Hola {{ $name }},</p>
    <p>Recibimos una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
    <p><a href="{{ $link }}">{{ $link }}</a></p>
    <p>Este enlace expirará el {{ $expiration }}.</p>
    <p>Si no solicitaste este cambio, ignora este correo.</p>
    <p>Saludos</p>
  </body>
</html>