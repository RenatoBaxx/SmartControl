<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['user'])) {
    // Redireciona para a página de login se não estiver autenticado
    header("Location: login.php");
    exit();
}

// Inclua a conexão com o banco de dados
include 'db.php';

// Obtenha as informações do usuário
$email = $_SESSION['user'];
$stmt = $conn->prepare("SELECT nome, email, celular, serialtag FROM professor WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($nome, $email, $celular, $serialtag);
$stmt->fetch();
$stmt->close();

// Atualizar informações do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_email = $_POST['email'];
    $novo_celular = $_POST['celular'];

    $stmt = $conn->prepare("UPDATE professor SET email = ?, celular = ? WHERE email = ?");
    $stmt->bind_param("sss", $novo_email, $novo_celular, $email);
    $stmt->execute();
    $stmt->close();

    // Atualize a sessão com o novo email
    $_SESSION['user'] = $novo_email;

    // Atualize as variáveis com os novos valores
    $email = $novo_email;
    $celular = $novo_celular;

    // Mensagem de sucesso
    $mensagem = "Informações atualizadas com sucesso!";
}

$conn->close();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configurações - Gerenciamento de Recursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./style.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-principal">
      <div class="container-fluid">
        <a class="navbar-brand text-white tx-principal" href="#">Gerenciamento Recursos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link text-white" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="#">Link</a>
            </li>
          </ul>
          <a href="logout.php" class="mt-auto mb-auto text-white fs-4"><i class="fi fi-br-exit"></i></a>
        </div>
      </div>
    </nav>

    <div class="container mt-5">
      <h1 class="tx-principal text-center">Configurações</h1>
      <div class="infotop p-5 mt-5">
        <?php if (isset($mensagem)): ?>
          <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($mensagem) ?>
          </div>
        <?php endif; ?>
        <form method="POST" action="">
          <div class="mb-3">
            <label>Nome Completo</label>
            <input class="form-control" type="text" value="<?= htmlspecialchars($nome) ?>" disabled>
          </div>
          
          <div class="mb-3 mt-4">
            <label for="exampleFormControlInput1" class="form-label">Email</label>
            <input type="email" class="form-control" id="exampleFormControlInput1" name="email" value="<?= htmlspecialchars($email) ?>">
          </div>

          <div class="mb-3 mt-4">
            <label for="exampleFormControlInput2" class="form-label">Celular</label>
            <input type="tel" class="form-control" id="exampleFormControlInput2" name="celular" value="<?= htmlspecialchars($celular) ?>">
          </div>

          <div class="mb-3 mt-4">
            <label>Serial TAG</label>
            <input class="form-control" type="text" value="<?= htmlspecialchars($serialtag) ?>" disabled>
          </div>

          <button type="submit" class="btn btn-red">Salvar Mudanças</button>
        </form>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
