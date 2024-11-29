<?php
require_once "classes/SessionManager.php";
SessionManager::validarAcesso();
?>

<html>
  <head>
    <meta charset="utf-8" />
    <title>App Help Desk - Admin</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
      .card-home {
        padding: 30px 0 0 0;
        width: 100%;
        margin: 0 auto;
      }
    </style>
  </head>

  <body>
    <nav class="navbar navbar-dark bg-dark">
      <a class="navbar-brand" href="home_admin.php">
        <img src="images/logo.png" width="30" height="30" alt="">
        App Help Desk - Admin
      </a>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="logoff.php">SAIR</a>
        </li>
      </ul>
    </nav>

    <div class="container">    
      <div class="row">
        <div class="card-home">
          <div class="card">
            <div class="card-header">
              Menu Admin
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-3 d-flex justify-content-center">
                  <a href="gerenciar_usuarios.php">
                    <img src="images/gerenciamento-de-equipe.png" width="70" height="70">
                    <p>Gerenciar Usuários</p>
                  </a>
                </div>
                <div class="col-3 d-flex justify-content-center">
                  <a href="gerenciar_nivel.php">
                    <img src="images/nivel.png" width="70" height="70">
                    <p>Gerenciar Categorias</p>
                  </a>
                </div>
                <div class="col-3 d-flex justify-content-center">
                  <a href="gerar_relatorios.php">
                    <img src="images/relatorio.png" width="70" height="70">
                    <p>Gerar Relatórios</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
