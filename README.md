**To get the project up and running, Pls Do the following After Cloning:**

- RUN `composer install`

- RUN `cp .env.example .env` _(Then setup ur .env file)_
  or duplicate the `.env.example` file and name the duplicated one `.env`

    - Set up DB connection in .env

- RUN `php artisan key:generate`

- RUN `php artisan migrate`

- RUN `php artisan db:seed` _(Seed some data to your db)_

- RUN `php artisan serve` _(To start the application)_

TO RUN The Tests

- Go to `.env.testing` located on the root directory and provide the correct absolute path for the
  `test.sqlite` file in the `database` folder also in the root directory
    - Correct PATH should be provided in the `DB_DATABASE` field

- RUN `php artisan migrate --seed --env=testing`

- RUN `composer test` _(To run the tests)_
