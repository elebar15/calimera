<?php
session_start();
require_once __DIR__ . '/db_connect.php'; 
$search = isset($_SESSION['search_term']) ? $_SESSION['search_term'] : ''; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="css/style_admin.css" />
</head>
<body>
    <br>
    <h2>Administration</h2>

    <form action="phrase_search.php" method="post" id="searchForm">
        <strong>Recherche de phrase existante dans la base de donnée</strong><br><br>
        <input type="text" name="search" value="<?= isset($_SESSION['search_term']) ? htmlspecialchars($_SESSION['search_term']) : '' ?>">
        <br><br>
        <input type="submit" value="Rechercher">
        <button type="button" id="clearSearchBtn">Effacer la recherche</button>
    </form>
    <br>
    <div id="searchResults">
    <?php
    if (isset($_SESSION['search_results'])) {
        $results = $_SESSION['search_results'];
        echo '<strong>Résultats</strong><br>';
        
        if (count($results) > 0) {
            foreach ($results as $phrase) {
                echo htmlspecialchars($phrase['phrase_text']) . " ";
                echo '<form action="phrase_delete.php" method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="' . $phrase['id'] . '">
                        <input type="submit" value="X" onclick="return confirm(\'Sûr ?\')">
                      </form>';

                if (isset($_GET['edit_id']) && $_GET['edit_id'] == $phrase['id']) {
                    echo '<br><br><form action="phrase_edit.php" method="POST" style="display:inline;">
                            <input type="hidden" name="edit_id" value="' . $phrase['id'] . '">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="text" name="edited_phrase"
                             value="' . htmlspecialchars($phrase['phrase_text']) . '" required><br><br>
                            <input type="submit" value="Mettre à jour">
                          </form>';
                } else {
                    echo '<form action="" method="GET" style="display:inline;">
                            <input type="hidden" name="edit_id" value="' . $phrase['id'] . '">
                            <input type="submit" value="E">
                          </form>';
                }
                echo "<br>";
            }
        } else {
            echo "Aucun résultat trouvé.";
        }
    }
    ?>
    </div>
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success']; ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <br>

    <?php
    $stmt = $pdo->query("SELECT id, theme_name FROM themes");
    $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <form action="phrase_add.php" method="post">
        <fieldset>
            <legend><strong>Ajout d'une Phrase</strong></legend> 
            <label>
                <span>Phrase</span>
                <input type="text" name="phrase_text" />
            </label>
            <br>
            <label>
                <span>Thèmes</span>
            </label><br>
                <?php foreach ($themes as $theme): ?>
                    <label class="themes">
                        <input type="checkbox" name="themes[]" value="<?= htmlspecialchars($theme['id']) ?>">
                        <?= htmlspecialchars($theme['theme_name']) ?>
                    </label><br>
                <?php endforeach; ?><br>
            <input type="submit" value="Ajouter" />
        </fieldset>
    </form>

    <br>
    <h2>Gestion des thèmes</h2>
    <h2>Gestion des utilisateurs</h2>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('clearSearchBtn').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('input[name="search"]').value = '';
            document.getElementById('searchResults').style.display = 'none';
            document.getElementById('searchForm').reset();

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'phrase_search.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    window.location.href = 'admin.php';  
                }
            };
            xhr.send('clear_search=true'); 
        });
    });
</script>

</body>
</html>
