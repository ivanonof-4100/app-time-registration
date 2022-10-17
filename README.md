# Presentation:
I am Ivan Mark Andersen, I am a professionel software developer, have done this tiny project to try prove my skills of software development in the process of getting a job as a Back-end Developer.

Here I have developed a web-application for time-registration written in native PHP (OOP), using my own custom MVC-framework using PHP-autoloding using Composer class-map and the latest Smarty template-engine for writing and displaying HTML5 content.
I also use the popular front-end framework of Bootstrap.

The web-app is a monolit yes, but it dont mean that I dont know how it should done with a micro-services arkitectur.
It was my opservation, that the company allready had a monolitic arcitecture, so this is just a new module and also an application that can house them all cause I also wanted to show how we can share and reuse code, if we just get a good directory-structure for the project.

I took my old but okay custom MVC-framework and added namespacing to use the autoloading functionality of PHP.
And the needed functionality to make it function.
The problem of having your own MVC-framework is that you need to do things your self and also maintain this,
but I am doing, because I can and because there are things you need to create an application of a good quallity.
- And because I did so, its now easy do a good application.

I am also using the latest version at the time of writing of the popular front-end framework Bootstrap to add Responsive Design.
Which is what makes the layout fit the size of the unit, that you are using wheter or not its a cell-phone or a laptop.
This adds better look and feel to my little demo web-application using custom HTML5 Smarty-template files to generate the intuitive layout.

As something special in the model-classes I decided to use Universal Unique IDs (UUID) because of the possible big amount of data that will be maintained in the web-application.

Using normal IDs which is 64-bit integers we can create 4 billions records before we neeed to export the data of the database-table or something thing else. With UUIDs we can go on for ever and ever because UUIDs are unique in all over the world.
UUIDs are a 128-bit number displayed a hexadecimal string of 5 numbers with dashes in between - 36 chars.
I let MySQL RDBMS generate the UUID using a before-insert trigger which is a smart way of doing it easy and in a atomar way.

# Create a MySQL database called: np_timesheets

# Import: SQL Import file.
Go to the following directory below:

SQL-file to import:
app-time-registration/modules/timesheets/classes/model/sql/np_timesheets.sql

# Install Composer
# This project relys on PHP-autoloading done by Composer, so you need composer installed.
# If you dont know how go to the URL and read all about it: https://getcomposer.org/
# Or if you are on a Linux-box you can run the bash-script that installs Composer 
# in the sub-directory bash-scripts of the app-directory:
# app-time-registration/bash-scripts/upgrade-composer.sh

# When your class is not found using auto-loading then generate a new autoload-map:
$ composer dump-autoload
Generating autoload files
Generated autoload files

# Setup the virtual web-server i run NginX on my Linux-box

# Setup the DNS-name for the hostname in /etc/hosts file

# Have fun evaluating my web-application!
# Access the URL with your favorit web-browser.

---

# Project Description:
## Mini-projekt
# ------------
### Kontekst:
# 
# Vi skal i fremtiden håndtere Timesedler automatisk.
# Projektopgaven er at lave en timeseddelsløsning som gør det nemt for medarbejdere,
# at rapportere arbejdsdage / timer.
# 
# I dag modtager vi timesedler for månedslønnede, 14 dages lønnede eller ugelønnede.
# Disse kommer fra mange forskellige timesystemer eller er blevet lavet manuelt af konsulenten selv
# og leveret igennem Excel skabeloner konverteret til PDF.
# 
# I nogle tilfælde modtages håndskrevne timesedler.
# Alt sammen leveret på e-mail hvor vi i dag modtager mere end 50 forskellige timesedler design
# Dette vil vi gerne gøre smartere. Der er stor løsningsfrihed Til at løse opgaven.
#
# Non-funktionelle krav:
# - Det skal være nemt for brugeren at indtaste eller uploade,
#  så byrden for konsulenten er så lille som mulig.
#
# Funktionelle krav:
# - Der skal være en sum af arbejdstimerne for perioden.
# - Prototype, samt reflektion over løsning - fordele / ulemper ønskes.
# 
# Formål:
# Jeg ønsker, at se hvordan du tænker og få et indblik i din kode.
# Der er som sagt ikke noget rigtigt og forkert – vær kreativ og løs det som du synes bedst.
#
#
# Kind regards
#
# Simon Kröger Kronmose
# Digitalization, Partner
# Northern Partners ApS