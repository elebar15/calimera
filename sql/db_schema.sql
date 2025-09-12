BEGIN TRANSACTION;
DROP TABLE IF EXISTS "phrases";
CREATE TABLE IF NOT EXISTS "phrases" (
	"id"	INTEGER NOT NULL UNIQUE,
	"phrase_text"	TEXT NOT NULL UNIQUE,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "frequency";
CREATE TABLE IF NOT EXISTS "frequency" (
	"id"	INTEGER NOT NULL,
	"daily"	INTEGER NOT NULL DEFAULT 1,
	"weekly"	INTEGER NOT NULL DEFAULT 2,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "alr_used";
CREATE TABLE IF NOT EXISTS "alr_used" (
	"id"	INTEGER NOT NULL UNIQUE,
	"id_user"	INTEGER NOT NULL,
	"id_phrase"	INTEGER NOT NULL,
	"date"	DATETIME NOT NULL,
	FOREIGN KEY("id_user") REFERENCES "users"("id") ON DELETE CASCADE,
	FOREIGN KEY("id_phrase") REFERENCES "phrases"("id") ON DELETE CASCADE,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "user_themes";
CREATE TABLE IF NOT EXISTS "user_themes" (
	"user_id"	INTEGER NOT NULL,
	"theme_id"	INTEGER NOT NULL,
	FOREIGN KEY("theme_id") REFERENCES "themes"("id") ON DELETE CASCADE,
	FOREIGN KEY("user_id") REFERENCES "users"("id") ON DELETE CASCADE,
	PRIMARY KEY("user_id","theme_id")
);
DROP TABLE IF EXISTS "phrase_theme";
CREATE TABLE IF NOT EXISTS "phrase_theme" (
	"phrase_id"	INTEGER NOT NULL,
	"theme_id"	INTEGER NOT NULL,
	FOREIGN KEY("phrase_id") REFERENCES "phrases"("id") ON DELETE CASCADE,
	FOREIGN KEY("theme_id") REFERENCES "themes"("id") ON DELETE CASCADE,
	PRIMARY KEY("phrase_id","theme_id")
);
DROP TABLE IF EXISTS "email_queue";
CREATE TABLE IF NOT EXISTS "email_queue" (
	"id"	INTEGER,
	"user_id"	INTEGER NOT NULL,
	"email"	TEXT NOT NULL,
	"phrase_id"	INTEGER NOT NULL,
	"status"	TEXT DEFAULT 'pending' CHECK("status" IN ('pending', 'processing', 'completed', 'failed')),
	"date_created"	TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	"date_processed"	TIMESTAMP,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "themes";
CREATE TABLE IF NOT EXISTS "themes" (
	"id"	INTEGER NOT NULL UNIQUE,
	"theme_name"	TEXT NOT NULL UNIQUE,
	"mail_subject"	TEXT,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "users";
CREATE TABLE IF NOT EXISTS "users" (
	"id"	INTEGER NOT NULL UNIQUE,
	"date_subscr"	DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"email"	TEXT NOT NULL UNIQUE,
	"frequency"	INTEGER DEFAULT 1,
	"day_of_week"	INTEGER DEFAULT 1,
	"unsubscribed"	BOOLEAN DEFAULT 0,
	"token"	TEXT UNIQUE,
	PRIMARY KEY("id" AUTOINCREMENT)
);
INSERT INTO "users" ("id","date_subscr","email","frequency","day_of_week","unsubscribed","token") VALUES (1,'2025-09-11 23:26:09','cron',0,NULL,0,NULL);
COMMIT;
