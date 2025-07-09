<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>
<form method="post" action="">
    <input type="text" name="email" placeholder="Enter email" />
    <br><br>
    <input type="password" name="password" placeholder="Enter password" />
    <br><br>
    <input type="text" name="phone" placeholder="Enter phone number" />
    <br><br>
    <input type="submit" name="btnsub" value="Submit"/>
    <br><br><br>
    <a href="Login.php">Login</a> <!-- Ainda em desenvolvimento -->
</form>
<?php
    include('Config.php');


    if(isset($_POST['btnsub'])) {
        $email = $_POST['email'];
        $password = $_POST['password']; // Senha em texto puro vinda do formulário
        $phone = $_POST['phone'];


        // 1. Criptografa a senha de forma segura usando password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        // 2. Prepara a consulta SQL com placeholders (?) para evitar Injeção SQL
        $query_template = "INSERT INTO accounts (email, password, phone) VALUES (?, ?, ?)";


        // 3. Cria a instrução preparada
        if ($stmt = mysqli_prepare($con, $query_template)) {
            // 4. Vincula os parâmetros aos placeholders. 'sss' indica que todos são strings.
            // Isso garante que os dados sejam tratados como valores e não como parte do código SQL.
            mysqli_stmt_bind_param($stmt, "sss", $email, $hashed_password, $phone);


            // 5. Executa a instrução preparada
            if (mysqli_stmt_execute($stmt)) {
                echo "O registro foi salvo com êxito!";
            } else {
                // Mensagem de erro mais detalhada para depuração (remover em produção)
                echo "Houve um problema ao registrar: " . mysqli_error($con);
            }


            // 6. Fecha a instrução para liberar recursos
            mysqli_stmt_close($stmt);
        } else {
            // Erro na preparação da consulta (para depuração)
            echo "Erro na preparação da consulta SQL: " . mysqli_error($con);
        }
    }
    /* 
     Observações importantes, para esse codigo funcionar vocês precisam dos servidores do Apache
     e o MySQL com uma Data Base chamado "dados", uma tabela chamada "accounts", onde o campo tem que
     ser: email com varchar, password como varchar e phone tambem como varchar, tambem com o localhost
     com o usuario de root e a senha em branco, caso queira mudar algumas coisas podem entrar no
     arquivo "Config.PHP" 
     ... E claro se quiserem fazer um teste de segurança com injeções SQL, fiquem a vontade.
     */
?>
</body>
</html>