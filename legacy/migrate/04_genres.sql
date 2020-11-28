# Genres
INSERT INTO genres
(
    id,
    slug,
    name,
    created_at,
    updated_at
)
(
    SELECT
        tmdb_id,
        url,
        name,
        erstellt,
        bearbeitet
    FROM
        legacy_genre
    WHERE
        legacy_genre.id NOT IN (SELECT id FROM genres)
    ORDER BY
        id ASC
);

# Film Genre
INSERT INTO genre_medium
(
    genre_id,
    medium_type,
    medium_id
)
(
    SELECT
        legacy_genre.tmdb_id,
        'App\\Models\\Movies\\Movie',
        legacy_film_genre.film_id
    FROM
        legacy_film_genre
            JOIN legacy_genre ON (legacy_genre.id = legacy_film_genre.genre_id)
);

# Serie Genre
INSERT INTO genre_medium
(
    genre_id,
    medium_type,
    medium_id
)
(
    SELECT
        legacy_genre.tmdb_id,
        'App\\Models\\Shows\\Show',
        legacy_serie_genre.serie_id
    FROM
        legacy_serie_genre
            JOIN legacy_genre ON (legacy_genre.id = legacy_serie_genre.genre_id)
);