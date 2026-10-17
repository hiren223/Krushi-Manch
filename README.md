# 🌱 Krushi Manch — Agricultural Information Platform

**Krushi Manch** is a multilingual, web-based agricultural platform designed to help farmers access crop-related information, cultivation guidance, and agricultural resources through a simple and user-friendly interface.

The project was developed as a Bachelor of Computer Applications (BCA) project at Silver Oak University, Ahmedabad, Gujarat, India.

---

## 📖 About the Project

Farmers often face difficulties accessing reliable information about crop cultivation, suitable soil conditions, seasonal requirements, crop diseases, and their management.

Krushi Manch aims to bridge this information gap by providing a centralized digital knowledge hub for farmers and agricultural enthusiasts.

The platform focuses on making agricultural knowledge easier to access through structured crop profiles, visual resources, multilingual content, and a responsive web interface.

### 🎯 Objectives

- Provide detailed information about different crop varieties.
- Help farmers understand crop seasons, growing duration, and soil requirements.
- Share information about common crop diseases and management practices.
- Improve accessibility through multilingual content.
- Encourage informed farming decisions and sustainable agricultural practices.
- Create a foundation for future agricultural services and integrations.

---

## ✨ Features

### 🌾 Crop Information

- Information covering more than 50 crop varieties, as described in the project thesis.
- Crop growing seasons and cultivation duration.
- Soil and climate requirements.
- Common crop diseases and management practices.
- Crop images and visual references.

### 🌐 Multilingual Support

- Designed to make agricultural information accessible to users from different linguistic backgrounds.
- Supports language selection and multilingual content as described in the project requirements.
- Provides a foundation for expanding regional language support.

### 🔎 Search and Filtering

- Find crop information more conveniently.
- Support crop discovery using relevant crop attributes.
- Help users navigate agricultural information efficiently.

### 👤 User Management

- User registration and login requirements.
- User profile management.
- Different access levels for users and administrators.

### 🛠️ Content Management

- Manage crop information through administrative functionality.
- Maintain agricultural resources and related content.
- Support content updates and platform maintenance.

### 📢 Notifications and Feedback

- Planned support for announcements and agricultural updates.
- Feedback submission for questions, suggestions, and reported issues.
- Potential notifications for seasonal advice, pest outbreaks, and other important information.

---

## 🧰 Technology Stack

| Technology | Purpose |
|---|---|
| HTML5 | Web page structure |
| CSS3 | Styling and responsive layouts |
| JavaScript | Client-side interactivity |
| PHP | Server-side processing and application logic |
| MySQL | Relational database management |
| Bootstrap | Responsive interface components, where used |
| XAMPP / WAMP / LAMP | Local development server environment |
| phpMyAdmin | Database administration |
| Visual Studio Code | Code editing and development |
| Git | Version control, where used |

---

## 🏗️ System Architecture

Krushi Manch follows a web-based client-server architecture.

```text
          User / Farmer / Admin
                   |
                   v
          Web Browser Interface
         HTML + CSS + JavaScript
                   |
                   v
             PHP Backend
       Application and Data Logic
                   |
                   v
             MySQL Database
                   |
                   v
        Crop and User Information
```

The frontend presents information and collects user input. The PHP backend processes application requests, while MySQL provides persistent storage for relevant application data.

---

## 👥 Intended Users

### Farmers
Access crop information, cultivation guidance, and agricultural resources.

### Agricultural Experts
Contribute agricultural knowledge, resources, and guidance where supported.

### Administrators
Manage users, crop information, announcements, and other administrative content according to their permissions.

### General Visitors
Explore publicly available agricultural information.

---

## 💻 Installation and Setup

The thesis documents PHP and MySQL as the core backend technologies and identifies XAMPP, WAMP, or LAMP as possible local development environments.

### Prerequisites

Install the following:

- XAMPP, WAMP, or another compatible Apache/PHP/MySQL environment.
- PHP 7.x or 8.x, according to your application's compatibility.
- MySQL.
- A modern web browser.
- A code editor such as Visual Studio Code.

### Step 1: Clone the Repository

```bash
git clone <YOUR_GITHUB_REPOSITORY_URL>
```

Move into the downloaded project directory:

```bash
cd <YOUR_PROJECT_FOLDER>
```

Replace the placeholders with your actual GitHub repository URL and project folder name.

### Step 2: Configure the Local Server

For XAMPP:

1. Start Apache and MySQL from the XAMPP Control Panel.
2. Copy the project folder into the `htdocs` directory.
3. Keep the project folder structure intact.

Example:

```text
C:/xampp/htdocs/krushi-manch/
```

### Step 3: Configure the Database

1. Open phpMyAdmin in your browser.
2. Create the MySQL database required by the application.
3. Import the SQL file supplied with the project, if available.
4. Update the database connection settings in the PHP configuration file.

**Important:** The exact database name, SQL import filename, and PHP configuration path depend on the source code. Check your repository before configuring them.

### Step 4: Open the Application

If the folder is located at `C:/xampp/htdocs/krushi-manch/`, open:

```text
http://localhost/krushi-manch/
```

If your project's entry point or folder name differs, use the corresponding local URL.

---

## 📂 Project Structure

The exact folder structure depends on the repository. A typical PHP application may be organized as follows:

```text
krushi-manch/
├── index.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── includes/
├── pages/
├── config/
├── database/
├── uploads/
└── README.md
```

This is an illustrative structure, not a verified listing of the original source files. Adjust it to match your actual repository.

---

## 🔐 Security Considerations

For a secure deployment, the application should use:

- Server-side validation of submitted data.
- Prepared SQL statements to reduce SQL injection risks.
- Secure password hashing.
- Role-based authorization for administrative operations.
- Output escaping to reduce cross-site scripting risks.
- HTTPS for deployed environments.
- Secure configuration of database credentials.

---

## 🔮 Future Enhancements

The thesis identifies several possible future improvements:

- Weather forecasting and regional weather alerts.
- Updated agricultural market prices.
- Crop disease and pest management resources.
- Crop yield prediction and AI-powered recommendations.
- Image-based crop disease detection.
- Offline access to selected agricultural information.
- Community discussion forums and expert Q&A.
- Government schemes and subsidy information.
- Advanced analytics and reporting.
- Additional regional languages and mobile support.

These are potential extensions described in the project documentation; their availability depends on the implemented version.

---

## 🎓 Academic Project Information

| Detail | Information |
|---|---|
| Project Name | Krushi Manch |
| Project Type | Web-Based Agricultural Information Platform |
| Degree | Bachelor of Computer Applications (BCA) |
| Institution | Silver Oak University |
| Department | Department of Computer Application |
| Location | Ahmedabad, Gujarat, India |
| Project Guide | Mr. Harsh Patel |
| Project Duration | Six months, as stated in the thesis |
| Technologies | HTML, CSS, JavaScript, PHP, MySQL |

### Project Contributors

- Satapara Abhi
- Kadivar Het
- Ubhadiya Chirag
- Keraliya Hiren

---

## 🤝 Contributions

Suggestions and improvements are welcome.

1. Fork the repository.
2. Create a feature branch.
3. Make and test your changes.
4. Commit your changes with a meaningful message.
5. Open a pull request describing your contribution.

---

## 📄 License

No license is specified in this README. Add a `LICENSE` file after choosing an appropriate license for your project.

---

## 🌱 Vision

**Krushi Manch — Making agricultural knowledge more accessible through technology.**

The project aims to support farmers with accessible information, promote informed cultivation practices, and provide a foundation for future digital agriculture services.
