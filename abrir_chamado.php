<?php
require_once "classes/SessionManager.php";
require_once "classes/Usuario.php";
require_once "classes/Chamado.php";

SessionManager::validarAcesso(['cliente']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['titulo']) || empty($_POST['categoria']) || empty($_POST['descricao'])) {
            throw new Exception("Todos os campos são obrigatórios.");
        }

        $usuario = new Usuario(SessionManager::getEmail());
        $id_usuario = $usuario->getId();

        $chamado = new Chamado();
        $chamado->criarChamado($_POST['titulo'], $_POST['categoria'], $_POST['descricao'], $id_usuario);

        header("Location: consultar_chamado.php");
        exit();
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<html>
  <head>
    <meta charset="utf-8" />
    <title>App Help Desk</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
      .card-abrir-chamado {
        padding: 30px 0 0 0;
        width: 100%;
        margin: 0 auto;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-dark">
      <a class="navbar-brand" href="home.php">
        <img src="images/logo.png" width="30" height="30" alt="">
        App Help Desk
      </a>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="logoff.php">SAIR</a>
        </li>
      </ul>
    </nav>

    <div class="container">    
      <div class="row">
        <div class="card-abrir-chamado">
          <div class="card">
            <div class="card-header">Abertura de chamado</div>
            <div class="card-body">
              <form action="abrir_chamado.php" method="POST">
                <div class="form-group">
                  <label>Título</label>
                  <input name="titulo" type="text" class="form-control" placeholder="Título">
                </div>
                <div class="form-group">
                  <label>Categoria</label>
                  <select name="categoria" class="form-control">
                    <option>Criação Usuário</option>
                    <option>Impressora</option>
                    <option>Hardware</option>
                    <option>Software</option>
                    <option>Rede</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Descrição</label>
                  <textarea name="descricao" class="form-control" rows="3"></textarea>
                </div>
                <div class="row mt-5">
                  <div class="col-6">
                    <a class="btn btn-lg btn-warning btn-block" href="home.php">Voltar</a>
                  </div>
                  <div class="col-6">
                    <button class="btn btn-lg btn-info btn-block" type="submit">Abrir</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
