## Calimera, a small php app to get a daily dose of sunshine

### Description

This webapp is just to receive a daily mail with a nice sentence to start a good day.

You can register your email to receive the sentence but is planned to be able to have thanks to a rss feed or just by poping on the main page.
Daily or weekly frequency choice will be added later.
Also planned:
- offer the possibilty for anyone to propose a sentence
- categories of sentences (and the possibility to choose to receive the wanted categories)
- unsubscribe option
- administration managment
- user options managment 

### Instructions

The app is in php and uses a sqlite database. The sql schema is available in the sql folder.

you need to 
- install the phpdotenv package using [Composer](https://getcomposer.org/download/)
  `composer require vlucas/phpdotenv`
- create a sqlite database
  `sqlite3 my_database.db`  
- populate the db with the file found in the sql folder
  `sqlite3 my_database.db < db_schema.sql` 
- copy the .env.example
  `$ cp .env.example .env` and fill it (`$ nano .env`)       
