# CountUp - *Legally distinct from Countdown*
Well... it's more like scrabble. 

Built using PHP 8.4.10, Symfony 6, Vue 3 and themed with good ol' Bootstrap. Using Nginx as both a web host and reverse proxy to both PHP-FPM and Node, it may require additional set up if using an alternative edge due to CORS issues between ports *(x.x.x.x:8080 Symfony and x.x.x.x:5178 Vite live server don't play very nice together).*

## Easy set up guide (docker)
1. Clone the repository
```sh
git clone git@github.com:Sectimus/arbor-engineering-task.git
```
2. Bring up the containers using docker compose
```sh
docker compose up -d
```
3. Execute migrations to bring your database schema up to date (against the `countup_php_fpm` service)
```sh
docker exec -it countup_php_fpm php bin/console doctrine:migrations:migrate
```
4. Place your favourite newline-delimited alpha dictionary (alpha = no punctuation or spaces, only using A-Z\) in the `backend/fixtures/alpha_dictionaries/en.txt` directory. (Don't have one? Download one for free here: https://raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_alpha.txt) 
5. Import your fancy new fixture into the database! (*beware this is a very computationally expensive task and may require unlocked memory limits, as are also performing calculations on the dictionary to save on compute time later*)
- On my machine, for the provided dictionary, this took about two minutes, so go make a cup of coffee ☕
```sh
docker exec -it countup_php_fpm php -d memory_limit=-1 bin/console doctrine:fixtures:load
```

**DONE!**

Visit the frontend at `localhost:8123`, and interact with the backend via `localhost:8123/api`