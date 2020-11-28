# Collections
INSERT INTO movie_collection
(
    id,
    name,
    overview,
    poster_path,
    backdrop_path,
    created_at,
    updated_at
)
(
    SELECT
        tmdb_id,
        name,
        inhalt,
        poster,
        backdrop,
        erstellt,
        bearbeitet
    FROM
        legacy_film_reihe
    WHERE
        tmdb_id NOT IN (SELECT id FROM movie_collection)
    ORDER BY
        id ASC
);

# Movies
INSERT INTO movies
(
    id,
    collection_id,
    tmdb_id,
    imdb_id,
    guidebox_id,
    slug,
    name,
    name_en,
    tagline,
    overview,
    released_at,
    year,
    runtime,
    homepage,
    status,
    budget,
    revenue,
    poster_path,
    backdrop_path,
    video_url,
    facebook,
    instagram,
    twitter,
    vote_count,
    vote_average,
    tmdb_trending,
    tmdb_popularity,
    created_at,
    updated_at
)
(
    SELECT
        legacy_film.id,
        (SELECT legacy_film_reihe.tmdb_id FROM legacy_film_reihe WHERE legacy_film_reihe.id = legacy_film.film_reihe_id),
        legacy_film.tmdb_id,
        legacy_film.imdb_id,
        legacy_film.guidebox_id,
        legacy_film.url,
        legacy_film.name,
        legacy_film.name_original,
        legacy_film.tagline,
        legacy_film.inhalt,
        IF(legacy_film.release_de = '0000-00-00', NULL, legacy_film.release_de),
        DATE_FORMAT(legacy_film.release_de, '%Y'),
        legacy_film.dauer,
        legacy_film.homepage,
        legacy_film.status,
        legacy_film.budget,
        legacy_film.revenue,
        legacy_film.poster,
        legacy_film.backdrop,
        legacy_film.trailer,
        legacy_film.facebook,
        legacy_film.instagram,
        legacy_film.twitter,
        legacy_film.stimmen,
        legacy_film.bewertung,
        legacy_film.trending,
        legacy_film.popularity,
        legacy_film.erstellt,
        legacy_film.bearbeitet
    FROM
        legacy_film
    WHERE
        legacy_film.id NOT IN (SELECT id FROM movies)
    ORDER BY
        id ASC
);