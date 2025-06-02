#!/bin/bash

# Install dependencies
composer install

# Create .env if it is initial start and generate key.
if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
fi

# Create SQLite and migrate fresh if it does not exist.
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
  php artisan migrate:fresh
fi


echo "✅ Laravel setup е завършен успешно."
