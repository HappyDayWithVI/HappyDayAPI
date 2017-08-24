# HappyDayAPI

An API that gather a lot of other APIs for HappyDayWithVi app, your personnal assistant.

## Getting Started

### Installing

Step by step tutorial to get the API running

First, you need to install php.

```
sudo apt-get install php5-common libapache2-mod-php5 php5-cli
```

Be sure to have, mbstring and dom extension install.

Then install composer as told [here](https://getcomposer.org/)

You now have to install all dependencies.

```
cd HappyDayAPI/api && composer install
```

Final step, get the API running on yout local env.
```
php -S localhost:8080 -t public
```

Aaaaaand you're done !
