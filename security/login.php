<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "exemple_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            header("Location: comments.php");
            exit();
        } else {
            echo "Échec de la connexion.";
        }
    }
    $conn->close();
} catch (Exception $e) {
    
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <style>
        pre {
            background-color: #eee;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            overflow: auto;
            font-family: monospace;
        }

        .code-highlight {
            color: black;
        }
        h2{
            text-align: center; 
        }
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f9;
        }
        form {
            margin-left: auto;
            margin-right: auto;
            width:35% ;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center; 
        }
        input[type="text"], input[type="password"], input[type="submit"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #0056b3;
            color: white;
            border: none;
            cursor: pointer;
            
        }
        input[type="submit"]:hover {
            background-color: #004494;
        }
        .warning {
            color: #d8000c;
            background-color: #ffd2d2;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>Connexion</h2>
    <form method="post">
        Nom d'utilisateur: <input type="text" name="username" required><br><br>
        Mot de passe: <input type="password" name="password" required><br><br>
        <input type="submit" value="Se connecter">
    </form>
    <div class="warning">
    <strong>Attention :</strong> Cette page contient des vulnérabilités de sécurité délibérées à des fins éducatives.<br>
    <ul>
        <li>
            <strong>Injection SQL :</strong> Les entrées utilisateur sont directement intégrées dans la requête SQL suivante sans validation ni échappement, ce qui permet d'exécuter des requêtes SQL arbitraires.<br><br>
            <strong>Exemple :</strong><br> Si on ajoute <strong> 'or username = 1<2 -- </strong> dans le Nom d'utilisateur et alors on se connect sur le compte d'un utilisateur sans connaitre le mot de passe.
            <br><br>Requête vulnérable :
            <pre><code class="code-highlight">
                SELECT * FROM users WHERE username = <strong>'$username'</strong> AND password = '$password';

                SELECT * FROM users WHERE username = '<strong>'or username = 1<2 --</strong>' AND password = '$password'';
            </code></pre>
                Pour contrer cette faille, il faut utilisez des requêtes préparées avec des paramètres liés, ainsi que d'échapper les caractères spéciaux d'une chaîne comme ceci :
            <pre><code class="code-highlight">
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $password = mysqli_real_escape_string($conn, $_POST['password']);

                $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
                $stmt->bind_param('ss', $username, $password);
                $stmt->execute();
            </code></pre>
        </li>
        <li>
            <strong>Brute Force :</strong> Il n'y a pas de limite sur le nombre de tentatives de connexion, permettant des attaques par force brute.<br>
            <br>Pour contrer cette faille, on peut vous implémenter un délai d'attente après un certain nombre de tentatives échouées se qui peut decourager le brute force :
            <pre><code class="code-highlight">
                <strong>// Vérifie si le compteur de tentatives existe, sinon le créer </strong>
                if (!isset($_SESSION['login_attempts'])) {
                     $_SESSION['login_attempts'] = 0;
                }

                <strong>// Appliquer un délai si le nombre de tentatives est trop élevé</strong>
                if ($_SESSION['login_attempts'] > 3) {
                   $delay = (1 << $_SESSION['login_attempts']) - 1; <strong> // Le délai augmente en fonction du nombre de tentatives </strong>
                   sleep($delay); // Pause l'exécution du script
                }

                if ($result->num_rows > 0) {
                    $_SESSION['login_attempts'] = 0; <strong>// Réinitialisez le compteur de tentatives après une connexion réussie</strong>
                    header("Location: comments.php");
                    exit();
                } else {
                    $_SESSION['login_attempts']++; <strong>// Incrémentez le compteur après un échec de connexion</strong>
                    echo "Échec de la connexion.";
                }
            </code></pre>
        </li>
    </ul>
</div>
</body>
</html>