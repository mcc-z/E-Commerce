<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            max-width: 600px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        
        .status {
            background: #4CAF50;
            color: white;
            padding: 15px 30px;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
        }
        
        p {
            color: #666;
            line-height: 1.6;
            margin: 15px 0;
            font-size: 1.1em;
        }
        
        .info-box {
            background: #f5f5f5;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: left;
        }
        
        .info-box strong {
            color: #333;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Library Management System</h1>
        <div class="status">✅ SYSTEM OPERATIONAL</div>
        
        <p>Your Laravel application is running successfully!</p>
        
        <div class="info-box">
            <strong>Framework:</strong> Laravel 10.50.2<br>
            <strong>PHP Version:</strong> 8.2.12<br>
            <strong>Environment:</strong> Local Development<br>
            <strong>Debug Mode:</strong> Enabled
        </div>
        
        <p>Your Library Management System is ready for development. Start building amazing features!</p>
        
        <div class="footer">
            <p>🚀 Happy coding!</p>
        </div>
    </div>
</body>
</html>
