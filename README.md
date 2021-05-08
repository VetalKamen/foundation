Steps to make api work:
1. execute composer install;
2. Change HOST & PORT in functions.php to your's for Redis config.
*(Precondition: you must have already installed and running Redis server)*
3. execute php -S localhost:8000 and go to the next url: http://localhost8000/src/View/view_searh.php 
Also there in header you can find links to the other pages.

P.S. 'logs/errors.txt' - has been left to demonstrate that logging errors is actually work.