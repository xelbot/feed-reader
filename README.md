Feed Reader
===========

[![CircleCI](https://circleci.com/gh/xelbot/feed-reader.svg?style=shield&circle-token=bec5c207fe7091bc3fc6920e4c8269aabbcf3ce6)](https://circleci.com/gh/xelbot/feed-reader)

Feed Reader is a platform for you to read and organize news and updates from all your favourite websites in one place.

### Setup project

#### Requirements

- docker
- docker-compose

#### Install

    cp .env.dist .env
    docker-compose up --build

#### Install vendors, build app, etc.

    docker exec -it container_name bash
    composer install
