CREATE DATABASE IF NOT EXISTS apartment_reservation;
USE apartment_reservation;

-- Users table (Customers and Admin)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Apartments table
CREATE TABLE apartments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    address TEXT NOT NULL,
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    area_sqm INT,
    price_per_night DECIMAL(10,2) NOT NULL,
    max_guests INT NOT NULL,
    amenities TEXT,
    images TEXT,
    status ENUM('available', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservations table
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    apartment_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT NOT NULL,
    total_nights INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_method ENUM('cash', 'credit_card', 'bank_transfer', 'gcash', 'paymaya') NULL,
    payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    payment_date TIMESTAMP NULL,
    special_requests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE CASCADE
);

-- Reviews table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    apartment_id INT NOT NULL,
    reservation_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE CASCADE,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
);

-- Insert default admin user
INSERT INTO users (username, password, role, first_name, last_name, email, status) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System', 'Administrator', 'admin@apartments.com', 'active');

-- Insert sample apartments
INSERT INTO apartments (title, description, address, bedrooms, bathrooms, area_sqm, price_per_night, max_guests, amenities) VALUES
('Modern Studio Downtown', 'Cozy studio apartment in the heart of the city with all modern amenities', '123 Main Street, Downtown', 1, 1, 35, 2500.00, 2, 'WiFi,Air Conditioning,Kitchen,TV,Parking'),
('Luxury 2BR Condo', 'Spacious 2-bedroom condo with city view and premium furnishings', '456 High Street, Business District', 2, 2, 75, 4500.00, 4, 'WiFi,Air Conditioning,Kitchen,TV,Balcony,Gym,Pool'),
('Family Apartment', 'Perfect for families with 3 bedrooms and kid-friendly amenities', '789 Family Lane, Residential Area', 3, 2, 95, 3800.00, 6, 'WiFi,Air Conditioning,Kitchen,TV,Playground,Parking'),
('Beachfront Studio', 'Beautiful studio with ocean view and beach access', '321 Beach Road, Coastal Area', 1, 1, 40, 3200.00, 2, 'WiFi,Air Conditioning,Kitchen,TV,Beach Access,Balcony'),
('Executive Suite', 'Premium executive suite with office space and luxury amenities', '654 Executive Plaza, CBD', 2, 2, 85, 5500.00, 3, 'WiFi,Air Conditioning,Kitchen,TV,Office Space,Concierge,Gym');
