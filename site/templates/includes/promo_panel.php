<?php

// Markup for a promo panel 
// that we might pull in to a couple of places in the future



?>

<style nonce="<?=$mu->nonce?>">

.promo_panel{
	background-color: white;
	padding:2px;
	border:2px solid var(--theme);
}

.pp_inner{
	padding:var(--gap);
	text-align: center;
	background-color: var(--theme);
	color:white;
}

.pp_inner h2{
	font-size: var(--step-1);
	line-height: 1.1;
}

.pp_inner h3{
	line-height: 1.1;
}

.pp_inner a{
	color:white;
	text-decoration: underline;
}

.pp_inner a:hover{
	text-decoration: none;
}

</style>


<div class="promo_panel">
	<div class="pp_inner">
		<?=$pp_content?>
	</div>
</div>