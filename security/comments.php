<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "exemple_db";
try {
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['comment'])) {
    $comment = $_POST['comment'];
    $stmt = $conn->prepare("INSERT INTO comments (comment) VALUES (?)");
    $stmt->bind_param("s", $comment);
    $stmt->execute();
}


$comments = $conn->query("SELECT comment FROM comments");

} catch (Exception $e) {
    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commentaires</title>
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #004494;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-top: 10px;
            background: #fff;
            padding: 10px;
            border-radius: 4px;
        }
        .warning {
            color: #d8000c;
            background-color: #ffd2d2;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Commentaires</h2>
    <div class="warning">
        <strong>Attention :</strong> Cette page contient une vulnérabilité XSS. Les commentaires sont affichés sans échappement du contenu HTML, permettant l'injection de scripts.<br><br>
        <strong>Exemple d'exploitation XSS :</strong> Un utilisateur pourrait saisir le commentaire suivant pour exécuter un script malveillant :
        <pre><code class="code-highlight">&lt;script type="text/javascript"&gt;alert('Attaque XSS');&lt;/script&gt;</code></pre>
        Si ce commentaire est affiché sans échappement, le JavaScript qu'il contient sera exécuté dans le navigateur de tout utilisateur lisant le commentaire.<br>

        <br>Pour corriger cette vulnérabilité, échappez le contenu HTML avec la fonction<strong>  <code>htmlspecialchars</code> </strong> lors de l'affichage :
        <pre><code class="code-highlight">
            <code>echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');</code>
        </code></pre>
        
    </div>
    <form method="post">
        Commentaire: <textarea name="comment" required></textarea><br>
        <input type="submit" value="Ajouter commentaire">
    </form>

    <?php
        if ($comments->num_rows > 0) {
            echo "<ul>";
            while ($row = $comments->fetch_assoc()) {
                echo "<li>" . $row['comment'] . "</li>";
            }
            echo "</ul>";
        }
?>


</body>
</html>
