<?php

/**
 * 404 with no dependancies.
 * and some rainbow text now.
 */

?>
<html lang="en-GB">
<head>
<style>
    *{
    transition: all 0.6s;
}

html {
    height: 100%;
}

body{
    font-family: 'Lato', sans-serif;
    color: #333;
    margin: 0;
}

#main{
    display: table;
    width: 100%;
    height: 100vh;
    text-align: center;
}

.fof{
	  display: table-cell;
	  vertical-align: middle;
}

.fof h1{
	  font-size: clamp(4rem, 20vw, 12rem);
	  display: inline-block;
	  padding-right: 12px;
	  animation: type .5s alternate infinite;
      background: linear-gradient(to left, #ef5350, #f48fb1, #7e57c2, #2196f3, #26c6da, #43a047, #eeff41, #f9a825, #ff5722);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
}

.fof p{
    font-size: 1.34rem;
}

@keyframes type{
	  from{box-shadow: inset -3px 0px 0px #333;}
	  to{box-shadow: inset -3px 0px 0px transparent;}
}
</style>
<title>Error 404 - Page not Found </title>
</head>
<body>
<main id="main">
    	<div class="fof">
                <h1>Error 404</h1>
                <p> Sorry we can't find that content.</p>
                <p>We've recently revamped the BRSA so <a href="/">try searching for a shop from the homepage.</a></p>
    	</div>
</main>
</body>
</html>