# Scan2Eat: Food Ordering System

## Overview
**Scan2Eat** เป็นระบบสั่งอาหารที่พัฒนาขึ้นเพื่ออำนวยความสะดวกให้ลูกค้าสามารถเลือกเมนูอาหารและสั่งซื้อได้โดยการสแกน QR Code ที่โต๊ะ หลังจากเลือกเมนูและยืนยันคำสั่งซื้อแล้ว ลูกค้าสามารถชำระเงินผ่านแอปพลิเคชัน ระบบจะทำการอัปเดตสถานะโต๊ะให้อัตโนมัติเมื่อการชำระเงินเสร็จสิ้น และจำนวนโต๊ะว่างจะแสดงผลบนหน้าจอ OLED ที่เชื่อมต่อกับ ESP32

## Features
### 1. เลือกและสั่งซื้อเมนู
- ลูกค้าสามารถเลือกเมนูอาหารจากรายการในแอปพร้อมระบุจำนวน
- เมื่อเลือกเมนูเสร็จสิ้น จะสามารถยืนยันคำสั่งซื้อและส่งคำสั่งไปยังเซิร์ฟเวอร์

### 2. ระบบการชำระเงิน
- หลังจากยืนยันคำสั่งซื้อแล้ว จะมีหน้าสรุปยอดรวมที่ต้องชำระ
- ลูกค้าสามารถชำระเงินผ่านระบบ (ในอนาคตอาจมีการรองรับการชำระเงินออนไลน์)

### 3. การอัปเดตสถานะโต๊ะ
- หลังจากการชำระเงิน ระบบจะอัปเดตสถานะโต๊ะให้อัตโนมัติว่า "ว่าง" พร้อมทั้งส่งข้อมูลไปยังหน้าจอ OLED ผ่าน ESP32 เพื่อแสดงจำนวนโต๊ะที่ว่าง

### 4. ระบบ REST API เชื่อมต่อกับฐานข้อมูล
- มีการใช้ REST API สำหรับดึงข้อมูลและอัปเดตสถานะต่าง ๆ ในระบบ เช่น สถานะโต๊ะ เมนู และคำสั่งซื้อ

## Architecture and Technologies
### **1. Flutter**
- พัฒนาแอปพลิเคชันมือถือทั้งฝั่งลูกค้าและแอดมินเพื่อจัดการคำสั่งซื้อและสถานะโต๊ะ
- หน้าจอที่สำคัญได้แก่:
  - `menu_screen.dart`: แสดงรายการเมนู
  - `table_selection_screen.dart`: ลูกค้าเลือกโต๊ะ
  - `order_confirmation.dart`: หน้าสรุปคำสั่งซื้อ
  - `order_payment.dart`: หน้าสำหรับยืนยันการชำระเงิน

### **2. PHP Backend with REST API**
- พัฒนา API สำหรับเชื่อมต่อระหว่างฐานข้อมูลและแอปพลิเคชัน โดยใช้ PHP เป็นเซิร์ฟเวอร์
- ตัวอย่างไฟล์:
  - `table.php`: สำหรับดึงข้อมูลสถานะโต๊ะ
  - `order.php`: สำหรับส่งคำสั่งซื้อไปยังฐานข้อมูล
  - `menu.php`: สำหรับดึงข้อมูลเมนูอาหาร

### **3. ESP32 with OLED Display**
- ใช้ ESP32 เชื่อมต่อกับหน้าจอ OLED เพื่อตรวจสอบจำนวนโต๊ะว่าง
- ESP32 จะรับข้อมูลจาก API และแสดงจำนวนโต๊ะที่ว่างหลังการชำระเงินของลูกค้า

### **4. MySQL Database**
- ฐานข้อมูลใช้สำหรับเก็บข้อมูลเมนู โต๊ะ และคำสั่งซื้อ
- ชื่อฐานข้อมูล: `scan2eat`
- ตารางสำคัญ:
  - `tables`: เก็บข้อมูลโต๊ะ (id, status)
  - `orders`: เก็บข้อมูลคำสั่งซื้อ (id, table_id, total_price, status)
  - `menu`: เก็บข้อมูลเมนูอาหาร (id, name, price)

## Database Schema
```sql
-- Table: menu
CREATE TABLE `menu` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Table: tables
CREATE TABLE `tables` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Table: orders
CREATE TABLE `orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `table_id` INT NOT NULL,
  `total_price` DECIMAL(10, 2) NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

# Scan2Eat: Food Ordering System

## Installation Guide

### Prerequisites
- **Flutter**: ติดตั้ง Flutter SDK บนเครื่อง [Flutter installation guide](https://docs.flutter.dev/get-started/install)
- **PHP**: ติดตั้ง PHP เวอร์ชันล่าสุด
- **MySQL**: ติดตั้ง MySQL สำหรับจัดการฐานข้อมูล
- **Arduino IDE**: สำหรับการติดตั้งบอร์ด ESP32

### Step-by-Step Installation

1. **Clone the repository**:
    ```bash
    git clone https://github.com/Andrewillerr/scan2eat.git
    cd scan2eat
    ```

2. **Database Setup**:
   - สร้างฐานข้อมูลใน MySQL ด้วยชื่อ `scan2eat`
   - นำเข้า schema จากไฟล์ SQL ที่ให้มา หรือใช้คำสั่งใน Database Schema ด้านบน

3. **Backend Configuration**:
   - ตั้งค่าไฟล์ `config.php` ในโฟลเดอร์ backend เพื่อเชื่อมต่อกับฐานข้อมูล MySQL:
    ```php
    $host = "localhost";
    $db_name = "scan2eat";
    $username = "root";
    $password = "your_password";
    ```

4. **Install Flutter Dependencies**:
    ```bash
    flutter pub get
    ```

5. **Run the Flutter Application**:
    ```bash
    flutter run
    ```

6. **Setup ESP32**:
   - เปิดโปรแกรม Arduino IDE และติดตั้งบอร์ด ESP32
   - อัปโหลดโค้ด ESP32 ที่ใช้สำหรับการแสดงผลจำนวนโต๊ะว่างไปยังบอร์ด

## How to Use the System
1. **ลูกค้าสแกน QR Code**: เพื่อเข้าสู่ระบบสั่งอาหาร ลูกค้าจะสแกน QR Code ที่โต๊ะเพื่อเลือกโต๊ะของตน
2. **เลือกเมนูอาหาร**: เลือกเมนูอาหารที่ต้องการและระบุจำนวน
3. **ยืนยันคำสั่งซื้อ**: หลังจากเลือกรายการอาหารเสร็จแล้ว สามารถยืนยันคำสั่งซื้อได้
4. **ชำระเงิน**: ชำระเงินและรอการยืนยัน หลังจากชำระเงิน สถานะโต๊ะจะอัปเดตเป็นว่าง และแสดงผลบนหน้าจอ OLED

## API Endpoints Documentation

| Method | Endpoint         | Description                        |
|--------|------------------|------------------------------------|
| GET    | `/tables`         | ดึงข้อมูลสถานะของทุกโต๊ะ          |
| POST   | `/order`          | ส่งคำสั่งซื้อไปยังฐานข้อมูล       |
| PUT    | `/table/{id}`     | อัปเดตสถานะโต๊ะโดยใช้ ID         |
| GET    | `/menu`           | ดึงรายการเมนูทั้งหมด             |

## Screenshots
### 1. หน้าจอเลือกเมนูอาหาร
![Menu Screen](screenshots/menu_screen.png)

### 2. หน้าจอยืนยันคำสั่งซื้อ
![Order Confirmation Screen](screenshots/order_confirmation.png)

### 3. หน้าจอชำระเงิน
![Payment Screen](screenshots/payment_screen.png)

## Known Issues
- ยังไม่มีระบบแจ้งเตือนเมื่อคำสั่งซื้อไม่สำเร็จ
- ไม่รองรับการชำระเงินออนไลน์ ณ ขณะนี้

## Future Development
- การเพิ่มการชำระเงินผ่านบัตรเครดิตและช่องทางออนไลน์
- ระบบแจ้งเตือนอัตโนมัติเมื่อมีคำสั่งซื้อใหม่
- รองรับหลายภาษาสำหรับการใช้งานในต่างประเทศ

## Contributors
- Andrewillerr - Lead Developer
- Onny - Backend Developer
- Realoporjung - UI/UX Designer
- Peare - IoT (Internet of Things) Engineer

## License
MIT License

Copyright (c) 2024 [Andrewillerr]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

