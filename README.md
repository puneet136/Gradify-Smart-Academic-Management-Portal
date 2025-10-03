# 📘 Gradify - Student Data, Exam, and Attendance Management System

Gradify is a web-based application designed to simplify student data, mid-term marks, exam schedule, and attendance management in colleges. It offers dedicated dashboards for HOD, Faculty, and Students, enabling efficient academic tracking and streamlined information flow.

---

## 🚀 Features

### 👨‍🎓 Student Panel
- View personal academic profile
- Access upcoming exam schedules
- Check midterm marks subject-wise
- Monitor attendance records in real time

### 👨‍🏫 Faculty Panel
- Post, edit, and delete exam notices
- Submit midterm marks for multiple students in one go
- Mark attendance with default "Present" status
- Edit previously submitted attendance
- Export attendance to CSV format

### 🧑‍💼 HOD Panel
- Filter and view student lists by session, semester, section, and branch
- Visual analytics for student counts (charts)
- View marks submitted by faculty with filters
- Export midterm marks and attendance to CSV
- Manage broadcast announcements

---

## 🧰 Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Web Server**: Apache (via XAMPP)
- **Version Control**: Git & GitHub
- **IDE**: Visual Studio Code

---

## 📁 Folder Structure
Gradify/ ├── Faculty/ │ ├── mid_term_filter.php │ ├── mid_term_marks.php │ ├── submit_marks.php │ ├── attendance_filter.php │ ├── mark_attendance.php │ ├── submit_attendance.php │ ├── view_attendance.php │ └── export_attendance_csv.php ├── Student/ │ ├── student_dashboard.php │ ├── view_attendance.php │ └── view_marks.php ├── HOD/ │ ├── hod_dashboard.php │ ├── view_students.php │ ├── marks_submitted.php │ └── filter_marks_submitted.php ├── CSS/ │ ├── hod.css │ ├── faculty.css │ ├── student.css │ └── common.css ├── image/ │ ├── Background.jpg │ └── logo.png ├── db.php ├── index.php └── README.md


---

## 🛠️ Installation & Setup

### 📌 Prerequisites

- XAMPP or any PHP/MySQL environment
- Git (optional)
- A browser and a code editor like VS Code

### ⚙️ Steps to Run

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-username/Gradify.git
2.**Move to XAMPP htdocs Directory**

Place the Gradify folder inside:
        ```C:\xampp\htdocs\
3.Start Apache & MySQL via XAMPP Control Panel

4.Set Up the Database

    Open your browser and go to: http://localhost/phpmyadmin

    Create a new database named gradify

    Import the SQL file containing tables:

        user, exam_schedule, midterm_marks, attendance

5.Update Database Connection

  Open db.php and ensure it looks like:
      $conn = new mysqli("localhost", "root", "", "your_db_name");
6.Access the Project

      Visit: http://localhost/Gradify/index.php to start
---
📜 License
This project is open-source and free to use for educational purposes.

🙌 Developed By
[Puneet Agrawal]
B.Tech CSE (AI) | 2nd Year | JECRC Jaipur
💻 Proficient in Java, PHP, DSA, Backend Development
🏆 140+ LeetCode problems solved | 5⭐ Java on HackerRank
🚀 Hackathons: Smart India Hackathon, Felicity'25 @ IIIT Hyderabad


Let me know if you'd like a sample SQL file or project screenshots section added too!

## 📸 Project Screenshot

[Click Here for show Image]()
![Screenshot](https://drive.google.com/uc?export=view&id=1jNMcCwYhcAp3PC39PpgsWpsSNdNDvJ7W)

