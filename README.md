
## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/i0midris/profile.git
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** in `.env`
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=profile
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seed**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the server**
   ```bash
   php artisan serve
   ```

8. **Visit the site**
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/admin


