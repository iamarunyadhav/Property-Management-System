# **🏠 Property Management System API**  

## ** Introduction**  
This is a **Laravel-based API** for managing property rentals, tenant records, and rent distribution. It includes authentication, CRUD operations, and automated rent calculations for property owners.  

---

## **⚙️ System Requirements**  

Before setting up the project, ensure your system meets the following requirements:  

- **PHP**: 8.1 or higher  
- **Composer**: Latest version  
- **Laravel**: 10+  
- **Database**: MySQL  
- **XAMPP/WAMP**: For running Apache and MySQL locally  
- **Postman**: For API testing  

---

## **🛠️ Getting Started**  

These instructions will guide you through setting up and running the project on your local machine for development and testing purposes.  

### **📌 Prerequisites**  

Ensure you have the following installed:  

- **Git**  
- **PHP (>= 8.1)**  
- **Composer**  
- **MySQL** (or any relational database system)  

---

## **💚 Cloning the Repository**  

Clone this repository to your local machine and navigate into the project directory:  

```sh
git clone https://github.com/iamarunyadhav/Property-Management-System.git
cd property-management-system
```

---

## **⚙️ Project Setup**  

### **1⃣ Configure the Database**  

- Use any **SQL server** like **XAMPP** or **WAMP** (e.g., phpMyAdmin, MySQL Workbench).  
- Create a new database (e.g., **property_management**).  

### **2⃣ Set Up Environment Variables**  

Copy the `.env.example` file and rename it to `.env`:  
```sh
cp .env.example .env
```

Open `.env` and update the **database credentials**:  
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=property_management  # Replace with your database name
DB_USERNAME=root   # Your MySQL username
DB_PASSWORD=       # Your MySQL password (leave blank if no password)
```

---

### **3⃣ Install Dependencies**  
```sh
composer install
```

### **4⃣ Generate Application Key**  
```sh
php artisan key:generate
```

### **5⃣ Migrate the Database**  
```sh
php artisan migrate
```

### **6⃣ Seed Sample Data**  
```sh
php artisan db:seed
```

---

## **🚀 Running the Application**  

### **Start the Laravel Server**  
```sh
php artisan serve
```

By default, the application will be available at:  
**http://127.0.0.1:8000**  

I deployed to the server you can access via this endpoint
**https://api.property.arunyadhav.live/api**

---

## **🛠️ Running the API**  

### **1⃣ Import API Collection into Postman**  
- Inside the project, find the **`property_management.postman_collection.json`** file (located in the `documentation` folder).  
- Open **Postman** → Click **Import** → Select the JSON file.  
- This will import all API endpoints for testing.  

### **2⃣ Testing Endpoints**  
- Ensure the Laravel server is running.  
- Use Postman to send requests and check responses.  
- Example request to **fetch tenant rent details**:  
  ```http
  GET /api/tenants/42/rent
  ```
  Example Response:
  ```json
  {
    "success": true,
    "data": [
      {
        "name": "Johny",
        "rent_share": 5000
      }
    ]
  }
  ```

### **3⃣ Verify Request Parameters & Payloads**  
- Check all required parameters before executing API requests.  
- Use different tenant IDs to fetch respective rent distributions.  

---

## **📄 Useful Artisan Commands**  

| Command | Description |
|---------|-------------|
| `php artisan migrate:refresh` | Refresh migrations (drop & re-run) |
| `php artisan optimize:clear` | Clear cache, config, routes, and views |
| `php artisan cache:clear` | Clear application cache |

---

## **📝 API Documentation**  
For a detailed list of endpoints and usage instructions, refer to the Postman documentation:  
🔗 **[API Documentation](https://documenter.getpostman.com/view/24328222/2sAYdeLrnP)**  

---

## **👨‍💻 Author**  
**Arun Pragash Alwar**  
---

