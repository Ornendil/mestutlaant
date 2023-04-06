<?php

function skaffListe($aar, $key, $ccl = 'ff=l'){
    global $config;
    $underdomene = htmlspecialchars($config['bibliotek']['underdomene']);
    $aar = preg_replace('/\D/', '', $aar);
    
    $ccl = preg_replace('/ /', '+', $ccl);
    $ccl = preg_replace('/[^0-9a-zA-ZÆØÅæøå=&+()]/', '', $ccl);

    $filename = "cache/".$aar."-".$key.".json";

    if (isset($_GET['reload']) && $_GET['reload'] == 'true'){
        $reload = true;
    } else {
        $reload = false;
    }
    
    if (file_exists($filename) && $reload == false) {
        $liste = json_decode(file_get_contents($filename),true);
    } else {
        $url = 'https://'.$underdomene.'.bib.no/cgi-bin/rest_service/webapi_statistikk/1.0/data/?f_ccl='.$ccl.'&f_dato_fra='.$aar.'-01-01&f_dato_til='.$aar.'-12-31&f_aktivitet=U&mode=utlangenerer&f_tid_fra=&f_tid_til=&f_alder_fra=&f_alder_til=&f_postnr_fra=&f_postnr_til=&f_eksnr_fra=&f_eksnr_til=&maxantall=10&sortering=antall&format=json&epost=&preskolonne=titnr';

        $json_statistikk = json_decode(file_get_contents($url), true);
        $liste = $json_statistikk["statistikk"]['data'];

        $ids = "";
        foreach ($liste as $key => $bok){
            $liste[$key]['tnr'] = $bok[0];
            $liste[$key]['Antall'] = $bok[1];
            unset($liste[$key][0]);
            unset($liste[$key][1]);
            $ids .= $bok[0].",";
        }
        
        // Berik med data fra rest-service
        $restUrl = 'https://'.$underdomene.'.bib.no/cgi-bin/rest_service/items/1.0/data/'.$ids;
        $restContents = json_decode(file_get_contents($restUrl), true);

        foreach ($liste as $key => $bok){
            foreach ($restContents[$bok['tnr']] as $jey => $value) {
                if (gettype($value) == 'array') {
                    $liste[$key][$jey] = implode(", ", $value);
                } else {
                    $liste[$key][$jey] = $value;
                }
            }

        }


        // Berik med bilde og beskrivelse fra krydderbasen
        $bibliofilIds = "";
        foreach ($liste as $key => $bok){
            $bibliofilIds .= $bok['bibliofilid'].",";
        }

        $krydderUrl = 'https://krydder.bib.no/cgi-bin/krydderxml?bibid=' . $bibliofilIds . '&format=json';
        $krydderContents = json_decode(file_get_contents($krydderUrl), true);

        foreach ($liste as $key => $bok){
            if ($krydderContents[$bok['bibliofilid']]['krydder_bilde']) {
                $krydderbilder = $krydderContents[$bok['bibliofilid']]['krydder_bilde'];
                $highest_epoc = 0;
                $highest_epoc_url = "";
                foreach ($krydderbilder as $child) {
                    if ($child["type"] === "forside" && $child["epoc"] > $highest_epoc) {
                        $highest_epoc = $child["epoc"];
                        $highest_epoc_url = $child["url"];
                    }
                }
                $liste[$key]['Krydderbilde'] = str_replace('.m.jpg','.k.jpg', $highest_epoc_url);
            }
            if (isset($krydderContents[$bok['bibliofilid']]['krydder_beskrivelse'])) {
                $liste[$key]['Krydderbeskrivelse'] = end($krydderContents[$bok['bibliofilid']]['krydder_beskrivelse'])['tekst'];
            }

        }

        if ($aar < date("Y")){
            //Write to file
            $myfile = fopen($filename, "w") or die("Unable to open file!");
            fwrite($myfile, json_encode($liste, JSON_PRETTY_PRINT));
            fclose($myfile);
        }
    }

    return $liste;
}
?>