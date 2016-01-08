<html>
<head>
    <title>Page Not Found</title>
</head>

<body>
<h1>404 Page Not Found</h1>
<p>
    The path "<?= $view->e($request->getPath()) ?>" does not exist !
</p>
<h3>Error Message :</h3>
<pre>
   <?= $view->e($exeception->getMessage()) ?>
</pre>
</body>
</html>