# TODO

Suite à un audit effectué en amont, voici les failles et les bugs qui ont été identifés comme prioritaire.

## FAILLES

- Des utilsateurs non admin ont des accès à l'interface de gestion des utilisateurs
- Les mots de passes ne sont pas chiffrée en base de données...
- Des injections de type XSS ont été détéctées sur certains formulaires
  Il faut rajouté des htmlspecialchars pour palier cette faille. Par exemple <p>Hello <?= htmlspecialchars($_GET['prenom']) ?></p>
- On nous a signalé des injections SQL lors de la création d'une nouvelles habitudes
  l'utilisation de la fonction htmlspecialchars peut être une bonne
  solution car celle-ci va retirer les apostrophes (quotes) de la chaîne traitée. Par exemple : <?php
  // BIEN !!!
  $sql = "SELECT * FROM user WHERE email = :email";
$stmt = $pdo->prepare($sql);
  $stmt->execute([
  'email' => $\_POST['email']
  ]);
  - exemple dans le champs "name" : foo', 'INJECTED-DESC', NOW()); --

## BUGS

- Une 404 est détéctée lors de l'accès à l'URL `/habit/toggle`
- Fatal error: Uncaught Error: Class "App\Controller\Api\HabitsController" lorsque l'on accède à l'URL `/api/habits`

**ATTENTION : certains bugs n'ont pas été listé**
