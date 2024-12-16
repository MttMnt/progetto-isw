<html lang="it/IT">
    <head>
        <title>Laureandosi - Progetto ISW</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="style/style.css">
    </head>
    <body>
        <main>
            <h2>Gestione Prospetti di Laurea</h2>
            <form action="" method="post" name="laureandosi" id="laureandosi">
                <div class="left">
                    <div class="cdl">
                        <label for="cdl">Cdl:</label>
                        <select name="cdl" id="cdl">
                            <option>Seleziona un CdL</option>
                            <option>prova1</option>
                            <option>prova2</option>
                            <option>prova3</option>
                            <option>T. Ing. Informatica</option>
                        </select>
                    </div>
                    <div class="dataLaurea">
                        <label for="dataLaurea">Data Laurea:</label>
                        <input type="date" name="dataLaurea" id="dataLaurea"/>
                    </div>
                </div>
                <div class="center">
                    <div class="matricole">
                        <label for="matricole">Matricole:</label>
                        <textarea name="matricole" id="matricole"></textarea>
                    </div>
                </div>
                <div class="right">
                    <input type="submit" name="creaProspetti" id="creaProspetti" value="Crea Prospetti"/>
                    <a href="#">apri prospetti</a>
                    <input type="button" name="inviaProspetti" id="inviaProspetti" value="Invia Prospetti"/>
                    <span class="message" id="message">
                        <?php

                        require "utils/GeneraProspettoLaurea.php";

                        if (isset($_POST["creaProspetti"])) {
                            echo GeneraProspettoLaurea::GeneraProspettoLaurea($_POST);
                        }

                        ?>
                    </span>
                </div>
            </form>
        </main>
    </body>
</html>