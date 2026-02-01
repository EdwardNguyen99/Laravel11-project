# Phân tích Database

## 1. Nhóm quản trị người dùng

### 1. Nhóm người dùng (Phân quyền)
```sql
CREATE TABLE `groups` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `permissions` TEXT, -- Lưu JSON hoặc danh sách các quyền
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 2. Quản trị viên (Admin/Staff)
```sql
CREATE TABLE `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `group_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`)
);
```

### 3. Học viên
```sql
CREATE TABLE `students` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `phone` VARCHAR(20),
    `password` VARCHAR(255) NOT NULL,
    `address` VARCHAR(200),
    `status` TINYINT(1) DEFAULT 1, -- 1: Active, 0: Locked
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 4. Giảng viên
```sql
CREATE TABLE `teachers` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) UNIQUE,
    `description` TEXT,
    `exp` VARCHAR(100), -- Kinh nghiệm chuyên môn
    `image` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 2. Nhóm Khóa học & Bài giảng

### 5. Danh mục khóa học
```sql
CREATE TABLE `categories` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) UNIQUE,
    `parent_id` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 6. Khóa học
```sql
CREATE TABLE `courses` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE,
    `detail` LONGTEXT,
    `teacher_id` INT,
    `price` DECIMAL(15,2) DEFAULT 0, -- Dùng Decimal cho tiền tệ
    `sale_price` DECIMAL(15,2) DEFAULT 0,
    `code` VARCHAR(100) UNIQUE,
    `duration` VARCHAR(100), -- Tổng thời lượng hiển thị
    `is_document` TINYINT(1) DEFAULT 0,
    `supports` TEXT,
    `status` TINYINT(1) DEFAULT 1, -- 1: Đang bán, 0: Ngừng bán
    `thumbnail` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`)
);
```

### 7. Trung gian Danh mục - Khóa học (Một khóa có thể thuộc nhiều danh mục)
```sql
CREATE TABLE `categories_courses` (
    `category_id` INT,
    `course_id` INT,
    PRIMARY KEY (`category_id`, `course_id`),
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
);
```

### 8. Bài giảng (Tích hợp Video/Document link vào đây cho gọn)
```sql
CREATE TABLE `lessons` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `course_id` INT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255),
    `content_type` ENUM('video', 'document', 'quiz') DEFAULT 'video',
    `video_url` VARCHAR(255), -- Link trực tiếp hoặc ID từ bảng video
    `document_url` VARCHAR(255),
    `is_trial` TINYINT(1) DEFAULT 0, -- Học thử
    `position` INT DEFAULT 0, -- Thứ tự bài học
    `duration` INT DEFAULT 0, -- Thời lượng (giây)
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
);
```

## 3. Nhóm Tiến độ & Tương tác

### 9. Tiến độ học tập
```sql
CREATE TABLE `student_progress` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `student_id` INT,
    `lesson_id` INT,
    `is_completed` TINYINT(1) DEFAULT 0,
    `last_watched_second` INT DEFAULT 0,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`),
    FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`)
);
```

### 10. Đánh giá khóa học
```sql
CREATE TABLE `reviews` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `course_id` INT,
    `student_id` INT,
    `rating` TINYINT CHECK (rating BETWEEN 1 AND 5),
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`),
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`)
);
```

## 4. Nhóm Đơn hàng & Thanh toán

### 11. Trạng thái đơn hàng
```sql
CREATE TABLE `order_status` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) -- Ví dụ: Chờ thanh toán, Thành công, Đã hủy
);
```

### 12. Đơn hàng
```sql
CREATE TABLE `orders` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `student_id` INT,
    `total_price` DECIMAL(15,2),
    `payment_method` VARCHAR(50), -- MOMO, VNPAY, Bank Transfer
    `status_id` INT,
    `transaction_id` VARCHAR(100), -- Mã giao dịch từ cổng thanh toán
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`),
    FOREIGN KEY (`status_id`) REFERENCES `order_status`(`id`)
);
```

### 13. Chi tiết đơn hàng
```sql
CREATE TABLE `order_details` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `order_id` INT,
    `course_id` INT,
    `price` DECIMAL(15,2),
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`)
);
```

### 14. Quyền truy cập khóa học (Sau khi thanh toán thành công)
```sql
CREATE TABLE `student_courses` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `student_id` INT,
    `course_id` INT,
    `expired_at` DATETIME NULL, -- NULL nếu học trọn đời
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`),
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`)
);
```

## 5. Nhóm Tin tức & Cấu hình

### 15. Tin tức (Gộp category vào đây nếu không quá phức tạp)
```sql
CREATE TABLE `posts` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE,
    `content` LONGTEXT,
    `thumbnail` VARCHAR(255),
    `status` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 16. Cấu hình hệ thống (Dùng để lưu Logo, Hotline, SEO...)
```sql
CREATE TABLE `settings` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `opt_key` VARCHAR(100) UNIQUE,
    `opt_value` TEXT
);
```