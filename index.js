const express = require('express');
const { google } = require('googleapis');
const bodyParser = require('body-parser');
const cors = require('cors');
const dotenv = require('dotenv');

dotenv.config();

const app = express();

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

const allowedOrigins = [
  'http://localhost',
  'http://localhost:3000',
  'http://127.0.0.1:5500/register.html',
  'http://127.0.0.1:5500/Frond_End/register.html',
  'https://formpayment.ogcs.co.in/Frond_End/register.html',
  'https://formpayment.ogcs.co.in/',
  'https://ogcs.co.in/',
];

app.use(cors({
  origin: function(origin, callback) {
    if (!origin || allowedOrigins.indexOf(origin) !== -1) {
      callback(null, true);
    } else {
      callback(new Error('Not allowed by CORS'));
    }
  },
  credentials: true
}));

app.use(cors({
  origin: 'https://formpayment.ogcs.co.in',  // Allow your frontend origin
  credentials: true
}));

const GOOGLE_CLIENT_EMAIL ='ogcs-895@tidal-glider-434811-p6.iam.gserviceaccount.com';
const GOOGLE_PRIVATE_KEY ="-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDrCzBtRfnpEjRf\n+9yM/KQcAZNEATEpmyQR6N5sxM4iaCigQNOkCtLTrsG+ss/KeFL+LF0bCtqgfgrQ\n/2e6NGMr967IFX/8kezO7cTIvmOVEeMpinYhHap2SMac+lYpYCjCdH0WyFJGSMl2\nyw1TW2cDcAIpfn5t8Jah2yQeLLJC4qoBfc9oH/sN1jUAm3a5nyu7VwydK87aHaGQ\nqiikUvgISxXp/O8kJMu9NKJgd3UJV4N9PrlOMvjEEdF3Y4u1/yQ0DMcqT4EjEDw9\nsHPbadrUYqZeNBj39benpev6XOtq18Vhp7hO5FrxB6GNIaTPr+zZlaDJ4pRzPEnY\nIW2izKDxAgMBAAECggEADuXHSJ/LMKWDnhNSxflj5fYIGWL5dJ2FlmCEC5EYTyg6\nrfiZ6X95oCrnExEWmErS2NMjRhnkpFBE230avBvCruhcosgxE+dEYxPBOaeOnIXV\nF/6TkPMGSUfA1fWMhUQX5UZYoNtq0pNduJqzYiWMlJRSgMKInSuHAqSHCCYVWOWR\nndcScMPZEC7ZzxZ9d2fep7s5aLUpp139+kLb22mIO6CjlrRiEbPehQbhSJGd0mDR\npAfaVt5tu9+LHpFnzxgxcVTvHSUnFx1Tm48iuJxpec73hVqMrWN41hbrPvPqkZOR\nkP3D5wppK5ilPmjjy0K36jfA9x7tEd7GkTqpHXuyuQKBgQD7apLAED2sFGyUwJSI\nh8XoV7bkH6xp8GT4nIAM2FzeSymvyKB6EhzEVVtkVSUHzj57ZnBmddkTdJQ4rLmQ\nhCQ8TCl4xuYcaBOVbv5Ln0Eg2jlExDhY7D4RvcA2qVw295C0Gnt6DMAOyaInuLJi\nNX4adzBbPT50ROhDPpemgseEGQKBgQDvVDNfSVJeYTGbA/F66n/3atAW3lP+Hw9j\nuIplnJ8cFBzRPmnSWUFiYmx4hpoZuT58TtPJKs9W2fZIXctiVsNecrMrYMiidW2W\nvKeGNkfb+agf4Jc5LJByHkQyK2/9kOulrW0ReT355NUyRmD3DRIYVmIGXTN2ps6z\n2sQwVSnemQKBgQDfvtSjANEh13taVpjZeJt0TruBAX8bOMljR0PeKp9bZWDQA6ht\nerkHMT+IZw3xiGaqw1u8k5yAZ/uRBIaQSklFMDKoPbqEBUuIbaL6AdygNBVLUaUj\n3frPJyNsggSDFoc9AWRqFfbGMkORPhnitOBpBTGPwU1XhAt/7LBhi3mr4QKBgQDg\nAYKX9EvpVLWWbyltr+GYldwxG2WoXDAOWWMIWoE+ScDzRKTNooclbBQ0919zJkTL\nGwj1qGEq3JW9BDViVO1GELuMpWkFTj4FsYxnfZTi2xk2xQMmG4UTmyqffrKythsq\nFloY2c9df4bhKKiHdC90oh3b8Q5DRzv/cYrh5kRrOQKBgAlcIoq5FjmokKU53+vn\nLxY3vqq8NINyNRyOZ2WKqmvpkuJbKAKbAobf0I6gObtd1k18fBLRa1zLMX7WzkD/\nTK34yXIhy4l+3nLvVknM+oa4BLT5jhP277GmWGma/MLJB4E6RllyIhww74cZrKLT\nr5coWN7cAuuBicwcsEhaNgQv\n-----END PRIVATE KEY-----\n"; 
const SPREADSHEET_ID = '1tFuSlM72F9J1A5oBMzLzguMq9tvSVy2CMc_NtWzItLA';

// Setup Google Sheets API
const auth = new google.auth.JWT(
  GOOGLE_CLIENT_EMAIL,
  null,
  GOOGLE_PRIVATE_KEY,
  ['https://www.googleapis.com/auth/spreadsheets']
);

const sheets = google.sheets({ version: 'v4', auth });

// Get data from Google Sheets
async function getDataFromGoogleSheet() {
  const response = await sheets.spreadsheets.values.get({
    spreadsheetId: SPREADSHEET_ID,
    range: 'Sheet1',
  });
  return response.data.values;
}

// Handle registration on POST request
app.post('/', async (req, res) => {
  console.log('Received a registration request');
  try {
    const { name, email, phone } = req.body;

    console.log('Received data:', { name, email, phone });

    const values = [
      [name, phone, email],
    ];

    const resource = {
      values,
    };

    const response = await sheets.spreadsheets.values.append({
      spreadsheetId: SPREADSHEET_ID,
      range: 'Sheet1!A1',
      valueInputOption: 'RAW',
      resource,
    });
    console.log('Response from Sheets API:', response.data);

    // Redirect to the data page after successful registration
    res.redirect('/');
  } catch (error) {
    console.error('Error processing registration:', error);
    res.status(500).json({ success: false, error: 'Registration failed', details: error.message });
  }
});

// New route for the data page
app.get('/', async (req, res) => {
  try {
    const data = await getDataFromGoogleSheet();
    res.json(data); // Or render an HTML page with this data
  } catch (error) {
    console.error('Error fetching data:', error);
    res.status(500).json({ error: 'Error fetching data from Google Sheets' });
  }
});

app.listen(process.env.PORT || 3000, () => {
  console.log(`Server running on port ${process.env.PORT || 3000}`);
});
