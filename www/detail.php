<?php
require 'db.php';


$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

$db = getDb();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smazat'])) {
    $db->prepare("DELETE FROM recepty WHERE id = ?")->execute([$id]);
    header('Location: index.php');
    exit;
}


$stmt = $db->prepare("SELECT * FROM recepty WHERE id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch();

if (!$r) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($r['nazev']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <a href="index.php">Recepty</a>
    <a href="pridat.php">+ Přidat recept</a>
</nav>

<main>
    <a href="index.php" class="zpet">← Zpět na seznam</a>

    <h1><?= htmlspecialchars($r['nazev']) ?></h1>
    <p class="meta"><?= htmlspecialchars($r['kategorie']) ?> · <?= $r['cas'] ?> min přípravy</p>

    <h2>Ingredience</h2>
    <pre><?= htmlspecialchars($r['ingredience']) ?></pre>

    <h2>Postup</h2>
    <pre><?= htmlspecialchars($r['postup']) ?></pre>

    <form method="post" onsubmit="return confirm('Opravdu smazat recept?')">
        <button type="submit" name="smazat" class="btn-smazat">Smazat recept</button>
    </form>
</main>
</body>
</html>
