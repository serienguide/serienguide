# recommendations
INSERT INTO lists
(
    user_id,
    type,
    slug,
    name,
    description,
    is_private,
    default_order,
    items_count,
    runtime,
    created_at,
    updated_at
)
(
    SELECT
        id,
        'recommendations',
        'recommendations',
        'Empfehlungen',
        NULL,
        0,
        'created_at',
        0,
        0,
        NOW(),
        NOW()
    FROM
        users
    ORDER BY
        id ASC
);

DELETE FROM
    legacy_empfehlung
WHERE
    id IN (
        SELECT
            legacy_empfehlung.id
        FROM
            legacy_empfehlung
                LEFT JOIN users ON (users.id = legacy_empfehlung.user_id)
        WHERE
            users.id IS NULL
    );

## Film
INSERT INTO list_item
(
    list_id,
    medium_type,
    medium_id,
    created_at,
    updated_at
)
(
    SELECT
        (SELECT lists.id FROM lists WHERE lists.user_id = legacy_empfehlung.user_id AND lists.type = 'recommendations'),
        'App\\Models\\Movies\\Movie',
        film_id,
        erstellt,
        bearbeitet
    FROM
        legacy_empfehlung
    WHERE
        film_id > 0 AND
        serie_id = 0
);

## Serie
INSERT INTO list_item
(
    list_id,
    medium_type,
    medium_id,
    created_at,
    updated_at
)
(
    SELECT
        (SELECT lists.id FROM lists WHERE lists.user_id = legacy_empfehlung.user_id AND lists.type = 'recommendations'),
        'App\\Models\\Shows\\Show',
        serie_id,
        erstellt,
        bearbeitet
    FROM
        legacy_empfehlung
    WHERE
        film_id = 0 AND
        serie_id > 0
);