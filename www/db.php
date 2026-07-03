<?php

function getDb(): PDO {
    static $db = null;
    if ($db !== null) return $db;

    $dir = __DIR__ . '/db';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $db = new PDO('sqlite:' . $dir . '/recepty.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $db->exec("CREATE TABLE IF NOT EXISTS recepty (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        nazev       TEXT    NOT NULL,
        kategorie   TEXT    NOT NULL,
        ingredience TEXT    NOT NULL,
        postup      TEXT    NOT NULL,
        cas         INTEGER NOT NULL
    )");


    if ($db->query("SELECT COUNT(*) FROM recepty")->fetchColumn() == 0) {
        $stmt = $db->prepare(
            "INSERT INTO recepty (nazev, kategorie, ingredience, postup, cas) VALUES (?,?,?,?,?)"
        );
        $stmt->execute(['Svíčková na smetaně', 'hlavní jídlo',
            "800 g hovězí svíčková\n2 mrkve\n1 celer\n200 ml smetany\nbobkový list, nové koření, pepř",
            "Zeleninu nakrájíme na kousky.\nMaso orestujeme z obou stran.\nPřidáme zeleninu, podlijeme vodou a dusíme 2 hodiny.\nOmáčku přecedíme a přidáme smetanu.",
            150]);
        $stmt->execute(['Bramborová polévka', 'polévka',
            "500 g brambor\n1 mrkev\n1 cibule\n1 l vývaru\npetržel, sůl, pepř",
            "Cibuli a mrkev osmažíme na oleji.\nPřidáme nakrájené brambory a zalijeme vývarem.\nVaříme 20 minut doměkka.\nDochutíme solí, posypeme petrželkou.",
            35]);
        $stmt->execute(['Tvarohové palačinky', 'dezert',
            "2 vejce\n250 g tvarohu\n4 lžíce mouky\nvanilkový cukr, sůl",
            "Vejce rozmícháme s tvarohem.\nPřidáme mouku, sůl a vanilkový cukr.\nTěsto necháme 10 minut odpočinout.\nSmažíme na oleji dozlatova z obou stran.",
            20]);
        $stmt->execute(['Česnečka', 'polévka',
            "8 stroužků česneku\n1 l vývaru\n4 plátky chleba\n100 g sýra\nkmín, sůl",
            "Česnek nasekáme a krátce osmažíme na oleji.\nZalijeme vývarem, přidáme kmín a sůl.\nVaříme 10 minut.\nNalijeme do talíře s opečeným chlebem, posypeme strouhaným sýrem.",
            15]);
    }

    return $db;
}
