## How to run this code

This is a Symfony 3 web app.

It should be set up like so. Here are the steps:

#### Clone the repository

Create directory e.g. `passwdReset` inside your web server document root dir.:

`$ mkdir passwdReset `

Run git clone: 

`$ git clone https://github.com/djuro/passwdResetFeature.git passwdReset`

#### Install dependencies
Inside the project directory run:
`$ composer install` to install all necessary Symfony dependency libraries.

At the end of process it will prompt you to enter database name, user, etc. But you can set those later as well in:
`.../passwdReset/app/config/parameters.yml`

    
#### Check configuration
Point the browser to this URL:
` http://localhost/passwdReset/web/config.php`

The page should show if requirements have been met. It usually complains about access permisions for `/var/logs` and `/var/cache` directories.
Here are the instructions how to fix those:
https://symfony.com/doc/3.4/setup/file_permissions.html

The 3rd point works on most of the systems. As an alternative you can set the Apache user to be the same as your CLI user.

#### Base URL
It is important that you set `base_url` parameter in `parameters.yml`. If you set up a virtual host, the domain root should point to `.../passwdReset/web` directory.

#### Application routes
`/login` Displays a login form with a link for Password reset. Make sure you enter your e-mail in the User's record, and then proceed to reset password. The username is `jdoe`. E.g:

`http://localhost/passwdReset/web/app_dev.php/login` 

