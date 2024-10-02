<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
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
            background-color: #17a2b8; /* Bootstrap info color */
            color: white;
            padding: 10px 20px;
            text-align: center; /* Center-align header */
            border-bottom: 2px solid grey; /* Grey divider */
        }
        .footer {
            background-color: #f8f9fa; /* Light grey footer */
            padding: 10px 20px;
            text-align: center;
            margin-top: 20px;
            border-top: 2px solid grey; /* Grey divider */
        }
        .header img {
            max-width: 80px; /* Adjust size of the logo */
            height: auto;
            display: block; /* Center the logo */
            margin: 0 auto; /* Center the logo */
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin: 10px 0;
            text-align: center; /* Center-align header */
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
        .status {
            font-weight: bold;
            color: #fff; /* Text color for status */
            border-radius: 5px;
            padding: 4px 8px;
            text-align: center;
            display: inline-block;
        }
        .status-ongoing {
            background-color: #007bff; /* Blue for ongoing */
        }
        .status-completed {
            background-color: #28a745; /* Green for completed */
        }
        .status-pending {
            background-color: #ffc107; /* Yellow for pending */
        }
        .contact-info {
            font-size: 14px;
            color: #080c10;
            text-align: center; /* Center-align contact info */
        }
        .contact-info a {
            color: #080c10;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <img src="{{ asset('https://scontent.fmnl4-4.fna.fbcdn.net/v/t39.30808-6/321054410_1467455973783193_7487495423071851918_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEK81qKGs9S-XRXJ1NNaFbN_0JvTdUQzjP_Qm9N1RDOM8ZIVPwo2FMLoEouxwC0BHoT0gxOBY3oXRzdF0E-P96W&_nc_ohc=cUpGNXY4eFoQ7kNvgFGAm-P&_nc_ht=scontent.fmnl4-4.fna&oh=00_AYCgu7qfX76A2lLjTJZucp86S8f4T2Qjic4KjZfJWiDijA&oe=66F5896C') }}" alt="Arfil's Landscaping Logo">
            <h1>Arfil's Landscaping Services</h1>
            <div class="contact-info">
                <p>
                    <i class="fas fa-map-marker-alt"></i> Zone 10, Carmen, Cagayan de Oro City
                </p>
                <p>
                    <i class="fas fa-phone-alt"></i> Contact: 09776912110
                </p>
                <p>
                    <i class="fas fa-envelope"></i> Email: <a style="text-decoration: underline;" href="mailto:arfilslandscaping@gmail.com">arfilslandscaping@gmail.com</a>
                </p>
                <p>
                    <i class="fab fa-facebook"></i> Facebook: <a style="text-decoration: underline;" href="https://www.facebook.com/search/top?q=arfil%27s%20landscaping%20%26%20swimmingpool%20services" target="_blank">Arfil's Landscaping</a>
                </p>
            </div>
        </div>

        <h1>Booking Details</h1>

        <p><strong>Name:</strong> {{ $bookingDetails['name'] }}</p>
        <p><strong>Contact:</strong> {{ $bookingDetails['contact'] }}</p>
        <p><strong>Email:</strong> {{ $bookingDetails['email'] }}</p>
        <p><strong>Site Visit Date:</strong> {{ date('F j, Y', strtotime($bookingDetails['site_visit_date'])) }}</p>
        <p><strong>Address:</strong> {{ $bookingDetails['address'] }}</p>
        <p><strong>Province:</strong> {{ $bookingDetails['province'] }}</p>
        <p><strong>City:</strong> {{ $bookingDetails['city'] }}</p>

        <div class="footer">
            &copy; {{ date('Y') }} Arfil's Landscaping Services. All rights reserved.
        </div>
    </div>
</body>
</html>
