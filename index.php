<?php
require_once "classes/DatabaseConnection.php";
require_once "classes/Usuario.php";
require_once "classes/SessionManager.php";

SessionManager::iniciarSessao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $usuario = new Usuario($_POST['email']);

        if ($usuario->autenticar($_POST['senha'])) {
            SessionManager::setAutenticado($_POST['email']);
            header("Location: home.php");
        } else {
            echo "Usuário ou senha inválidos.";
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>


<html>
  <head>
    <meta charset="utf-8" />
    <title>App Help Desk</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
      .card-login {
        padding: 30px 0 0 0;
        width: 350px;
        margin: 0 auto;
      }
    </style>
  </head>

  <body>

    <nav class="navbar navbar-dark bg-dark">
      <a class="navbar-brand" href="home.php">
        <img src="logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        App Help Desk
      </a>
    </nav>

    <div class="container">    
      <div class="row">

        <div class="card-login">
          <div class="card">
            <div class="card-header">
              Login
            </div>
            <div class="card-body">
              <form action ="index.php" method="POST">
                <div class="form-group">
                  <input name="email" type="email" class="form-control" placeholder="E-mail">
                </div>
                <div class="form-group">
                  <input name = "senha" type="password" class="form-control" placeholder="Senha">
                </div>
                <button class="btn btn-lg btn-info btn-block" name="submit" type="submit">Entrar</button>
              </form>
            </div>
          </div>
        </div>
    </div>
  </body>
</html>