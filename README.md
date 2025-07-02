## Simple Apache web server for demo purposes

This is a simple Apache web server used for doing Cloudflare demo purposes.
It allows the user to download a PDF file, leave a comment in a text input file and upload a document.

### Installation instructions

Assuming an Ubuntu-based system, install Apache and php with the following commands:

```
sudo apt update
sudo apt install apache2 -y
sudo apt install php libapache2-mod-php -y
```

Enable and start Apache:
```
sudo systemctl enable apache2
sudo systemctl start apache2
```

Change into sudo mode and navigate do the apache server folder:
```
sudo su
cd /var/www/html
```
Remove the index.html file and add the files from this github repository:
```
rm index.hmtl
<copy files from this github into the folder>
```
Ensure the right permissions for the sase_reference.pdf and dlp_test.txt file:
```
sudo chmod 644 /var/www/html/sase_reference.pdf
sudo chmod 644 /var/www/html/dlp_test.txt
```
For file uploads, create an uploads directory, ensure it is writable by the web server and secure the upload directory so that direct execution of uploaded scripts is not possible:
```
sudo mkdir -p /var/www/html/uploads
sudo chown -R www-data:www-data /var/www/html/uploads
sudo chmod -R 755 /var/www/html/uploads

sudo vi /var/www/html/uploads/.htaccess
<content of the file>
# Disable script execution
Options -ExecCGI
AddHandler cgi-script .php .pl .py .sh
```
Adjust size of PHP file uploads
```
php -i | grep upload_max_filesize
php -i | grep post_max_size

vi /etc/php/7.4/cli/php.ini
<adjust to these values>
    upload_max_filesize = 10M
    post_max_size = 12M

sudo systemctl restart apache2

php -i | grep upload_max_filesize
php -i | grep post_max_size
```
For file sandboxing use case, a php module needs to be installed and the permissions adjusted:
```
sudo apt install php-zip
sudo phpenmod zip

sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/

sudo systemctl restart apache2
```

For AV scanning use case, the eicarcom2.zip file from [eicar.org](https://www.eicar.org/download-anti-malware-testfile/) needs to be placed in the /var/www/html/ folder.

### (Optional) Serve the Apache web server over HTTPS (port 443) using a self-signed certificate
Ensure Apache has the SSL module instaled:
```
sudo apt update
sudo apt install apache2 openssl -y
sudo a2enmod ssl
```
Generate a Self-Signed SSL Certificate, when asked for the Common Name (CN) provide the internal DNS name for this server (for example, webapp.jdores.internal)
```
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt

<Example>
(...)
Country Name (2 letter code) [AU]:
State or Province Name (full name) [Some-State]:
Locality Name (eg, city) []:
Organization Name (eg, company) [Internet Widgits Pty Ltd]:
Organizational Unit Name (eg, section) []:
Common Name (e.g. server FQDN or YOUR name) []:webapp.jdores.internal
Email Address []:
```
Edit the Apaphe SSL configuration file:
```
sudo vi /etc/apache2/sites-available/default-ssl.conf
```
Content of the file should be:
```
<VirtualHost *:443>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/apache-selfsigned.crt
    SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
Enable SSL site and restart apache:
```
sudo a2ensite default-ssl
sudo systemctl restart apache2
```