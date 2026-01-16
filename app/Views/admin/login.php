<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Login Admin') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            width: 360px;
            padding: 24px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 16px;
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
        }
        button {
            width: 100%;
            margin-top: 16px;
            padding: 10px;
            background: #0d6efd;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .error {
            background: #f8d7da;
            color: #842029;
            padding: 8px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Admin</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('/admin/login') ?>">
        <?= csrf_field() ?>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
