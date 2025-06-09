# Database Setup Instructions

## Step 1: Configure Database Connection
Make sure your database connection is properly set up in `.env` file:

```
database.default.hostname = localhost
database.default.database = wisata_lokal
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

## Step 2: Run the Migration
To set up all the tables, run:

```
php spark migrate
```

## Step 3: Seed the Database
To populate the database with sample data, run:

```
php spark db:seed InitialSeeder
```

## Default User Accounts

### Admin Account
- Username: admin
- Password: admin123

### Regular User Account
- Username: user
- Password: user123

## Database Schema

The database consists of the following tables:

1. **users** - Stores user information
2. **wisata** - Stores destination information
3. **berita** - Stores news articles
4. **wishlists** - Stores user's favorite destinations
5. **bookings** - Stores booking records

## Additional Notes

- All passwords are hashed using PHP's `password_hash()` function
- Foreign key constraints are in place to maintain data integrity
- Sample data includes various types of destinations across Indonesia
