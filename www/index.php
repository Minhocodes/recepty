<?php
require 'db.php';

$db  = getDb();
$kat = $_GET['kat'] ?? '';


if ($kat) {
    $stmt = $db->prepare("SELECT * FROM recepty WHERE kategorie = ? ORDER BY nazev");
    $stmt->execute([$kat]);
} else {
    $stmt = $db->query("SELECT * FROM recepty ORDER BY nazev");
}
$recepty = $stmt->fetchAll();

$filtry = [
    ''            => 'Vše',
    'polévka'     => 'Polévky',
    'hlavní jídlo'=> 'Hlavní jídla',
    'dezert'      => 'Dezerty',
];
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Recepty</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <a href="index.php">Recepty</a>
    <a href="pridat.php">+ Přidat recept</a>
</nav>

<main>
    <h1>Recepty</h1>

    
    <div class="filtry">
        <?php foreach ($filtry as $hodnota => $popisek): ?>
        <a href="?kat=<?= urlencode($hodnota) ?>"
           class="<?= $kat === $hodnota ? 'aktivni' : '' ?>">
            <?= $popisek ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Vyhledávání: JS filtruje existující DOM prvky bez dotazu na server -->
    <input id="hledej" type="search" placeholder="Vyhledat recept…">

    <ul id="seznam">
        <?php foreach ($recepty as $r): ?>
        <li>
            <a href="detail.php?id=<?= $r['id'] ?>">
                <?= htmlspecialchars($r['nazev']) ?>
            </a>
            <span class="meta">
                <?= htmlspecialchars($r['kategorie']) ?> · <?= $r['cas'] ?> min
            </span>
        </li>
        <?php endforeach; ?>
    </ul>

    <?php if (empty($recepty)): ?>
    <p class="prazdno">Žádné recepty v této kategorii.</p>
    <?php endif; ?>
</main>

<script src="script.js"></script>
</body>
</html>
