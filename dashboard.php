<?php

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

/*require_once "./libs/helpers.php";

$premiVinti = Helpers::ReadDashboardValue("PREMI_VINTI", $_SESSION['emailAccount']);
$invitiSospesi = Helpers::ReadDashboardValue("INVITI_SOSPESI", $_SESSION['emailAccount']);
$sondaggiCreati = Helpers::ReadDashboardValue("SONDAGGI_CREATI", $_SESSION['emailAccount']);
$premiCreati = Helpers::ReadDashboardValue("PREMI_CREATI", $_SESSION['emailAccount']);
$segnalazioniSospese = Helpers::ReadDashboardValue("SEGNALAZIONI_SOSPESE", $_SESSION['emailAccount']);
$segnalazioniGestite = Helpers::ReadDashboardValue("SEGNALAZIONI_GESTITE", $_SESSION['emailAccount']);
$sondaggiPreferiti = Helpers::ReadDashboardValue("SONDAGGI_PREFERITI", $_SESSION['emailAccount']);
$sondaggiCompleti = Helpers::ReadDashboardValue("SONDAGGI_COMPLETI", $_SESSION['emailAccount']);
$sondaggiIncompleti = Helpers::ReadDashboardValue("SONDAGGI_INCOMPLETI", $_SESSION['emailAccount']);

<?php require_once "./menu.php"; ?>

*/


$_SESSION['tipoAccount']= "UTENTE_STANDARD"

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.korzh.com/metroui/v4/css/metro-all.min.css">
    <style>
        body {
            background-image: url('/PROGETTOBASINOSTRO/design/milad-fakurian-UiiHVEyxtyA-unsplash.jpg');
            background-size: cover; /* Copre l'intera pagina */
            background-position: center; /* Centra l'immagine di sfondo */
        }
    </style>
</head>
<body>


    <div class="grid">
        <?php switch($_SESSION['tipoAccount']) {
        case "UTENTE_STANDARD":?>

            <div class="row pt-10">
                <div class="cell-2 offset-2 pt-30">
                    <div class="more-info-box bg-blue fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Sondaggi<br>da compilare</div>
                        </div>
                        <div class="icon">
                            <span class="mif-list-numbered"></span>
                        </div>
                        <a href="./selezione_sondaggio.php" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
                
                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-crimson fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Inviti Sospesi</div>
                        </div>
                        <div class="icon">
                            <span class="mif-envelop"></span>
                        </div>
                        <a href="./visualizza_inviti_sospesi.php" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
            
                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-amber fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Segnalazioni Sospese</div>
                        </div>
                        <div class="icon">
                            <span class="mif-event-busy"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
            </div>

            <div class="row pt-10">
                <div class="cell-2 offset-2 pt-30">
                    <div class="more-info-box bg-blue fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Sondaggi<br>compilati</div>
                        </div>
                        <div class="icon">
                            <span class="mif-list"></span>
                        </div>
                        <a href="./visualizza_sondaggi_risposte_personali.php" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
                
                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-green fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Premi Vinti</div>
                        </div>
                        <div class="icon">
                            <span class="mif-dollars"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
                
                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-amber fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Segnalazioni Gestite</div>
                        </div>
                        <div class="icon">
                            <span class="mif-event-available"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
            </div>
            
            <div class="row pt-10">
                <div class="cell-2 offset-2 pt-30">
                    <div class="more-info-box bg-blue fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Sondaggi<br>preferiti</div>
                        </div>
                        <div class="icon">
                            <span class="mif-star-full"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
            </div>
            <?php
            break;
        case "DOCENTE":?>
            <div class="row pt-10">
                <div class="cell-2 offset-2 pt-30">
                    <div class="more-info-box bg-blue fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Sondaggi<br>da compilare</div>
                        </div>
                        <div class="icon">
                            <span class="mif-list-numbered"></span>
                        </div>
                        <a href="./selezione_sondaggio.php" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>

                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-crimson fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Inviti Sospesi</div>
                        </div>
                        <div class="icon">
                            <span class="mif-envelop"></span>
                        </div>
                        <a href="./visualizza_inviti_sospesi.php" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>

                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-amber fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Segnalazioni Sospese</div>
                        </div>
                        <div class="icon">
                            <span class="mif-event-busy"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
            </div>

            <div class="row pt-10">
                <div class="cell-2 offset-2 pt-30">
                    <div class="more-info-box bg-blue fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Sondaggi<br>compilati</div>
                        </div>
                        <div class="icon">
                            <span class="mif-list"></span>
                        </div>
                        <a href="./visualizza_sondaggi_risposte_personali.php" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>

                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-green fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Premi Vinti</div>
                        </div>
                        <div class="icon">
                            <span class="mif-dollars"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>

                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-amber fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Segnalazioni Gestite</div>
                        </div>
                        <div class="icon">
                            <span class="mif-event-available"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
            </div>

            <div class="row pt-10">
                <div class="cell-2 offset-2 pt-30">
                    <div class="more-info-box bg-blue fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Sondaggi<br>creati</div>
                        </div>
                        <div class="icon">
                            <span class="mif-plus"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
                
                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-blue fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo "6666666666"; ?></h2>
                            <div>Sondaggi<br>preferiti</div>
                        </div>
                        <div class="icon">
                            <span class="mif-star-full"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
            </div>

            <?php
            break;
        
        case "AZIENDA":?>
            <div class="row pt-10">
                <div class="cell-2 offset-2 pt-30">
                    <div class="more-info-box bg-green fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo $premiCreati; ?></h2>
                            <div>Premi Creati</div>
                        </div>
                        <div class="icon">
                            <span class="mif-plus"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
                
                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-amber fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo $segnalazioniSospese; ?></h2>
                            <div>Segnalazioni Sospese</div>
                        </div>
                        <div class="icon">
                            <span class="mif-event-busy"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
                
                <div class="cell-2 offset-1 pt-30">
                    <div class="more-info-box bg-amber fg-white">
                        <div class="content">
                            <h2 class="text-bold mb-0"><?php echo $segnalazioniGestite; ?></h2>
                            <div>Segnalazioni Gestite</div>
                        </div>
                        <div class="icon">
                            <span class="mif-event-available"></span>
                        </div>
                        <a href="#" class="more"> Vedi <span class="mif-arrow-right"></span></a>
                    </div>
                </div>
                
            </div>
            <?php
            break;
        } ?>

    </div>

    <script src="https://cdn.korzh.com/metroui/v4/js/metro.min.js"></script>
</body>
</html>