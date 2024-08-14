# Rukka Library 1.0

This is a library for encrypting and decrypting data with two pkey (**pkey1** and **pkey2**). 

Please contact [author] if you find any issue. It requires PHP 5.3 or newer.



## Tutorial


### Example 1

```php
$text = "Hello World.";
$rukka1 = new rukka();

$encrypted = $rukka1->rencrypt($text);
$decrypted = $rukka1->rdecrypt($encrypted);

encrypted: "O6RwO0JVPCOww6bJJOE6IGkJNva2axo52-T15g-V596s5Pu9"
decrypted: "Hello World."
```

### Example 2

```php
// GENERATE NEW pkey
$pkey1 = $rukka1->gen_PKey();

// Use this new pkey as a pkey1
$rukka2 = new rukka();
$rukka2->setPkey1($pkey1);
// pkey2 can be changed too: $rukka2->setPkey2($pkey2);

$encrypted = $rukka2->rencrypt($text);
$decrypted = $rukka2->rdecrypt($encrypted);

pkey1: "Q0nKca7UIqj8Vfr4ElGuYZ3bJ-FpHziSNkBgdwW6TLoXCxvtyDsOM5R1A9_m2heP"
encrypted: "4gotjNr_2ZI9VjQAv2W2zt_y_2svn6Y5q4EiIEkfcjae2yqy"
decrypted: "Hello World."
```

### Example 3

```php
// If you want change your RSA-KEYS

$rukka3 = new rukka();

// Genererate only 2 pairs
$rukka3->genPair(2);

// Thi will print 2 pairs, something like this:

[1921,41,1049],
[18721,137,11705]

// The only thing you need to do is:
// 1 - Copy 2 pairs
// 2 - Open rukka.php and find "private $rsa" and change it

private $rsa = [
	[1921,41,1049],
	[18721,137,11705]
];

```

**Close file** and **save it**

**Warning:** Use more than 4 pairs to make hard to decode.

---

Licence: [Attribution 4.0 International (CC BY 4.0)][1]

[1]: <http://creativecommons.org/licenses/by/4.0/>
[author]: Rukka71
