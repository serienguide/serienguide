INSERT INTO users
(
    id,
    name,
    email,
    password,
    created_at,
    updated_at
)
(
    SELECT
        id,
        name,
        email,
        passwort,
        erstellt,
        bearbeitet
    FROM
        legacy_user
    WHERE
        id NOT IN (SELECT id FROM users)
    ORDER BY
        id ASC
);