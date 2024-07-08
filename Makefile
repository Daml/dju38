#.PRECIOUS: %.csv

%.csv: Q_38_previous-1950-2022_RR-T-Vent.csv.gz Q_38_latest-2023-2024_RR-T-Vent.csv.gz
	zgrep -E '^NUM_POSTE;|$*;' Q_38_previous-1950-2022_RR-T-Vent.csv.gz > $@
	zgrep '$*' Q_38_latest-2023-2024_RR-T-Vent.csv.gz >> $@

%.tsv: %.csv
	php mfq_to_dju.php < $< > $@

Q_38_latest-2023-2024_RR-T-Vent.csv.gz:
	curl -sS -o $@ https://object.files.data.gouv.fr/meteofrance/data/synchro_ftp/BASE/QUOT/Q_38_latest-2023-2024_RR-T-Vent.csv.gz

Q_38_previous-1950-2022_RR-T-Vent.csv.gz:
	curl -sS -o $@ https://object.files.data.gouv.fr/meteofrance/data/synchro_ftp/BASE/QUOT/Q_38_previous-1950-2022_RR-T-Vent.csv.gz

