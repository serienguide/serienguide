# Images

## Film
INSERT INTO watched
(
    id,
    user_id,
    show_id,
    watchable_type,
    watchable_id,
    watched_at,
    created_at,
    updated_at
)
(
    SELECT
        id,
        user_id,
        NULL,
        'App\\Models\\Movies\\Movie',
        film_id,
        IF(gesehen = '0000-00-00', '2011-01-01 00:00:00', gesehen),
        erstellt,
        bearbeitet
    FROM
        legacy_gesehen
    WHERE
        film_id > 0 AND
        folge_id = 0
    ORDER BY
        id ASC
);

## Folge
INSERT INTO watched
(
    id,
    user_id,
    show_id,
    watchable_type,
    watchable_id,
    watched_at,
    created_at,
    updated_at
)
(
    SELECT
        id,
        user_id,
        serie_id,
        'App\\Models\\Shows\\Episodes\\Episode',
        folge_id,
        IF(gesehen = '0000-00-00', '2011-01-01 00:00:00', gesehen),
        erstellt,
        bearbeitet
    FROM
        legacy_gesehen
    WHERE
        film_id = 0 AND
        folge_id > 0
    ORDER BY
        id ASC
);