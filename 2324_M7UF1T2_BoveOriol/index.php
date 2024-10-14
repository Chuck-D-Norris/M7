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
    $mode_factorial = isset($_POST['mode_factorial']) ? $_POST['mode_factorial'] : 'iterative'; // Val per defecte
    $resultat = '';

    // Operacions numèriques
    if ($operador == 'factorial') {
        // Només permetre un input per al factorial
        if (!empty($num1) && empty($num2)) {
            // Decidir quin mètode de factorial usar segons la selecció de l'usuari
            if ($mode_factorial == 'iterative') {
                $resultat = factorial_iterative($num1);
            } else {
                $resultat = factorial_recursive($num1);
            }
        } else {
            $resultat = 'Error: Només es permet introduir un número per al factorial.';
        }
    } elseif (!empty($num1) && !empty($num2)) {
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

// Funció per calcular el factorial de manera iterativa
function factorial_iterative($n) {
    if (!is_numeric($n) || $n < 0 || $n != floor($n)) {
        return 'Error: El factorial no està definit per a nombres decimals o negatius.';
    } elseif ($n == 0) {
        return 1;
    } else {
        $result = 1;
        for ($i = 1; $i <= $n; $i++) {
            $result *= $i;
        }
        return $result;
    }
}

// Funció per calcular el factorial de manera recursiva
function factorial_recursive($n) {
    if (!is_numeric($n) || $n < 0 || $n != floor($n)) {
        return 'Error: El factorial no està definit per a nombres decimals o negatius.';
    } elseif ($n == 0) {
        return 1;
    } else {
        return $n * factorial_recursive($n - 1);
    }
}

if (isset($_POST['logout'])) {
    session_unset(); // Esborra totes les variables de sessió
    session_destroy(); // Destrueix la sessió
    echo "<h2>L'historial s'ha esborrat.</h2>";
}
?>


<!-- Formulari per operacions numèriques -->
<div class="Numerics">
    <form action="" method="post">
        <h3>Operacions numèriques</h3>
        <label for="num1"></label>
        <input type="number" id="num1" name="num1" step="any"><br><br>

        <label for="num2"></label>
        <input type="number" id="num2" name="num2" step="any"><br><br>

        <label for="operador">Operador:</label><br>
        <button type="submit" name="operador" value="+">+</button>
        <button type="submit" name="operador" value="-">-</button>
        <button type="submit" name="operador" value="*">*</button>
        <button type="submit" name="operador" value="/">/</button>
        <button type="submit" name="operador" value="factorial">Factorial</button><br><br>

        <!-- Opció per seleccionar iteratiu o recursiu -->
        <label for="mode_factorial">Selecciona el mode de càlcul del factorial:</label><br>
        <input type="radio" id="iterative" name="mode_factorial" value="iterative" checked>
        <label for="iterative">Iteratiu</label><br>
        <input type="radio" id="recursive" name="mode_factorial" value="recursive">
        <label for="recursive">Recursiu</label><br><br>
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
