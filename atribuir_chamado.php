<?php
require_once "classes/SessionManager.php";
require_once "classes/Usuario.php";
require_once "classes/Chamado.php";

SessionManager::validarAcesso(['tecnico']);

try {
    $usuario = new Usuario(SessionManager::getEmail());
    $tecnicoId = $usuario->getId();

    $chamadosNaoAtribuidos = Chamado::getChamadosNaoAtribuidos();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $chamadoId = $_POST['id_chamado'];

        $chamado = new Chamado($chamadoId);
        $chamado->atribuirChamado($tecnicoId);

        header("Location: atribuir_chamado.php");
        exit();
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Atribuir Chamado</title>
    <!-- Inclua o Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
      .card-atribuir-chamado {
        padding: 30px 0 0 0;
        width: 100%;
        margin: 0 auto;
      }
    </style>
  </head>
  <body>
    <!-- Barra de navegação -->
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
        <div class="card-atribuir-chamado">
          <div class="card">
            <div class="card-header">
              Atribuir Chamado
            </div>
            <div class="card-body">
              <?php if (!empty($chamadosNaoAtribuidos)): ?>
                <form action="atribuir_chamado.php" method="POST">
                  <div class="form-group">
                    <label for="id_chamado">Selecione o Chamado</label>
                    <select name="id_chamado" class="form-control">
                      <?php foreach ($chamadosNaoAtribuidos as $chamado): ?>
                        <option value="<?php echo $chamado['id']; ?>">
                          <?php echo $chamado['titulo']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Atribuir Chamado</button>
                </form>
              <?php else: ?>
                <p class="alert alert-warning">Não há chamados disponíveis para atribuir.</p>
              <?php endif; ?>

              <!-- Botão Voltar -->
              <div class="row mt-5">
                <div class="col-6">
                  <a class="btn btn-lg btn-warning btn-block" href="home_tecnico.php">Voltar</a>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Inclua o Bootstrap JS (opcional) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  </body>
</html>
