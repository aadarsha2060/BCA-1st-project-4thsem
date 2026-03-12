CREATE DATABASE IF NOT EXISTS system;
USE system;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) VALUES (
    'admin',
    MD5('yourpassword'),
    'admin'
);
CREATE TABLE diseases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    severity ENUM('low','medium','high') NOT NULL
);
-- newadmin123

-- ================================
-- TABLE: symptoms
-- ================================
CREATE TABLE symptoms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disease_id INT NOT NULL,
    symptom VARCHAR(100) NOT NULL,
    FOREIGN KEY (disease_id) REFERENCES diseases(id) ON DELETE CASCADE
);

-- ================================
-- INSERT DISEASES
-- ================================
INSERT INTO diseases (name, description, severity) VALUES
('Common Cold', 'A mild viral infection of the nose and throat.', 'low'),
('Influenza', 'A viral respiratory illness with fever and fatigue.', 'medium'),
('Migraine', 'A neurological condition causing severe headaches.', 'medium'),
('Food Poisoning', 'Illness caused by contaminated food.', 'medium'),
('Allergy', 'Immune reaction to allergens.', 'low'),
('COVID-19', 'A contagious respiratory disease caused by coronavirus.', 'high'),
('Dengue Fever', 'A mosquito-borne viral infection.', 'high'),
('Typhoid', 'Bacterial infection from contaminated food or water.', 'high'),
('Asthma', 'A condition affecting airways and breathing.', 'medium'),
('Pneumonia', 'Infection inflaming lung air sacs.', 'high'),
('Diabetes', 'A chronic disease affecting blood sugar.', 'medium'),
('Hypertension', 'High blood pressure condition.', 'medium'),
('Gastritis', 'Inflammation of stomach lining.', 'low'),
('Sinusitis', 'Inflammation of sinus cavities.', 'low'),
('Anemia', 'Low red blood cell count.', 'medium'),
('Tuberculosis', 'Serious lung infection.', 'high'),
('Bronchitis', 'Inflammation of bronchial tubes.', 'medium'),
('Cholera', 'Severe diarrheal disease.', 'high'),
('Malaria', 'Mosquito-borne febrile illness.', 'high'),
('Hepatitis A', 'Liver infection caused by virus.', 'medium'),
('Hepatitis B', 'Serious viral liver infection.', 'high'),
('Kidney Stones', 'Hard mineral deposits in kidneys.', 'medium'),
('Urinary Tract Infection', 'Infection in urinary system.', 'medium'),
('Depression', 'Mental health disorder.', 'medium'),
('Anxiety Disorder', 'Excessive worry and fear.', 'medium'),
('Arthritis', 'Joint inflammation and pain.', 'medium'),
('Back Pain', 'Pain affecting back muscles.', 'low'),
('Heat Stroke', 'Dangerous overheating condition.', 'high'),
('Hypothermia', 'Dangerously low body temperature.', 'high'),
('Eye Infection', 'Infection affecting eyes.', 'low');

-- ================================
-- INSERT SYMPTOMS
-- ================================
INSERT INTO symptoms (disease_id, symptom) VALUES
-- Common Cold
(1,'runny nose'),(1,'cough'),(1,'sneezing'),(1,'sore throat'),

-- Influenza
(2,'fever'),(2,'fatigue'),(2,'body pain'),(2,'cough'),

-- Migraine
(3,'headache'),(3,'nausea'),(3,'light sensitivity'),

-- Food Poisoning
(4,'vomiting'),(4,'diarrhea'),(4,'stomach pain'),

-- Allergy
(5,'itching'),(5,'rash'),(5,'sneezing'),

-- COVID-19
(6,'fever'),(6,'dry cough'),(6,'loss of taste'),(6,'loss of smell'),(6,'fatigue'),

-- Dengue
(7,'high fever'),(7,'joint pain'),(7,'muscle pain'),(7,'rash'),

-- Typhoid
(8,'high fever'),(8,'weakness'),(8,'abdominal pain'),

-- Asthma
(9,'shortness of breath'),(9,'wheezing'),(9,'chest tightness'),

-- Pneumonia
(10,'chest pain'),(10,'cough with phlegm'),(10,'fever'),

-- Diabetes
(11,'frequent urination'),(11,'excessive thirst'),(11,'fatigue'),

-- Hypertension
(12,'headache'),(12,'dizziness'),(12,'vision problems'),

-- Gastritis
(13,'stomach pain'),(13,'bloating'),(13,'nausea'),

-- Sinusitis
(14,'facial pain'),(14,'nasal congestion'),(14,'headache'),

-- Anemia
(15,'fatigue'),(15,'pale skin'),(15,'dizziness'),

-- Tuberculosis
(16,'chronic cough'),(16,'night sweats'),(16,'weight loss'),

-- Bronchitis
(17,'persistent cough'),(17,'mucus'),(17,'shortness of breath'),

-- Cholera
(18,'watery diarrhea'),(18,'dehydration'),(18,'vomiting'),

-- Malaria
(19,'high fever'),(19,'chills'),(19,'sweating'),

-- Hepatitis A
(20,'yellow skin'),(20,'dark urine'),(20,'fatigue'),

-- Hepatitis B
(21,'joint pain'),(21,'yellow eyes'),(21,'nausea'),

-- Kidney Stones
(22,'severe back pain'),(22,'painful urination'),(22,'blood in urine'),

-- UTI
(23,'burning urination'),(23,'frequent urination'),(23,'pelvic pain'),

-- Depression
(24,'persistent sadness'),(24,'loss of interest'),(24,'sleep problems'),

-- Anxiety
(25,'excessive worry'),(25,'rapid heartbeat'),(25,'restlessness'),

-- Arthritis
(26,'joint pain'),(26,'stiffness'),(26,'swelling'),

-- Back Pain
(27,'lower back pain'),(27,'muscle stiffness'),

-- Heat Stroke
(28,'high body temperature'),(28,'confusion'),(28,'hot skin'),

-- Hypothermia
(29,'shivering'),(29,'cold skin'),(29,'slurred speech'),

-- Eye Infection
(30,'red eyes'),(30,'itchy eyes'),(30,'eye discharge');


