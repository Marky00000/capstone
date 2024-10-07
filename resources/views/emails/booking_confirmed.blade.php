<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #e9f5ff; /* Light background color */
        }
        .card {
            background-color: #ffffff;
            border: 1px solid grey; /* Card border color */
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 0 auto;
            max-width: 600px;
            position: relative;
        }
        .header {
            background-color: #256b76; /* Info color */
            color: white;
            padding: 10px 20px;
            text-align: left;
            border-bottom: 2px solid grey; /* Grey divider */
        }
        .footer {
            background-color: #f8f9fa; /* Light grey footer */
            padding: 10px 20px;
            text-align: center;
            margin-top: 20px;
            border-top: 2px solid grey; /* Grey divider */
        }
        .card-header {
            background-color: #17a2b8; /* Same as header */
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0; /* Rounded top corners */
            text-align: center; /* Center-aligned */
        }
        .header img {
            max-width: 80px; /* Reduced size of the logo */
            height: auto;
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin: 10px 0;
        }
        h3 {
            font-size: 18px;
            color: #555;
            border-bottom: 2px solid #3f4449;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        li:last-child {
            border-bottom: none; /* Remove border from last item */
        }
        p {
            color: #555;
        }
        .icon {
            margin-right: 5px;
        }
        .contact-info {
            font-size: 14px;
            color: #080c10;
        }
        .contact-info a {
            color: #080c10;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <img src="{{ asset('https://scontent.fmnl4-4.fna.fbcdn.net/v/t39.30808-6/321054410_1467455973783193_7487495423071851918_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEK81qKGs9S-XRXJ1NNaFbN_0JvTdUQzjP_Qm9N1RDOM8ZIVPwo2FMLoEouxwC0BHoT0gxOBY3oXRzdF0E-P96W&_nc_ohc=cUpGNXY4eFoQ7kNvgFGAm-P&_nc_ht=scontent.fmnl4-4.fna&oh=00_AYCgu7qfX76A2lLjTJZucp86S8f4T2Qjic4KjZfJWiDijA&oe=66F5896C') }}" alt="Arfil's Landscaping Logo" style="max-width: 80px; height: auto;">
            <h1>Arfil's Landscaping Services</h1>
            <div class="contact-info">
                <p>
                    <i class="fas fa-map-marker-alt icon"></i> Address: Zone 10, Carmen, Cagayan de Oro City
                </p>
                <p>
                    <i class="fas fa-phone-alt icon"></i> Contact: 09776912110
                </p>
                <p>
                    <i class="fas fa-envelope icon"></i> Email: <a href="mailto:arfilslandscaping@gmail.com">arfilslandscaping@gmail.com</a>
                </p>
                <p>
                    <i class="fab fa-facebook icon"></i> Facebook: <a href="https://www.facebook.com/search/top?q=arfil%27s%20landscaping%20%26%20swimmingpool%20services" target="_blank">Arfil's Landscaping</a>
                </p>
            </div>
        </div>

        <h1>Booking Confirmation!</h1>

        <p>Dear {{ $booking->name }},
            <span style="float: right; font-size: 12px; color: #777;">
                {{ now()->format('F j, Y, g:i a') }}
            </span>
        </p>

        <p>We are pleased to confirm your booking with us. Here are the details:</p>

        <h3>Booking Details:</h3>
        <ul>
            <li><span><strong>Booking ID:</strong></span><span>{{ $booking->id }}</span></li>
            <li><span><strong>Name:</strong></span><span>{{ $booking->name }}</span></li>
            <li><span><strong>Contact:</strong></span><span>{{ $booking->contact }}</span></li>
            <li><span><strong>Email:</strong></span><span>{{ $booking->email }}</span></li>
            <li><span><strong>Site Visit Date:</strong></span><span>{{ \Carbon\Carbon::parse($booking->site_visit_date)->format('F j, Y') }}</span></li>
            <li><span><strong>Address:</strong></span><span>{{ $booking->address }}</span></li>
            <li><span><strong>City:</strong></span><span>{{ $booking->city }}</span></li>
            <li><span><strong>Province:</strong></span><span>{{ $booking->province }}</span></li>
        </ul>

        <p>If you have any questions, feel free to contact us at <a href="mailto:support@arfil-landscaping.com">support@arfil-landscaping.com</a>.</p>

        <div class="footer">
            <p>Thank you for choosing Arfil's Landscaping Services!</p>
            <p>Best regards,<br> Arfil's Landscaping Services Team</p>
        </div>
    </div>  
</body>
</html>
