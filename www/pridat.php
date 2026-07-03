<?php
require 'db.php';

$chyby   = [];
$hodnoty = ['nazev' => '', 'kategorie' => '', 'ingredience' => '', 'postup' => '', 'cas' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $hodnoty = [
        'nazev'       => trim($_POST['nazev']       ?? ''),
        'kategorie'   =>      $_POST['kategorie']   ?? '',
        'ingredience' => trim($_POST['ingredience'] ?? ''),
        'postup'      => trim($_POST['postup']      ?? ''),
        'cas'         => (int)($_POST['cas']        ?? 0),
    ];

    
    if (!$hodnoty['nazev'])       $chyby[] = 'Zadejte název receptu.';
    if (!$hodnoty['kategorie'])   $chyby[] = 'Vyberte kategorii.';
    if (!$hodnoty['ingredience']) $chyby[] = 'Zadejte ingredience.';
    if (!$hodnoty['postup'])      $chyby[] = 'Zadejte postup přípravy.';
    if ($hodnoty['cas'] <= 0)     $chyby[] = 'Zadejte čas přípravy (v minutách).';

    if (!$chyby) {
        $db   = getDb();
        $stmt = $db->prepare(
            "INSERT INTO recepty (nazev, kategorie, ingredience, postup, cas) VALUES (?,?,?,?,?)"
        );
        $stmt->execute(array_values($hodnoty));
        
        header('Location: detail.php?id=' . $db->lastInsertId());
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat recept</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <a href="index.php">Recepty</a>
    <a href="pridat.php">+ Přidat recept</a>
</nav>

<main>
    <h1>Přidat recept</h1>

    <?php if ($chyby): ?>
    <ul class="chyby">
        <?php foreach ($chyby as $ch): ?>
        <li><?= htmlspecialchars($ch) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <form method="post">
        <label>Název receptu
            <input name="nazev" type="text"
                   value="<?= htmlspecialchars($hodnoty['nazev']) ?>"
                   required autofocus>
        </label>

        <label>Kategorie
            <select name="kategorie" required>
                <option value="">— vyberte —</option>
                <?php foreach (['polévka', 'hlavní jídlo', 'dezert'] as $k): ?>
                <option value="<?= $k ?>"
                        <?= $hodnoty['kategorie'] === $k ? 'selected' : '' ?>>
                    <?= ucfirst($k) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Čas přípravy (minuty)
            <input name="cas" type="number" min="1"
                   value="<?= htmlspecialchars((string)$hodnoty['cas'] ?: '') ?>"
                   required>
        </label>

        <label>Ingredience <small>(každá na nový řádek)</small>
            <textarea name="ingredience" rows="6"
                      required><?= htmlspecialchars($hodnoty['ingredience']) ?></textarea>
        </label>

        <label>Postup přípravy
            <textarea name="postup" rows="8"
                      required><?= htmlspecialchars($hodnoty['postup']) ?></textarea>
        </label>

        <button type="submit">Uložit recept</button>
    </form>
</main>
</body>
</html>
