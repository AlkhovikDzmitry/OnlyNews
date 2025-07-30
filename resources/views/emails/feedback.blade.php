<!DOCTYPE html>
<html>
<head>
    <title>Новое сообщение с сайта</title>
</head>
<body>
    <h2>Новое сообщение через форму обратной связи</h2>
    
    <p><strong>Имя:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Сообщение:</strong></p>
    <p>{{ $data['message'] }}</p>
</body>
</html>