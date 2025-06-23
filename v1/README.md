# HireUp - Comprehensive Recruitment & Employment Platform

<div align="center">
  <img src="./front%20office%20assets/images/HireUp_darkMode.png" alt="HireUp Logo" height="100">
</div>

## 🚀 Overview

**HireUp** is a modern, comprehensive recruitment and employment platform designed to revolutionize the way individuals and businesses connect. More than just a job board, HireUp serves as a catalyst for career growth and success, empowering individuals to realize their full potential and achieve their professional aspirations.

### Mission Statement
At HireUp, we're committed to creating a win-win-win situation: a win for us, a win for the user, and a win for society. By facilitating meaningful employment connections, we strive to foster mutual benefit, satisfaction, and positive societal impact.

## ✨ Key Features

### 🔍 **Job Search & Management**
- Advanced job search options with personalized recommendations
- Comprehensive job posting and management system
- Application tracking and status management
- Employer-candidate matching algorithms

### 👤 **Profile Management**
- Professional profile creation and management
- Skills and experience showcase
- Resume upload and management
- Profile verification system

### 💬 **Communication & Messaging**
- Integrated messaging system between employers and candidates
- Real-time notifications
- Meeting scheduling and management
- Video call integration

### 🤖 **AI-Powered Features**
- **HireUp ChatBot**: Advanced AI assistant for recruitment guidance
- Voice recognition and navigation
- Face recognition authentication
- Automated candidate screening

### 📊 **Analytics & Reporting**
- Comprehensive dashboard with statistics
- Application progress tracking
- Performance analytics
- Data visualization and insights

### 🔐 **Security & Authentication**
- Multi-factor authentication
- Secure data encryption
- Role-based access control
- Account verification system

### 🌐 **Additional Features**
- Multi-language support
- Mobile-responsive design
- Social media integration
- Payment processing (Stripe integration)
- PDF generation for reports and documents
- QR code generation
- Email notifications and templates

## 🛠️ Technology Stack

### **Backend**
- **PHP** - Core server-side language
- **MySQL** - Primary database
- **PDO** - Database abstraction layer

### **Frontend**
- **HTML5/CSS3** - Structure and styling
- **JavaScript** - Client-side functionality
- **Bootstrap** - Responsive framework
- **jQuery** - DOM manipulation
- **Font Awesome** - Icons

### **AI & Machine Learning**
- **Python** - AI/ML scripts
- **Ollama** - Local AI model management
- **Flet** - Python GUI framework
- **OpenCV** - Computer vision (face recognition)

### **Third-Party Integrations**
- **Google APIs** - Maps, authentication, calendar
- **Stripe** - Payment processing
- **Infobip** - SMS/communication services
- **Firebase JWT** - Token authentication
- **mPDF/DomPDF** - PDF generation
- **Endroid QR Code** - QR code generation

### **Development Tools**
- **Composer** - PHP dependency management
- **Git** - Version control
- **XAMPP** - Local development environment

## 📁 Project Structure

```
v1/
├── assets/                     # Static assets (CSS, JS, images)
├── Controller/                 # Business logic controllers
│   ├── phpmailer/             # Email functionality
│   ├── py_face_recognation/   # Face recognition scripts
│   ├── py_script/             # Python AI scripts
│   ├── vendor/                # Composer dependencies
│   └── *.php                  # Various controllers
├── Model/                     # Data models and entities
├── View/                      # User interface views
│   ├── back_office/           # Admin dashboard
│   └── front_office/          # Public-facing pages
├── front office assets/       # Frontend static files
├── config.php                 # Database configuration
├── index.php                  # Main entry point
└── *.php                      # Additional pages
```

### **Key Controllers**
- `user_con.php` - User management
- `JobC.php` - Job operations
- `profileController.php` - Profile management
- `messaging_con.php` - Communication system
- `applyController.php` - Application handling
- `notification_con.php` - Notifications

### **Key Models**
- `user.php` - User entity
- `jobModel.php` - Job entity
- `profileModel.php` - Profile entity
- `message.php` - Messaging entity
- `notification.php` - Notification entity

## 🚀 Installation & Setup

### Prerequisites
- **PHP 7.4+**
- **MySQL 5.7+**
- **Apache/Nginx** web server
- **Composer** for PHP dependencies
- **Python 3.8+** for AI features
- **XAMPP** (recommended for local development)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd hireup/v1
   ```

2. **Install PHP Dependencies**
   ```bash
   cd Controller
   composer install
   ```

3. **Database Setup**
   - Create a MySQL database named `hire_up`
   - Import the database schema (if available)
   - Update database credentials in `config.php`

4. **Configure Database Connection**
   ```php
   // config.php
   self::$pdo = new PDO(
       'mysql:host=localhost;dbname=hire_up',
       'your_username',
       'your_password'
   );
   ```

5. **Set Up Python Environment** (for AI features)
   ```bash
   pip install flet ollama opencv-python requests
   ```

6. **Configure Web Server**
   - Point document root to the `v1` directory
   - Ensure proper permissions for file uploads
   - Enable URL rewriting if needed

7. **Environment Configuration**
   - Update API keys for third-party services
   - Configure email settings in PHPMailer
   - Set up Stripe payment credentials

## 🗄️ Database Configuration

The application uses MySQL with the following connection settings:

```php
Host: localhost
Database: hire_up
Username: root (default)
Password: (empty by default)
```

Key database tables include:
- `users` - User accounts and authentication
- `profiles` - User profile information
- `jobs` - Job postings and details
- `applications` - Job applications
- `messages` - Communication between users
- `notifications` - System notifications

## 👥 Development Team

- **Nesrine Derouiche** - Jobs Manager
- **Karim Feki** - Profile Manager
- **Abidi Mohamed** - Users Manager
- **Salma Laifi** - Reports Manager
- **Amin Saadallah** - Team Member

## 🌍 Sustainable Development Goals

HireUp is committed to supporting the UN Sustainable Development Goals:

- **SDG 5**: Gender Equality - Promoting inclusive opportunities
- **SDG 8**: Decent Work and Economic Growth - Creating meaningful employment
- **SDG 10**: Reduced Inequality - Fostering diversity and inclusion

## 🔧 Configuration

### Environment Variables
Create a `.env` file for sensitive configurations:
```
DB_HOST=localhost
DB_NAME=hire_up
DB_USER=root
DB_PASS=

STRIPE_SECRET_KEY=your_stripe_secret
GOOGLE_API_KEY=your_google_api_key
INFOBIP_API_KEY=your_infobip_key
```

### File Permissions
Ensure proper permissions for:
- Upload directories
- Log files
- Cache directories
- Configuration files

## 📱 Features in Detail

### AI ChatBot
- Powered by Ollama with local AI models
- Supports multiple models (llava, llama2-uncensored, gemma)
- Provides 24/7 recruitment assistance
- Natural language processing for user queries

### Face Recognition
- OpenCV-based face detection and recognition
- Secure biometric authentication
- User enrollment and verification
- Privacy-focused implementation

### Voice Recognition
- Speech-to-text functionality
- Voice navigation capabilities
- Accessibility enhancement
- Multi-language support

## 🚀 Getting Started

1. **For Job Seekers:**
   - Create a profile
   - Upload your resume
   - Browse and apply for jobs
   - Track application status
   - Communicate with employers

2. **For Employers:**
   - Post job openings
   - Review applications
   - Schedule interviews
   - Manage hiring process
   - Access analytics

3. **For Administrators:**
   - Access admin dashboard
   - Manage users and content
   - View system analytics
   - Configure platform settings

## 📞 Contact & Support

- **Website**: [HireUp Platform](http://localhost/hireup/v1/)
- **Email**: contact@hireup.com
- **Phone**: +216 93 213 636
- **Hours**: Daily 9:00-20:00

## 📄 License

This project is proprietary software developed by the HireUp team. All rights reserved.

---

**HireUp** - Empowering careers, connecting opportunities, building futures. 🚀
