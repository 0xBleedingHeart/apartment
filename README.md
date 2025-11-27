# PHP Boarding House Management System

A complete web-based boarding house management system built with PHP and MySQL for managing rooms, tenants, contracts, payments, and maintenance issues.

## Features

### Admin Features
- **Dashboard**: Real-time statistics and overview
- **Room Management**: Add, edit, delete, and manage room availability
- **Tenant Management**: Approve/reject tenant registrations and manage tenant accounts
- **Contract Management**: Create new contracts and terminate existing ones
- **Payment Verification**: Review and verify tenant payments with receipt validation
- **Maintenance Tracking**: Monitor and manage maintenance requests and issues
- **Reports**: Generate various reports for business insights

### Tenant Features
- **Registration & Login**: Secure account creation and authentication
- **Room Details**: View assigned room information and contract details
- **Payment System**: Make payments with receipt upload functionality
- **Payment History**: Track all payment records and transaction history
- **Maintenance Reports**: Submit and track maintenance issue requests
- **Profile Management**: Update personal information and account settings

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5.1.3
- **Server**: Apache/Nginx compatible

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (optional, for dependency management)

### Setup Instructions

1. **Clone/Download the Project**
   ```bash
   git clone <repository-url>
   cd boarding_house_system
   ```

2. **Database Configuration**
   ```bash
   # Create database
   mysql -u root -p
   CREATE DATABASE boarding_house_db;
   
   # Import schema
   mysql -u root -p boarding_house_db < database.sql
   ```

3. **Configure Database Connection**
   Edit `config/db_connect.php` with your database credentials:
   ```php
   <?php
   $host = 'localhost';
   $username = 'your_db_username';
   $password = 'your_db_password';
   $database = 'boarding_house_db';
   ?>
   ```

4. **Set File Permissions**
   ```bash
   # Ensure upload directory has write permissions
   chmod 755 assets/uploads/
   chown www-data:www-data assets/uploads/
   ```

5. **Web Server Setup**
   - Place files in your web server document root
   - Configure virtual host (optional)
   - Ensure mod_rewrite is enabled (for Apache)

## Default Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `password`

**Important**: Change the default admin password immediately after first login for security.

## Project Structure

```
boarding_house_system/
├── config/                 # Configuration files
│   ├── db_connect.php     # Database connection
│   └── init.php           # System initialization
├── includes/              # Shared components
│   ├── header.php         # Common header
│   ├── footer.php         # Common footer
│   ├── sidebar.php        # Navigation sidebar
│   └── functions.php      # Utility functions
├── assets/                # Static resources
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   ├── js/
│   │   └── script.js      # JavaScript functions
│   └── uploads/           # File uploads (needs write permissions)
├── admin/                 # Admin panel
│   ├── dashboard.php      # Admin dashboard
│   ├── rooms.php          # Room management
│   ├── tenants.php        # Tenant management
│   ├── contracts.php      # Contract management
│   ├── payments.php       # Payment verification
│   └── maintenance.php    # Maintenance issues
├── tenant/                # Tenant portal
│   ├── dashboard.php      # Tenant dashboard
│   ├── my_room.php        # Room details
│   ├── payment_history.php # Payment records
│   ├── make_payment.php   # Payment submission
│   └── report_issue.php   # Maintenance reports
├── actions/               # Backend processing
│   ├── login_action.php   # Authentication
│   ├── signup_action.php  # Registration
│   ├── save_room.php      # Room operations
│   ├── submit_payment.php # Payment processing
│   └── [other actions]    # Various form handlers
├── index.php              # Login page
├── signup.php             # Registration page
├── logout.php             # Session termination
└── database.sql           # Database schema
```

## Security Features

- **Password Security**: Passwords hashed using PHP's `password_hash()` function
- **SQL Injection Prevention**: All database queries use prepared statements
- **Input Sanitization**: User inputs are properly sanitized and validated
- **Session Management**: Secure session-based authentication system
- **Access Control**: Role-based permissions (Admin/Tenant)
- **File Upload Security**: Restricted file types and size limits

## Usage

### For Administrators
1. Login with admin credentials
2. Access admin dashboard for system overview
3. Manage rooms, tenants, and contracts
4. Verify payments and handle maintenance requests
5. Generate reports as needed

### For Tenants
1. Register for a new account (requires admin approval)
2. Login to access tenant portal
3. View room details and contract information
4. Submit payments with receipt uploads
5. Report maintenance issues
6. Track payment history

## API Endpoints

The system uses form-based submissions to various action files:
- `actions/login_action.php` - User authentication
- `actions/signup_action.php` - New user registration
- `actions/save_room.php` - Room management operations
- `actions/submit_payment.php` - Payment processing

## Troubleshooting

### Common Issues
1. **Database Connection Error**: Check credentials in `config/db_connect.php`
2. **File Upload Issues**: Verify `assets/uploads/` permissions
3. **Session Problems**: Ensure PHP sessions are properly configured
4. **Bootstrap Not Loading**: Check internet connection for CDN resources

### Error Logs
Check your web server error logs for detailed error information:
```bash
# Apache
tail -f /var/log/apache2/error.log

# Nginx
tail -f /var/log/nginx/error.log
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review the troubleshooting section

## Version History

- **v1.0.0** - Initial release with core functionality
- Features: Room management, tenant portal, payment system, maintenance tracking

---

**Note**: Remember to change default credentials and configure proper security settings before deploying to production.
