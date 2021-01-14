# Comments

DELETE FROM legacy_kommentar WHERE user_id = 0;

## Film
INSERT INTO comments
(
    id,
    user_id,
    commentable_type,
    commentable_id,
    comment_id,
    text,
    created_at,
    updated_at
)
(
    SELECT
        id,
        user_id,
        'App\\Models\\Movies\\Movie',
        film_id,
        IF(kommentar_id = 0, NULL, kommentar_id),
        text,
        erstellt,
        bearbeitet
    FROM
        legacy_kommentar
    WHERE
        film_id > 0 AND
        folge_id = 0 AND
        serie_id = 0 AND
        person_id = 0 AND
        legacy_kommentar.liste_id = 0
    ORDER BY
        kommentar_id ASC,
        id ASC
);

## Episode
INSERT INTO comments
(
    id,
    user_id,
    commentable_type,
    commentable_id,
    comment_id,
    text,
    created_at,
    updated_at
)
(
    SELECT
        id,
        user_id,
        'App\\Models\\Shows\\Episodes\\Episode',
        folge_id,
        IF(kommentar_id = 0, NULL, kommentar_id),
        text,
        erstellt,
        bearbeitet
    FROM
        legacy_kommentar
    WHERE
        film_id = 0 AND
        folge_id > 0 AND
        serie_id = 0 AND
        person_id = 0 AND
        legacy_kommentar.liste_id = 0
    ORDER BY
        kommentar_id ASC,
        id ASC
);

## Show
INSERT INTO comments
(
    id,
    user_id,
    commentable_type,
    commentable_id,
    comment_id,
    text,
    created_at,
    updated_at
)
(
    SELECT
        id,
        user_id,
        'App\\Models\\Shows\\Show',
        serie_id,
        IF(kommentar_id = 0, NULL, kommentar_id),
        text,
        erstellt,
        bearbeitet
    FROM
        legacy_kommentar
    WHERE
        film_id = 0 AND
        folge_id = 0 AND
        serie_id > 0 AND
        person_id = 0 AND
        legacy_kommentar.liste_id = 0
    ORDER BY
        kommentar_id ASC,
        id ASC
);

## Person
INSERT INTO comments
(
    id,
    user_id,
    commentable_type,
    commentable_id,
    comment_id,
    text,
    created_at,
    updated_at
)
(
    SELECT
        legacy_kommentar.id,
        legacy_kommentar.user_id,
        'App\\Models\\People\\Person',
        legacy_person.tmdb_id,
        IF(kommentar_id = 0, NULL, kommentar_id),
        text,
        legacy_kommentar.erstellt,
        legacy_kommentar.bearbeitet
    FROM
        legacy_kommentar
            JOIN legacy_person ON (legacy_person.id = legacy_kommentar.person_id)
    WHERE
        legacy_kommentar.film_id = 0 AND
        legacy_kommentar.folge_id = 0 AND
        legacy_kommentar.serie_id = 0 AND
        legacy_kommentar.person_id > 0 AND
        legacy_kommentar.liste_id = 0
    ORDER BY
        kommentar_id ASC,
        id ASC
);

## Liste
INSERT INTO comments
(
    id,
    user_id,
    commentable_type,
    commentable_id,
    comment_id,
    text,
    created_at,
    updated_at
)
(
    SELECT
        legacy_kommentar.id,
        legacy_kommentar.user_id,
        'App\\Models\\Lists\\Listing',
        legacy_kommentar.liste_id,
        IF(kommentar_id = 0, NULL, kommentar_id),
        text,
        legacy_kommentar.erstellt,
        legacy_kommentar.bearbeitet
    FROM
        legacy_kommentar
    WHERE
        legacy_kommentar.film_id = 0 AND
        legacy_kommentar.folge_id = 0 AND
        legacy_kommentar.serie_id = 0 AND
        legacy_kommentar.person_id = 0 AND
        legacy_kommentar.liste_id > 0
    ORDER BY
        kommentar_id ASC,
        id ASC
);
