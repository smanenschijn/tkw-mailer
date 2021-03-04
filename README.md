# TKW-Mailer

Welcome to the TKW-Mailer application. A microservice based on the Laravel Framework. It utilizes MailJet and Sendgrid for making sure your emails will reach their target.

To make sure all emails get delivered the application utilizes a fallback mechanism which after multiple failures from one service switches on to the next. The setup of the application makes it easy to add more services without too much hassle. Implement the Interfaces that come with the application in a new service and add it to the tkw-mailer configuration file and you are good to go. The Mailer class will automatically pick up the new service and use it when it needs to.

When a mail service fails multiple times it will be shut down for a cooling down period, making the application automatically switch to the next preferred Mail Service. This is done with the internal Laravel Rate Limiter. After a service fails for five times in a period of fifteen minutes the service will not be used for the next fifteen minutes. After that it will automatically come back and the application will try to use it again.

When all services fail the application will wait one minute before trying to send the email again. It will do so for the following hour until it will ultimately fail.

## Installation
First clone the repository
```
git clone https://github.com/smanenschijn/tkw-mailer.git
```

After cloning it's time to launch the application through Docker. From the application root use the following command:
```
docker-compose up -d
```

When the application is running it's time to finish up the installation:
```
cp .env.example .env
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan queue:work
```

Voila, that's it you are ready to send some emails. Do not forget to fill in the right credentials in the .env file.

## Using the application
you are now ready to start sending mails with this fancy microservice. This can be done in two ways:
* By sending json to http://localhost:8000/api/email/message
* With an artisan command

When using the API endpoint you have to use the following json:
```
{
    "recipients": ["johndoe@example.com"],
    "subject": "We would like to take your order",
    "body": "<h1>JustEat/TakeAway</h1>We will make sure you will get what you ordered"
}
```

The artisan commando uses a similar approach
```
docker-compose exec app php artisan tkw:mail:send "one@example.com,two@example.com" "We would like to take your order" "<h1>JustEat/TakeAway</h1>We will make sure you will get what you ordered"
```

Just give it a try and enjoy this simple way of sending emails.

## Scalability
This application is easily scalable. Right now it uses a Laravel internal queue worker per instance, but it's easily changed to e.g. Laravel Horizon or Beanstalk. When connecting to the same Redis instance it is even possible for multiple application instances to know about unavailable mail services and to take tries from all seperate instances into account.

## Improvements
There were a couple of things I didn't get to do, which I wanted to implement, but simply didn't have the time for.
* The application saves the message id from the service it was sent with, but it doesn't track if the mail was delivered or bounced.
* There are some basic tests for the application logic but the things that happen in the listeners remain untested
* A better solution for getting configuration would be a seperate config repository
* The email repository is now returning Eloquent models; a better option would have been to create a simple class representing the email object.

## Finally
I hope you like what you are seeing in this repository and if you have any questions feel free to ask me.
