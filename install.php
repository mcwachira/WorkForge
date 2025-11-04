<?php

/**
 *
 * Create database and install sample data
 */


// -------------------------
// 1. CONFIG VARIABLES
// -------------------------
$host = "localhost";
$port = 3306;
$username = "root";
$password = "Nokia100#";
$dbname = "workforge";

try{
    // -------------------------------------------
    // 2. CONNECT WITHOUT DB (TO CREATE DATABASE)
    // -------------------------------------------

    $conn = new PDO("mysql:host=$host;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h3>  Connected to Msql Succesfully . </h3>";

    // -------------------------------------------
    // 3. CREATE DATABASE
    // -------------------------------------------
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "<p>✅ Database '<b>$dbname</b>' created or already exists.</p>";

    //switch to the DB
    $conn->exec("USE $dbname");

    // -------------------------------------------
    // 4. CREATE TABLES
    // -------------------------------------------
    $createUsers = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255),
            city VARCHAR(45),
            state VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ";

    $createListings = "
        CREATE TABLE IF NOT EXISTS listings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description LONGTEXT,
            salary VARCHAR(45),
            tags VARCHAR(45),
            address VARCHAR(45),
            city VARCHAR(45),
            state VARCHAR(45),
            phone VARCHAR(45),
            email VARCHAR(45),
            requirements LONGTEXT,
            benefits LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_listings_users FOREIGN KEY (user_id) REFERENCES users(id)
                ON UPDATE CASCADE ON DELETE CASCADE
        );
    ";

    $conn->exec($createUsers);
    $conn->exec($createListings);

    echo "<p>✅ Tables created successfully.</p>";

    // -------------------------------------------
    // 5. INSERT SAMPLE USERS
    // -------------------------------------------
    $insertUsers = "
        INSERT INTO users (name, email, password, city, state, created_at) VALUES
        ('Alice Johnson', 'alice.johnson@example.com', 'password123', 'New York', 'NY', NOW()),
        ('Bob Smith', 'bob.smith@example.com', 'mypassword', 'Los Angeles', 'CA', NOW()),
        ('Charlie Davis', 'charlie.davis@example.com', 'secretpass', 'Chicago', 'IL', NOW()),
        ('Diana Lee', 'diana.lee@example.com', 'pass456', 'Houston', 'TX', NOW()),
        ('Ethan Brown', 'ethan.brown@example.com', 'letmein', 'Phoenix', 'AZ', NOW()),
        ('Fiona Clark', 'fiona.clark@example.com', 'qwerty', 'Philadelphia', 'PA', NOW()),
        ('George Wilson', 'george.wilson@example.com', '123456', 'San Antonio', 'TX', NOW()),
        ('Hannah Adams', 'hannah.adams@example.com', 'welcome', 'San Diego', 'CA', NOW()),
        ('Ian Thompson', 'ian.thompson@example.com', 'password1', 'Dallas', 'TX', NOW()),
        ('Julia Roberts', 'julia.roberts@example.com', 'testpass', 'San Jose', 'CA', NOW());
    ";

    $conn->exec($insertUsers);
    echo "<p>✅ Sample users inserted.</p>";

    // -------------------------------------------
    // 6. INSERT SAMPLE LISTINGS
    // -------------------------------------------
    $insertListings = "
        INSERT INTO listings (
            user_id, title, description, salary, tags, address, city, state, phone, email, requirements, benefits, created_at
        ) VALUES
        (1, 'Software Engineer', 'We are looking for a skilled software engineer to join our team.', '$90,000 - $120,000', 'Tech,Full-time,Remote', '123 Main St', 'New York', 'NY', '(212) 555-1234', 'hr@techcorp.com', '3+ years experience in JS/Python.', 'Health insurance, 401k.', NOW()),
        (2, 'Marketing Specialist', 'Join our marketing department to design campaigns.', '$60,000 - $75,000', 'Marketing,Creative', '456 Market Ave', 'Los Angeles', 'CA', '(310) 555-5678', 'jobs@marketpro.com', 'Strong social media skills.', 'Paid vacations.', NOW()),
        (3, 'Graphic Designer', 'Design digital and print materials.', '$50,000 - $65,000', 'Design,Creative', '22 Art Blvd', 'Chicago', 'IL', '(773) 555-4321', 'design@creativespot.com', 'Photoshop, Illustrator skills.', 'Flexible hours.', NOW()),
        (4, 'Project Manager', 'Oversee client projects and coordinate teams.', '$85,000 - $100,000', 'Management', '321 Elm St', 'Houston', 'TX', '(281) 555-7890', 'careers@buildit.com', 'PMP preferred.', 'Bonuses, Holidays.', NOW()),
        (5, 'Data Analyst', 'Analyze datasets for insights.', '$70,000 - $90,000', 'Data,Analytics', '99 Insight Rd', 'Phoenix', 'AZ', '(602) 555-2468', 'hr@dataflow.com', 'SQL + Excel proficiency.', 'Remote flexibility.', NOW()),
        (6, 'Customer Support Representative', 'Provide chat and phone support.', '$40,000 - $55,000', 'Support,Service', '55 Service Ln', 'Philadelphia', 'PA', '(215) 555-1111', 'support@helpnow.com', 'Communication skills.', 'Paid leave.', NOW()),
        (7, 'Sales Executive', 'Expand B2B customer network.', '$75,000 + Commission', 'Sales,Business', '78 Commerce St', 'San Antonio', 'TX', '(210) 555-2222', 'sales@b2bconnect.com', 'Experience in sales.', 'Commission bonuses.', NOW()),
        (8, 'UX/UI Designer', 'Improve user experience for apps.', '$80,000 - $95,000', 'Design,UX', '88 Creative Way', 'San Diego', 'CA', '(619) 555-3333', 'design@innovateui.com', '3+ years UX experience.', 'Stock options.', NOW()),
        (9, 'HR Coordinator', 'Assist with recruitment and onboarding.', '$55,000 - $65,000', 'HR,People', '40 Office Park', 'Dallas', 'TX', '(972) 555-4444', 'hr@teamworks.com', 'HR degree preferred.', 'Retirement plan.', NOW()),
        (10, 'Content Writer', 'Write articles and newsletters.', '$45,000 - $60,000', 'Writing,Marketing', '12 Story Ln', 'San Jose', 'CA', '(408) 555-5555', 'content@storyline.com', 'SEO experience preferred.', 'Remote flexibility.', NOW());
    ";

    $conn->exec($insertListings);
    echo "<p>✅ Sample listings inserted.</p>";

    echo "<h2>🎉 Installation completed successfully!</h2>";

} catch (PDOException $e) {
    die("<p style='color:red;'>❌ ERROR: " . $e->getMessage() . "</p>");
}
?>
