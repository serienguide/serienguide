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

UPDATE
    users,
    legacy_user
SET
    users.provider = legacy_user.provider,
    users.provider_id = legacy_user.social_id
WHERE
    legacy_user.id = users.id AND
    legacy_user.social_id != '0' AND
    legacy_user.social_id != '';