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
`/login` Displays a login form with a link for Password reset. Make sure you enter your e-mail during setup (preferably a Gmail address), in the User's record, and then proceed to reset password. The username is `jdoe`.

E.g:
`http://localhost/passwdReset/web/app_dev.php/login` 

`/logout` is available as well.


### Why I wrote it like this

I have chosen Symfony 3 and multitier architecture. I use it on a daily basis so I estimated that it would take me less time to finish. Also, using a MVC framework makes it  much easier to write clear and consistently organized code. There is no need to develop or maintain low-level utilities such as autoloading, routing, or rendering controllers.

The following files make my implementation:

#### Presentation layer
###### Controllers:

  `AppBundle/Controller/UserAuthController.php`
  
  `AppBundle/Controller/AdminController.php`

###### Form Component classes:

  `AppBundle/Form/PasswordResetType.php`
  
  `AppBundle/Form/LoginType.php`
  
  `AppBundle/Form/Model/PasswordResetData.php`
  
###### Template views are in:

  `AppBundle/Resources` directory.
  
#### Application layer

  `AppBundle/Service/MailingService.php`

  `AppBundle/Service/SecureUrlService.php`

  `AppBundle/Service/UserService.php`
  
#### Business layer
  
  `AppBundle/Entity/User.php`

#### Data access layer

  `AppBundle/Service/UserRepositoryService.php`


### Assumptions

I assumed that app should have a basic authentication feature with at least one page behind security firewall. And that the process of resetting a password should have enough security so that it could not be easily compromised. I achieved that by supplying a unique hash string per each user, which would be used as a required part of the reset link.
The link looks like this:

`http://example.com/reset-password/58a8e4c30ea6a5e4552053dbdb254863e7dfd733d4a6555d11f3ae65c4e997e8`

### Alternatives
It could have used flat PHP but it would be complicated and less secure.
I could have choosen Gmail Rest API service to handle e-mail sending for better deliverability. Swift_Mailer library seemed like a faster solution to implement.

### With more time
I could implement an expiry time limit for the generated reset link, to add another layer of security.


  


