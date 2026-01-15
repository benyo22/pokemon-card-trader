> 🇬🇧 English version below

# 🎴 Pokémon Card Market

[![PHP](https://img.shields.io/badge/Language-PHP_8.x-purple)](https://www.php.net/)
[![Storage](https://img.shields.io/badge/Data-JSON_Storage-yellow)](https://www.json.org/)
[![Course](https://img.shields.io/badge/ELTE-Webprogramozás-darkred)](https://www.elte.hu/)

Ez a projekt az **ELTE IK Webprogramozás** kurzusának szerveroldali beadandó feladata. Az alkalmazás egy online piactér, ahol a felhasználók regisztráció után Pokémon kártyákat adhatnak el és vásárolhatnak meg, adminisztrátori felügyelet mellett.

---

### 🎮 Funkciók

- **Autentikáció**
  - Regisztráció (`register.php`)
  - Bejelentkezés (`login.php`)
  - Jogosultságkezelés (felhasználó / admin)

- **Piactér**
  - Kártyák böngészése (`home.php`)
  - Kártya részletek (`details.php`)
  - Vásárlás virtuális egyenlegből (`buycard.php`)
  - Saját kártyák eladása (`sell.php`)

- **Felhasználói fiók**
  - Profil és gyűjtemény megtekintése (`userdetails.php`)

- **Admin funkciók**
  - Új kártya létrehozása (`newcard.php`)
  - Kártyák szerkesztése (`editcard.php`)

### 📂 Könyvtárszerkezet
  - **`storage/`** – JSON alapú adattárolás (`users.json`, `cards.json`) és kezelőosztályok  
  - **`styles/`** – Oldalspecifikus CSS fájlok  
  - **Gyökérkönyvtár** – PHP vezérlők és nézetek

### 🛠️ Technológiák
* **Backend:** Natív PHP (keretrendszer nélkül).
* **Adattárolás:** Fájl alapú adattárolás (JSON).
* **Frontend:** HTML5, CSS3.

### 🚀 Telepítés és Futtatás
Mivel ez egy PHP projekt, webszerverre van szükség a futtatáshoz.

Klónozd a repót:
   ```bash
   git clone https://github.com/benyo22/pokemon-card-trader.git
   ```

#### Opció 1: PHP beépített fejlesztői szerver (Ajánlott)

1. Ellenőrizd, hogy a PHP telepítve van:
     ```bash
     php -v
     ```
2. Navigálj a projekt gyökérkönyvtárába:
    ```bash
     cd pokemon-card-trader
     ```
3. Indítsd el a PHP szervert:
    ```bash
    php -S localhost:8000
     ```
4. Böngészőben nyisd meg:
    ```bash
     http://localhost:8000/home.php
     ```

#### Opció 2: XAMPP / WAMP / MAMP

1. Telepíts egy lokális szervert (pl. **XAMPP**, WAMP, MAMP).
2. Másold a projekt mappáját a szerver `htdocs` (vagy `www`) könyvtárába.
3. Indítsd el az Apache szervert.
4. Nyisd meg a böngészőben: `http://localhost/projekt-neve/home.php`

---

## English Version

# PokéTrader

A server-side web programming assignment designed to simulate a Pokémon card trading platform. Users can manage their collections, trade cards, and administrators can manage the global card database.

### ✨ Features
Based on the file structure provided:

* **Authentication:** Secure Registration (`register.php`) and Login (`login.php`) system handling user sessions (`auth.php`).
* **Marketplace:**
    * Browse available cards on the homepage (`home.php`).
    * View card details (`details.php`).
    * Buy cards using virtual currency (`buycard.php`).
    * Sell cards from personal collection (`sell.php`).
* **User Profile:**
    * View personal inventory and details (`userdetails.php`).
* **Admin Panel:**
    * Create new cards (`newcard.php`).
    * Edit existing card attributes (`editcard.php`).
    * *Note: Only accessible users with admin privileges.*

### 📂 File Structure Overview
* **`storage/`**: Handles data persistence using JSON files (`users.json`, `cards.json`) and helper classes for CRUD operations.
* **`styles/`**: Contains modular CSS files for specific pages (e.g., `details.css`, `register_login.css`).
* **Root**: Contains the main PHP entry points and logic files.

### 🛠️ Tech Stack
* **Language:** PHP 8.x
* **Database:** JSON (File-based storage)
* **Styling:** Custom CSS

### 🚀 How to Run

Clone the repository:
   ```bash
   git clone https://github.com/benyo22/pokemon-card-trader.git
   ```

#### Option 1: PHP Built-in Development Server (Recommended)

1. Check PHP installation:
     ```bash
     php -v
     ```
2. Navigate to the project root:
    ```bash
     cd pokemon-card-trader
     ```
3. Start the PHP server:
    ```bash
    php -S localhost:8000
     ```
4. Open in browser:
    ```bash
     http://localhost:8000/home.php
     ```

#### Option 2: XAMPP / WAMP / MAMP

1. Install a local web server environment like **XAMPP**, WAMP, or Docker.
2. Copy the project files into the web server's root directory (e.g., `htdocs`).
3. Start the Apache module.
4. Navigate to `http://localhost/pokemon-card-trader/home.php` in your browser.
