# Currently Watching
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
        'currently_watching',
        'currently_watching',
        'Meine Serien',
        NULL,
        0,
        'name',
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
    legacy_meine_serien
WHERE
    id IN (
        SELECT
            legacy_meine_serien.id
        FROM
            legacy_meine_serien
                LEFT JOIN users ON (users.id = legacy_meine_serien.user_id)
        WHERE
            users.id IS NULL
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
        (SELECT lists.id FROM lists WHERE lists.user_id = legacy_meine_serien.user_id AND lists.type = 'currently_watching'),
        'App\\Models\\Shows\\Show',
        serie_id,
        erstellt,
        bearbeitet
    FROM
        legacy_meine_serien
);
