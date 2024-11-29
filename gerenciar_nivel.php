<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "classes/SessionManager.php";
require_once "classes/admin.php";
require_once "classes/Chamado.php";

SessionManager::validarAcesso(['admin']);

$admin = new Admin(SessionManager::getEmail());

// Obtendo os chamados com seus níveis, excluindo os finalizados
$conexao = (new DatabaseConnection())->getConexao();
$query = "SELECT id, titulo, nivel FROM chamados WHERE status != 'finalizado'";
$result = $conexao->query($query);
$chamados = $result->fetch_all(MYSQLI_ASSOC);

// Processando alterações de nível
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'alterar') {
    try {
        $id_chamado = $_POST['id_chamado'];
        $novo_nivel = $_POST['novo_nivel'];

        $query = "UPDATE chamados SET nivel = ? WHERE id = ?";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("si", $novo_nivel, $id_chamado);
        $stmt->execute();

        header("Location: gerenciar_nivel.php");
        exit();
    } catch (Exception $e) {
        echo "Erro ao alterar nível: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gerenciar Níveis</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .card-gerenciar-nivel {
            padding: 30px 0 0 0;
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="home_admin.php">App Help Desk - Admin</a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="logoff.php">SAIR</a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <div class="row">
            <div class="card-gerenciar-nivel">
                <div class="card">
                    <div class="card-header">Gerenciar Níveis</div>
                    <div class="card-body">
                        <h5 class="card-title">Chamados</h5>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Nível Atual</th>
                                    <th>Alterar Nível</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($chamados as $chamado): ?>
                                    <tr>
                                        <td><?= $chamado['id'] ?></td>
                                        <td><?= $chamado['titulo'] ?></td>
                                        <td><?= $chamado['nivel'] ?></td>
                                        <td>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="id_chamado" value="<?= $chamado['id'] ?>">
                                                <select name="novo_nivel" class="form-control form-control-sm">
                                                    <option value="n1" <?= $chamado['nivel'] === 'n1' ? 'selected' : '' ?>>Nível 1 (n1)</option>
                                                    <option value="n2" <?= $chamado['nivel'] === 'n2' ? 'selected' : '' ?>>Nível 2 (n2)</option>
                                                    <option value="n3" <?= $chamado['nivel'] === 'n3' ? 'selected' : '' ?>>Nível 3 (n3)</option>
                                                </select>
                                                <button class="btn btn-primary btn-sm mt-2" type="submit" name="acao" value="alterar">Alterar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="row mt-4">
                            <div class="col-6">
                                <a class="btn btn-lg btn-warning btn-block" href="home_admin.php">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
