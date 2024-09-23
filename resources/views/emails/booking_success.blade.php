<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0; /* Light gray background */
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border: solid 1px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px 0;
            border-radius: 8px 8px 0 0;
            background: #e0e0e0; /* Grayish background for header */
        }
        .header img {
            max-width: 80px; /* Adjust size of the logo */
            height: auto;
        }
        h1 {
            font-size: 24px;
            margin: 10px 0;
        }
        .contact-info {
            font-size: 14px;
            margin: 5px 0;
        }
        .receipt {
            border-top: 2px solid #757070;
            padding-top: 20px;
            margin-top: 20px;
        }
        .receipt p {
            margin: 10px 0;
            color: #555;
        }
        .footer {
            border-top: 2px solid #757070;
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('https://scontent.fmnl4-4.fna.fbcdn.net/v/t39.30808-6/321054410_1467455973783193_7487495423071851918_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=a5f93a&_nc_eui2=AeEK81qKGs9S-XRXJ1NNaFbN_0JvTdUQzjP_Qm9N1RDOM8ZIVPwo2FMLoEouxwC0BHoT0gxOBY3oXRzdF0E-P96W&_nc_ohc=cUpGNXY4eFoQ7kNvgFGAm-P&_nc_ht=scontent.fmnl4-4.fna&oh=00_AYCgu7qfX76A2lLjTJZucp86S8f4T2Qjic4KjZfJWiDijA&oe=66F5896C') }}" alt="Arfil's Landscaping Logo">
            <p class="contact-info">Zone 10, Carmen, Cagayan de Oro City</p>
            <p class="contact-info"><a href="https://www.facebook.com/profile.php?id=100087594043346">Facebook: Arfil's Landscaping and Swimming Pool Services</a></p>
            <p class="contact-info"><a href="https://mail.google.com/mail/u/0/#inbox?compose=jrjtXFBjqfMzNMcCpJTDGhPJRdkVjFQVJCkKrZlcNZPzQqJFBXcGMdvbnZsxqSgQgGDtffdG">Email: arfil landscaping@gmail.com</a></p>
            <p class="contact-info">Contact: 09776912110</p>
        </div>

        <div class="receipt">
            <center><h1>Booking Details</h1></center>

            <p><strong>Name:</strong> {{ $bookingDetails['name'] }}</p>
            <p><strong>Contact:</strong> {{ $bookingDetails['contact'] }}</p>
            <p><strong>Email:</strong> {{ $bookingDetails['email'] }}</p>
            <p><strong>Site Visit Date:</strong> {{ date('F j, Y', strtotime($bookingDetails['site_visit_date'])) }}</p>
            <p><strong>Address:</strong> {{ $bookingDetails['address'] }}</p>
            <p><strong>Province:</strong> {{ $bookingDetails['province'] }}</p>
            <p><strong>City:</strong> {{ $bookingDetails['city'] }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Arfil's Landscaping Services. All rights reserved.
        </div>
    </div>
</body>
</html>
