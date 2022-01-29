<html>
<head>
    <style>
        .mail {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }

        .mail__top,
        .mail__bottom {
            display: block;
            padding: 16px;
            background-color: #6C63FF;
            color: #ffffff;
            width: 100%;
            height: 80px;
        }

        .mail__top-title {
            line-height: 80px;
            font-size: 22pt;
            margin: 0;
        }

        .mail__top-logo {
            display: block;
            margin: auto;
            text-align: center;
            font-size: 18pt;
        }

        .mail__body {
            width: 100%;
        }

        .mail__body-image {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: auto;
            object-fit: contain;
            border-radius: 50%;
        }

        .mail__spacing {
            display: block;
            padding: 12px;
        }

        .mail__button {
            display: block;
            margin: 16px auto;
            color: #fff;
            background-color: #6C63FF;
            border-radius: 8px;
            padding: 8px 12px;
            width: max-content;
            text-decoration: none;
        }

    </style>
</head>
<body>
    <div>
        <table class="mail" >
            <tr class="mail__top">
                <td class="mail__top-logo">
                    <h1 class="mail__top-title">Verifique seu E-mail</h1>
                </td>
            </tr>
        </table>
        <table class="mail">
            <tr class="mail__spacing"><td></td></tr>
            <tr class="mail__body">
                <td width="100%">
                    <img class="mail__body-image" alt="Mailbox" src="http://i.imgur.com/skjuki4.png" />
                </td>
            </tr>
            <tr class="mail__body">
                <td>
                    <p>Olá, {{ $user->name }}.</p>
                    <p>Verifique seu e-mail para ter acesso ao sistema.</p>
                    <p>Para resetar sua senha, basta clicar no botão abaixo: </p>
                </td>
            </tr>
            <tr class="mail__body-action">
                <td>
                    <a href="{{ $url }}" class="mail__button">Verifcar E-mail</a>
                </td>
            </tr>
            <tr class="mail__body-action">
                <td>
                    <p>Ou copie o link abaixo no seu browser: </p>
                    <a href="{{ $url }}">{{ $url }}</a>
                </td>
            </tr>
            <tr class="mail__spacing"><td></td></tr>
        </table>
        <table class="mail" width="100%" cellspacing="0" >
            <tr class="mail__bottom">
                <td class="mail__bottom-info">
                    <p>&copy; </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
