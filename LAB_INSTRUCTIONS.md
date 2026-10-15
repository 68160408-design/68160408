# Database Lab: Personal Resume Website with PHP + MySQL

## Learning Objectives
หลังจบแลป นิสิตสามารถ
1. เชื่อมต่อ PHP กับ MySQL ด้วย `mysqli` แบบ procedural ได้
2. สร้างฐานข้อมูลและตารางผ่าน phpMyAdmin ได้
3. เขียนคำสั่ง `SELECT` และนำข้อมูลจากฐานข้อมูลมาแสดงบนหน้าเว็บได้
4. ออกแบบฐานข้อมูลให้เหมาะกับข้อมูล Resume ของตนเองได้
5. เข้าใจความสัมพันธ์ระหว่างตารางด้วย Primary Key และ Foreign Key

## Scenario
ให้นิสิตสร้างเว็บไซต์ Resume ส่วนตัว โดยข้อมูลหลักเก็บอยู่ใน MySQL และใช้ PHP อ่านข้อมูลมาแสดงผล

เว็บไซต์ต้องประกอบด้วยอย่างน้อย
- รูปประจำตัว
- ชื่อและข้อมูลติดต่อ
- ข้อมูลแนะนำตัว
- ประวัติการศึกษา
- Skills / ความถนัด
- ประวัติการอบรม / Certificates

## Starter Files
ไฟล์ที่อาจารย์ให้ประกอบด้วย
- `index.php` หน้า Resume ตัวอย่าง
- `config/db.php` ไฟล์เชื่อมต่อฐานข้อมูล
- `assets/css/style.css` CSS responsive แบบง่าย
- `database/starter.sql` ฐานข้อมูลตั้งต้น
- `assets/images/student.jpg` ให้นิสิตเปลี่ยนเป็นรูปของตนเอง

## Part 1: Setup
1. เปิด Apache และ MySQL ใน XAMPP/MAMP
2. เปิด phpMyAdmin
3. Import ไฟล์ `database/starter.sql`
4. ตรวจสอบว่ามีฐานข้อมูลชื่อ `resume_lab`
5. นำโฟลเดอร์ `database_resume_lab` ไปไว้ใน `htdocs`
6. เปิด
   `http://localhost/database_resume_lab/`

## Part 2: Edit Your Personal Data
แก้ข้อมูลในตาราง `profile`
- full_name
- headline
- email
- phone
- address
- about_me
- photo

เปลี่ยนรูป `assets/images/student.jpg` เป็นรูปของตนเอง

## Part 3: Education
แก้ไขและเพิ่มข้อมูลในตาราง `education` อย่างน้อย 2 รายการ

จากนั้นตรวจสอบผลบนหน้าเว็บ

## Part 4: Database Design Task
นิสิตต้องออกแบบตารางเพิ่มอย่างน้อย 2 ตาราง

### Table 1: skill
ตารางต้องเก็บข้อมูลอย่างน้อย
- id
- profile_id
- skill_name
- skill_level

### Table 2: training
ตารางต้องเก็บข้อมูลอย่างน้อย
- id
- profile_id
- course_name
- organization
- training_year

ให้กำหนด
- Primary Key
- Foreign Key
- Data Type ที่เหมาะสม

## Part 5: PHP Coding Task
ในไฟล์ `index.php` ปัจจุบันส่วน Skills และ Training ยังใช้ Array ตัวอย่างใน PHP

ให้นิสิตแก้ไขให้
1. ใช้ `mysqli_query()` อ่านข้อมูลจาก MySQL
2. ใช้ `mysqli_fetch_assoc()` อ่านทีละแถว
3. นำข้อมูลมาแสดงด้วย `foreach` หรือ `while`

### Example Pattern
```php
$sql = "SELECT * FROM skill WHERE profile_id = 1";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['skill_name'];
}
```

## Part 6: Individual Design
ปรับแต่งหน้า Resume ของตนเองอย่างน้อย 3 จุด เช่น
- สี
- Layout
- Font size
- รูปแบบ Skill
- รูปแบบ Education
- เพิ่มส่วน Hobby / Project / Language

ถ้าเพิ่ม Section ใหม่ ต้องออกแบบตารางในฐานข้อมูลเพิ่มเอง

## Deliverables
ส่งเป็นไฟล์ ZIP ชื่อ
`studentid_resume_lab.zip`

ภายในต้องมี
1. Source code PHP
2. CSS
3. รูปตัวเอง
4. SQL Export ของฐานข้อมูลฉบับสมบูรณ์
5. Screenshot หน้า Resume ที่ทำเสร็จแล้ว 1 ภาพ

## Questions
ตอบคำถามสั้น ๆ ในไฟล์ `answer.txt`
1. Primary Key คืออะไร และใช้ในตารางใดบ้าง
2. Foreign Key มีประโยชน์อย่างไรในเว็บไซต์นี้
3. `mysqli_query()` ทำหน้าที่อะไร
4. `mysqli_fetch_assoc()` คืนค่าข้อมูลในรูปแบบใด
5. ถ้าต้องการเพิ่มประวัติการทำงาน ควรออกแบบตารางอย่างไร

## Suggested Score (20 points)
- Import / Database setup 3 คะแนน
- Profile + Education 4 คะแนน
- Database design: skill + training 5 คะแนน
- PHP reads data from MySQL correctly 4 คะแนน
- Responsive / Personal design 2 คะแนน
- SQL export + screenshot + answers 2 คะแนน
