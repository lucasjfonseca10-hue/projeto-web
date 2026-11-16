<?php

// Arquivos utilizados
$arquivo = "motos.json";
$arquivoManutencoes = "manutencoes.json";

// CARREGAR AS MOTOS

if (file_exists($arquivo)) {
    $motos = json_decode(file_get_contents($arquivo), true);

    if (!is_array($motos)) {
        $motos = [];
    }
} else {
    $motos = [];
}

// CARREGAR AS MANUTENÇÕES

if (file_exists($arquivoManutencoes)) {
    $manutencoes = json_decode(file_get_contents($arquivoManutencoes), true);

    if (!is_array($manutencoes)) {
        $manutencoes = [];
    }
} else {
    $manutencoes = [];
}

$totalGasto = 0;

foreach ($manutencoes as $manutencao) {
    $totalGasto += (float)$manutencao["valor"];
}

// EXCLUIR UMA MOTO

if (isset($_GET["excluir"])) {

    $indice = $_GET["excluir"];

    if (isset($motos[$indice])) {
        unset($motos[$indice]);

        $motos = array_values($motos);

        file_put_contents($arquivo, json_encode($motos, JSON_PRETTY_PRINT));
    }

    header("Location: index.php");
    exit;
}

// EXCLUIR UMA MANUTENÇÃO

if (isset($_GET["excluir_manutencao"])) {

    $indice = $_GET["excluir_manutencao"];

    if (isset($manutencoes[$indice])) {
        unset($manutencoes[$indice]);

        $manutencoes = array_values($manutencoes);

        file_put_contents(
            $arquivoManutencoes,
            json_encode($manutencoes, JSON_PRETTY_PRINT)
        );
    }

    header("Location: index.php");
    exit;
}

// IDENTIFICAR MOTO PARA EDIÇÃO

$indiceEditar = null;
$motoEditar = null;

if (isset($_GET["editar"])) {

    $indice = $_GET["editar"];

    if (isset($motos[$indice])) {
        $indiceEditar = $indice;
        $motoEditar = $motos[$indice];
    }
}

// IDENTIFICAR MANUTENÇÃO PARA EDIÇÃO

$indiceEditarManutencao = null;
$manutencaoEditar = null;

if (isset($_GET["editar_manutencao"])) {

    $indice = $_GET["editar_manutencao"];

    if (isset($manutencoes[$indice])) {
        $indiceEditarManutencao = $indice;
        $manutencaoEditar = $manutencoes[$indice];
    }
}

// CADASTRAR OU EDITAR MANUTENÇÃO

if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["acao"]) &&
    $_POST["acao"] == "manutencao"
) {

    $manutencao = [
        "tipo" => $_POST["tipo"],
        "data" => $_POST["data"],
        "km" => $_POST["km"],
        "valor" => $_POST["valor"],
        "observacoes" => $_POST["observacoes"]
    ];

    // Verificar se é uma edição
    if (isset($_POST["editar_indice_manutencao"])) {

        $indice = $_POST["editar_indice_manutencao"];

        if (isset($manutencoes[$indice])) {
            $manutencoes[$indice] = $manutencao;
        }

    } else {

        // Cadastrar nova manutenção
        $manutencoes[] = $manutencao;

    }

    file_put_contents(
        $arquivoManutencoes,
        json_encode($manutencoes, JSON_PRETTY_PRINT)
    );

    header("Location: index.php");
    exit;
}

// CADASTRAR OU EDITAR MOTO

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $moto = [
        "marca" => $_POST["marca"],
        "modelo" => $_POST["modelo"],
        "cor" => $_POST["cor"],
        "ano" => $_POST["ano"],
        "quilometragem" => $_POST["quilometragem"],
        "datacompra" => $_POST["datacompra"],
        "placa" => $_POST["placa"]
    ];

    // Verificar se é uma edição
    if (isset($_POST["editar_indice"])) {

        $indice = $_POST["editar_indice"];

        if (isset($motos[$indice])) {
            $motos[$indice] = $moto;
        }

    } else {

        // Cadastrar nova moto
        $motos[] = $moto;

    }

    file_put_contents(
        $arquivo,
        json_encode($motos, JSON_PRETTY_PRINT)
    );

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto web</title>
    <link rel="stylesheet" href="./stylle.css">
</head>
<body>

    <header>
        <div>
            <div class="logo-titulo">
            <img src="logomoto.png" alt="Logo MotoControl">
            <h1>MotoControl</h1>
            </div>
            <ul>
              <li><a href="#motos">Minhas Motos</a></li>
              <li><a href="#manutencoes">Manutenções</a></li>
              <li><a href="#relatorios">Relatórios</a></li>
            </ul>
        </div>
    </header>

    <main>
        <h2>Controle da sua moto</h2>
        <p>Acompanhe gastos, manutenções e informações da sua moto</p>
    </main>

    <section>
        <h2>Cadastro da moto</h2>
        <form id="formMoto" method="POST" action="index.php">

        <?php if ($indiceEditar !== null) { ?>
            <input type="hidden" name="editar_indice"
                value="<?php echo $indiceEditar; ?>">
        <?php } ?>
                          
            <label for="marca">Marca: </label>
            <input type="text" id="marca" name="marca">

            <br><br>

            <label for="modelo">Modelo: </label>
            <input type="text" id="modelo" name="modelo">

            <br><br>

            <label for="cor">Cor: </label>
            <input type="text" id="cor" name="cor">

            <br><br>

            <label for="ano">Ano: </label>
            <input type="number" id="ano" name="ano">

            <br><br>

            <label for="quilometragem">Quilometragem: </label>
            <input type="number" id="quilometragem" name="quilometragem">

            <br><br>

            <label for="datacompra">Data da Compra: </label>
            <input type="date" id="datacompra" name="datacompra">

            <br><br>

            <label for="placa">Placa: </label>
            <input type="text" id="placa" name="placa">
            
            <br><br>

            <button type="submit">
                <?php echo $indiceEditar !== null ? "Salvar alterações" : "Cadastrar"; ?>
            </button>

        </form>
    </section>

    <section id="motos">
        <h2>Minhas Motos</h2>

        <table>
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Cor</th>
                    <th>Ano</th>
                    <th>Quilometragem</th>
                    <th>Data da Compra</th>
                    <th>Placa</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody><!--as motos cadastradas aparecerão-->
                <?php foreach ($motos as $indice => $moto) {?>

        <tr>
            <td><?php echo $moto["marca"]; ?></td>
            <td><?php echo $moto["modelo"]; ?></td>
            <td><?php echo $moto["cor"]; ?></td>
            <td><?php echo $moto["ano"]; ?></td>
            <td><?php echo $moto["quilometragem"]; ?></td>
            <td><?php echo $moto["datacompra"]; ?></td>
            <td><?php echo $moto["placa"]; ?></td>
            <td>
                <a href="index.php?editar=<?php echo $indice; ?>">
                Editar
                </a>
                
                 <a href="index.php?excluir=<?php echo $indice; ?>"
                    onclick="return confirm('Deseja realmente excluir esta moto?')">
                     Excluir
                 </a>
            </td>
        </tr>

                <?php } ?>
            </tbody>
        </table>    

    </section>

    <section id="manutencoes">
     <h2>Histórico de Manutenções</h2>

        <table>
            <thead>
                <tr>
                  <th>Data</th>
                  <th>Manutenção</th>
                  <th>Quilometragem</th>
                  <th>Valor</th>
                  <th>Observações</th>
                  <th>Ações</th>
                </tr>
            </thead>

         <tbody><!--as manutenções cadastradas aparecerão aqui futuramente-->
            <?php foreach ($manutencoes as $indice=> $manutencao) { ?>

        <tr>
            <td><?php echo htmlspecialchars($manutencao["data"]); ?></td>

            <td><?php echo htmlspecialchars($manutencao["tipo"]); ?></td>

            <td><?php echo htmlspecialchars($manutencao["km"]); ?></td>

            <td>R$ <?php echo htmlspecialchars($manutencao["valor"]); ?></td>

            <td><?php echo htmlspecialchars($manutencao["observacoes"]); ?></td>

            <td>

                <a href="index.php?editar_manutencao=<?php echo $indice; ?>">
                    Editar
                </a>

                <a href="index.php?excluir_manutencao=<?php echo $indice; ?>"
                    onclick="return confirm('Deseja realmente excluir esta manutenção?')">
                    Excluir
                </a>
</td>
        </tr>

            <?php } ?>
         </tbody>

        </table>

    </section>

     <!-- TOTAL GASTO -->
    <section id="relatorios">
        <h2>Relatórios</h2>
        <p>Consulte as informações e os gastos com as manutenções da sua moto.</p>

     <h3>Total Gasto com Manutenções</h3>

     <p>Total gasto:
        <strong>
            R$ <?php echo number_format($totalGasto, 2,",", "."); ?>
        </strong>
     </p>

    </section>

    <section>
        <h2>Registrar Manutenção</h2>

       
<form id="formManutencao" method="POST" action="index.php">

    <input type="hidden" name="acao" value="manutencao">

    <?php if ($indiceEditarManutencao !== null) { ?>
        <input type="hidden" name="editar_indice_manutencao"
               value="<?php echo $indiceEditarManutencao; ?>">
    <?php } ?>

    <label for="tipo">Tipo de Manutenção</label>

    <select name="tipo" id="tipo">
        <option value="">Selecione</option>

        <option value="oleo" <?php if (($manutencaoEditar["tipo"] ?? "") == "oleo") echo "selected"; ?>>
            Troca de Óleo
        </option>

        <option value="pneus" <?php if (($manutencaoEditar["tipo"] ?? "") == "pneus") echo "selected"; ?>>
            Pneus
        </option>

        <option value="freios" <?php if (($manutencaoEditar["tipo"] ?? "") == "freios") echo "selected"; ?>>
            Freios
        </option>

        <option value="relação" <?php if (($manutencaoEditar["tipo"] ?? "") == "relação") echo "selected"; ?>>
            Relação
        </option>

        <option value="revisao" <?php if (($manutencaoEditar["tipo"] ?? "") == "revisao") echo "selected"; ?>>
            Revisão
        </option>

        <option value="outros" <?php if (($manutencaoEditar["tipo"] ?? "") == "outros") echo "selected"; ?>>
            Outros
        </option>
    </select>

    <br><br>

    <label for="data">Data da Manutenção</label>
    <input type="date" id="data" name="data"
           value="<?php echo htmlspecialchars($manutencaoEditar["data"] ?? ""); ?>">

    <br><br>

    <label for="km">Quilometragem</label>
    <input type="number" id="km" name="km"
           value="<?php echo htmlspecialchars($manutencaoEditar["km"] ?? ""); ?>">

    <br><br>

    <label for="valor">Valor Gasto</label>
    <input type="number" id="valor" name="valor" step="0.01"
           value="<?php echo htmlspecialchars($manutencaoEditar["valor"] ?? ""); ?>">

    <br><br>

    <label for="observacoes">Observações</label>

    <br>

    <textarea name="observacoes" id="observacoes" rows="5" cols="40"><?php echo htmlspecialchars($manutencaoEditar["observacoes"] ?? ""); ?></textarea>

    <br><br>

    <button type="submit">
        <?php echo $indiceEditarManutencao !== null ? "Salvar alterações" : "Registrar"; ?>
    </button>

</form>

    </section>
    <script src="./script.js"></script>
</body>
</html>
