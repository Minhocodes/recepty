const pole   = document.getElementById('hledej');
const seznam = document.getElementById('seznam');


if (pole && seznam) {
    pole.addEventListener('input', () => {
        const dotaz = pole.value.toLowerCase();

        for (const polozka of seznam.children) {
            const nazev = polozka.querySelector('a').textContent.toLowerCase();
            
            polozka.style.display = nazev.includes(dotaz) ? '' : 'none';
        }
    });
}

// Živé vyhledávání – filtruje položky v seznamu bez načítání stránky
//
// Jak to funguje:
//   1. Uživatel píše do pole #hledej
//   2. Při každém stisku klávesy (událost 'input') projdeme všechny <li>
//   3. Každé <li> skryjeme nebo zobrazíme podle toho, zda název obsahuje hledaný text
