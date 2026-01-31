<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メールアドレスの認証</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 480px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden;">
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #fb7185 0%, #f472b6 100%);"></td>
                    </tr>
                    <tr>
                        <td style="padding: 32px 24px;">
                            <h1 style="margin: 0 0 8px; font-size: 20px; font-weight: 600; color: #27272a;">メールアドレスの認証</h1>
                            <p style="margin: 0 0 24px; font-size: 14px; line-height: 1.6; color: #52525b;">
                                以下のボタンをクリックして、メールアドレスの認証を完了してください。
                            </p>
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="border-radius: 6px; background-color: #fb7185;">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 12px 24px; font-size: 14px; font-weight: 500; color: #ffffff; text-decoration: none;">
                                            メールアドレスを認証する
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 24px 0 0; font-size: 12px; line-height: 1.5; color: #71717a;">
                                心当たりがない場合は、このメールを無視してください。<br>
                                このリンクは有効期限があります。
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 24px; border-top: 1px solid #e4e4e7; font-size: 12px; color: #a1a1aa;">
                            © {{ date('Y') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
