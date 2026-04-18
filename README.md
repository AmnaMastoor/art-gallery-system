<<<<<<< HEAD

---

# 🎨 Art Gallery Database Management System (PHP + MySQL)

This project is a basic web application built using **PHP**, **MySQL**, and **HTML/CSS**. It allows users to manage an art gallery's data, such as artists, artworks, exhibitions, customers, and orders.

## 💻 How It Works

* The project connects to a MySQL database using a `connection.php` file.
* Data like artist info, artwork details, customer profiles, and sales orders are stored in separate tables.
* You can **add**, **view**, **update**, or **delete** records using web forms.
* The data is displayed on different pages, including artist profiles, artwork listings, exhibition info, and customer details.
* It also supports searching and filtering artworks by artist, style, or type (like painting or sculpture).
* The SQL file (`art_gallery.sql`) sets up the database structure, including tables and relationships using foreign keys.

## 📂 Technologies Used

* **Frontend**: HTML, CSS
* **Backend**: PHP
* **Database**: MySQL (phpMyAdmin)

## 🧪 Features

* Add and manage artist and artwork info
* Manage customer and order details
* View exhibitions and filter artworks
* Structured database with foreign key constraints
* User-friendly interface for browsing gallery records

## 🔗 How to Run It Locally

1. Install WAMP or XAMPP.
2. Move the project folder to the `www` directory.
3. Create a database called `art_gallery` in phpMyAdmin and import the `.sql` file.
4. Open the project in your browser:
   `http://localhost/art-gallery-database-management`

---
=======
🎨 Art Gallery Management System – Project Description

The Art Gallery Management System is a database-driven project designed to efficiently manage the core operations of an art gallery. The system focuses primarily on strong database design and normalization to ensure data integrity, reduce redundancy, and maintain structured relationships between entities.

The project includes multiple interconnected tables such as Gallery, Employee, Artist, Artwork, Exhibition, Customer, Order, and Order Details. Each table is carefully designed with appropriate primary and foreign key relationships to maintain consistency across the system. For example, artworks are linked to artists, employees are associated with galleries, and orders are connected to both customers and employees.

The system also supports many-to-many relationships, such as between Artwork and Exhibition, which is handled through a junction table (Artwork_Exhibition). Similarly, order details manage the relationship between orders and artworks, allowing multiple artworks to be included in a single order.

The database was normalized after design to minimize redundancy and improve efficiency. Although the frontend is simple, it serves as an interface for interacting with the backend database.

Overall, the project demonstrates strong understanding of relational database design, normalization principles, and real-world data modeling for managing an art gallery system.
>>>>>>> 1ef3d966a21d1252686ad6c1c48d2b2f4c1b7c0e
