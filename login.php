<?php
session_start(); // Inicia a sessão

include 'db.php'; // Arquivo onde você conecta ao banco

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Preparar a consulta para evitar SQL Injection
    $stmt = $conn->prepare("SELECT * FROM professor WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $senha); // "ss" significa que ambos os parâmetros são strings
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuário encontrado
        $_SESSION['user'] = $email; // Definindo a sessão do usuário
        $_SESSION['email'] = $email; // Definindo a sessão do email
        header("Location: index.php"); // Redireciona para index.php
        exit(); // Certifique-se de chamar exit após o redirecionamento
    } else {
        // Usuário não encontrado
        echo "<script>alert('Email ou senha incorretos. Tente novamente.');</script>";
    }
    $stmt->close();
}
$conn->close();
?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Gerenciamento de Recursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
  </head>
  <body style="background-color: red;">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
          <div class="col-md-6 bg-white rounded p-4">
            <form action="login.php" method="POST">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" required>
                <div id="emailHelp" class="form-text">Utilize apenas o email profissional do senai.</div>
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Senha</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="senha" required>
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Lembrar-me</label>
              </div>
              <button type="submit" class="btn btn-red w-100">Entrar Agora</button>
            </form>
          </div>
          <div class="col-md-6 rounded p-4 text-white text-center">
            <img src="./img/mobile-password-forgot.png" width="200px">
            <h1 class="tx-principal">Entrar na sua conta</h1>
            <span>Verifique equipamentos e ferramentas de forma única e inovadora do mercado.</span>
          </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
