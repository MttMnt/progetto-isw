<?php

require('lib/fpdf184/fpdf.php');

Class GeneraProspettoLaurea{
    public static function GeneraProspettoLaurea($param): string
    {
        // Viene ricavato il corso di laurea selezionato
        $corso = (string) $param['cdl'];

        // Viene ottenuto il nome della cartella del corso, evitando caratteri che possono creare problemi
        $percorso = str_replace(" ", "_", $corso);
        $percorso = str_replace(".", "", $percorso);

        // Viene creato l'array contenente le matricole inserite (Possono essere separate da whitespaces o virgole)
        $matricole = preg_split("/[\s,]+/", $param['matricole']);

        // Viene svuotata la cartella del corso, come richiesto dal GDPR
        $path = "prospetti/$percorso";
        $files = glob($path."/*");

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Viene generato il report per la commissione
        $reportCommissione = new FPDF();

        // Pagina 1

        $reportCommissione->AddPage();
        $reportCommissione->SetFont('Helvetica', '', 10);
        $reportCommissione->Cell(0, 7, $corso, 0, 1, 'C');

        $txt = "LAUREANDOSI, Progetto ISW - Progettazione: m.montalto5@studenti.unipi.it";
        $reportCommissione->Cell(0, 7, $txt, 0, 1, 'C');

        $reportCommissione->Cell(0, 7, "LISTA LAUREANDI", 0, 1, 'C');

        // Viene determinata la dimensione delle celle
        $reportCommissione->setMargins(10, 10);
        $cellWidth = ($reportCommissione->GetPageWidth() - 20) / 4;

        // Viene generata la tabella contenente "Cognome/Nome/CdL/VotoLaurea" di ogni laureando la cui matricola è presente in $matriocole
        $reportCommissione->Cell($cellWidth, 5, "COGNOME", 1, 0, "C");
        $reportCommissione->Cell($cellWidth, 5, "NOME", 1, 0, "C");
        $reportCommissione->Cell($cellWidth, 5, "CDL", 1, 0, "C");
        $reportCommissione->Cell($cellWidth, 5, "VOTO LAUREA", 1, 1, "C");

        // todo: SOLUZIONE PROVVISORIA da qua in poi, implementa GestioneCarrieraStudente
        foreach ($matricole as $matricola) {
            // Vengono ottenuti i dati
            $nome = "tbd";
            $cognome = "tbd";
            $cdl = "tbd";
            $votoLaurea = "tbd";

            $reportCommissione->Cell($cellWidth, 5, $cognome, 1, 0, "C");
            $reportCommissione->Cell($cellWidth, 5, $nome, 1, 0, "C");
            $reportCommissione->Cell($cellWidth, 5, $cdl, 1, 0, "C");
            $reportCommissione->Cell($cellWidth, 5, "$votoLaurea/110", 1, 1, "C");
        }

        // Vengono generati i singoli report degli studenti
        foreach ($matricole as $matricola) {

            // Vengono ottenuti i dati
            # Ho già la matricola
            $nome = "tbd";
            $cognome = "tbd";
            $email = "tbd";
            $data = $param["dataLaurea"];
            $esami = ["esame1", "esame2"];

            $reportCommissione->AddPage();
            $reportCommissione->SetFont('Helvetica', '', 10);

            $reportCommissione->Cell(0, 7, $corso, 0, 1, 'C');

            $txt = "CARRIERA E SIMULAZIONE DEL VOTO DI LAUREA";
            $reportCommissione->Cell(0, 7, $txt, 0, 1, 'C');

            // Viene stampata la tabella di informazioni dello studente

            $old_x = $reportCommissione->GetX();
            $old_y = $reportCommissione->GetY();
            $reportCommissione->MultiCell($cellWidth, 5, "Matricola:\nNome:\nCognome:\nEmail:\nData:", "TBL", 'L');
            $reportCommissione->SetXY($old_x + $cellWidth, $old_y);
            $reportCommissione->MultiCell(0, 5, "$matricola\n$nome\n$cognome\n$email\n$data", "TBR", 'L');
            $reportCommissione->Ln(3);

            // Viene stampata la tabella degli esami

            $cellWidth = ($reportCommissione->GetPageWidth() - 20) / 17 * ($corso == "T. Ing. Informatica" ? 13 : 14);

            $reportCommissione->Cell($cellWidth, 6, "ESAME", 1, 0, "C");

            $cellWidth = ($reportCommissione->GetPageWidth() - 20) / 17;

            $reportCommissione->Cell($cellWidth, 6, "CFU", 1, 0, "C");
            $reportCommissione->Cell($cellWidth, 6, "VOT", 1, 0, "C");
            if ($corso == "T. Ing. Informatica") {
                $reportCommissione->Cell($cellWidth, 6, "MED", 1, 0, "C");
                $reportCommissione->Cell($cellWidth, 6, "INF", 1, 1, "C");
            } else {
                $reportCommissione->Cell($cellWidth, 6, "MED", 1, 1, "C");
            }

            // todo: Ciclo gli esami dati e li stampo

            foreach ($esami as $esame) {
                $cellWidth = ($reportCommissione->GetPageWidth() - 20) / 17 * ($corso == "T. Ing. Informatica" ? 13 : 14);

                $reportCommissione->Cell($cellWidth, 5, $esame, 1, 0, "L");

                $cellWidth = ($reportCommissione->GetPageWidth() - 20) / 17;

                $reportCommissione->Cell($cellWidth, 5, " ", 1, 0, "C");
                $reportCommissione->Cell($cellWidth, 5, " ", 1, 0, "C");
                if ($corso == "T. Ing. Informatica") {
                    $reportCommissione->Cell($cellWidth, 5, " ", 1, 0, "C");
                    $reportCommissione->Cell($cellWidth, 5, " ", 1, 1, "C");
                } else {
                    $reportCommissione->Cell($cellWidth, 5, " ", 1, 1, "C");
                }
            }

            $reportCommissione->Ln(3);

            // Viene stampata la tabella con le informazioni su voti e crediti

            $cellWidth = ($reportCommissione->GetPageWidth() - 20) / 2.5;

            $old_x = $reportCommissione->GetX();
            $old_y = $reportCommissione->GetY();
            $txt = ($corso == "T. Ing. Informatica") ? "Media Pesata (M):\nCrediti che fanno media (CFU):\nCrediti curriculari conseguiti:\nVoto di Tesi (T):\nFormula calcolo voto di laurea:\nMedia pesata esami INF:" : "Media Pesata (M):\nCrediti che fanno media (CFU):\nCrediti curriculari conseguiti:\nFormula calcolo voto di laurea:";
            $reportCommissione->MultiCell($cellWidth, 6, $txt, "TBL", 'L');
            $reportCommissione->SetXY($old_x + $cellWidth, $old_y);
            $txt = ($corso == "T. Ing. Informatica") ? "tbd\ntbd\ntbd\ntbd\ntbd\ntbd" : "tbd\ntbd\ntbd\ntbd";
            $reportCommissione->MultiCell(0, 6, $txt, "TBR", 'L');

            $reportCommissione->Ln(3);

            // Viene stampata la tabella di Simulazione del voto di laurea

            // todo: Vengono ottenuti i possibili voti
            $votiCommissione = ["tbd", "tbd"];

            $reportCommissione->Cell(0, 7, "SIMULAZIONE DI VOTO DI LAUREA", 1, 1, 'C');

            $cellWidth = ($reportCommissione->GetPageWidth() - 20) / 2;

            $reportCommissione->Cell($cellWidth, 7, "VOTO COMMISSIONE (C)", 1, 0, "C");
            $reportCommissione->Cell($cellWidth, 7, "VOTO LAUREA", "TBR", 1, "C");

            foreach ($votiCommissione as $votoCommissione) {
                $votoLaurea = "tbd"; // todo: Calcola con la formula
                $reportCommissione->Cell($cellWidth, 7, $votoCommissione, 1, 0, "C");
                $reportCommissione->Cell($cellWidth, 7, $votoLaurea, "TBR", 1, "C");
            }

            $reportCommissione->Ln(5);

            // Viene stampata l'ultima linea

            $reportCommissione->SetFontSize(10);
            $txt = "VOTO DI LAUREA FINALE: scegli voto commissione, prendi il corrispondente voto di laurea ed arrotonda";
            $reportCommissione->Cell(0, 7, $txt, 0, 1, 'L');

        }

        // Salvataggio report commissione

        $filename = "/Users/matteomontalto/Local Sites/progetto/app/public/prospetti/$percorso/prospettoCommissione.pdf";
        $reportCommissione->Output('F', $filename);

        // Vengono generati i report per gli studenti

        foreach ($matricole as $matricola) {

            // Vengono ottenuti i dati
            # Ho già la matricola
            $nome = "tbd";
            $cognome = "tbd";
            $email = "tbd";
            $data = $param["dataLaurea"];
            $esami = ["esame1", "esame2"];

            $reportStudente = new FPDF();

            $reportStudente->AddPage();
            $reportStudente->SetFont('Helvetica', '', 16);

            $reportStudente->Cell(0, 7, $corso, 0, 1, 'C');

            $txt = "CARRIERA E SIMULAZIONE DEL VOTO DI LAUREA";
            $reportStudente->Cell(0, 7, $txt, 0, 1, 'C');

            $reportStudente->SetFontSize(10);
            $reportStudente->Ln(7);

            // Viene stampata la tabella di informazioni dello studente

            $cellWidth = ($reportStudente->GetPageWidth() - 20) / 4;

            $old_x = $reportStudente->GetX();
            $old_y = $reportStudente->GetY();
            $reportStudente->MultiCell($cellWidth, 5, "Matricola:\nNome:\nCognome:\nEmail:\nData:", "TBL", 'L');
            $reportStudente->SetXY($old_x + $cellWidth, $old_y);
            $reportStudente->MultiCell(0, 5, "$matricola\n$nome\n$cognome\n$email\n$data", "TBR", 'L');
            $reportStudente->Ln(3);

            // Viene stampata la tabella degli esami

            $cellWidth = ($reportStudente->GetPageWidth() - 20) / 17 * ($corso == "T. Ing. Informatica" ? 13 : 14);

            $reportStudente->Cell($cellWidth, 6, "ESAME", 1, 0, "C");

            $cellWidth = ($reportStudente->GetPageWidth() - 20) / 17;

            $reportStudente->Cell($cellWidth, 6, "CFU", 1, 0, "C");
            $reportStudente->Cell($cellWidth, 6, "VOT", 1, 0, "C");
            if ($corso == "T. Ing. Informatica") {
                $reportStudente->Cell($cellWidth, 6, "MED", 1, 0, "C");
                $reportStudente->Cell($cellWidth, 6, "INF", 1, 1, "C");
            } else {
                $reportStudente->Cell($cellWidth, 6, "MED", 1, 1, "C");
            }

            // todo: Ciclo gli esami dati e li stampo

            foreach ($esami as $esame) {
                $cellWidth = ($reportStudente->GetPageWidth() - 20) / 17 * ($corso == "T. Ing. Informatica" ? 13 : 14);

                $reportStudente->Cell($cellWidth, 5, $esame, 1, 0, "L");

                $cellWidth = ($reportStudente->GetPageWidth() - 20) / 17;

                $reportStudente->Cell($cellWidth, 5, " ", 1, 0, "C");
                $reportStudente->Cell($cellWidth, 5, " ", 1, 0, "C");
                if ($corso == "T. Ing. Informatica") {
                    $reportStudente->Cell($cellWidth, 5, " ", 1, 0, "C");
                    $reportStudente->Cell($cellWidth, 5, " ", 1, 1, "C");
                } else {
                    $reportStudente->Cell($cellWidth, 5, " ", 1, 1, "C");
                }
            }

            $reportStudente->Ln(3);

            // Viene stampata la tabella con le informazioni su voti e crediti

            $cellWidth = ($reportStudente->GetPageWidth() - 20) / 2.5;

            $old_x = $reportStudente->GetX();
            $old_y = $reportStudente->GetY();
            $txt = ($corso == "T. Ing. Informatica") ? "Media Pesata (M):\nCrediti che fanno media (CFU):\nCrediti curriculari conseguiti:\nVoto di Tesi (T):\nFormula calcolo voto di laurea:\nMedia pesata esami INF:" : "Media Pesata (M):\nCrediti che fanno media (CFU):\nCrediti curriculari conseguiti:\nFormula calcolo voto di laurea:";
            $reportStudente->MultiCell($cellWidth, 6, $txt, "TBL", 'L');
            $reportStudente->SetXY($old_x + $cellWidth, $old_y);
            $txt = ($corso == "T. Ing. Informatica") ? "tbd\ntbd\ntbd\ntbd\ntbd\ntbd" : "tbd\ntbd\ntbd\ntbd";
            $reportStudente->MultiCell(0, 6, $txt, "TBR", 'L');

            // Salvataggio report studente

            $filename = "/Users/matteomontalto/Local Sites/progetto/app/public/prospetti/$percorso/$matricola.pdf";
            $reportStudente->Output('F', $filename);
        }

        // Al termine dell'esecuzione degli script di generazione dei pdf, viene passata la stringa "Prospetti Generati" di modo che venga stampata a schermo come richiesto
        return "Prospetti Generati";
    }

    // todo: Implementa la gestione con cartelle per ogni corso di laurea !!
    // todo: Implementa la distruzione dei file all'inizio della generazione per rimanere conformi al GDPR

    // Implementata la funzionalità GeneraProspettoLaurea. Creazione dell'omonimo file e implementazione per la omonima classe. Implementato parziale funzionamento della generazione dei pdf (ancora da creare la capacità del codice di interfacciarsi con i file json necessari). Aggiustamento del design dei pdf in base alle immagini fornite (figure b e c). La funzione è in grado di generare i report sia della commissione che degli studenti, come richiesto (rimangono i problemi detti in precedenza).
}

?>