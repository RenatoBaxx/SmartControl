<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['user'])) {
  // Redireciona para a página de login se não estiver autenticado
  header("Location: login.php");
  exit();
}

// Inclua a conexão com o banco de dados para obter os dados do usuário
include 'db.php';

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT apelido, admin FROM professor WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($nome, $isAdmin);
$stmt->fetch();
$stmt->close();
$conn->close();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciamento de Recursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <?php if ($isAdmin == 1): ?>
                  <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Admin
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Novo Professor</a></li>
            <li><a class="dropdown-item" href="#">Armarios</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Equipamentos</a></li>
          </ul>
        </li>
                <?php endif; ?>
            </ul>
            <a href="logout.php" class="mt-auto mb-auto text-white fs-4"><i class="fi fi-br-exit"></i></a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="infotop p-3 rounded">
        <div class="m-2" style="padding-left: 50px;">
            <h1>Bem Vindo,</h1>
            <span class="tx-principal" style=" font-size: 60px;"><?php echo htmlspecialchars($nome); ?>👋</span>
        </div>
    </div>

      <div class="container text-center" style="margin-top: 90px;">
        <div class="row">
          <div class="col">
            <a href="backup.html" class="btn-opcoes">Historico</a> 
          </div>
          <div class="col">
            <a href="tempo-real.html" class="btn-opcoes">Tempo Real</a> 
          </div>
          <div class="col">
            <a href="cadastro-aluno.html" class="btn-opcoes">Cadastro</a> 
          </div>
          <div class="col">
            <a href="config.php" class="btn-opcoes">Configurações</a> 
          </div>
        </div>
      </div>
      <span id="Status"></span>

      <div style="margin-top: 120px;">
        <table class="table table-striped table-hover" id="resourceTable">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Status</th>
              <th scope="col">Nome</th>
              <th scope="col">Ferramenta</th>
              <th scope="col">Armário</th>
              <th scope="col">Opções</th>
            </tr>
          </thead>
          <tbody>
            <!-- As linhas serão inseridas aqui pelo JavaScript -->
          </tbody>
        </table>
      </div>
    </div>

    <script>
      let idCounter = 1; // Contador para garantir IDs únicos para as linhas

      // Criação do WebSocket
      const ws = new WebSocket('ws://localhost:8080');
  
      // Evento de conexão do WebSocket
      ws.onopen = () => {
        console.log("Conectado ao WebSocket com sucesso!");
      };
  
      // Evento para manipular mensagens recebidas pelo WebSocket
      ws.onmessage = (event) => {
        const data = event.data.trim(); // Recebe os dados do servidor
        console.log("Mensagem recebida do servidor:", data); // Verifique se a mensagem está sendo recebida
  
        const texto = document.getElementById('Status');
        const tableBody = document.getElementById('resourceTable').querySelector('tbody');

        
        
        if (data === 'ButtonPressed') {
          // Adiciona um item fictício à tabela se o botão foi pressionado
          const row = document.createElement('tr');
          row.id = 'resourceRow' + idCounter; // Garante um ID único para cada linha
          row.innerHTML = `
            <th scope="row">${idCounter}</th>
            <td>Ativo</td>
            <td>Renato</td>
            <td>Paquimetro</td>
            <td>Armário 1</td>
            <td><button class="btn btn-danger btn-sm" onclick="removeRow(${idCounter})">Remover</button></td>
          `;
          tableBody.appendChild(row);
          console.log("Item adicionado à tabela.");
          idCounter++; // Incrementa o contador para o próximo ID único
        } else if (data === 'ButtonReleased') {
          // Se o botão foi liberado, removemos a linha mais recente
          const row = document.getElementById('resourceRow' + (idCounter - 1));
          if (row) {
            tableBody.removeChild(row);
            console.log("Item removido da tabela.");
          }
        }
      };
  
      // Função para remover a linha ao clicar no botão "Remover"
      function removeRow(id) {
        const row = document.getElementById('resourceRow' + id);
        if (row) {
          row.parentNode.removeChild(row);
          console.log(`Item com ID ${id} removido da tabela.`);
        }
      }
  
      // Evento de erro do WebSocket
      ws.onerror = (error) => {
        console.error("Erro no WebSocket:", error);
      };
  
      // Evento de fechamento do WebSocket
      ws.onclose = () => {
        console.log("Conexão WebSocket encerrada.");
      };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
