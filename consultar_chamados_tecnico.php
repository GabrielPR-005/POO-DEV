<?php

require_once "classes/SessionManager.php";
require_once "classes/Usuario.php";
require_once "classes/Chamado.php";

SessionManager::validarAcesso(['tecnico']); 

try {
    $usuario = new Usuario(SessionManager::getEmail());
    $tecnicoId = $usuario->getId();

    $chamadosData = Chamado::getChamadosDoTecnico($tecnicoId);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
    exit();
}
?>

<html>
  <head>
    <meta charset="utf-8" />
    <title>Chamados Atribuídos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
      .card-chamado {
        margin-bottom: 20px;
      }
    </style>
  </head>

  <body>
    <nav class="navbar navbar-dark bg-dark">
      <a class="navbar-brand" href="home_tecnico.php">App Help Desk - Técnico</a>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="logoff.php">SAIR</a>
        </li>
      </ul>
    </nav>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class="mt-4">Chamados Atribuídos</h1>

          <?php if (count($chamadosData) > 0): ?>
            <?php foreach ($chamadosData as $chamadoInfo): 
                $chamado = new Chamado($chamadoInfo['id']);
            ?>
              <div class="card card-chamado">
                <div class="card-body">
                  <h5 class="card-title"><?php echo $chamado->getTitulo(); ?></h5>
                  <h6 class="card-subtitle mb-2 text-muted"><?php echo $chamado->getCategoria(); ?></h6>
                  <p class="card-text"><?php echo $chamado->getDescricao(); ?></p>

                  <h6>Comentários:</h6>
                  <?php
                  $comentarios = $chamado->consultarComentarios();
                  if ($comentarios->num_rows > 0):
                      while ($comentario = $comentarios->fetch_assoc()):
                  ?>
                    <p><strong><?php echo $comentario['data_comentario']; ?></strong>: <?php echo $comentario['comentario']; ?></p>
                  <?php
                      endwhile;
                  else:
                  ?>
                    <p>Sem comentários.</p>
                  <?php endif; ?>

                  <form action="adicionar_comentario.php" method="POST" class="mt-3">
                    <input type="hidden" name="id_chamado" value="<?php echo $chamado->getId(); ?>">
                    <div class="form-group">
                      <textarea name="comentario" class="form-control" rows="2" placeholder="Adicionar comentário..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Comentar</button>
                  </form>

                  <form action="finalizar_chamado.php" method="POST" class="mt-2">
                    <input type="hidden" name="id_chamado" value="<?php echo $chamado->getId(); ?>">
                    <button type="submit" class="btn btn-success btn-sm">Marcar como Finalizado</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="alert alert-warning">Nenhum chamado atribuído encontrado.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </body>
</html>
