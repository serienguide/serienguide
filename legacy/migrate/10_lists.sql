# Lists
INSERT INTO lists
(
    id,
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
        user_id,
        NULL,
        url,
        name,
        beschreibung,
        privat,
        standard_sortierung,
        anzahl_items,
        dauer,
        erstellt,
        bearbeitet
    FROM
        legacy_liste
    WHERE
        legacy_liste.id NOT IN (SELECT id FROM lists)
    ORDER BY
        id ASC
);

DELETE FROM
    legacy_liste_item
WHERE
    id IN (
        SELECT
            legacy_liste_item.id
        FROM
            legacy_liste_item
                LEFT JOIN legacy_liste ON (legacy_liste.id = legacy_liste_item.liste_id)
        WHERE
            legacy_liste.id IS NULL
    );

## Film
INSERT INTO list_item
(
    list_id,
    medium_type,
    medium_id,
    rank,
    created_at,
    updated_at
)
(
    SELECT
        liste_id,
        'App\\Models\\Movies\\Movie',
        film_id,
        sortierung,
        erstellt,
        bearbeitet
    FROM
        legacy_liste_item
    WHERE
        film_id > 0 AND
        folge_id = 0 AND
        serie_id = 0 AND
        person_id = 0
);

## Serie
INSERT INTO list_item
(
    list_id,
    medium_type,
    medium_id,
    rank,
    created_at,
    updated_at
)
(
    SELECT
        liste_id,
        'App\\Models\\Shows\\Show',
        serie_id,
        sortierung,
        erstellt,
        bearbeitet
    FROM
        legacy_liste_item
    WHERE
        film_id = 0 AND
        folge_id = 0 AND
        serie_id > 0 AND
        person_id = 0
);
