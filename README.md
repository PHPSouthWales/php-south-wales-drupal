# PHP South Wales Drupal codebase

The Drupal codebase for the [PHP South Wales user group](https://www.phpsouthwales.uk) website.

## Local environment

The project is using Docker and Docker Compose for local development.

If you are using [Traefik](https://docs.traefik.io) (recommended), then the site should now be available at <http://phpsouthwales.localhost>.

## Updating Drupal core using Composer

    docker-compose exec php composer update 'drupal/core-*'

## Hosting

The hosting is provided and sponsored by [Platform.sh](http://platform.sh/?medium=referral&utm_campaign=sponsored_sites&utm_source=phpsouthwales).
