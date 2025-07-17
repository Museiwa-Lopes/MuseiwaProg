<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRM Vilankulo | MuseiwaProg</title>
    <link rel="icon" type="image/png" href="image/emblema_mocambique.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            min-height: 100vh;
            width: 100vw;
            background-image: url(/image/PRM.jpeg);
            background-repeat: round;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 20, 40, 0.45);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            color: #fff;
            background: #48ed;
            border-radius: 30px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            width: 850px;
            max-width: 98vw;
            min-height: 320px;
            padding: 40px 30px 30px 30px;
            margin: 30px 0;
            display: flex;
            flex-direction: row;
            align-items: stretch;
            border: 2px solid #fff;
            overflow: hidden;
        }

        .form-container {
            flex: 1 1 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
        }

        .form-container h1 {
            text-align: center;
            width: 100%;
            line-height: 1.5;
            font-size: 2.2vw;
            color: #fff;
            text-shadow: 0 2px 8px #0008;
        }

        .toggle-container {
            flex: 1 1 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(30, 48, 90, 0.10);
            border-radius: 0 30px 30px 0;
            box-shadow: 0px 2px 10px #0003;
        }

        .toggle-panel {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            text-align: center;
        }

        .toggle-panel h1 {
            margin-bottom: 18px;
            font-size: 2em;
            color: #fff;
            text-shadow: 0 2px 8px #0004;
        }

        .toggle-panel p {
            margin-bottom: 30px;
            font-size: 1.1em;
            color: #fff;
            text-shadow: 0 1px 4px #0005;
        }

        .button {
            border: 2px solid #fff;
            background-color: rgb(55, 142, 65);
            color: #fff;
            border-radius: 8px;
            letter-spacing: 0.5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 18px;
            padding: 12px 40px;
            outline: none;
            transition: background 0.4s, transform 0.2s;
            box-shadow: 0 2px 8px #0002;
        }

        .button:hover {
            background-color: rgba(11, 2, 53, 0.718);
            color: #fff;
            transform: scale(1.04);
        }

        @media screen and (max-width: 900px) {
            .container {
                flex-direction: column;
                width: 98vw;
                min-width: unset;
                max-width: 99vw;
                padding: 20px 2vw;
                border-radius: 18px;
            }

            .form-container,
            .toggle-container {
                width: 100%;
                border-radius: 0;
                box-shadow: none;
                padding: 0;
            }

            .form-container h1 {
                font-size: 4vw;
            }

            .toggle-panel h1 {
                font-size: 1.7em;
            }
        }

        @media screen and (max-width: 570px) {
            body {
                background-image: url(/image/PRMmobile.jpeg);
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                min-height: 100vh;
                height: auto;
                padding: 0;
            }

            .container {
                flex-direction: column;
                width: 99vw !important;
                min-width: unset !important;
                max-width: 99vw !important;
                padding: 10px 2vw !important;
                border-radius: 10px !important;
                margin: 10px auto !important;
            }

            .form-container h1 {
                font-size: 6vw;
            }

            .toggle-panel h1 {
                font-size: 1.2em;
            }

            .toggle-panel p {
                font-size: 1em;
            }

            .button {
                font-size: 16px !important;
                padding: 12px 20px !important;
            }
        }

        @media screen and (max-width: 400px) {
            .form-container h1 {
                font-size: 7vw;
            }

            .toggle-panel h1 {
                font-size: 1em;
            }

            .toggle-panel p {
                font-size: 0.95em;
            }
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="container" id="container">
        <div class="form-container">
            <form>
                <h1>Sistema Integrado de Gestão de Ocorrências<br>PRM Vilankulo</h1>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle-panel">
                <h1>Olá!</h1>
                <p>Para prosseguir com a denúncia clique no botão abaixo!</p>
                <a href="/cidadao/login.php">
                    <button type="button" class="button">Denunciar</button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>
