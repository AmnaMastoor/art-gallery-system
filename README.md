🎨 Art Gallery Management System – Project Description

The Art Gallery Management System is a database-driven project designed to efficiently manage the core operations of an art gallery. The system focuses primarily on strong database design and normalization to ensure data integrity, reduce redundancy, and maintain structured relationships between entities.

The project includes multiple interconnected tables such as Gallery, Employee, Artist, Artwork, Exhibition, Customer, Order, and Order Details. Each table is carefully designed with appropriate primary and foreign key relationships to maintain consistency across the system. For example, artworks are linked to artists, employees are associated with galleries, and orders are connected to both customers and employees.

The system also supports many-to-many relationships, such as between Artwork and Exhibition, which is handled through a junction table (Artwork_Exhibition). Similarly, order details manage the relationship between orders and artworks, allowing multiple artworks to be included in a single order.

The database was normalized after design to minimize redundancy and improve efficiency. Although the frontend is simple, it serves as an interface for interacting with the backend database.

Overall, the project demonstrates strong understanding of relational database design, normalization principles, and real-world data modeling for managing an art gallery system.
