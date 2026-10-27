# STAF PLM — Product Lifecycle Management System

This project contains the complete **PLM (Product Lifecycle Management) Styles Workspace** ported from OPMA ERP with full enterprise architecture.

---

## 🚀 Key Modules & Architecture

### 1. PLM Styles Workspace (`/styles`)
* **Styles Master**: DataTables-driven styles listing with live image previews, dynamic stage badges, and progress tracking.
* **Style Create & Edit**: Dynamic forms with automated style number handling, dropdown masters, and multi-image uploads.
* **PLM 10-Tab Interactive Workspace (`/styles/{id}`)**:
  1. **Overview**: Summary cards, high-level status, dates, and primary specification details.
  2. **Tech Pack**: Fit specifications, stitching, label/branding, care instructions, and construction notes.
  3. **Bill of Materials (BOM)**: Component breakdown (Fabric, Rib, Trims, Labels, etc.), consumption, unit costs, and total calculations.
  4. **Measurements Chart**: Grading chart (XS through 6XL), tolerances, and points of measurement.
  5. **Colorways**: Multi-color palettes integrated with **Pantone Service** (over 2,600+ TCX colors and hex swatches).
  6. **Samples Timeline**: Tracking sample iterations (Proto, Fit 1, Fit 2, PP, TOP) with statuses and approval dates.
  7. **Artwork & Prints**: Artwork codes, print techniques (Screen Print, Embroidery, Heat Transfer), placements, and dimensions.
  8. **Costing**: Direct material costs, manufacturing costs, overheads, margins, and target prices.
  9. **Documents & Files**: Multi-category file management (Tech Packs, Specs, Artwork).
  10. **Approvals**: Gate approval tracking with sign-off statuses.
  11. **History & Revisions**: Audit revisions and comments history.

### 2. Master Data Management (`/masters`)
* **Colors Master** (`/masters/colors`)
* **Sizes Master** (`/masters/sizes`)
* **Scales Master** (`/masters/scales`)
* **Design Positions Master** (`/masters/design-positions`)
* **Clients Master** (`/clients`)

---

## 🛠️ Database Setup (When Ready)

1. Open `.env` and set your database connection details:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_db_name
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

2. Run the clean migrations:
   ```bash
   php artisan migrate
   ```

3. Seed the default master data, admin accounts, and demo style:
   ```bash
   php artisan db:seed
   ```

### 🔑 Default Login Credentials
* **Super Admin**: `superadmin@gmail.com` | Password: `admin@4321`
* **Admin**: `admin@gmail.com` | Password: `admin@123`

---

## 💻 Running the Application

```bash
# Start Laravel Development Server
php artisan serve

# Run Vite Asset Bundler (if modifying JS/CSS)
npm run dev
```

Navigate to: `http://localhost:8000/styles`