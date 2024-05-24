# Arogya Hospital System

Arogya Hospital System is a web-based application which provides a comprehensive solution for managing hospital operations, including patient registration, appointments, treatment history, doctor management, and administrative tasks.

## Features

1. **Frontend**
   - **Patient Module**:
     - Registration: Allows patients to register accounts.
     - Login: Secure login system for registered patients.
     - Reset Password : Patients can securely reset their password via sms otp system. 
     - Appointment Management: Patients can place, view, and cancel appointments.
     - Treatment History: View past treatment history.
     - [Live URL: Arogya Hospital Online - Patient] https://arogyahospital.online

   - **Doctor Module**:
     - Login: Secure login system for doctors.
     - View Doctor Details: Access doctor profiles and details.
     - Appointment Management: View scheduled appointments.
     - Update Treatment History: Add or update treatment history for patients.
     - [Live URL: Arogya Hospital Online - Doctor](https://arogyahospital.online/doctor/index.php)

2. **Admin Module**:
   - **Doctor Management**:
     - Add Doctors: Admin can add new doctors to the system.
     - Manage Doctor Types: Add, update, or delete doctor types.
     - Doctor Schedule: Add,update or delete doctor schedules.
     - View All Doctors: Admin can view details of all doctors.
     - Doctor Treatment History: Admin can view treatment history of doctors.
   
   - **Patient Management**:
     - View All Patients: Admin can view and delete details of all registered patients.
   
   - **Message Management**:
     - View All Messages: Admin can view and send replies to all messages sent by users.
   
   - **Appointment Management**:
     - View All Appointments: Admin can view all appointments made by patients.

   - **Treatment History Management**:
     - View All Treatment History: Admin can view treatment history of patients.
   

   - [Live URL: Arogya Hospital Online - Admin](https://arogyahospital.online/admin/index.php)

## Installation

1. Clone the repository: `git clone https://github.com/ayeshmadusanka/arogya.git`
2. Import the SQL database provided (`sql/hms.sql`) into your MySQL database.
3. Configure the database connection in the `connection/connect.php` file.
4. Upload the files to your web server.
5. Access the application through the provided URLs for the frontend, doctor, and admin modules.

## Technologies Used

- PHP
- MySQL
- HTML
- JavaScript (jQuery and jQuery DataTables Plugin) 
- CSS (Bootstrap)

## Authors

- Ayesh Madusanka https://github.com/ayeshmadusanka 
- Viraji Dias https://github.com/virajidias
