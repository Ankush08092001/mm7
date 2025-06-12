from flask import Flask, request, jsonify, send_file
from flask_cors import CORS
import sqlite3
import os
from datetime import datetime
import uuid
from werkzeug.utils import secure_filename

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Configuration
UPLOAD_FOLDER = 'uploads'
ALLOWED_EXTENSIONS = {'pdf'}
DATABASE = 'study_materials.db'

app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Ensure upload directory exists
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def init_db():
    """Initialize the database with required tables"""
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
    
    conn.commit()
    conn.close()

def get_db_connection():
    """Get database connection"""
    conn = sqlite3.connect(DATABASE)
    conn.row_factory = sqlite3.Row
    return conn

# API Routes

@app.route('/api/written_materials', methods=['GET'])
def get_written_materials():
    """Get all written study materials with optional filtering"""
    conn = get_db_connection()
    
    # Build query with filters
    query = "SELECT * FROM written_materials WHERE 1=1"
    params = []
    
    if request.args.get('subject'):
        query += " AND subject = ?"
        params.append(request.args.get('subject'))
    
    if request.args.get('topic'):
        query += " AND topic = ?"
        params.append(request.args.get('topic'))
    
    if request.args.get('author'):
        query += " AND author = ?"
        params.append(request.args.get('author'))
    
    if request.args.get('search'):
        search_term = f"%{request.args.get('search')}%"
        query += " AND (title LIKE ? OR topic LIKE ?)"
        params.extend([search_term, search_term])
    
    query += " ORDER BY upload_date DESC"
    
    materials = conn.execute(query, params).fetchall()
    conn.close()
    
    return jsonify([dict(material) for material in materials])

@app.route('/api/orals_materials', methods=['GET'])
def get_orals_materials():
    """Get all orals study materials with optional filtering"""
    conn = get_db_connection()
    
    # Build query with filters
    query = "SELECT * FROM orals_materials WHERE 1=1"
    params = []
    
    if request.args.get('function'):
        query += " AND function = ?"
        params.append(request.args.get('function'))
    
    if request.args.get('topic'):
        query += " AND topic = ?"
        params.append(request.args.get('topic'))
    
    if request.args.get('author'):
        query += " AND author = ?"
        params.append(request.args.get('author'))
    
    if request.args.get('search'):
        search_term = f"%{request.args.get('search')}%"
        query += " AND (question_type LIKE ? OR topic LIKE ?)"
        params.extend([search_term, search_term])
    
    query += " ORDER BY upload_date DESC"
    
    materials = conn.execute(query, params).fetchall()
    conn.close()
    
    return jsonify([dict(material) for material in materials])

@app.route('/api/upload_written', methods=['POST'])
def upload_written_material():
    """Upload a new written study material"""
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No file selected'}), 400
    
    if not allowed_file(file.filename):
        return jsonify({'error': 'Only PDF files are allowed'}), 400
    
    # Get form data
    title = request.form.get('title')
    subject = request.form.get('subject')
    topic = request.form.get('topic', '')
    author = request.form.get('author')
    pages = request.form.get('pages', 0, type=int)
    
    if not all([title, subject, author]):
        return jsonify({'error': 'Title, subject, and author are required'}), 400
    
    # Save file
    filename = secure_filename(file.filename)
    unique_filename = f"{uuid.uuid4()}_{filename}"
    file_path = os.path.join(app.config['UPLOAD_FOLDER'], unique_filename)
    file.save(file_path)
    
    # Save to database
    conn = get_db_connection()
    cursor = conn.cursor()
    
    cursor.execute('''
        INSERT INTO written_materials (title, subject, topic, author, upload_date, file_path, pages)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ''', (title, subject, topic, author, datetime.now().isoformat(), file_path, pages))
    
    material_id = cursor.lastrowid
    conn.commit()
    conn.close()
    
    return jsonify({
        'id': material_id,
        'title': title,
        'subject': subject,
        'topic': topic,
        'author': author,
        'message': 'Written material uploaded successfully'
    }), 201

@app.route('/api/upload_orals', methods=['POST'])
def upload_orals_material():
    """Upload a new orals study material"""
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No file selected'}), 400
    
    if not allowed_file(file.filename):
        return jsonify({'error': 'Only PDF files are allowed'}), 400
    
    # Get form data
    question_type = request.form.get('question_type')
    function = request.form.get('function')
    topic = request.form.get('topic', '')
    author = request.form.get('author')
    
    if not all([question_type, function, author]):
        return jsonify({'error': 'Question type, function, and author are required'}), 400
    
    # Save file
    filename = secure_filename(file.filename)
    unique_filename = f"{uuid.uuid4()}_{filename}"
    file_path = os.path.join(app.config['UPLOAD_FOLDER'], unique_filename)
    file.save(file_path)
    
    # Save to database
    conn = get_db_connection()
    cursor = conn.cursor()
    
    cursor.execute('''
        INSERT INTO orals_materials (question_type, function, topic, author, upload_date, file_path)
        VALUES (?, ?, ?, ?, ?, ?)
    ''', (question_type, function, topic, author, datetime.now().isoformat(), file_path))
    
    material_id = cursor.lastrowid
    conn.commit()
    conn.close()
    
    return jsonify({
        'id': material_id,
        'question_type': question_type,
        'function': function,
        'topic': topic,
        'author': author,
        'message': 'Orals material uploaded successfully'
    }), 201

@app.route('/api/written_materials/<int:material_id>', methods=['PUT'])
def update_written_material(material_id):
    """Update an existing written study material"""
    data = request.get_json()
    
    conn = get_db_connection()
    cursor = conn.cursor()
    
    # Build update query dynamically
    update_fields = []
    params = []
    
    for field in ['title', 'subject', 'topic', 'author', 'pages']:
        if field in data:
            update_fields.append(f"{field} = ?")
            params.append(data[field])
    
    if not update_fields:
        return jsonify({'error': 'No fields to update'}), 400
    
    params.append(material_id)
    query = f"UPDATE written_materials SET {', '.join(update_fields)} WHERE id = ?"
    
    cursor.execute(query, params)
    conn.commit()
    
    if cursor.rowcount == 0:
        conn.close()
        return jsonify({'error': 'Material not found'}), 404
    
    conn.close()
    return jsonify({'message': 'Written material updated successfully'})

@app.route('/api/orals_materials/<int:material_id>', methods=['PUT'])
def update_orals_material(material_id):
    """Update an existing orals study material"""
    data = request.get_json()
    
    conn = get_db_connection()
    cursor = conn.cursor()
    
    # Build update query dynamically
    update_fields = []
    params = []
    
    for field in ['question_type', 'function', 'topic', 'author']:
        if field in data:
            update_fields.append(f"{field} = ?")
            params.append(data[field])
    
    if not update_fields:
        return jsonify({'error': 'No fields to update'}), 400
    
    params.append(material_id)
    query = f"UPDATE orals_materials SET {', '.join(update_fields)} WHERE id = ?"
    
    cursor.execute(query, params)
    conn.commit()
    
    if cursor.rowcount == 0:
        conn.close()
        return jsonify({'error': 'Material not found'}), 404
    
    conn.close()
    return jsonify({'message': 'Orals material updated successfully'})

@app.route('/api/written_materials/<int:material_id>', methods=['DELETE'])
def delete_written_material(material_id):
    """Delete a written study material"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    # Get file path before deletion
    material = cursor.execute('SELECT file_path FROM written_materials WHERE id = ?', (material_id,)).fetchone()
    
    if not material:
        conn.close()
        return jsonify({'error': 'Material not found'}), 404
    
    # Delete from database
    cursor.execute('DELETE FROM written_materials WHERE id = ?', (material_id,))
    conn.commit()
    conn.close()
    
    # Delete file
    try:
        if os.path.exists(material['file_path']):
            os.remove(material['file_path'])
    except Exception as e:
        print(f"Error deleting file: {e}")
    
    return jsonify({'message': 'Written material deleted successfully'})

@app.route('/api/orals_materials/<int:material_id>', methods=['DELETE'])
def delete_orals_material(material_id):
    """Delete an orals study material"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    # Get file path before deletion
    material = cursor.execute('SELECT file_path FROM orals_materials WHERE id = ?', (material_id,)).fetchone()
    
    if not material:
        conn.close()
        return jsonify({'error': 'Material not found'}), 404
    
    # Delete from database
    cursor.execute('DELETE FROM orals_materials WHERE id = ?', (material_id,))
    conn.commit()
    conn.close()
    
    # Delete file
    try:
        if os.path.exists(material['file_path']):
            os.remove(material['file_path'])
    except Exception as e:
        print(f"Error deleting file: {e}")
    
    return jsonify({'message': 'Orals material deleted successfully'})

@app.route('/api/download/<filename>')
def download_file(filename):
    """Download a study material file"""
    file_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
    
    if not os.path.exists(file_path):
        return jsonify({'error': 'File not found'}), 404
    
    # Update download count
    conn = get_db_connection()
    cursor = conn.cursor()
    
    # Try to update download count in both tables
    cursor.execute('UPDATE written_materials SET downloads = downloads + 1 WHERE file_path LIKE ?', (f'%{filename}%',))
    cursor.execute('UPDATE orals_materials SET downloads = downloads + 1 WHERE file_path LIKE ?', (f'%{filename}%',))
    
    conn.commit()
    conn.close()
    
    return send_file(file_path, as_attachment=True)

@app.route('/api/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({'status': 'healthy', 'message': 'Study Materials API is running'})

if __name__ == '__main__':
    init_db()
    app.run(host='0.0.0.0', port=5000, debug=True)

