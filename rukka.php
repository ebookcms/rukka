<?php 

/*	RUKKA version 1.0

	Copyright 2016 by Rui Mendes
	Date: May 24, 2016
	
	This script is distributed under the terms of the "Attribution 4.0 International (CC BY 4.0)"
	See http://creativecommons.org/licenses/by/4.0/ for license details

	You are free to
		Share — copy and redistribute the material in any medium or format
		Adapt — remix, transform, and build upon the material for any purpose, even commercially.
	
	The licensor cannot revoke these freedoms as long as you follow the license terms.
	To make sure you have the genuine, up-to-date version, visit github: https://github.com/ebookcms/rukka

*/

class rukka {
	
	// PRIVATE KEYS
	private $pkey1 = '2vuUz5s8b_eTDYEcIVK3Hk4WhJLx1RSAjoFp6C0gdGlNyniBQr9f-Owa7MtmqXZP';
	private $pkey2 = 'R76FansHWehxU1ICq5fd_JD09ciOzX3BrEMAKjLkgZQ8v4lGytom2upVTbYw-SNP';
	
	// PRIVATE RSA KEYS "m,e,d" - use genPair()
	private $rsa = [
		[2483,181,781],
		[16789,157,421],
		[1079,79,847],
		[17399,131,2747],
		[18509,211,3451],
		[34189,181,21301],
		[7387,181,3309],
		[10519,151,8455]
	];
	
	// RSA FUNCTIONS
	private function mo($g, $l) {return $g-($l*floor($g/$l));}
	private function powmod ($base, $exp, $modulus) {
		$accum = 1; $i = 0; $basepow2 = $base;
		while (($exp >> $i)>0) {
			if ((($exp >> $i) & 1) == 1) {$accum = $this->mo(($accum * $basepow2), $modulus);}
			$basepow2 = $this->mo(($basepow2 * $basepow2), $modulus); $i++;
		} return $accum;
	} private function calcD($e, $n) {
		for ($i = 1; $i < $n; $i++) {$d = ($i * $e) % $n; if ($d == 1) {return $i;}}
	}
	
	// GENERATE PRIVATE KEY (list 64 unique chars [A-Za-z0-9-_])
	public function gen_PKey() {
		$str = ''; $k = 0;
		$key = 'PWonG9wj4DydQqArkTgI38xNOp2SeFcsmL-0lRK_t5EJvfMCZBUbV1zY7hXH6uia';
		while (strlen($str) < 63) {
			srand((double) microtime() * 99999);
			$var = rand(1, strlen($key));
			$str .= isset($key[$var]) ? $key[$var] : '';
			$key = isset($key[$var]) ? str_replace($key[$var], '', $key) : $key;
		} return $str.$key;
	}
	
	// SET PKEY1 & PKEY2
	public function setPkey1($key) {$this->pkey1 = $key;}
	public function setPkey2($key) {$this->pkey2 = $key;}
	
	// GENERATE RSA KEYS
	private $last_keys = '';
	public function get_LastPair() {return $this->last_keys;}
	public function genPair($numPair = 1) {
		# FIRST 50 PRIMES  - LIMITATION FOR OTHER PRIMES (char convertion in hexdec must be a 32bits [4chars max])
		$prime = [3,7,11,13,17,19,23,29,31,37,41,43,47,53,59,61,67,71,73,79,83,89,97,101,103,107,109,113,
			127,131,137,139,149,151,157,163,167,173,179,181,191,193,197,199,211,223,227,229,233,239];
		srand((double) microtime() * 99999);
		for ($i = 0; $i < $numPair; $i++) {
			$var1 = rand(0, count($prime)-1);
			$x = -1; $num = 0;
			while ($x == -1) {
				$num = rand(0, count($prime)-1);
				if ($num != $var1 && abs($num - $var1) > 2) {$x = $num;}
			} if ($var1 > $x) {$var2 = $var1; $var1 = $x;} else {$var2 = $x;}
			$num1 = $prime[$var1]; $num2 = $prime[$var2]; $index = rand($var1+1, $var2-1);
			$e = $prime[$index];
			$m = $num1 * $num2;
			$n = ($num1-1) * ($num2-1);
			$d = $this->calcD($e, $n);
			$this->last_keys .= '['.$m.','.$e.','.$d.']';
			echo '['.$m.','.$e.','.$d.']';
			if ($i <> $numPair-1) {
				$this->last_keys .= ',<br />'; echo ',';
			} echo '<br />';
		}
	}
	
	// RUKKA ENCRYPTION + str_rot13
	public function erukka ($text, $ignore = true) {
		$result = ''; $k = 0;
		if (empty($text)) {return $result;}
		$key1 = $this->pkey1;
		$key2 = $this->pkey2;
		if (strlen($key1) != 64) {echo "Bad PKEY1 length"; return null;}
		if (strlen($key2) != 64) {echo "Bad PKEY2 length"; return null;}
		for ($i = 0; $i < strlen($text); $i++) {
			$k += ord($key1[$i % 63]); $kpos = $this->mo($k, 64); $index = strpos($key1, $text[$i]);
			if ($index !== false) {
				$pk = ord((chr($index) ^ $key1[(int)$kpos]) & chr(63));
				$result .= $key2[$pk];
			} else if ($ignore != true) {
				$char = urlencode($text[$i]);
				$char = str_replace('%', '$', $char);
				$result .= $char;
			} else {$result .= chr(0);}
		} return str_rot13($result);
	}
	
	// RUKKA DECRYPTION
	public function drukka($text) {
		$str = '';  $str1 = ''; $result = ''; $k = 0;
		if (empty($text)) {return $result;}
		$key1 = $this->pkey1;
		$key2 = $this->pkey2;
		if (strlen($key1) != 64) {echo "Bad PKEY1 length"; return null;}
		if (strlen($key2) != 64) {echo "Bad PKEY2 length"; return null;}
		$text = str_rot13($text); $text = str_replace('$', '%', $text); $text = urldecode($text);
		for ($i= 0; $i < strlen($text); $i++) {
			$k += ord($key1[$i % 63]); $kpos = (int)$this->mo($k, 64);
			$index = strpos($key2, $text[$i]);
			if ($index !== false) {
				$pk = ord((chr($index) ^ $key1[(int)$kpos]) & chr(63));
				$result .= $key1[$pk];
			} else if ($text[$i] != chr(0)){$result .= $text[$i];}
		} return $result;
	}
	
	// DECRYPT STRING USING RSA+RUKKA
	public function rdecrypt($str, $key = '', $rukka = true) {
		$nRSA = count($this->rsa);
		if ($rukka) {$str = $this->drukka($str, $key);}
		$n = ceil(strlen($str)/4); $txt = '';
		for ($i=0; $i < $n; $i++) {
			$k = ($i*4);
			$val = substr($str, $k, 4);
			$val = hexdec($val);
			$ks = $i % $nRSA; $x = '';
			$x = $this->powmod($val, $this->rsa[$ks][2], $this->rsa[$ks][0]);
			$txt .= chr($x);
		} return $txt;
	}
	
	// ENCRYPT STRING USING RSA+RUKKA
	public function rencrypt($str, $key = '', $rukka = true) {
		$txt = ''; $nRSA = count($this->rsa);
		for ($i = 0; $i < strlen($str); $i++) {
			$val = ord(substr($str, $i, 1));
			$ks = $i % $nRSA; $x = '';
			$x = $this->powmod($val, $this->rsa[$ks][1], $this->rsa[$ks][0]);
			$res = dechex($x);
			if (strlen($res) == 1) {$res = '000'.$res;}
			if (strlen($res) == 2) {$res = '00'.$res;}
			if (strlen($res) == 3) {$res = '0'.$res;}
			$txt .= $res;
		} if ($rukka) {return $this->erukka($txt, $key);} else {return $txt;}
	}
}

?>