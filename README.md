### MPDTunes - Free your music, own your cloud

Official Site: www.mpdtunes.com

The only MPD client you'll need. Built with HTML5 and jQuery Mobile for an intuitive, platform independent client to stream music to any device with a browser.

To share with your friends on facebook, or just to simply show that you like MPDTunes: 
http://www.facebook.com/mpdtunes

Follow MPDTunes on twitter to get notifications of updates in the future.  Or mention MPDTunes to send us feedback.
https://twitter.com/mpdtunes

### To set up MPDTunes with a clone from the git repository

```shell
cd /var/www/whatever.com/
git clone git://github.com/dcarrith/mpdtunes.git htdocs
```

### To set up MPDTunes from the downloaded tar.gz file

```shell
cd /var/www/whatever.com/
tar -xvf mpdtunes.tar.gz
```

### Set the ownership, file and directories permissions for htdocs
```shell
sudo chown -R apacheuser:apachegroup htdocs/
sudo find htdocs/ -type d -exec chmod 770 {} \;
sudo find htdocs/ -type f -exec chmod 664 {} \;
```

### Proceed with the rest of the initial setup

Install composer.phar into the document root of your site
```shell
cd /var/www/whatever.com/htdocs/
curl -sS https://getcomposer.org/installer | php
```

Install all dependencies for MPDTunes
```shell
php composer.phar install
```

Set the domain in app/config/session.php 
Set the base_domain, base_site_title and admin_email in app/config/server.php
Set any other configurations that are specific to your environment.  

The main configuration files are located at app/config/

Make sure you manually set up the base music directory structure you'll be using as the main MPD music directory (for example /nfs/music/).  Then update the appropriate line in the app/config/defaults.php file:

```php
// default base music directory - with beginning slash
$config['default_base_music_dir'] = "/nfs/music/";
```

Then, just make sure that the uploads directory exists (for example /nfs/music/uploads/) and the relavent line is set in the app/config/defaults.php file.  Also, make sure it's not in a directory that is resolvable in the browser:

```php
// default base uploads directory - with beginning slash - NOTE: this should be outside of a browser's reach
$config['default_base_uploads_dir'] = "/nfs/music/uploads/";
```

### Setting up the database schema and user

```sql
CREATE SCHEMA mpdtunes;

CREATE USER 'mpdtunes'@'localhost' IDENTIFIED BY 'E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI';
CREATE USER 'mpdtunes'@'127.0.0.1' IDENTIFIED BY 'E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI';
CREATE USER 'mpdtunes'@'192.168.1.10' IDENTIFIED BY 'E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI';

// Or, if you already have a user you want to use, you may need to reset the password
SET PASSWORD FOR 'mpdtunes'@'localhost' = PASSWORD('E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI');
SET PASSWORD FOR 'mpdtunes'@'127.0.0.1' = PASSWORD('E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI');
SET PASSWORD FOR 'mpdtunes'@'192.168.1.10' = PASSWORD('E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI');

GRANT ALL ON mpdtunes.* TO 'mpdtunes'@'localhost' IDENTIFIED BY 'E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL ON mpdtunes.* TO 'mpdtunes'@'127.0.0.1' IDENTIFIED BY 'E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL ON mpdtunes.* TO 'mpdtunes'@'192.168.1.10' IDENTIFIED BY 'E$YQE!1ojUXDIj^D3eRfW0JecKCSDgfI' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
```

### Running the database setup

You'll need to enter your database credentials in app/config/database.php before you will be able to run the migration.  So, do that, and then run the migration.

Create all the database tables and default records for the base site:
```shell
cd /var/www/whatever.com/htdocs/
php artisan migrate
```

Before logging in for the first time, you have to make sure to edit the mpd.conf file for the master account.

```shell
vi publc/mpd/master/mpd.conf
```

Set the paths for music_directory, playlist_directory, db_file, log_file, pid_file and state_file to match your environment.  You may also need to set the bind_to_address, port and some of the other settings to match your environment as well.  When you log in, it will attempt to start up mpd using the default path to mpd binary as set in the app/config/defaults.php config file.  MPD will then read through your music_directory and generate the mpd.db file.  

Now, you should be able to load the site and log in with the following credentials:
```shell
username: admin@mpdtunes.com
password: o_tRy5dAi_wh
```

