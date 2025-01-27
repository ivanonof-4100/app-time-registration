# 1. Introduction
## 1.1 About the Author
I am Ivan Mark Andersen, I am a professionel Senior Full-stack Web Developer from DENMARK in the EU.

I have a deep knowledge and lots of relevant expert experiences in software development.
My big strength shurly is within developing custom web-applications
that streamlines the new workday in the organization. 

## 1.2 The Project
I have developed this project as a prove of my skills as a Full-stack Web Developer,
back when I was in the process of getting a job as a Back-end Developer.

Orginally this project was only ment as a prototype on how I would build a web-application
that solved the needs of the company which I was in a process with,
but afterwords I continued to improve my inital code and now it is more like a fully featured
web-application that can do time-registration in a very smart, uniform and intuitive manner.

I have developed a web-application for daily time-registration written in native PHP (OOP),
using my own solid and custom build MVC-framework.

My implementation of the MVC-framework uses Best-practices - Design-patterns like
Modular Monolith architecture, Model-View-Controller (MVC), patterns of Object-oriented Programming (OOP),
SOLID (OOP-related), Dependency injection, Registry, Dynamic Routes-based system-setup with Requests and Responses,
Delegation, Decorator, Factory, Singleton, Modules, Configuration-files
and others including thoes for handling multiple-languages on the same site.

And for storeing and retriving data in the database I use MySQL RDBMS and my custom database-abstraction
class that make use of PDO-objects to prevent SQL-injections.
To also address that I understand and is able to create and intergrate REST-APIs (REST, CRUD, JSON)
in a web-application, I have done that as well in the web-application.

My MVC-framework uses the PSR-4 standard for doing auto-loding using Composer and a generated class-map.
I use a clean request- and response-model through out the framework to handle each request.
I also utilize the latest Smarty template-engine which is also known for having grate support for both caching and writing your own plugins and easy displaying of HTML5-content.

Every UI-component is written as a Smarty-templates using HTML5, CSS and JavaScripting.
I also utilize the popular front-end framework of Bootstrap to create a modern Responsive Web-design
that both make your web-application fit any end-user device and look COOL.
It adds a better look and feel to the web-application.

As something special for this project I decided to use Universal Unique IDs (UUID),
because of the possible vast amount of data that will be accumulated and maintained in the web-application,
over time, if used every day by all of the employees in a company for years.

Using the normal unique-IDs, which is 64-bit unsigned integers, we can create like
4 billions records in the database before we need to export all the data of the database-table
or something thing else, but what if we dont want to have any upper limit on the number of records??

Using UUIDs we can go on for ever and ever, because UUIDs are unique in all over the world.
And it all comes at a small price of just using 64-bit more for each row.

Because UUIDs are 128-bit numbers displayed as a hexadecimal string of 36 chars that contains 5 numbers
with dashes in between. I let the MySQL RDBMS generate the UUIDs using a before-insert trigger which
is a smart way of both doing it in an easy and a atomar way.

## 1.3 Copyright and license
Ivan Mark Andersen holds the Copyright (C) for this web-application and the MVC-framework.

You are only able to evaluate my coding-skills by either just looking at it online on github.com
or you can clone my project using Git to experience how well it runs in a local-environment.

The evaluation-period is offcause NOT for ever, lets say maximum 2 weeks.

So You need to afterwards remove my web-application,
unless you or your organization have committed to a yearly subscription-plan
and received a license to make it run in an online production-environment for your organization.

## 1.4 Contact information
Write me an simple e-mail to my personal e-mail address at Gmail: Ivan Mark Andersen <ivanonof@gmail.com>
and explain what you want in simple terms and I will get back to you with an offer.

Have fun evaluating my web-application!

Kind regards
Ivan Mark Andersen, Senior Full-stack Web Developer

# 2. How-to configure the Web-application locally
Install and configure Git on your device, if not allready installed.

## 2.1 STEP 1: Clone repository
 $ git clone https://github.com/ivanonof-4100/app-time-registration

## 2.2 STEP 2: Install and configure MySQL, NginX and PHP-FPM (CGI)
Use at least PHP v7.4 or newer PHP version.

## 2.3 STEP 3: Create a MySQL-database
Create the database that is to be used.

## 2.4 STEP 4: Import the SQL-file
I have made a dump of the database with some data which you can import into your newly created MySQL database.

SQL-file:
app-time-registration/modules/timesheets/classes/model/sql/np_timesheets.sql

## 2.5 STEP 5: Information about the PSR-4 auto-loading standard
This project uses the PSR-4 auto-loading standard to do auto-loading in PHP.

Read more about PHP Standard Recommendation (PSR):
https://www.php-fig.org/psr/

## 2.6 STEP 6: Installing Composer
This project depends on PSR-4 auto-loading done by Composer, so you need Composer installed.
If you dont know how-to install Composer, go to the URL below and read all about it: 

Read more about Composer:
https://getcomposer.org/

If you are on a Linux-box like me, you can run the bash-script that installs Composer for you.
I normally like to put Composer at a global location on the Linux-server like in /usr/local/bin/

 $ app-time-registration/bash-scripts/upgrade-composer.sh

## 2.7 STEP 7: Generate new autoload-map using Composer
When your class'es are not found using auto-loading then generate a new autoload-map.

 $ composer dump-autoload
 Generating autoload files
 Generated autoload files

## 2.8 STEP 8: Setup the config-file of the web-application
You can also do this by just by changing the name of the skeleton-file to app.conf.json in the config-directory.
Or by manually editing the app.conf.json file.

Add the following JSON-structure:

{
    "app_lang_supported": {
        "lang_da": {
        "lang_ident": "da",
        "lang_native": "Dansk",
        "lang_target": "/da/"
        },
        "lang_en": {
        "lang_ident": "en",
        "lang_native": "English",
        "lang_target": "/en/"
        },
        "lang_es": {
        "lang_ident": "es",
        "lang_native": "Español",
        "lang_target": "/es/"
        }
    },
    "datetime": {
        "datetime_timezone": "UTC"
    },
    "session": {
        "driver": "db",
        "expire_secs": 7200
    },
    "db_connection": {
        "driver": "mysql",
        "host": "localhost",
        "dbname": "np_timesheets",
        "dbcodepage": "utf8mb4",
        "dbuser": "dbusr_web_np",
        "dbpassword": "secretpasswd"
    }
}

## 2.9 STEP 9: Setup the virtual-host in web-server
I run the very popular web-server of NginX on my Linux-box.
I suggest that you do the same. I have configuration-files for NginX, if you want that.

## 2.10 STEP 10: Setup the DNS-name for the domain-name
This setup is done in /etc/hosts file.

# 3. Original Project Description
## 3.1 Context
In the future, we need to handle timesheets in a more uniform and more stream-lined way.
The project task is to create a timesheet web-solution that makes it easy for employees to report working days and spent hours for each employee.
 
Today we retrive the timesheets from our employees from the onces that are monthly-payed,
weekly-payed and the onces that are only payed every 2-weeks.

And these timesheets can come in many different forms and formats.
They can even be made mannualy by the consultant him self.

They even come in many different formats like MS-Excel, some can even be hand-written or in PDF-files
and some can be in like 50 different designs, so we need a common solution, looking forward.

Today (it was back in 2022) is every thing delivered using e-mail and is therefore a manual process
which takes up costly human-resources to process all the retrived timesheets.

We want to do all this in a much smarter and more uniform way!
You will get a great degree of freedom on how the solution should be, to solve this task.

## 3.2 Non-functional requirements
- All we ask is that its easy for the end-users to report or upload,
  so the burden can be as little as possible for the consultant.

## 3.3 Functional requirements
- There needs to be a sum of the working-hours for the time-period.
- A running online prototype
- A reflection on the solution - advantages / disadvantages is desired.

## 3.4 Purpose
- I want to see how you think and get an insight into how you code.
- There is no right or wrong.
- Be creative and solve it the way you think is the best.

I know the name of the company and the CIO, with whom I spoke with and gave me this assignment
before the second job-interview.
I will not list them here any more.

## 3.5 Use my web-solution
Unfortunately, it wasn't me who got the job, even though I solved the task in a very smart way.

So here I am trying to sell a license to use my web-solution in organizations who face the same challenges
each day, having many consultants who need to report their working hours and afterwords processing the timesheets
for documenting and sending the correct payment to their employees for their help and services.

My web-solution can also be used to meet the requirements for a company where the new EU-law
says that the company has do register and document how much their employees has worked during the time-period.
Like a year or week or what the request is.
Only employees who plan their own time during the week dont have to document it.

See my contact information in this README.md document and try to reach out to me,
if you are interested.

Kind regards
Ivan Mark Andersen, Senior Full-stack Web Developer