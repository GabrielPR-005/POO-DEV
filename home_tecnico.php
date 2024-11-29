<?php
require_once "classes/SessionManager.php";
SessionManager::validarAcesso();
?>

<html>
  <head>
    <meta charset="utf-8" />
    <title>App Help Desk - Técnico</title>

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
      <a class="navbar-brand" href="home_tecnico.php">
        <img src="images/logo.png" width="30" height="30" alt="">
        App Help Desk - Técnico
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
              Menu Técnico
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-4 d-flex justify-content-center">
                  <a href="consultar_chamados_tecnico.php">
                    <img src="images/formulario_consultar_chamado.png" width="70" height="70">
                    <p>Chamados Atribuídos</p>
                  </a>
                </div>
                <div class="col-4 d-flex justify-content-center">
                  <a href="atribuir_chamado.php">
                    <img src="images/formulario_abrir_chamado.png" width="70" height="70">
                    <p>Atribuir Chamados</p>
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
