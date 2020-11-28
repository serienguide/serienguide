# People
INSERT INTO people
(
    id,
    imdb_id,
    slug,
    name,
    birthday_at,
    deathday_at,
    gender,
    biography,
    place_of_birth,
    profile_path,
    backdrop_path,
    video_url,
    homepage,
    facebook,
    instagram,
    twitter,
    created_at,
    updated_at
)
(
    SELECT
        legacy_person.tmdb_id,
        legacy_person.imdb_id,
        legacy_person.url,
        legacy_person.name,
        IF(legacy_person.geburtstag = '0000-00-00', NULL, legacy_person.geburtstag),
        IF(legacy_person.todestag = '0000-00-00', NULL, legacy_person.todestag),
        legacy_person.geschlecht,
        legacy_person.bio,
        legacy_person.geburtsort,
        legacy_person.poster,
        legacy_person.backdrop,
        legacy_person.trailer,
        legacy_person.homepage,
        legacy_person.facebook,
        legacy_person.instagram,
        legacy_person.twitter,
        legacy_person.erstellt,
        legacy_person.bearbeitet
    FROM
        legacy_person
    WHERE
        legacy_person.tmdb_id NOT IN (SELECT id FROM people)
    GROUP BY
        legacy_person.tmdb_id
    ORDER BY
        id ASC
);

# Credits

## Film
INSERT INTO credits
(
    id,
    person_id,
    medium_type,
    medium_id,
    department,
    job,
    `character`,
    `order`,
    created_at,
    updated_at
)
(
    SELECT
        legacy_credits.tmdb_id,
        legacy_person.tmdb_id,
        'App\\Models\\Movies\\Movie',
        legacy_credits.film_id,
        legacy_credits.department,
        legacy_credits.job,
        legacy_credits.name,
        legacy_credits.reihenfolge,
        legacy_person.erstellt,
        legacy_person.bearbeitet
    FROM
        legacy_credits
            JOIN legacy_person ON (legacy_person.id = legacy_credits.person_id)
    WHERE
        legacy_credits.film_id > 0 AND
        legacy_credits.serie_id = 0 AND
        legacy_credits.folge_id = 0
    ORDER BY
        legacy_credits.id ASC
);

## Serie
INSERT INTO credits
(
    id,
    person_id,
    medium_type,
    medium_id,
    department,
    job,
    `character`,
    `order`,
    created_at,
    updated_at
)
(
    SELECT
        legacy_credits.tmdb_id,
        legacy_person.tmdb_id,
        'App\\Models\\Shows\\Show',
        legacy_credits.serie_id,
        legacy_credits.department,
        legacy_credits.job,
        legacy_credits.name,
        legacy_credits.reihenfolge,
        legacy_person.erstellt,
        legacy_person.bearbeitet
    FROM
        legacy_credits
            JOIN legacy_person ON (legacy_person.id = legacy_credits.person_id)
    WHERE
        legacy_credits.film_id = 0 AND
        legacy_credits.serie_id > 0 AND
        legacy_credits.folge_id = 0
    ORDER BY
        legacy_credits.id ASC
);

## Epsiode
INSERT INTO credits
(
    id,
    person_id,
    medium_type,
    medium_id,
    department,
    job,
    `character`,
    `order`,
    created_at,
    updated_at
)
(
    SELECT
        legacy_credits.tmdb_id,
        legacy_person.tmdb_id,
        'App\\Models\\Shows\\Episodes\\Episode',
        legacy_credits.folge_id,
        legacy_credits.department,
        legacy_credits.job,
        legacy_credits.name,
        legacy_credits.reihenfolge,
        legacy_person.erstellt,
        legacy_person.bearbeitet
    FROM
        legacy_credits
            JOIN legacy_person ON (legacy_person.id = legacy_credits.person_id)
    WHERE
        legacy_credits.film_id = 0 AND
        legacy_credits.serie_id = 0 AND
        legacy_credits.folge_id > 0
    ORDER BY
        legacy_credits.id ASC
);