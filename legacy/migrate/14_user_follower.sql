INSERT INTO user_follower
(
    following_id,
    follower_id,
    accepted_at,
    created_at,
    updated_at
)
(
    SELECT
        user_id,
        follower_id,
        erstellt,
        erstellt,
        bearbeitet
    FROM
        legacy_follower
    ORDER BY
        id ASC
);