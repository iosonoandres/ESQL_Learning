<?php
session_start(); // Assicurati che questa sia la prima istruzione PHP nel file

$features = [
    ["Inserimento Test", "testDesign.php", true],
    ["Forum Test Vari", "testCommentiDesign.php", true],
    ["Inserimento Messaggio", "inserimentoMessaggioDesign.php", true],
    ["Inserimento Tabella", "inserimentoTabellaDesign.php", true],
    ["not-implemented", "inserimentoTabellaDesign.php", true],
    ["Svolgimento test", "visualizzaTestDisponibili.php", true],
    ["Creazione quesito", "creazioneQuesitoDesign.php",true],
    ["Inserimento quesito","da cambiare",false], 
    ["Visuale Statistiche", "statisticheDesign.php",true]
    // Altre funzionalità vanno qui...
];
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.korzh.com/metroui/v4/css/metro-all.min.css">
    <style>
        body {
            background-image: url('design/462749.jpg');
            background-size: cover;
            background-position: center;
        }

        body {
            height: 100%;
            /* Assicurati che body prende l'altezza intera della pagina */
            margin: 0;
            /* Rimuovi i margini di default */
            display: flex;
            justify-content: center;
            align-items: center;
            /* Centra il contenuto verticalmente */
        }

        .grid {
            flex-wrap: wrap;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: flex-start;
            /* Centra i pulsanti orizzontalmente e verticalmente */
            padding: 2rem;
            gap: 1rem;
            /* Aggiunge spazio tra le celle */
        }

        .cell {
            flex-basis: calc(25% - 1rem);
            /* Assegna un quarto della larghezza */
            
            margin: 0.5rem;
            /* Aggiunge lo spazio intorno ai pulsanti */
            flex-grow: 0;
            /* Impedisce ai pulsanti di espandersi */
            flex-shrink: 0;
            min-width: 250px;
            /* Imposta una larghezza minima per i pulsanti */
        }



        .more-info-box {
            margin-bottom: 1rem;

            min-height: 250px;
            /* Imposta un'altezza minima per i pulsanti */
            min-width: 200px;

            border-radius: 15px;
            /* Angoli arrotondati per il box */
            overflow: hidden;
            /* Nasconde il contenuto che fuoriesce */
            transition: transform 0.3s;
            /* Transizione fluida per effetti di hover */
            cursor: pointer;
            /* Cambia il cursore quando si passa sopra il box */
            position: relative;
            /* Posizione relativa per posizionamento assoluto degli elementi interni */
            color: #000000;
            /* Colore del testo, esempio nero */
        }

        .more-info-box:hover {
            transform: scale(1.05);
            /* Effetto zoom leggero al passaggio del mouse */
        }

        .more-info-box .content {
            padding: 50px;
            /* Spaziatura interna del contenuto */
        }

        .more-info-box .icon {
            font-size: 3rem;
            /* Dimensione dell'icona */
        }

        .more-info-box .more {
            position: absolute;
            /* Posizionamento assoluto per riempire il parent */
            bottom: 0;
            /* Posiziona in fondo al box */
            left: 0;
            /* Allinea a sinistra */
            width: 100%;
            /* Larghezza completa */
            background-color: #f0ffff;
            /* Colore di sfondo per l'area cliccabile */
            text-align: center;
            /* Centra il testo */
            padding: 25px 0;
            /* Spaziatura verticale */
            border-top: 1px solid #ddd;
            /* Linea di separazione */
            z-index: 10;
            /* Assicura che sia sopra gli altri elementi */
            text-decoration: none;
            /* Rimuovi la sottolineatura del link */
            color: inherit;
            /* Eredita il colore del testo dai genitori */
        }

        .more-info-box.not-implemented .more {
            background-color: #ccc;
            /* Sfondo per funzionalità non implementate */
            cursor: not-allowed;
            /* Cambia il cursore in non permesso */
        }

        .more-info-box.selected {
            border: 3px solid #90ee90;
            /* Bordo verde per indicare la selezione */
        }

        /* Aggiungi qui ulteriori stili personalizzati se necessario */
    </style>
</head>

<body>
    <div class="grid">
        <?php foreach ($features as $feature) : ?>
            <div class="cell">
                <div class="more-info-box <?= $feature[2] ? '' : 'not-implemented' ?>" style="background-color: <?= $feature[2] ? '#f0ffff' : '#dc3545' ?>">
                    <div class="content">
                        <h2 class="text-bold mb-0"><?= $feature[0] ?></h2>
                    </div>
                    <div class="icon">
                        <span class="mif-list-numbered"></span>
                    </div>
                    <?php if ($feature[2]) : ?>
                        <a href="<?= $feature[1] ?>" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    <?php else : ?>
                        <div class="more">DA IMPLEMENTARE</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        // JavaScript modificato
        document.querySelectorAll('.more-info-box').forEach(function(box) {
            box.addEventListener('click', function(e) {
                // Controlla se il click è avvenuto sull'ancora <a>
                if (e.target.tagName !== 'A') {
                    var href = this.querySelector('.more a')?.getAttribute('href');
                    if (href && !this.classList.contains('not-implemented')) {
                        window.location.href = href;
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.korzh.com/metroui/v4/js/metro.min.js"></script>
</body>

</html>