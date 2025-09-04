## Calimera, a small php app to get a daily dose of sunshine

### Description

This webapp is just to receive a daily mail with a nice sentence to start a good day.

You can register your email to receive the sentence but it is also planned to be able to read the sentence thanks to a rss feed or just by poping on the main page.
It offers the possibilty for anyone to propose a sentence.

Also planned:
- daily or weekly delivery choice
- categories of sentences (and the possibility to choose to receive the wanted categories)
- unsubscribe option
- administration managment
- user options managment 

### Instructions

The app is in php and uses a sqlite database. The sql schema is available in the sql folder.

you need to 
- git clone the repository in your webserver folder
- install some necessary packages with [Composer](https://getcomposer.org/download/)
  `composer install`
- copy the .env.example
  `$ cp .env.example .env` and fill it (`$ nano .env`)    
- use the config files helpers depending on the webserver you are using, [.htaccess for Apache](https://github.com/elebar15/calimera/blob/master/webserver_configs/.htaccess) or the [block code for nginx](https://github.com/elebar15/calimera/blob/master/webserver_configs/nginx)    
