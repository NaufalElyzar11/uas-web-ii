<?php
/**
 * Quick Setup Script for Wisata Lokal Application
 * 
 * This script will:
 * 1. Run migrations to create database tables
 * 2. Seed the database with sample data
 * 3. Check if the uploads directory exists and is writable
 * 4. Provide a summary of what's been done
 */

// Welcome message
echo "\n";
echo "=======================================================\n";
echo "       WISATA LOKAL APPLICATION SETUP WIZARD\n";
echo "=======================================================\n";
echo "\n";

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}

// Check if we're in the right directory
if (!file_exists('spark')) {
    echo "Error: This script must be run from the root directory of your CodeIgniter 4 installation.\n";
    exit(1);
}

echo "Starting setup process...\n\n";

// Step 1: Run migrations
echo "Step 1: Setting up database tables...\n";
$migrationOutput = shell_exec('php spark migrate');
echo $migrationOutput . "\n";

// Step 2: Seed the database
echo "Step 2: Adding sample data...\n";
$seedOutput = shell_exec('php spark db:seed InitialSeeder');
echo $seedOutput . "\n";

// Step 3: Check uploads directory
echo "Step 3: Checking uploads directory...\n";
$uploadsDir = 'public/uploads/wisata';
if (!is_dir($uploadsDir)) {
    echo "Creating uploads directory...\n";
    mkdir($uploadsDir, 0755, true);
    echo "Uploads directory created.\n";
} else {
    echo "Uploads directory already exists.\n";
}

// Check if the uploads directory is writable
if (is_writable($uploadsDir)) {
    echo "Uploads directory is writable.\n";
} else {
    echo "Warning: Uploads directory is not writable. Please make sure the web server has write permissions.\n";
}

// Step 4: Display completion message
echo "\n";
echo "=======================================================\n";
echo "                 SETUP COMPLETED!\n";
echo "=======================================================\n";
echo "\n";
echo "Your Wisata Lokal application is ready to use.\n\n";
echo "Default user accounts:\n";
echo "- Admin: username 'admin', password 'admin123'\n";
echo "- User: username 'user', password 'user123'\n\n";
echo "Start the application with:\n";
echo "php spark serve\n\n";
echo "Then visit http://localhost:8080 in your browser.\n";
echo "\n";
echo "Thank you for using Wisata Lokal!\n";
echo "\n";
