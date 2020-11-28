# Shows
INSERT INTO shows
(
    id,
    tmdb_id,
    tvdb_id,
    guidebox_id,
    imdb_id,
    slug,
    name,
    name_en,
    original_language,
    overview,
    first_aired_at,
    last_aired_at,
    air_day,
    air_time,
    year,
    runtime,
    homepage,
    status,
    type,
    is_anime,
    poster_path,
    backdrop_path,
    video_url,
    episodes_count,
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
        legacy_serie.id,
        legacy_serie.tmdb_id,
        legacy_serie.tvdb_id,
        legacy_serie.guidebox_id,
        legacy_serie.imdb_id,
        legacy_serie.url,
        legacy_serie.name,
        legacy_serie.name_original,
        legacy_serie.original_language,
        legacy_serie.inhalt,
        IF(legacy_serie.first_air_date = '0000-00-00', NULL, legacy_serie.first_air_date),
        IF(legacy_serie.last_air_date = '0000-00-00', NULL, legacy_serie.last_air_date),
        legacy_serie.airs_day,
        legacy_serie.zeit,
        DATE_FORMAT(legacy_serie.first_air_date, '%Y'),
        legacy_serie.dauer,
        legacy_serie.homepage,
        legacy_serie.status,
        legacy_serie.type,
        legacy_serie.isAnime,
        legacy_serie.poster,
        legacy_serie.backdrop,
        legacy_serie.trailer,
        legacy_serie.anz_folgen,
        legacy_serie.facebook,
        legacy_serie.instagram,
        legacy_serie.twitter,
        legacy_serie.stimmen,
        legacy_serie.bewertung,
        legacy_serie.trending,
        legacy_serie.popularity,
        legacy_serie.erstellt,
        legacy_serie.bearbeitet
    FROM
        legacy_serie
    WHERE
        legacy_serie.id NOT IN (SELECT id FROM shows)
    ORDER BY
        id ASC
);

# Seasons
DELETE FROM
    legacy_staffel
WHERE
    nr >= 10000 OR
    nr < 0;

INSERT INTO seasons
(
    id,
    show_id,
    tmdb_id,
    tvdb_id,
    season_number,
    overview,
    poster_path,
    video_url,
    episode_count,
    vote_count,
    vote_average,
    created_at,
    updated_at,
    deleted_at
)
(
    SELECT
        legacy_staffel.id,
        legacy_staffel.serie_id,
        legacy_staffel.tmdb_id,
        legacy_staffel.tvdb_id,
        legacy_staffel.nr,
        legacy_staffel.inhalt,
        legacy_staffel.poster,
        legacy_staffel.trailer,
        legacy_staffel.anz_folgen,
        legacy_staffel.stimmen,
        legacy_staffel.bewertung,
        legacy_staffel.erstellt,
        legacy_staffel.bearbeitet,
        IF(legacy_staffel.geloescht = '0000-00-00', NULL, legacy_staffel.geloescht)
    FROM
        legacy_staffel
            JOIN legacy_serie ON (legacy_serie.id = legacy_staffel.serie_id)
    WHERE
        legacy_staffel.id NOT IN (SELECT id FROM seasons)
    ORDER BY
        id ASC
);

UPDATE
    shows
SET
    shows.seasons_count = (SELECT
        COUNT(*)
    FROM
        seasons
    WHERE
        seasons.show_id = shows.id);

# Episodes
DELETE FROM
    legacy_folge
WHERE
    nr >= 10000 OR
    nr < 0;

DELETE FROM
    legacy_folge
WHERE legacy_folge.id IN (
    SELECT
        legacy_folge.id
    FROM
        legacy_folge
            LEFT JOIN legacy_staffel ON (legacy_staffel.id = legacy_folge.staffel_id)
            LEFT JOIN legacy_serie ON (legacy_serie.id = legacy_staffel.serie_id)
    WHERE
        legacy_serie.id IS NULL
);

INSERT INTO episodes
(
    id,
    show_id,
    season_id,
    tmdb_id,
    tvdb_id,
    guidebox_id,
    episode_number,
    absolute_number,
    name,
    name_en,
    name_full,
    overview,
    first_aired_at,
    first_aired_de_at,
    first_aired_en_at,
    still_path,
    video_url,
    vote_count,
    vote_average,
    created_at,
    updated_at,
    deleted_at
)
(
    SELECT
        legacy_folge.id,
        legacy_staffel.serie_id,
        legacy_folge.staffel_id,
        legacy_folge.tmdb_id,
        legacy_folge.tvdb_id,
        legacy_folge.guidebox_id,
        legacy_folge.nr,
        legacy_folge.absolute_number,
        legacy_folge.name,
        legacy_folge.name_original,
        legacy_folge.name_lang,
        legacy_folge.inhalt,
        IF(legacy_folge.erstausstrahlung = '0000-00-00', NULL, legacy_folge.erstausstrahlung),
        IF(legacy_folge.release_de = '0000-00-00', NULL, legacy_folge.release_de),
        IF(legacy_folge.release_en = '0000-00-00', NULL, legacy_folge.release_en),
        legacy_folge.backdrop,
        legacy_folge.trailer,
        legacy_folge.stimmen,
        legacy_folge.bewertung,
        legacy_folge.erstellt,
        legacy_folge.bearbeitet,
        IF(legacy_folge.geloescht = '0000-00-00', NULL, legacy_folge.geloescht)
    FROM
        legacy_folge
            JOIN legacy_staffel ON (legacy_staffel.id = legacy_folge.staffel_id)
    WHERE
        legacy_folge.id NOT IN (SELECT id FROM episodes)
    ORDER BY
        id ASC
);
