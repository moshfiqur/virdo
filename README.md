# virdo

When I was working as a web developer, I had to create virtual domain on my localhost every now and then. Though it was not really a hard task, but it was a boring task nonetheless to do it manually everytime. So, I wrote a script, which automates all the steps of creating a virtual domain on apache2. I was working mostly with PHP, so I wrote that script in PHP.

This script will work on Linux based system, I used it on Ubuntu distros speicifically. The code is quite straight forward, can be easily converted to work on other OS as well. 

The script must run as root, otherwise no domain creation, restarting apache will work. The script can be run like the following command:

```
php create_virdo.php domain_name
```
