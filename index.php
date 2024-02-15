<!DOCTYPE html>
<html lang="it" dir="ltr">
<head>
    <title>ESQL</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #222;
            color: #fff;
        }

        .navbar {
            background-color: #333;
        }

        #intro {
            height: 100vh;
            background-image: url('design/pexels-olia-danilevich-5088017.jpg');
            background-size: cover;
            background-position: center;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .display-3 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hr-light {
            border-top: 2px solid #fff;
            width: 50px;
            margin: 20px auto;
        }

        .view {
            animation: fadeIn 2s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div id="intro" class="view hm-black-strong">
        <div class="overlay"></div>
        <div class="container-fluid full-bg-img d-flex align-items-center justify-content-center">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 text-center">
                    <h2 class="display-3 font-bold text-center mb-2">Benvenuto su ESQL ULTIMATE</h2>
                    <hr class="hr-light">
                    <h4 class="text-center">Benvenuti nel futuro dell'ELEARNING!</h4>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
