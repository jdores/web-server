## Simple Apache2 web server for demo purposes

This is a simple Apache2 web server used for doing Cloudflare demo purposes.
It allows the user to download a PDF file, leave a comment in a text input file and upload a document.

## Installation instructions

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
Remove the index.html file and add the files in this:
```
rm index.hmtl
<copy files from this github into the folder>
```
Ensure the right permissions for the sase_reference.pdf file:
```
sudo chmod 644 /var/www/html/sase_reference.pdf
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
<adjust these values>
    upload_max_filesize = 10M
    post_max_size = 12M

sudo systemctl restart apache2

php -i | grep upload_max_filesize
php -i | grep post_max_size
```