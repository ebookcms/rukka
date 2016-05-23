<!DOCTYPE html>
<html lang="en">
<head>	
	<title>Rukka</title>
	<style type="text/css" media="all">
	
		h1 {text-align: center;}
		.code {
			width: 70%;
			margin: 5px auto;
		}
		blockquote {
			background-color: #003399;
			border-left:2px dashed #ddd;
			margin: 35px;
			padding: 0.5em 10px;
			font-family: Georgia;
			font-size: 18px;
			color: #fff;
		}
		blockquote span {
			color: #00ff00;
		}
	
	</style>
</head>

<body>

	<h1>RUKKA Example</h1>
	<?php include('rukka.php');
		
		// HOW TO USE
		$text = "Hello World.";
		$rukka1 = new rukka();
		$encrypted = $rukka1->rencrypt($text);
		$decrypted = $rukka1->rdecrypt($encrypted);
		echo '<div class="code">
			<h2>Example 1</h2>
			<blockquote>
			<b>$text</b> = "Hello World.";<br />
			<b>$rukka1</b> = new rukka();<br /><br />
			<b>$encrypted</b> = $rukka1->rencrypt($text);<br />
			<b>$decrypted</b> = $rukka1->rdecrypt($encrypted);<br />
			</blockquote>
			<blockquote>
				<b>encrypted:</b> "'.$encrypted.'"<br />
				<b>decrypted:</b> "'.$decrypted.'"';		// Will print Hello World.
			echo '</blockquote>
		</div>';
		
		echo '<hr />';
		
		// GET A PKEY
		$pkey1 = $rukka1->gen_PKey();
		
		// YOU CAN MODIFY PKEY INSIDE CLASS OR USE PKEY DIRECTLY
		$rukka2 = new rukka();
		$rukka2->setPkey1($pkey1);
		$encrypted = $rukka2->rencrypt($text);
		$decrypted = $rukka2->rdecrypt($encrypted);
		echo '<div class="code">
			<h2>Example 2</h2>
			<blockquote>
				<span>// GENERATE NEW pkey</span><br />
				$pkey1 = $rukka1->gen_PKey();<br /><br />
				<span>// Use this new pkey as a pkey1</span><br />
				<b>$rukka2</b> = new rukka();<br />
				<b>$rukka2</b>->setPkey1($pkey1);<br />
				<span>// pkey2 can be changed too: <b>$rukka2</b>->setPkey2($pkey2);</span><br /><br />
				<b>$encrypted</b> = $rukka2->rencrypt($text);<br />
				<b>$decrypted</b> = $rukka2->rdecrypt($encrypted);<br />
			</blockquote>
			<blockquote>
				<b>pkey1:</b> "'.$pkey1.'"<br /><br />
				<b>encrypted:</b> "'.$encrypted.'"<br />
				<b>decrypted:</b> "'.$decrypted,'"
			</blockquote>
		</div>';
		
		echo '<hr />';
		
		echo '<div class="code">
			<h2>Example 3</h2>
			<blockquote>
				<span>// If you want change your RSA-KEYS</span><br /><br />
				<b>$rukka3</b> = new rukka();<br /><br />
				
				<span>// Genererate only 2 pairs</span><br />
				$rukka3->genPair(2);<br /><br />
				
				<span>// Thi will print 2 pairs, something like this:</span><br /><br />';
				
				$rukka3 = new rukka();
				$rukka3->genPair(2);
				
				echo '<br />
				
				<span>// The only thing you need to do is:</span><br />
				<span>// 1 - Copy 2 pairs</span><br />
				<span>// 2 - Open rukka.php and find "<b>private $rsa</b>" and change it</span><br /><br />
				private $rsa = [<br />
					<span>'.$rukka3->get_LastPair().'</span><br />
				];<br /><br />
				<b>Close file and save it</b><br /><br />
				<b>PS:</b> Use more than 4 pairs to make hard to decode.
			</blockquote>
			
		</div>';
	?>
</body>
</html>