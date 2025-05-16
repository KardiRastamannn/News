<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Oldal nem található</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Arial', sans-serif;
        }

        .container {
            text-align: center;
            padding: 2rem;
        }

        .robot {
            width: 200px;
            height: 300px;
            margin: 0 auto 2rem;
            position: relative;
            animation: float 3s ease-in-out infinite;
        }

        .robot-head {
            width: 120px;
            height: 120px;
            background: #4a90e2;
            border-radius: 50%;
            margin: 0 auto;
            position: relative;
        }

        .robot-eye {
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            top: 40px;
            animation: blink 5s infinite;
        }

        .left-eye { left: 30px; }
        .right-eye { right: 30px; }

        .robot-body {
            width: 150px;
            height: 150px;
            background: #4a90e2;
            border-radius: 20px;
            margin: -20px auto 0;
            position: relative;
        }

        .robot-arm {
            width: 40px;
            height: 100px;
            background: #4a90e2;
            position: absolute;
            top: 50px;
            animation: wave 2s infinite;
        }

        .left-arm { left: -50px; transform-origin: top right; }
        .right-arm { right: -50px; transform-origin: top left; }

        h1 {
            color: #2c3e50;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        p {
            color: #34495e;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .home-btn {
            padding: 15px 30px;
            font-size: 1.1rem;
            background: linear-gradient(45deg, #4a90e2, #6b48ff);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: transform 0.3s, background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .home-btn:hover {
            transform: scale(1.1);
            background: linear-gradient(45deg, #6b48ff, #4a90e2);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(-30deg); }
        }

        @keyframes blink {
            0%, 96%, 98%, 100% { height: 20px; }
            97%, 99% { height: 5px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="robot">
            <div class="robot-head">
                <div class="robot-eye left-eye"></div>
                <div class="robot-eye right-eye"></div>
            </div>
            <div class="robot-body">
                <div class="robot-arm left-arm"></div>
                <div class="robot-arm right-arm"></div>
            </div>
        </div>
        
        <h1>404 🤖 Útvonalat tévesztett!</h1>
        <p>Úgy tűnik, a robotunk eltévedt a digitális űrben...<br>
        De ne aggódj, itt egy gomb hogy visszavigyen a biztonságos helyre!</p>
        
        <a href="/" class="home-btn">
            🏠 Vissza a fedélzetre
        </a>
    </div>
</body>
</html>