<?php
session_start();
require_once("validador_acesso.php");
include_once("config.php");

try {
    if (isset($_POST["submit"])) {
        $titulo = mysqli_real_escape_string($conexao, $_POST['titulo']);
        $categoria = mysqli_real_escape_string($conexao, $_POST['categoria']);
        $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);


        $email = $_SESSION["email"]; 
        $query_usuario = "SELECT id FROM usuarios WHERE email = '$email'";
        $result_usuario = mysqli_query($conexao, $query_usuario);


        if ($result_usuario && mysqli_num_rows($result_usuario) > 0) {
            $row_usuario = mysqli_fetch_assoc($result_usuario);
            $id_usuario = $row_usuario['id']; 

 
            $query = "INSERT INTO chamados (titulo, categoria, Descricao, id_usuario) VALUES ('$titulo', '$categoria', '$descricao', '$id_usuario')";

            if (mysqli_query($conexao, $query)) {
                header("location: consultar_chamado.php");
            } else {
                echo "Erro ao adicionar chamado: " . mysqli_error($conexao);
            }
        } else {
            echo "Usuário não encontrado.";
        }
    }
} catch (\Throwable $th) {
    echo "Ocorreu um erro: " . $th->getMessage();
}
?>

<html>
  <head>
    <meta charset="utf-8" />
    <title>App Help Desk</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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
        <img src="logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        App Help Desk
      </a>
      <ul class ="navbar-nav">
        <li class ="nav-item">
          <a class ="nav-link" href ="logoff.php">SAIR</a>
        </li>
      </ul>
    </nav>

    <div class="container">    
      <div class="row">

        <div class="card-abrir-chamado">
          <div class="card">
            <div class="card-header">
              Abertura de chamado
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col">
                  
                  <form action = "abrir_chamado.php" method="POST">
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
                        <a class="btn btn-lg btn-warning btn-block" href ="home.php">Voltar</a>
                      </div>

                      <div class="col-6">
                        <button class="btn btn-lg btn-info btn-block" name="submit" type="submit">Abrir</button>
                      </div>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </body>
</html>