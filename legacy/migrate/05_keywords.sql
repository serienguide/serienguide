# Keywords
INSERT INTO keywords
(
    id,
    name,
    created_at,
    updated_at
)
(
    SELECT
        id,
        name,
        erstellt,
        bearbeitet
    FROM
        legacy_keywords
    WHERE
        legacy_keywords.id NOT IN (SELECT id FROM keywords)
    ORDER BY
        id ASC
);

# Film Keyword
INSERT INTO keyword_medium
(
    keyword_id,
    medium_type,
    medium_id
)
(
    SELECT
        keyword_id,
        'App\\Models\\Movies\\Movie',
        film_id
    FROM
        legacy_keywords_zuordnung
    WHERE
        film_id > 0 AND
        serie_id = 0
);

# Serie Keyword
INSERT INTO keyword_medium
(
    keyword_id,
    medium_type,
    medium_id
)
(
    SELECT
        keyword_id,
        'App\\Models\\Shows\\Show',
        serie_id
    FROM
        legacy_keywords_zuordnung
    WHERE
        film_id = 0 AND
        serie_id > 0
);