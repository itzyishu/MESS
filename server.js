const express = require('express');
const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

// MongoDB User Schema
const UserSchema = new mongoose.Schema({
    registrationNo: { 
        type: String, 
        required: true, 
        unique: true 
    },
    password: { 
        type: String, 
        required: true 
    },
    name: String,
    hostelMess: String
});

const User = mongoose.model('User', UserSchema);

// Database Connection
mongoose.connect('mongodb://localhost:27017/mymess', {
    useNewUrlParser: true,
    useUnifiedTopology: true
});

// Login Route
app.post('/api/login', async (req, res) => {
    try {
        const { registrationNo, password } = req.body;

        // Find user
        const user = await User.findOne({ registrationNo });
        if (!user) {
            return res.status(400).json({ message: 'Invalid credentials' });
        }

        // Check password
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(400).json({ message: 'Invalid credentials' });
        }

        // Generate JWT
        const token = jwt.sign(
            { id: user._id, registrationNo: user.registrationNo },
            'YOUR_SECRET_KEY',
            { expiresIn: '1h' }
        );

        res.json({ 
            token, 
            user: { 
                registrationNo: user.registrationNo, 
                name: user.name 
            } 
        });
    } catch (error) {
        res.status(500).json({ message: 'Server error', error: error.message });
    }
});

// Registration Route
app.post('/api/register', async (req, res) => {
    try {
        const { registrationNo, password, name, hostelMess } = req.body;
        
        // Check if user already exists
        const existingUser = await User.findOne({ registrationNo });
        if (existingUser) {
            return res.status(400).json({ message: 'User already exists' });
        }

        // Hash password
        const salt = await bcrypt.genSalt(10);
        const hashedPassword = await bcrypt.hash(password, salt);

        // Create new user
        const newUser = new User({
            registrationNo,
            password: hashedPassword,
            name,
            hostelMess
        });

        await newUser.save();

        res.status(201).json({ message: 'User registered successfully' });
    } catch (error) {
        res.status(500).json({ message: 'Server error', error: error.message });
    }
});

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));