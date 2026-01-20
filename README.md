[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/c3PHjGNX)

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About KU Award

The Student Affairs Division of Kasetsart University aims to develop the "Nisit Deeden System" to streamline the selection process for the Outstanding Student Award, which grants tuition waivers or certificates to qualified students. In this workflow, students self-nominate in one of three categories—Extra-curricular Activities, Creativity and Innovation, or Good Conduct—by submitting specific supporting documents for a single chosen category per round. The application undergoes a hierarchical approval process starting with the Head of Department, followed by the Associate Dean and the Dean, before reaching the Student Affairs Division for verification, where staff possess the authority to correct award categories if necessary. Subsequently, a committee reviews and votes on the candidates, requiring a majority consensus for approval, after which the process concludes with the Committee Chairman and University President signing off on the final announcement; however, complex specific steps may remain as manual processes depending on development feasibility

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.



## 🚀 Get Started

Copy the `.env` file

```shell
cp .env.example .env
```

Install vendor

```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/app" \
    -w /app \
    composer:latest \
    install --ignore-platform-reqs
```

Run the container

```shell
sail up -d
```

Generate `APP_KEY`

```shell
sail artisan key:generate
```

Run the migrations and seeders

```shell
sail artisan migrate:fresh --seed
```

Install dependencies

```shell
sail bun install
```

Load the style (in this project using `bun` as a package manager)

```shell
sail bun dev
```
