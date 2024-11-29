<?php
require_once "classes/SessionManager.php";
require_once "classes/Usuario.php";
require_once "classes/Chamado.php";

SessionManager::validarAcesso(['cliente', 'tecnico']);

try {
    $usuario = new Usuario(SessionManager::getEmail());
    $id_usuario = $usuario->getId();
    $tipo_usuario = $usuario->getTipo();

    if ($tipo_usuario === 'cliente') {
        $chamados = Chamado::getChamadosDoUsuario($id_usuario);
    } elseif ($tipo_usuario === 'tecnico') {
        $chamados = Chamado::getChamadosDoTecnico($id_usuario);
    } else {
        throw new Exception("Tipo de usuário inválido.");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
    exit();
}
?>

<html>
  <head>
    <meta charset="utf-8" />
    <title>App Help Desk</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
      .card-consultar-chamado {
        padding: 30px 0 0 0;
        width: 100%;
        margin: 0 auto;
      }
    </style>
  </head>

  <body>
    <nav class="navbar navbar-dark bg-dark">
      <a class="navbar-brand" href="home.php">App Help Desk</a>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="logoff.php">SAIR</a>
        </li>
      </ul>
    </nav>

    <div class="container">    
      <div class="row">
        <div class="card-consultar-chamado">
          <div class="card">
            <div class="card-header">
              Consulta de chamados
            </div>
            <div class="card-body">
              <?php
              if (count($chamados) > 0) {
                foreach ($chamados as $chamadoData) {
                  // Criar uma instância de Chamado para utilizar os métodos de instância
                  $chamado = new Chamado($chamadoData['id']);

                  echo '<div class="card mb-3 bg-light">';
                  echo '  <div class="card-body">';
                  echo '    <h5 class="card-title">' . $chamado->getTitulo() . '</h5>';
                  echo '    <h6 class="card-subtitle mb-2 text-muted">' . $chamado->getCategoria() . '</h6>';
                  echo '    <p class="card-text">' . $chamado->getDescricao() . '</p>';

                  $comentarios = $chamado->consultarComentarios();
                  if ($comentarios->num_rows > 0) {
                    echo '<h6>Comentários:</h6>';
                    while ($comentario = $comentarios->fetch_assoc()) {
                      echo '<p><strong>' . $comentario['data_comentario'] . '</strong>: ' . $comentario['comentario'] . '</p>';
                    }
                  } else {
                    echo '<p>Sem comentários.</p>';
                  }

                  echo '<form action="adicionar_comentario.php" method="POST">';
                  echo '  <input type="hidden" name="id_chamado" value="' . $chamado->getId() . '">';
                  echo '  <div class="form-group">';
                  echo '    <textarea name="comentario" class="form-control" rows="2" placeholder="Adicionar comentário..."></textarea>';
                  echo '  </div>';
                  echo '  <button type="submit" class="btn btn-sm btn-primary">Comentar</button>';
                  echo '</form>';

                  echo '  </div>';
                  echo '</div>';
                }
              } else {
                echo '<div class="alert alert-warning">Nenhum chamado encontrado.</div>';
              }
              ?>

              <div class="row mt-5">
                <div class="col-6">
                  <a class="btn btn-lg btn-warning btn-block" href="home.php">Voltar</a>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
