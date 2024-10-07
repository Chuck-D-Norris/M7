<?php
session_start(); // Inicia la sessió per a l'historial d'operacions

// Comprova si el formulari ha estat enviat
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Variables per als inputs
    $num1 = isset($_POST['num1']) ? $_POST['num1'] : null;
    $num2 = isset($_POST['num2']) ? $_POST['num2'] : null;
    $string1 = isset($_POST['string1']) ? $_POST['string1'] : '';
    $string2 = isset($_POST['string2']) ? $_POST['string2'] : '';
    $operador = isset($_POST['operador']) ? $_POST['operador'] : '';
    $resultat = '';

    // Operacions numèriques
    if (!empty($num1) && !empty($num2)) {
        switch ($operador) {
            case '+':
                $resultat = $num1 + $num2;
                break;
            case '-':
                $resultat = $num1 - $num2;
                break;
            case '*':
                $resultat = $num1 * $num2;
                break;
            case '/':
                if ($num2 != 0) {
                    $resultat = $num1 / $num2;
                } else {
                    $resultat = 'Error: Divisió per zero!';
                }
                break;
            case 'factorial':
                $resultat = factorial($num1);
                break;
            default:
                $resultat = 'Error: Operador no vàlid!';
                break;
        }
    }

    // Operacions amb strings
    if (!empty($string1)) {
        switch ($operador) {
            case 'concat':
                $resultat = $string1 . $string2;
                break;
            case 'remove':
                $resultat = str_replace($string2, '', $string1);
                break;
            default:
                $resultat = 'Error: Operador no vàlid per a strings!';
                break;
        }
    }

    // Guarda l'operació a l'historial de la sessió
    if (!isset($_SESSION['historial'])) {
        $_SESSION['historial'] = [];
    }
    $_SESSION['historial'][] = "Operació: $operador, Resultat: $resultat";

    echo "<h2>Resultat: $resultat</h2>";
}

// Funció per calcular el factorial d'un nombre
function factorial($n) {
    if ($n < 0) {
        return 'Error: El factorial no està definit per nombres negatius.';
    } elseif ($n == 0) {
        return 1;
    } else {
        return $n * factorial($n - 1);
    }
}

if (isset($_POST['logout'])) {
    session_unset(); // Esborra totes les variables de sessió
    session_destroy(); // Destrueix la sessió
    echo "<h2>L'historial s'ha esborrat.</h2>";
}
?>


<link rel="stylesheet" href="style.css">

<!-- Formulari per operacions numèriques -->
 
<div class="Numerics">
    <form action="" method="post">
        <h3>Operacions numèriques</h3>
        <label for="num1"></label>
        <input type="number" id="num1" name="num1" step="any" required><br><br>
 
        <label for="num2"></label>
        <input type="number" id="num2" name="num2" step="any" required><br><br>

        <label for="operador">Operador:</label><br>
        <button type="submit" name="operador" value="+">+</button>
        <button type="submit" name="operador" value="-">-</button>
        <button type="submit" name="operador" value="*">*</button>
        <button type="submit" name="operador" value="/">/</button>
        <button type="submit" name="operador" value="factorial">Factorial</button><br><br>
    </form>
</div>

<!-- Formulari per operacions amb strings -->
<div class="String">
    <form action="" method="post">
        <h3>Operacions amb strings</h3>
        <label for="string1"></label>
        <input type="text" id="string1" name="string1" required><br><br>

        <label for="string2"></label>
        <input type="text" id="string2" name="string2" required><br><br>

        <label for="operador">Operador:</label><br>
        <button type="submit" name="operador" value="concat">Concatenar</button>
        <button type="submit" name="operador" value="remove">Eliminar Substring</button><br><br>
    </form>
</div>

<form action="" method="post">
    <input type="submit" name="logout" value="Borrar Historial">
</form>

<h3>Historial d'operacions:</h3>
<?php
if (isset($_SESSION['historial'])) {
    echo "<ul>";
    foreach ($_SESSION['historial'] as $operacio) {
        echo "<li>$operacio</li>";
    }
    echo "</ul>";
}
?>
