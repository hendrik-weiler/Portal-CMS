# domain:null
<IfModule mod_rewrite.c>
    RewriteEngine on

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    RewriteRule ^(.*)$ index.php/$1 [L]

    Options +SymLinksIfOwnerMatch
    RewriteCond %{HTTP_HOST} ^|domain|
    RewriteRule (.*) http://www.|domain|/$1 [R=301,L]
</IfModule>