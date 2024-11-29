<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "classes/SessionManager.php";
require_once "classes/admin.php";

SessionManager::validarAcesso(['admin']);

$admin = new Admin(SessionManager::getEmail());
$usuarios = $admin->gerenciarUsuarios();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    try {
        $conexao = (new DatabaseConnection())->getConexao();
        if ($_POST['acao'] === 'remover') {
            $query = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $conexao->prepare($query);
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
        } elseif ($_POST['acao'] === 'adicionar') {
            $query = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $conexao->prepare($query);
            $senhaHash = password_hash($_POST['senha'], PASSWORD_BCRYPT);
            $stmt->bind_param("ssss", $_POST['nome'], $_POST['email'], $senhaHash, $_POST['tipo']);
            $stmt->execute();
        }
        header("Location: gerenciar_usuarios.php");
        exit();
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .card-gerenciar-usuarios {
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
            <div class="card-gerenciar-usuarios">
                <div class="card">
                    <div class="card-header">Gerenciar Usuários</div>
                    <div class="card-body">
                        <h5 class="card-title">Lista de Usuários</h5>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Tipo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?= $usuario['id'] ?></td>
                                        <td><?= $usuario['nome'] ?></td>
                                        <td><?= $usuario['email'] ?></td>
                                        <td><?= ucfirst($usuario['tipo']) ?></td>
                                        <td>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                                <button class="btn btn-danger btn-sm" type="submit" name="acao" value="remover">Remover</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <h5 class="card-title mt-4">Adicionar Novo Usuário</h5>
                        <form method="POST">
                            <div class="form-group">
                                <label>Nome:</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Senha:</label>
                                <input type="password" name="senha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Tipo:</label>
                                <select name="tipo" class="form-control">
                                    <option value="cliente">Cliente</option>
                                    <option value="tecnico">Técnico</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button class="btn btn-primary" type="submit" name="acao" value="adicionar">Adicionar Usuário</button>
                        </form>
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
