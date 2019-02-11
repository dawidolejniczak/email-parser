# Email Parser

## Project setup
```
cp .env.example .env
composer install

php artisan key:generate
php artisan migrate

npm install & npm run dev
```

Install and run ngrok or other tunnel to localhost

Fill .env with config from Mailgun. EMAIL_FROM is same as login in sandbox domain.

Add yourserver.ngrok.io/mails/webhook to routes in Mailgun

Happy checking! :smiley:
