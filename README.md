## Problem Statement
##### 1. Email a random XKCD challenge
Please create a simple PHP application that accepts a visitor's email address and emails them random XKCD comics every five minutes.

1. Your app should include email verification to avoid people using others’ email addresses.
2. XKCD image should go as an email attachment as well as inline image content.
3. You can visit https://c.xkcd.com/random/comic/ programmatically to return a random comic URL and then use JSON API for details https://xkcd.com/json.html
4. Please make sure your emails contain an unsubscribe link so a user can stop getting emails.

Since this is a simple project it must be done in core PHP including API calls, recurring emails, including attachments should happen in core PHP. Please do not use any libraries.

## 
<h1 style="background-color: rgb(96, 202, 101);
    color: rgb(247, 234, 181);
    text-align: center;
    font-size: xx-large;">ComiSite</h1>
 <a href="https://comisite.herokuapp.com/">View Demo</a>
 
## Table Of Content

- <a href="#about-the-project">About The Project</a>
- <a href="#built-with">Built With</a>
- <a href="#working">Working</a>
- <a href="#assumptions">Assumptions</a>
- <a href="#contact">Contact</a>

## About The Project
Comisite is the site that delivers comics to your inbox to keep you entertained throughout the day. Living in a Fast-Paced World, with lots of workload and pressure, we bring you a bunch of puns to help you survive the day.

This is a PHP based project, once you connect with us we bring comic's to your inbox every five minutes. All you have to do is sign up and subscribe to our mailing service to get started.

Waiting for you on the other side.
**<a href="https://comisite.herokuapp.com/">View Demo</a>**
## Built With

Here is the list of Programming languages, framework and other services that we have used for this project :

- PHP
- HTML
- Javascript
- CSS
- SendGrid (email)
- remotemysql (Database)
- Heroku (Hosting)

## Working
- After opening the site you can login or register instead to sign up. 
- After filling the registration form you have to verify your account.
- After you have successfully verified your account you have to sign in.
- The Dashboard will be visible to you after signing in.
- Here you can send a sample email with a comic attached to the email or can subscribe to the email service to get comics sent to your inbox every five minutes interval.

## Assumptions
*There are no other assumptions made, as such to the knowledge.*


## Contact
 *Yash Khade*
Email :  <khadeyash777@gmail.com>
 [Github](https://github.com/yashkhade777/comisite) 

Project Repository Link: [https://github.com/rtlearn/php-yashkhade777](https://github.com/rtlearn/php-yashkhade777)
Project Hosting Repository Link: [https://github.com/yashkhade777/comisite](https://github.com/yashkhade777/comisite)
###### *The code was being pushed to both the repositories simultaneously, so both rtlearn and personal repository has same data.
###### *Some times you might miss a mail due to SendGrid's functionality. 