# Stock management of Santa Claus.

## Installation

Copy the the .env

```bash
$ cp .env.dist .env.local
```

Install the dependencies
```bash
$ composer install
```

Create and install the database.
The db will be create with sqlite (you have to enable the extension in php.ini) in the folder "var/data.db"
```bash
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
```

Generate the SSL keys (The passphrase is in ".env.local")
```bash
$ mkdir -p config/jwt
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```


## Usage
You can use the symfony server to use the api:
```bash
$ symfony server:start
```


To create a user I had create a command:

```bash
$ php bin/console app:create-user laruche@mail.com password
```

The doc api is on "/api/docs"

You will can test the api by this page.

You will can get the JWT token to login.

DON'T FORGET TO ADD "Bearer " at the beginning of the value, before the token.

example:
Bearer xxxxxx


I add a filter "stock" on the route "/api/gifts" 
