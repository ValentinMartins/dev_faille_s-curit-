<?php
if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == 0) {
    $uploadedFile = $_FILES['uploaded_file'];

    $uploadDir = 'uploads/';
    $dest_path = $uploadDir . basename($uploadedFile['name']);

    if (move_uploaded_file($uploadedFile['tmp_name'], $dest_path)) {
        echo "Le fichier a été téléchargé avec succès.";
    } else {
        echo "Une erreur est survenue lors du téléchargement de votre fichier.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f9;
        }
         .warning {
            color: #d8000c;
            background-color: #ffd2d2;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }
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
        input[type="submit"] {
            background-color: #0056b3;
            color: white;
            border: none;
            cursor: pointer;
            
        }
    </style>
<head>
    <title>Téléchargement de fichier</title>
</head>
<body>
<h2>Télécharger un fichier</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="uploaded_file">Choisissez un fichier:</label>
    <input type="file" name="uploaded_file" id="uploaded_file">
    <input type="submit" value="Télécharger">
</form>

<div class="warning">
    <strong>Attention :</strong> Cette page contient une vulnérabilité liée à la sécurité des fichiers téléversés. 
    Elle ne limite pas les types de fichiers qui peuvent être téléchargés, permettant potentiellement l'exécution de fichiers malveillants sur le serveur.
    <br><br>
    <strong>Exemple d'exploitation :</strong> Un attaquant pourrait télécharger un script PHP malveillant, comme un fichier nommé  <strong>'evil.php' </strong> contenant le code suivant :
    <pre><code class="code-highlight">&lt;?php echo 'Ceci est un script malveillant!'; ?&gt;</code></pre>
    Après le téléchargement, l'attaquant pourrait accéder à ce script via l'URL  <strong>"http://localhost/security/uploads/evil.php" </strong>, ce qui entraînerait son exécution par le serveur.
    <br><br>
    <strong>Solution pour contrer cette vulnérabilité :</strong> Il est crucial de limiter les types de fichiers autorisés à être téléchargés et de s'assurer qu'ils ne sont pas exécutables.
    Ainsi que modifier le nom du fichier pour eviter a l'utilisateur de le retrouve.<br><br>
    Voici un exemple de code pour améliorer la sécurité :
    <pre><code class="code-highlight">
    if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == 0) {
        $uploadedFile = $_FILES['uploaded_file'];
        <strong>$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Extensions autorisées</strong>
        $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);

        <strong>// Vérifier si l'extension du fichier est parmi celles autorisées</strong>
        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            $uploadDir = 'uploads/';
            $newFileName = uniqid('file_') . '.' . $fileExtension;<strong> // Renommer le fichier</strong>
            $dest_path = $uploadDir . $newFileName;

            <strong>// Déplacer le fichier téléchargé dans le dossier uploads</strong>
            if (move_uploaded_file($uploadedFile['tmp_name'], $dest_path)) {
                echo "Le fichier a été téléchargé avec succès.";
            } else {
                echo "Erreur lors du téléchargement du fichier.";
            }
        } else {
            echo "Type de fichier non autorisé.";
        }
    }
    </code></pre>
</div>

</body>
</html>
