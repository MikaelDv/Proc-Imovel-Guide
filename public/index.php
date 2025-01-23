<?php
require __DIR__ . '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dbhost = $_ENV['host'];
$dbuser = $_ENV['user'];
$dbpass = $_ENV['password'];
$dbname = $_ENV['db'];
$conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$cpf = $creci = $nome = $id = ''; 
$message = '';
$titleChange = 'Cadastro de Corretor';
$labelButton = 'Enviar';

session_start();

if (isset($_GET['editar'])) {
    $titleChange = 'Editar Cadastro';
    $labelButton = 'Salvar';
    $id = $_GET['editar'];
    $edit_sql = "SELECT id, cpf, creci, nome FROM corretores WHERE id = ?";
    $edit_stmt = $conn->prepare($edit_sql);
    $edit_stmt->bind_param("i", $id);
    $edit_stmt->execute();
    $result = $edit_stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $cpf = $row['cpf'];
        $creci = $row['creci'];
        $nome = $row['nome'];
    }
    $edit_stmt->close();
} else {
    $id = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $cpf = $_POST['cpf'];
    $creci = $_POST['creci'];
    $nome = $_POST['nome'];

    if (strlen($cpf) == 11 && strlen($creci) > 2 && strlen($nome) > 2) {
        if (!empty($id)) {
            $update_sql = "UPDATE corretores SET cpf = ?, creci = ?, nome = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssi", $cpf, $creci, $nome, $id);
            if ($update_stmt->execute()) {
                $_SESSION['message'] = "<p class='message' style='color: green;'>Dados atualizados com sucesso!</p>";
                header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
                exit();
            } else {
                $message = "<p style='color: red;'>Erro ao atualizar: " . $update_stmt->error . "</p>";
            }
            $update_stmt->close();
        } else {

            try {
                $insert_sql = "INSERT INTO corretores (cpf, creci, nome) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("sss", $cpf, $creci, $nome);
            
                if ($insert_stmt->execute()) {
                    $_SESSION['message'] = "<p class='message' style='color: green;'>Cadastro realizado com sucesso!</p>";
                }
            
                $insert_stmt->close();
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    if (str_contains($e->getMessage(), 'cpf')) {
                        $_SESSION['message'] = "<p class='message' style='color: red;'>Erro: O CPF já está cadastrado.</p>";
                    } elseif (str_contains($e->getMessage(), 'creci')) {
                        $_SESSION['message'] = "<p class='message' style='color: red;'>Erro: O CRECI já está cadastrado.</p>";
                    } else {
                        $_SESSION['message'] = "<p class='message' style='color: red;'>Erro: Registro já existente.</p>";
                    }
                } else {
                    $_SESSION['message'] = "<p class='message' style='color: red;'>Erro ao cadastrar: " . $e->getMessage() . "</p>";
                }
            }
        }
    } else {
        $_SESSION['message'] = "<p class='message' style='color: red;'>Todos os campos devem ser preenchidos.</p>";
    }
}

$result = $conn->query("SELECT id, cpf, creci, nome FROM corretores");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <title>Cadastro de Corretor</title>
    <script src="./js/filtro.js" defer></script>
</head>
<body>
    <main>
        <div class="wrapper">
            <h1><?php echo $titleChange; ?></h1>
            <form action="" method="POST" class="form">
                <input type="text" class="form-input input-menor" name="cpf" id="cpf" placeholder="Digite seu CPF" minlength="11" maxlength="11" value="<?php echo htmlspecialchars($cpf); ?>" required>
                <input type="text" class="form-input input-medio" name="creci" placeholder="Digite seu Creci" minlength="2" maxlength="20" value="<?php echo htmlspecialchars($creci); ?>" required>
                <input type="text" class="form-input" name="nome" placeholder="Digite seu Nome" minlength="2" maxlength="100" value="<?php echo htmlspecialchars($nome); ?>" required>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit"><?php echo $labelButton; ?></button>
            </form>

            <div class="wrapper-table">
                <?php
                    if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                ?>
                <table>
                    <caption class="table-title">Corretores Cadastrados</caption>
                    <thead>
                        <tr>
                            <th>ID </th>
                            <th>CPF</th>
                            <th>CRECI</th>
                            <th class="th-name">Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . $row['id'] . "</td>
                                            <td>" . $row['cpf'] . "</td>
                                            <td>" . $row['creci'] . "</td>
                                            <td>" . $row['nome'] . "</td>
                                            <td><a href='?editar=" . $row['id'] . "'>Editar</a> |
                                            <a href='?excluir=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</a>
                                            </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr>
                                        <td>Não há corretores cadastrados.</td>
                                    </tr>";
                            }

                            if (isset($_GET['excluir'])) {
                                $id = $_GET['excluir'];
                                $delete_sql = "DELETE FROM corretores WHERE id = ?";
                                $delete_stmt = $conn->prepare($delete_sql);
                                $delete_stmt->bind_param("i", $id);
                                if ($delete_stmt->execute()) {
                                    $_SESSION['message'] = "<p class='message' style='color: green;'>Corretor excluído com sucesso!</p>";
                                } else {
                                    $_SESSION['message'] = "<p class='message' style='color: red;'>Erro ao excluir corretor.</p>";
                                };
                                $delete_stmt->close();

                                header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
                                exit();
                            }
                            
                            $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
