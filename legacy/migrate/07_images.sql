# Images

## Film
INSERT INTO images
(
    id,
    medium_type,
    medium_id,
    path,
    type,
    created_at,
    updated_at
)
(
    SELECT
        id,
        'App\\Models\\Movies\\Movie',
        film_id,
        path,
        type,
        erstellt,
        bearbeitet
    FROM
        legacy_image
    WHERE
        film_id > 0 AND
        filmreihe_id = 0 AND
        folge_id = 0 AND
        serie_id = 0 AND
        staffel_id = 0
    ORDER BY
        id ASC
);

## Filmreihe
INSERT INTO images
(
    id,
    medium_type,
    medium_id,
    path,
    type,
    created_at,
    updated_at
)
(
    SELECT
        id,
        'App\\Models\\Movies\\Collection',
        filmreihe_id,
        path,
        type,
        erstellt,
        bearbeitet
    FROM
        legacy_image
    WHERE
        film_id = 0 AND
        filmreihe_id > 0 AND
        folge_id = 0 AND
        serie_id = 0 AND
        staffel_id = 0
    ORDER BY
        id ASC
);

## Folge
INSERT INTO images
(
    id,
    medium_type,
    medium_id,
    path,
    type,
    created_at,
    updated_at
)
(
    SELECT
        id,
        'App\\Models\\Shows\\Episodes\\Episode',
        folge_id,
        path,
        type,
        erstellt,
        bearbeitet
    FROM
        legacy_image
    WHERE
        film_id = 0 AND
        filmreihe_id = 0 AND
        folge_id > 0 AND
        serie_id = 0 AND
        staffel_id = 0
    ORDER BY
        id ASC
);

INSERT INTO images
(
    id,
    medium_type,
    medium_id,
    path,
    type,
    created_at,
    updated_at
)
(
    SELECT
        id,
        'App\\Models\\Shows\\Show',
        serie_id,
        path,
        type,
        erstellt,
        bearbeitet
    FROM
        legacy_image
    WHERE
        film_id = 0 AND
        filmreihe_id = 0 AND
        folge_id = 0 AND
        serie_id > 0 AND
        staffel_id = 0
    ORDER BY
        id ASC
);

INSERT INTO images
(
    id,
    medium_type,
    medium_id,
    path,
    type,
    created_at,
    updated_at
)
(
    SELECT
        id,
        'App\\Models\\Shows\\Seasons\\Season',
        staffel_id,
        path,
        type,
        erstellt,
        bearbeitet
    FROM
        legacy_image
    WHERE
        film_id = 0 AND
        filmreihe_id = 0 AND
        folge_id = 0 AND
        serie_id = 0 AND
        staffel_id > 0
    ORDER BY
        id ASC
);