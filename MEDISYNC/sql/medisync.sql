-- ============================================================
--  MediSync — Medical Test Booking System
--  Run once in phpMyAdmin to set up all tables + seed data
-- ============================================================

CREATE DATABASE IF NOT EXISTS medisync CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE medisync;

-- Users table for patient authentication
CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150) NOT NULL,
    phone           VARCHAR(20) NOT NULL UNIQUE,
    age             INT,
    password        VARCHAR(255) NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Admin table for hospital staff
CREATE TABLE IF NOT EXISTS admins (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(100) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (username: Romail, password: CSE299)
INSERT INTO admins (username, password) VALUES ('Romail', 'CSE299');

CREATE TABLE IF NOT EXISTS categories (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL,
    icon  VARCHAR(10)  NOT NULL DEFAULT '🔬',
    color VARCHAR(20)  DEFAULT '#006d6d'
);

INSERT INTO categories (name, icon, color) VALUES
('General',        '🩺', '#2196F3'),
('Heart',          '❤️',  '#E53935'),
('Liver',          '🫀', '#FB8C00'),
('Kidney',         '🫘', '#8E24AA'),
('Women\'s Health','🌸', '#E91E8C'),
('ENT',            '👂', '#00897B');

CREATE TABLE IF NOT EXISTS tests (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    category_id   INT NOT NULL,
    name          VARCHAR(200) NOT NULL,
    description   TEXT,
    preparation   TEXT,
    procedure_txt TEXT,
    duration_min  INT DEFAULT 30,
    price         DECIMAL(10,2) NOT NULL,
    room          VARCHAR(50),
    floor         VARCHAR(50),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO tests (category_id, name, description, preparation, procedure_txt, duration_min, price, room, floor) VALUES
(1,'Complete Blood Count (CBC)',
 'Measures red blood cells, white blood cells, and platelets to detect infections, anaemia, and blood disorders. It is one of the most common tests ordered by doctors.',
 'No special preparation needed. You may eat and drink normally before the test.',
 'A small amount of blood is drawn from a vein in your arm using a needle. The sample is sent to the lab where machines count the different blood cells. Results are usually available within a few hours.',
 20, 350.00, 'Room G-04', 'Ground Floor'),

(1,'Blood Glucose (Fasting)',
 'Checks blood sugar levels to screen for diabetes or prediabetes. High glucose levels may indicate diabetes mellitus.',
 'Fast for 8–10 hours before the test. You may drink plain water. Avoid sugary drinks and food.',
 'Blood is collected from a vein in your arm after the fasting period. The sample is analysed in the lab. The process takes about 5 minutes and results are available within 2 hours.',
 15, 150.00, 'Room G-06', 'Ground Floor'),

(1,'Urinalysis',
 'Examines urine for infections, kidney disease, or diabetes markers. It checks colour, clarity, chemical content, and microscopic elements.',
 'Collect a clean midstream urine sample in the sterile container provided at the lab reception.',
 'You are given a sterile container. Collect a midstream urine sample. The lab technician tests it using dipstick and microscopy. Results in 1–2 hours.',
 15, 200.00, 'Room G-08', 'Ground Floor'),

(1,'ESR',
 'Erythrocyte Sedimentation Rate detects inflammation in the body. Useful for diagnosing autoimmune conditions and monitoring inflammatory diseases.',
 'No fasting required. Inform the doctor of any medications you are taking.',
 'A blood sample is drawn from a vein. The rate at which red blood cells settle in a tube is measured over 1 hour. A higher rate indicates inflammation.',
 20, 180.00, 'Room G-10', 'Ground Floor'),

(2,'ECG (Electrocardiogram)',
 'Records electrical signals in the heart to detect irregularities, arrhythmias, or evidence of a heart attack. Essential for heart health assessment.',
 'Avoid applying lotion, oil, or powder to your chest on the day of the test. Wear comfortable, loose clothing.',
 'Small adhesive electrodes are placed on your chest, arms, and legs. They record electrical impulses from the heart for about 5–10 minutes. The test is completely painless.',
 25, 500.00, 'Room C-12', '2nd Floor'),

(2,'Echocardiogram',
 'Uses ultrasound waves to produce live images of your heart, showing its structure, valve function, and pumping efficiency.',
 'No special preparation required. Wear comfortable, two-piece clothing for easy access to your chest.',
 'A technician applies gel to your chest and moves a transducer (probe) across it. Sound waves create images on a screen. The procedure takes about 40 minutes and is painless.',
 45, 2500.00, 'Room C-15', '2nd Floor'),

(2,'Lipid Profile',
 'Measures total cholesterol, HDL, LDL, and triglycerides. Used to assess the risk of cardiovascular disease and heart attack.',
 'Fast for 9–12 hours before the test. Avoid alcohol for 24 hours. You may drink plain water.',
 'Blood is drawn from a vein in your arm. The sample is analysed in the laboratory for different cholesterol and fat levels. Results are ready within 4–6 hours.',
 20, 600.00, 'Room C-08', '2nd Floor'),

(3,'Liver Function Test (LFT)',
 'Checks levels of enzymes, proteins, and bilirubin produced by the liver. Used to detect liver disease, damage, or dysfunction.',
 'Fast for at least 4 hours before the test. Avoid alcohol for 24 hours beforehand.',
 'A blood sample is drawn from a vein in the arm. The lab analyses levels of ALT, AST, ALP, GGT, albumin, and bilirubin. Results are available within a few hours.',
 20, 700.00, 'Room L-02', '3rd Floor'),

(3,'Hepatitis B Surface Antigen',
 'Detects the presence of the Hepatitis B virus (HBsAg) in the blood. A positive result indicates active Hepatitis B infection.',
 'No special preparation or fasting required.',
 'Blood is collected from a vein. The lab uses an immunoassay test to detect the Hepatitis B surface antigen. Results are typically available within 24 hours.',
 20, 800.00, 'Room L-04', '3rd Floor'),

(3,'Ultrasound — Abdomen',
 'Imaging scan to visualise the liver, gallbladder, spleen, kidneys, and other abdominal organs for abnormalities.',
 'Fast for 6 hours before the scan. Drink 4–6 glasses of water 1 hour before and do not urinate until after the scan.',
 'You lie on a bed and the sonographer applies gel to your abdomen. A probe is moved over the skin to capture sound wave images. The procedure is painless and takes 20–30 minutes.',
 40, 1200.00, 'Room L-09', '3rd Floor'),

(4,'Renal Function Test (RFT)',
 'Evaluates blood urea, creatinine, uric acid, and electrolytes to measure how well the kidneys are working.',
 'Stay well-hydrated. No special fasting required. Inform the doctor of any medications.',
 'A blood sample is collected from a vein. The lab measures creatinine, urea, sodium, potassium, and other markers. Results in 4–6 hours.',
 20, 650.00, 'Room K-03', '4th Floor'),

(4,'Urine Culture & Sensitivity',
 'Identifies the specific bacteria causing a urinary tract infection (UTI) and determines which antibiotics will be most effective.',
 'Collect a clean-catch midstream urine sample in the sterile container provided. Do not touch the inside of the container.',
 'The urine sample is placed on a culture medium in the lab and incubated for 24–48 hours to allow bacteria to grow. Sensitivity testing follows to determine antibiotic effectiveness.',
 30, 900.00, 'Room K-07', '4th Floor'),

(5,'Pap Smear',
 'Screens for cervical cancer and HPV by collecting cells from the cervix. Recommended for women aged 21 and above every 3 years.',
 'Schedule the test mid-cycle (not during menstruation). Avoid intercourse, douching, or vaginal products for 2 days before.',
 'You lie on an examination table. The doctor uses a speculum to gently open the vaginal walls and collects cells from the cervix with a soft brush. The sample is sent to the lab. The procedure takes about 5 minutes.',
 30, 1500.00, 'Room W-01', '5th Floor'),

(5,'Ultrasound — Pelvis',
 'Examines the uterus, ovaries, and fallopian tubes using sound waves. Used to detect cysts, fibroids, or other abnormalities.',
 'Drink 4–6 glasses of water 1 hour before the scan and do not urinate until after the procedure.',
 'You lie on a bed and gel is applied to your lower abdomen. A transducer probe is moved over the skin to create images. The procedure is painless and takes about 20–30 minutes.',
 40, 1400.00, 'Room W-03', '5th Floor'),

(5,'Hormonal Panel',
 'Assesses FSH, LH, and Estradiol hormone levels to evaluate fertility, menstrual irregularities, or the onset of menopause.',
 'Ideally done on day 2 or 3 of your menstrual cycle. Fast for 4 hours before. Avoid strenuous exercise the day before.',
 'Blood is drawn from a vein. The sample is analysed in the lab for specific hormone concentrations. Results are usually available within 24 hours.',
 25, 2200.00, 'Room W-05', '5th Floor'),

(6,'Audiometry',
 'Measures your hearing ability across different sound frequencies to detect hearing loss and determine its severity and type.',
 'Avoid loud noise environments for at least 16 hours before the test.',
 'You sit in a soundproof booth wearing headphones. The audiologist plays tones at different volumes and frequencies. You press a button each time you hear a sound. The test takes 20–30 minutes.',
 30, 800.00, 'Room E-02', '1st Floor'),

(6,'Nasal Endoscopy',
 'A thin flexible scope with a camera is used to examine the nasal passages, sinuses, and back of the throat for polyps or blockages.',
 'Inform the doctor of any blood-thinning medications. Do not eat or drink for 2 hours before the procedure.',
 'A local anaesthetic spray is applied to the nasal passages. The endoscope is gently inserted through one nostril and advanced to visualise structures. The procedure takes 10–15 minutes.',
 45, 1800.00, 'Room E-06', '1st Floor'),

(6,'Throat Swab Culture',
 'A swab sample from the throat is cultured in the lab to identify bacterial or viral infections such as strep throat or tonsillitis.',
 'Do not eat, drink, or use mouthwash for at least 1 hour before the swab is taken.',
 'The doctor uses a sterile swab to collect a sample from the back of your throat and tonsils. The swab is sent to the lab for culture. Results are available in 24–48 hours.',
 20, 550.00, 'Room E-04', '1st Floor');

CREATE TABLE IF NOT EXISTS bookings (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT,
    patient_name    VARCHAR(150) NOT NULL,
    patient_phone   VARCHAR(20),
    payment_method  VARCHAR(50),
    payment_number  VARCHAR(50),
    total_amount    DECIMAL(10,2),
    status          ENUM('pending','paid','cancelled','completed') DEFAULT 'pending',
    txn_ref         VARCHAR(100),
    token_number    VARCHAR(20),
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS booking_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    booking_id  INT NOT NULL,
    test_id     INT NOT NULL,
    price       DECIMAL(10,2),
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (test_id)    REFERENCES tests(id)
);

-- ============================================================
--  MediSync v2 — Admin Order Management Dashboard migration
--  Run this block if the database already exists from a prior
--  install (adds 'completed' to the status ENUM safely).
-- ============================================================
ALTER TABLE bookings
  MODIFY COLUMN status ENUM('pending', 'paid', 'cancelled', 'completed') DEFAULT 'pending';
