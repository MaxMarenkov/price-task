# PHP backend developer assessment

## Background:
You work for a fashion e-commerce company. In the fashion industry it is usual that products of
different sizes have the same price. For example, shoes of size 36 have the same price as
shoes of size 45 of the same model. However, some categories of fashion can have different
prices depending on the size/weight/length. Example: perfumes (same perfume sold in bottles
of different volume), jewelry (the more the length of the gold bracelet the more it costs).

## Task:
- You need to develop a REST API which will allow users to update or create prices of
products.
- Price information contains price, currency, name of the size/variant of product (eg: XS,
41), name of the product, category.
- The price must be created only if the product has no price in the database.
- Categories are limited to Shoes (same price for all sizes should be applied, when price
for one size is set, other sizes should be updated to new price) and Jewelry (sizes can
have different prices).
- Your API should perform just a single task: accept price information for a single product
and save it to the database.

## Expected result:
- Public github repository or ZIP file of git repo including commit history with instructions
how to run created API.
- You can use any way of running your API (Docker is preferred but you can use a built-in
web-server of PHP, Vagrant, whatever else you find suitable).
- You can use any database or storage that you find suitable for this task.
- Key thing is that users will be able start your application and the API works according to
task specification.

## Docker setup

A [recommended Docker container](https://symfony.com/doc/current/setup/docker.html) used for the project: https://github.com/dunglas/symfony-docker

## Getting Started

1. Run `docker compose build --pull --no-cache` to build fresh images
2. Run `docker compose up -d`
3. Run `docker-compose exec php composer prepare-db`
4. Run `docker-compose exec php composer test`
5. The endpoint available for POST request on `https://localhost/prices`

## Assumptions & tradeoffs

- Product, variant, currency are not extracted into separate entities
- Products with specific names can't exist in both categories, so some checks are not implemented
- The product can have a unique price for each currency, e.g. update of the USD price won't update other prices for a specific product
- Categories are added to the database via migration, not fixtures
- Category name validation is done via hardcoded values, but not actual DB values
- Basic validation is done via the Serializer component. No error details are reported in response, only 400 HTTP code
- Request converter implemented as custom service, as I'm personally not a fan of packages like `sensio/framework-extra-bundle`
- Price value [transferred and stored](https://stripe.com/docs/api/prices/object) as an `integer`, value representing [minor units](https://github.com/brick/money) (cents) of the currency
