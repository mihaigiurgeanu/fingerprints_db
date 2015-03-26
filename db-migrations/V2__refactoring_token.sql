rename table fp_hardware_sessions to fp_tokens;
alter table fp_fingerprnts drop column session_id;
alter table fp_photos drop column session_id;

alter table fp_tokens add (
    token_consumed boolean default 0,
    token_type varchar(10),
    fingerprint_id char(36),
    photo_id char(36)
);

