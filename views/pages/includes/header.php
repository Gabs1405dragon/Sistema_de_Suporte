<html lang="pt-br"> 
<?php if(isset($_GET['deslogar'])){
session_destroy();
setcookie('user2',true,time() - 24*24*7,'/');
echo '<script>location.href="home"</script>';
}
?>
    <head>
        <meta name="author" content="Gabriel.H assis de souza" >
        <meta charset="utf-8" >
        <meta name="viewport" content="width=device-width , initial-scale=1.0, maximum-scale=1.0" />
        <title><?php echo $arr['titulo']; ?></title>
        <link rel="stylesheet" href="<?php echo BASE2 ?>Estilos/style.css">
        
    </head>
    <body>
