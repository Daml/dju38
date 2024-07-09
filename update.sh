#!/bin/sh

curl -sS -o - https://object.files.data.gouv.fr/meteofrance/data/synchro_ftp/BASE/QUOT/Q_38_latest-2023-2024_RR-T-Vent.csv.gz \
    | zgrep -E "^NUM_POSTE;|^38538002;" - \
    | php mfq_to_dju.php $(tail -n 1 38538002.tsv | cut -b 1-8) \
    | tee -a 38538002.tsv
