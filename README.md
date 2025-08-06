# Sales Orders Management System

A comprehensive sales orders management platform built with CodeIgniter 4, featuring modular architecture and modern web technologies.

## 🚀 Features

### Core Functionality
- **Sales Orders Management**: Complete CRUD operations for sales orders
- **Client & Contact Management**: Manage clients and their associated contacts
- **Services Catalog**: Maintain services with client-specific and general offerings
- **Real-time Dashboard**: Live updates with order statistics and charts
- **Order Tracking**: Comprehensive status tracking and history
- **PDF Generation**: Professional order documents with QR codes

### Advanced Features
- **Real-time Chat System**: WebSocket-based communication using Ratchet
- **Modular Architecture**: Clean separation with independent modules
- **Staff Roles & Permissions**: Granular access control system
- **Multi-language Support**: English, Spanish, and Portuguese
- **Contact Groups & Invitations**: Organize contacts efficiently
- **Internal Notes System**: Track internal communications
- **Email & SMS Integration**: Automated notifications

### Technical Features
- **Modern UI**: Bootstrap 5 with Velzon theme
- **Interactive Charts**: ApexCharts integration for analytics
- **QR Code Generation**: Automatic QR codes for orders
- **AJAX-powered**: Seamless user experience
- **Security**: CodeIgniter Shield for authentication
- **Database**: MySQL/MariaDB support

## 🛠 Technology Stack

- **Backend**: CodeIgniter 4 (PHP 8.1+)
- **Frontend**: Bootstrap 5, jQuery, ApexCharts
- **Database**: MySQL/MariaDB
- **WebSocket**: Ratchet for real-time features
- **Authentication**: CodeIgniter Shield
- **PDF**: TCPDF for document generation
- **Styling**: SCSS, modern CSS3

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js (for asset compilation)
- Apache/Nginx web server

## 🔧 Installation

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/sales-orders-system.git
cd sales-orders-system
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp env.example .env
```

Edit `.env` file with your database credentials:
```env
database.default.hostname = localhost
database.default.database = your_database_name
database.default.username = your_db_username
database.default.password = your_db_password
database.default.DBDriver = MySQLi
```

### 4. Database Setup
```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

### 5. Set Permissions
```bash
chmod -R 777 writable/
```

### 6. Build Assets (Optional)
```bash
npm run build
```

## 🚀 Deployment

### Production Setup
1. Upload files to your hosting provider
2. Set up environment variables
3. Run database migrations
4. Configure web server (Apache/Nginx)
5. Set proper file permissions

### SiteGround Deployment
This project is optimized for SiteGround hosting with Git deployment support.

## 📂 Project Structure

```
app/
├── Modules/
│   └── SalesOrders/          # Sales Orders Module
│       ├── Controllers/
│       ├── Models/
│       ├── Views/
│       └── Config/
├── Controllers/              # Main Controllers
├── Models/                   # Data Models
├── Views/                    # View Templates
└── Config/                   # Configuration Files

assets/                       # Frontend Assets
├── css/
├── js/
├── images/
└── libs/

vendor/                       # Composer Dependencies
writable/                     # Writable Files
```

## 🔑 Key Modules

### Sales Orders Module
- **Location**: `app/Modules/SalesOrders/`
- **Features**: Complete order management with modular architecture
- **Views**: Automatic view copying system for CodeIgniter compatibility

### Authentication
- **System**: CodeIgniter Shield
- **Features**: User roles, permissions, secure authentication

### Real-time Features
- **Chat System**: WebSocket implementation
- **Live Updates**: Real-time order status updates

## 🎨 UI Components

- **Dashboard**: Interactive widgets and charts
- **Order Management**: CRUD operations with modal forms
- **Data Tables**: Advanced filtering and pagination
- **Responsive Design**: Mobile-first approach
- **Modern Styling**: Clean, professional interface

## 🔒 Security Features

- **CSRF Protection**: Built-in CodeIgniter security
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: Input sanitization
- **Authentication**: Secure user sessions
- **Role-based Access**: Granular permissions

## 📊 Analytics & Reporting

- **Dashboard Metrics**: Real-time statistics
- **Performance Charts**: Visual data representation
- **Order Analytics**: Detailed reporting system
- **Client Insights**: Customer behavior tracking

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Support

- **Documentation**: Check the `docs/` folder for detailed guides
- **Issues**: Report bugs via GitHub Issues
- **Contact**: [Your contact information]

## 🎯 Roadmap

- [ ] API REST implementation
- [ ] Mobile app integration
- [ ] Advanced reporting dashboard
- [ ] Multi-tenant support
- [ ] Integration with third-party services

---

**Built with ❤️ using CodeIgniter 4**
