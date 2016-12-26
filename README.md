Feed Reader
===========

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
