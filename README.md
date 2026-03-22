# 📅 Schedule Project

A web-based schedule management application developed using PHP and MySQL (XAMPP environment).

## Live Demo
http://arinschedule.infinityfreeapp.com

## Responsive Notice
This application is currently optimized for desktop usage.
Some features or layouts may not display correctly on mobile devices. 

## ✨ Features

- User registration and login system
- Create personal schedules
- Create urgent schedules with friends (Email notification using PHPMailer)
- Add and manage friends
- Share schedules with friends
- View schedules in calendar format (personal and shared schedules)
- Monthly summary reports (bar charts and pie charts)
- Edit personal profile information

## 🛠 Tech Stack

- PHP
- MySQL
- XAMPP
- PHPMailer
- HTML / CSS / JavaScript

## ⚙ Installation

1. Install XAMPP
2. Place the project folder inside the `htdocs` directory
3. Open phpMyAdmin
4. Import the `lastproject.sql` file
5. Access the application at:  
   `http://localhost/schedule/index.html`

## 📧 SMTP Configuration
### sendEmail.php and update_schedule_user.php

Inside `sendEmail.php` and `update_schedule_user.php`, please configure your own email address and Google App Password for SMTP authentication.

## 📌 Notes

- This project was developed as part of a University Graduation Project.
- The email notification system is implemented using PHPMailer via SMTP.