# Security Plugin for WordPress

## Plugin Features
* Stop user enumeration (?author=1)
* Remove version param from any enqueued scripts
* Remove version meta tag and from rss feed
* Remove unnecessary header information
* Remove error message in login
* Remove various feeds
* Remove xpingback header
* Disable redirect to login page
* Disable ping back scanner and complete xmlrpc class

## Additional security config
Add the following code into your .htaccess

```apacheconf
<Files wp-config.php,.htaccess,.svn,error_log>
	order allow,deny
	deny from all
</Files>

<IfModule mod_rewrite.c>
	# Block User ID Phishing Requests
	RewriteCond %{QUERY_STRING} ^author=([0-9]*)
	RewriteRule .* /? [L,R=302]
	
	# Block Info-Scans
	RewriteRule (?:readme|license|changelog|-config|-sample)\.(?:php|md|txt|html?) - [R=404,NC,L]
	RewriteRule \.(?:psd|log|cmd|exe|bat|c?sh)$ - [NC,F]
</IfModule>


#Redirecting common scanner bots as well as malacious input to where they should be targeted to.
<IfModule mod_rewrite.c>
	RewriteCond %{HTTP_USER_AGENT} ^w3af.sourceforge.net [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} dirbuster [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} nikto [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} wpscan [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} SF [OR]
	RewriteCond %{HTTP_USER_AGENT} sqlmap [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} fimap [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} nessus [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} whatweb [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} Openvas [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} jbrofuzz [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} libwhisker [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} webshag [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (havij|Netsparker|libwww-perl|python|nikto|curl|scan|java|winhttp|clshttp|loader) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (;|<|>|'|"|\)|\(|%0A|%0D|%22|%27|%28|%3C|%3E|%00).*(libwww-perl|python|nikto|curl|scan|java|winhttp|HTTrack|clshttp|archiver|loader|email|harvest|extract|grab|miner) [NC,OR]
	RewriteCond %{HTTP:Acunetix-Product} ^WVS
	RewriteCond %{REQUEST_URI} (<|%3C)([^s]*s)+cript.*(>|%3E) [NC,OR]
	RewriteCond %{REQUEST_URI} (<|%3C)([^e]*e)+mbed.*(>|%3E) [NC,OR]
	RewriteCond %{REQUEST_URI} (<|%3C)([^o]*o)+bject.*(>|%3E) [NC,OR]
	RewriteCond %{REQUEST_URI} (<|%3C)([^i]*i)+frame.*(>|%3E) [NC,OR]
	RewriteCond %{REQUEST_URI} base64_(en|de)code[^(]*\([^)]*\) [NC,OR]
	RewriteCond %{REQUEST_URI} (%0A|%0D|\\r|\\n) [NC,OR]
	RewriteCond %{REQUEST_URI} union([^a]*a)+ll([^s]*s)+elect [NC]
	RewriteRule ^(.*)$ http://127.0.0.1 [R=301,L]
</IfModule>
