<?php
include 'db.php';
session_start();

$error = '';
$success = '';

// Quando viene inviato un form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = 'Inserisci username e password.';
        } else {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Username o password non validi.';
            }
        }
    }

    if ($action === 'register') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm = trim($_POST['confirm_password'] ?? '');

        if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
            $error = 'Compila tutti i campi.';
        } elseif ($password !== $confirm) {
            $error = 'Le password non coincidono.';
        } else {
            // Verifica se l'username esiste già
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'Username già esistente.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hash]);
                $success = 'Registrazione avvenuta con successo! Ora puoi accedere.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>YouChef login</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="header-wrapper">
  <h2><span class="white">You</span><span class="red">Chef</span><span class="white"> login</span></h2>
</div>

<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="login.php" method="post">
			<h1>Crea un account</h1>
			<span>Usa la tua mail per registrarti</span>
			<input type="hidden" name="action" value="register">
			<input type="text" name="username" placeholder="username" />
			<input type="email" name="email" placeholder="Email" />
			<input type="password" name="password" placeholder="password" />
			<input type="password" name="confirm_password" placeholder="Conferma password" />
			<button type="submit">Registrati ora</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="login.php" method="post">
			<h1>Accedi</h1>
			<p>Inserisci username e password per accedere</p>
			<?php if ($error): ?>
				<div class="error"><?php echo $error; ?></div>
			<?php endif; ?>
			<?php if ($success): ?>
				<div class="success"><?php echo $success; ?></div>
			<?php endif; ?>
			<input type="hidden" name="action" value="login">
			<input type="text" name="username" placeholder="username" />
			<input type="password" name="password" placeholder="password" />
			<button type="submit">Accedi</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Bentornato!</h1>
				<p>Per continuare ad usare YouChef, accedi qui</p>
				<button class="ghost" id="signIn">Accedi</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Ciao!</h1>
				<p>Inserisci i tuoi dati personali ed inizia ad usare YouChef al massimo</p>
				<button class="ghost" id="signUp">Registrati</button>
			</div>
		</div>
	</div>
</div>

<footer>	
</footer>

<script src="login.js"></script>
</body>