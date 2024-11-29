<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "classes/SessionManager.php";
require_once "classes/admin.php";
require_once "classes/DatabaseConnection.php";

SessionManager::validarAcesso(['admin']);

$admin = new Admin(SessionManager::getEmail());

// Consulta detalhada para relatórios
$conexao = (new DatabaseConnection())->getConexao();
$query = "
    SELECT 
        chamados.id,
        chamados.titulo,
        chamados.categoria,
        chamados.status,
        chamados.nivel,
        chamados.data_criacao,
        chamados.data_finalizacao,
        usuarios.nome AS nome_usuario,
        tecnicos.nome AS nome_tecnico
    FROM chamados
    LEFT JOIN usuarios ON chamados.id_usuario = usuarios.id
    LEFT JOIN usuarios AS tecnicos ON chamados.tecnico_id = tecnicos.id
";
$result = $conexao->query($query);
$chamados = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatórios de Chamados</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .card-relatorios {
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
            <div class="card-relatorios">
                <div class="card">
                    <div class="card-header">Relatórios de Chamados</div>
                    <div class="card-body">
                        <h5 class="card-title">Lista Detalhada de Chamados</h5>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Categoria</th>
                                    <th>Status</th>
                                    <th>Nível</th>
                                    <th>Usuário</th>
                                    <th>Técnico</th>
                                    <th>Data Criação</th>
                                    <th>Data Finalização</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($chamados as $chamado): ?>
                                    <tr>
                                        <td><?= $chamado['id'] ?></td>
                                        <td><?= $chamado['titulo'] ?></td>
                                        <td><?= $chamado['categoria'] ?></td>
                                        <td><?= ucfirst($chamado['status']) ?></td>
                                        <td><?= ucfirst($chamado['nivel']) ?></td>
                                        <td><?= $chamado['nome_usuario'] ?? 'Não especificado' ?></td>
                                        <td><?= $chamado['nome_tecnico'] ?? 'Não atribuído' ?></td>
                                        <td><?= $chamado['data_criacao'] ?? 'Não disponível' ?></td>
                                        <td><?= $chamado['data_finalizacao'] ?? 'Não finalizado' ?></td>
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
