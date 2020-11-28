# Images

## Film
INSERT INTO ratings
(
    id,
    user_id,
    medium_type,
    medium_id,
    rating,
    created_at,
    updated_at
)
(
    SELECT
        id,
        user_id,
        'App\\Models\\Movies\\Movie',
        film_id,
        punkte,
        erstellt,
        bearbeitet
    FROM
        legacy_bewertung
    WHERE
        film_id > 0 AND
        folge_id = 0 AND
        serie_id = 0 AND
        person_id = 0
    ORDER BY
        id ASC
);

## Episode
INSERT INTO ratings
(
    id,
    user_id,
    medium_type,
    medium_id,
    rating,
    created_at,
    updated_at
)
(
    SELECT
        id,
        user_id,
        'App\\Models\\Shows\\Episodes\\Episode',
        folge_id,
        punkte,
        erstellt,
        bearbeitet
    FROM
        legacy_bewertung
    WHERE
        film_id = 0 AND
        folge_id > 0 AND
        serie_id = 0 AND
        person_id = 0
    ORDER BY
        id ASC
);

## Show
INSERT INTO ratings
(
    id,
    user_id,
    medium_type,
    medium_id,
    rating,
    created_at,
    updated_at
)
(
    SELECT
        id,
        user_id,
        'App\\Models\\Shows\\Show',
        serie_id,
        punkte,
        erstellt,
        bearbeitet
    FROM
        legacy_bewertung
    WHERE
        film_id = 0 AND
        folge_id = 0 AND
        serie_id > 0 AND
        person_id = 0
    ORDER BY
        id ASC
);

## Person
INSERT INTO ratings
(
    id,
    user_id,
    medium_type,
    medium_id,
    rating,
    created_at,
    updated_at
)
(
    SELECT
        legacy_bewertung.id,
        legacy_bewertung.user_id,
        'App\\Models\\People\\Person',
        legacy_person.tmdb_id,
        legacy_bewertung.punkte,
        legacy_bewertung.erstellt,
        legacy_bewertung.bearbeitet
    FROM
        legacy_bewertung
            JOIN legacy_person ON (legacy_person.id = legacy_bewertung.person_id)
    WHERE
        legacy_bewertung.film_id = 0 AND
        legacy_bewertung.folge_id = 0 AND
        legacy_bewertung.serie_id = 0 AND
        legacy_bewertung.person_id > 0
    ORDER BY
        id ASC
);
