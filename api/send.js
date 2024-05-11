// api/send.js
const express = require('express');
const nodemailer = require('nodemailer');
const bodyParser = require('body-parser');

const app = express();

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

app.get('/api', (req, res) => {
    res.send('Workingggggg!');
});

app.post('/api/send', async (req, res) => {
    try {
        let errors = '';

        // Validate User Inputs
        const { firstName, lastName, email, phone, message } = req.body;

        // Name
        if (!firstName || !lastName) {
            errors += 'Please enter your name.\n';
        }

        // Email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            errors += 'Please enter a valid email address.\n';
        }

        // Phone
        if (phone && !phone.match(/^\+{1}[0-9]+$/)) {
            errors += 'Please enter a valid phone number like: +363012345.\n';
        }

        // Message
        if (!message) {
            errors += 'Please enter your message.\n';
        }

        if (errors) {
            return res.status(400).json({ success: false, error: errors });
        }

        // Customer Details
        const customerMessage = `
            CONTACT DATA
            --
            First Name: ${firstName}
            Last Name: ${lastName}
            Email: ${email}
            Phone: ${phone || 'Not provided'}

            MESSAGE
            --
            ${message}
        `;

        // Nodemailer Configuration
        let transporter = nodemailer.createTransport({
            // Configure your email service provider here
            service: 'gmail',
            auth: {
                user: 'maghjiironscompany@gmail.com',
                pass: 'mahrenpkcwoszcfo'
            }
        });

        // Email to Site Owner
        let mailOptionsOwner = {
            from: 'Maghji <maghjiironscompany@gmail.com>',
            to: 'maghjiironscompany@gmail.com',
            subject: 'Request',
            text: customerMessage
        };

        // Email to User
        let mailOptionsUser = {
            from: 'Maghji <your-email@gmail.com>',
            to: email,
            subject: 'Request Confirmation',
            text: `Dear ${firstName} ${lastName},\n\nThank you for contacting us. We will reply shortly.\n\nBest Regards,\nMaghji Team`
        };

        // Send Emails
        await Promise.all([
            transporter.sendMail(mailOptionsOwner),
            transporter.sendMail(mailOptionsUser)
        ]);

        // Success response
        const successHtml = `
            <div id="success">
                <div class="icon icon-order-success svg">
                    <svg width="72px" height="72px">
                        <g fill="none" stroke="#02b843" stroke-width="2">
                            <circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                            <path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                        </g>
                    </svg>
                </div>    
                <h4>Thank you for contacting us.</h4>
                <small>Check your mailbox.</small>
            </div>
        `;
        res.status(200).send(successHtml);
        
        // Redirect after 5 seconds
        setTimeout(() => {
            res.redirect('https://maghji.vercel.app/contact.html');
        }, 2000);

    } catch (error) {
        // Error response
        console.error('Error:', error);
        res.status(500).json({ success: false, error: 'Failed to send email' });
    }
});

console.log('Serverless function started...');


// Start the server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server is running on ${PORT}`);
});

module.exports = app;