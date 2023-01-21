<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Renta</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *,
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: #f5f5f5;
            color: #333;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        * {
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            align-items: center;
            max-width: 1100px;
            margin: 34px auto;
        }

        .right>span {
            font-weight: 600;
            font-size: 18px;
            line-height: 22px;
            text-align: center;
            color: #151284;
        }


        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            padding: 70px 0;
        }

        .container>h1 {
            font-weight: 600;
            font-size: 48px;
            line-height: 120%;
            text-align: center;
            padding-bottom: 15px;
        }

        .container>p {
            padding: 20px 0;
            font-weight: 400;
            font-size: 16px;
            line-height: 160.19%;
            text-align: center;
            color: #4E4E4E;
        }

        .join-wrapper {
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        .join-wrapper>input {
            width: 100%;
            max-width: 400px;
            height: 64px;
            border: 1px solid #E5E5E5;
            box-sizing: border-box;
            border-radius: 4px;
            padding: 0 20px;
            font-size: 16px;
            line-height: 20px;
            color: #4E4E4E;
        }

        .join-wrapper>input:hover {
            border: 1px solid #151284;
        }

        .join-wrapper>button {
            height: 64px;
            width: 132px;
            background: #151284;
            border-radius: 4px;
            font-weight: 600;
            font-size: 16px;
            line-height: 20px;
            text-align: center;
            color: #FFFFFF;
            border: none;
            cursor: pointer;
        }

        .join-wrapper>button:hover {
            background: #0D0D5A;
        }

        .creators {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .creators>div:first-child {
            display: flex;
            justify-content: center;
            margin: 20px;
        }

        .creators>div:first-child>img {
            width: 44px;
            height: 44px;
            border: 2px solid #fff;
            border-radius: 50%;
            margin-left: -8px;
        }

        .creators>p {
            font-weight: 600;
            font-size: 14px;
            line-height: 128.9%;
            text-align: center;
            color: #4E4E4E;
        }

        @media screen and (max-width: 1120px) {
            header {
                padding: 0 20px;
            }

            .container {
                padding: 70px 20px;
            }
        }

        @media screen and (max-width: 768px) {
            .join-wrapper {
                flex-direction: column;
                align-items: center;
            }
        }
        img {
            width: 100px;
            filter: invert(100%);
        }
    </style>
</head>

<body data-new-gr-c-s-check-loaded="14.1085.0" data-gr-ext-installed="">
    <header>
        <div class="logo">
            <img class="logo" src="https://www.renta.se/wp-content/uploads/2018/04/valkoinen_png.png" alt="logo">
        </div>
    </header>

    <div class="container">
        <h1>Acquire your next property with ease.</h1>
        <p>We're making a simple link between property owners and finder, by making everything accessible at a tap, will like to be part of this wonderful journey? Kindly join our waiting list below, and watch out for our next mail.</p>
        <form class="join-wrapper">
            <input type="email" id="email" placeholder="Enter your email">
            <button>Join waitlist</button>
        </form>
        <p class="result"></p>
        <div class="creators">
            <div>
                <img src="https://i.pravatar.cc/150?img=3" alt="">
                <img src="https://i.pravatar.cc/150?img=3" alt="">
                <img src="https://i.pravatar.cc/150?img=3" alt="">
                <img src="https://i.pravatar.cc/150?img=3" alt="">
                <img src="https://i.pravatar.cc/150?img=3" alt="">
                <img src="https://i.pravatar.cc/150?img=3" alt="">
            </div>
            <p>Join 5k other creatives and get early access to beta.</p>
        </div>
    </div>
    <script>
        const email = document.getElementById('email');
        const button = document.querySelector('button');
        const form = document.querySelector('form');
        const result = document.querySelector('.result');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            try {
                if (!email.value) {
                    throw new Error('Please enter your email');
                }

                button.innerHTML = 'Sending...';
                button.disabled = true;

                const formData = new FormData();
                formData.append('email', email.value);

                fetch('/api/subscribe', {
                        method: 'POST',
                        body: formData
                    }).then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (data.content) {
                            button.innerHTML = 'Success!';
                            button.disabled = true;
                        } else {
                            button.innerHTML = 'Try again';
                            button.disabled = false;
                            result.innerHTML = data.message;
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        button.innerHTML = 'Try again';
                        button.disabled = false;
                        result.innerHTML = 'Something went wrong';
                    });
            } catch (error) {
                button.innerHTML = 'Try again';
                button.disabled = false;
                result.innerHTML = error.message;
                console.log(error);
            }
        })
    </script>
</body>
</html>