<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <a href="/">Home</a>
    <form action="/login.php" method="post">
        <label for="email">Email:</label> <br>
        <input type="email" name="email" id="email"> <br>
        <label for="password">Password:</label> <br>
        <input type="password" name="password" id="password"> <br>
        <button type="submit">Login</button>
    </form>
    <a href="/register.php">Register jika belum punya akun</a>
</body>
</html>