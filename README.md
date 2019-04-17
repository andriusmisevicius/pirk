# PiRK- simple, minimalistic personal ad website.

If you're using Vagrant to run Symfony apps use this guide: https://symfony.com/doc/current/setup/homestead.html

1. Run composer install.
2. Run composer yarn install to install node modules.
3. Build front-end files by running: yarn encore production.
4. Configure .env to your configurations.

If you want to make a user with admin privileges, you'll need to do it manually in database by adding ['ROLE_ADMIN'] inside the array of roles.

