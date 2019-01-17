#! /bin/sh

echo "Очистка кэша изображений Users, Brands, Products"

rm -rf public/images/users/*
touch public/images/users/.gitignore

rm -rf public/images/brands/*
touch public/images/brands/.gitignore

rm -rf public/images/brands/*
touch public/images/brands/.gitignore

