# 🏆 KU Award - Core API & Admin Portal

The backbone of the KU Award system, built with Laravel. This repository handles the business logic, database
management, file storage via MinIO, and the high-level administrative interface.

## 👤 WebTech Team Members

| Student ID | Full Name (Nickname)                 |                  GitHub Username                  |
|:----------:|--------------------------------------|:-------------------------------------------------:|
| 6610401934 | Kerdsiri Srijaroen (Tonnam)          | [Tonwantpillow](https://github.com/Tonwantpillow) |
| 6610402132 | Bowornrat Tangnararatchakit (Bright) |        [thg1rb](https://github.com/thg1rb)        |
| 6610402205 | Rugsit Rungrattanachai (Nest)        |        [Rugsit](https://github.com/Rugsit)        |
| 6610405905 | Narakorn Thanapornpakdee (Nam)       |          [nk-n](https://github.com/nk-n)          |

## 👤 DevOps Team Members

| Student ID | Full Name (Nickname)                 |                  GitHub Username                  |
|:----------:|--------------------------------------|:-------------------------------------------------:|
| 6610401934 | Kerdsiri Srijaroen (Tonnam)          | [Tonwantpillow](https://github.com/Tonwantpillow) |
| 6610402116 | Tee Anusonsart (Tee)                 | [TeeAnusonsart](https://github.com/TeeAnusonsart) |
| 6610402132 | Bowornrat Tangnararatchakit (Bright) |        [thg1rb](https://github.com/thg1rb)        |
| 6610402183 | Pawat Chaijaroen (Keam)              |      [KeamKRUB](https://github.com/KeamKRUB)      |
| 6610402205 | Rugsit Rungrattanachai (Nest)        |        [Rugsit](https://github.com/Rugsit)        |
| 6610402272 | Isarapong Tuensakul (Game)           |     [Gamenakub](https://github.com/Gamenakub)     |
| 6610405905 | Narakorn Thanapornpakdee (Nam)       |          [nk-n](https://github.com/nk-n)          |

## 🛠 Tech Stack

* **Framework:** Laravel (PHP)
* **Database:** MySQL
* **Caching/Queues:** Redis
* **Object Storage:** MinIO (for handling dynamic student uploads)
* **Auth:** Laravel Breeze/Socialite (Password & Google OAuth)

## 🌟 Key Features

* **Dynamic Form Builder:** Create award types with custom requirements. Support for N+ additional files (transcripts,
  ID cards, etc.).
* **Academic Calendar:** Manage scholarship events by Year and Semester.
* **Campus Isolation:** Strict data separation ensures admins only see data relevant to their specific campus.
* **Admin Workflow:** Manage Users, Departments, and Faculties. Review, approve, or reject applications.
* **PDF Engine:** Export approved applicants into the official university-formatted PDF for the Chancellor's signature.
* **API Provider:** Serves as the headless backend for the Svelte frontend.

## 🔐 Security & Auth

* **Dual Login:** Supports traditional credentials and Google OAuth.
* **First-Login Policy:** Users are forced to change their password upon first entry.
* **Password Recovery:** Integrated "Forgot Password" flow via email.

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

Open another terminal window to run the queue and background jobs

```shell
sail artisan queue:work
```

## 🎓 Student Activity Management System

| Role | Full Name | Username | Email | Password |
| :--- | :--- | :--- | :--- | :--- |
| **กองพัฒนานิสิต (NISIT_DEV)** | พัฒนพงศ์ วงค์นิสิต | `admin_dev` | `admin@example.com` | `password` |

