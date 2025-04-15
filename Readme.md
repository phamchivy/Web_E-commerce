# 🛒 E-commerce Website (CRUD-Based)

A simple E-commerce web application with full CRUD functionality for managing users, orders, and products. It includes user and admin roles with proper authentication and authorization using sessions and JWT.

---

## 🔧 Tools & Technologies

- **Frontend:** HTML, CSS, JavaScript, Bootstrap, Bootstrap Studio
- **Backend:** PHP
- **Database:** MySQL
- **Client-side Interaction:** jQuery, Fetch API
- **Development Environment:** XAMPP
- **Authentication:** Sessions and JWT

---

## ✨ Features

### 👤 User Features
- User registration, login, and password recovery
- Add, edit, delete, search, and submit order information
- User authentication and session management with JWT

### 🛠️ Admin Features
- Admin login
- View all user orders and product details

---

## 🖥️ UI/UX
- Designed using HTML, CSS, and **Bootstrap Studio**
- JavaScript used for interactive components and client-side validations

---

## 🔗 API Interaction
- Utilizes `fetch()` and `jQuery AJAX` to communicate with backend API endpoints

---

## 🚀 How to Run the Project

1. **Clone the repository:**

   ```bash
   git clone <repository-url>
   cd <project-folder>
    ```

2. **Set up .env file at the root of the project with the following variables:**

   ```bash
    ADMIN_PASSWORD=your_admin_password_here
    JWT_ADMIN_SECRET_KEY=your_jwt_admin_secret_key_here
    EMAIL=your_email_here@gmail.com
    PASSWORD="your_email_app_password_here"
    ```

3. **Start XAMPP and make sure Apache and MySQL are running.**

4. **Import the SQL database:**

- Open `http://localhost/phpmyadmin`
- Create a new database (e.g. `ecommerce_db`)
- Import the `.sql` file from the project if provided

## 📧 Email Support
Uses Gmail SMTP via PHPMailer or mail() for:
- Password reset functionality
- Order confirmation (optional)

Make sure to enable "Less secure app access" or use an App Password if you have 2FA enabled on Gmail.

## 👨‍💻 Author
- Developed by Phạm Chí Vỹ
- Student at Hanoi University of Science and Technology
- Electronics and Telecommunication Engineering

## 📌 Preview

![Demo Gif](assets/php.gif)
