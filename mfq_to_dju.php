<?php

$startToken = false;

if (isset($argv[1]) && preg_match("|^\d{8}$|", $argv[1])) {
    $startToken = $argv[1];
} else {
    printf("AAAAMMDD\ttMin\ttMax\ttMoy\tdjuM\tdjuC\ttag\n");
}

$fpInput = fopen('php://stdin', 'r');

$base = 18;

$headers = fgetcsv($fpInput, null, ';');

while ($line = fgetcsv($fpInput, null, ';')) {
    $data = array_combine($headers, $line);

    if ($startToken) {
        if ($data['AAAAMMJJ'] == $startToken) {
            $startToken = false;
        }

        continue;
    }

    // OK CEGIBAT 2+
    $tMin = round((float)$data['TN'], 2, PHP_ROUND_HALF_UP);
    $tMax = round((float)$data['TX'], 2, PHP_ROUND_HALF_UP);
    $tMoy = ($tMin + $tMax) / 2;

    // Méthode météo
    if ($tMoy >= $base) {
        $djM = 0;
    } else {
        $djM = $base - $tMoy;
    }

    // Méthode pro
    if ($base > $tMax) {
        $djB = $base - $tMoy;
    } elseif ($base < $tMin) {
        $djB = 0;
    } else {
        $djB = ($base - $tMin) * (0.08 + 0.42 * ($base  - $tMin) / ($tMax - $tMin));
    }

    $key = substr($data['AAAAMMJJ'], 0, 6);
    if (in_array(substr($data['AAAAMMJJ'], 4, 2), ['06', '07', '08'])) {
        $key = "0";
    } else if (substr($data['AAAAMMJJ'], 4, 2) == '09') {
        if ((int)substr($data['AAAAMMJJ'], 6, 2) < 21) {
            $key = "0";
        }
    }

    printf(
        "%s\t%0.2f\t%0.2f\t%0.2f\t%0.2f\t%0.2f\t%s\n",
        $data['AAAAMMJJ'],
        $tMin,
        $tMax,
        $tMoy,
        $djM,
        $djB,
        $key
    );
}

fclose($fpInput);
