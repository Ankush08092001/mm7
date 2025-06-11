#!/usr/bin/env python3
"""
Database initialization script for Study Materials API
"""

import sqlite3
from datetime import datetime

DATABASE = 'study_materials.db'

def init_database():
    """Initialize the database with sample data"""
    conn = sqlite3.connect(DATABASE)
    cursor = conn.cursor()
    
    # Create written_materials table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS written_materials (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            subject TEXT NOT NULL,
            topic TEXT,
            author TEXT NOT NULL,
            upload_date TEXT NOT NULL,
            file_path TEXT NOT NULL,
            pages INTEGER,
            downloads INTEGER DEFAULT 0
        )
    ''')
    
    # Create orals_materials table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS orals_materials (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question_type TEXT NOT NULL,
            function TEXT NOT NULL,
            topic TEXT,
            author TEXT NOT NULL,
            upload_date TEXT NOT NULL,
            file_path TEXT NOT NULL,
            downloads INTEGER DEFAULT 0
        )
    ''')
    
    # Insert sample written materials
    written_materials = [
        ("Marine Engineering Fundamentals", "MEK-G", "Engine Room Operations", "MarineMonks", "2024-01-15", "uploads/sample_written_1.pdf", 245, 1250),
        ("Auxiliary Machinery Operations", "MEK-M", "Machinery Operations", "Samraj", "2024-02-10", "uploads/sample_written_2.pdf", 320, 1560),
        ("Marine Propulsion Systems", "MEP", "Propulsion", "Dieselship", "2024-01-20", "uploads/sample_written_3.pdf", 275, 980),
        ("Electrical Systems and Controls", "MET", "Electrical Engineering", "Ankush Notes", "2024-02-05", "uploads/sample_written_4.pdf", 210, 1120),
        ("Naval Architecture Basics", "NAVAL", "Ship Design", "MarineMonks", "2024-01-25", "uploads/sample_written_5.pdf", 180, 890),
        ("Safety and Emergency Procedures", "SSEP", "Safety Management", "Samraj", "2024-02-15", "uploads/sample_written_6.pdf", 195, 1340)
    ]
    
    cursor.executemany('''
        INSERT OR IGNORE INTO written_materials 
        (title, subject, topic, author, upload_date, file_path, pages, downloads)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ''', written_materials)
    
    # Insert sample orals materials
    orals_materials = [
        ("Emergency Response Questions", "Function 3", "Emergency Situations", "MarineMonks", "2024-01-18", "uploads/sample_orals_1.pdf", 150),
        ("Engine Room Operations", "Function 4B", "System Failures", "Samraj", "2024-02-08", "uploads/sample_orals_2.pdf", 200),
        ("Electrical System Troubleshooting", "Function 5", "System Failures", "Dieselship", "2024-01-22", "uploads/sample_orals_3.pdf", 180),
        ("Maintenance Procedures", "Function 6", "Sketch-based", "Ankush Notes", "2024-02-12", "uploads/sample_orals_4.pdf", 220),
        ("Ship Handling in Emergency", "Function 3", "Emergency Situations", "MarineMonks", "2024-01-30", "uploads/sample_orals_5.pdf", 160),
        ("Power Plant Operations", "Function 4B", "Sketch-based", "Samraj", "2024-02-18", "uploads/sample_orals_6.pdf", 190)
    ]
    
    cursor.executemany('''
        INSERT OR IGNORE INTO orals_materials 
        (question_type, function, topic, author, upload_date, file_path, downloads)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ''', orals_materials)
    
    conn.commit()
    conn.close()
    
    print("Database initialized successfully with sample data!")

if __name__ == '__main__':
    init_database()

