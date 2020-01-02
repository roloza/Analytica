# Analytica

---
### Importer jQuery
```
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
```

---
### Ajouter dans le head
```
<script type="text/javascript">
    var timerStart = Date.now();
</script>
```

---
### Ajouter en fin de body
```
<script src="###SITE###/js/stats.min.js"></script>
```

---
### Récupérer GEOIP (GeoLite2-City.mmdb)

[https://dev.maxmind.com/geoip/geoip2/geolite2/](https://dev.maxmind.com/geoip/geoip2/geolite2/)

---
### Modifier le fichier stats.php
Initialiser la bdd
```
$database = new medoo([
	'database_type' => 'mysql',
	'database_name' => '',
	'server' => '',
	'username' => '',
	'password' => '',
	'charset' => 'utf8',
]);
```

---
### Format BDD
```
desc stats;
+------------------+--------------+------+-----+---------+----------------+
| Field            | Type         | Null | Key | Default | Extra          |
+------------------+--------------+------+-----+---------+----------------+
| id               | int(11)      | NO   | PRI | NULL    | auto_increment |
| ident            | bigint(20)   | NO   |     | NULL    |                |
| ip               | varchar(255) | YES  |     | NULL    |                |
| url              | varchar(255) | YES  |     | NULL    |                |
| referer          | varchar(255) | YES  |     | NULL    |                |
| refererDomain    | varchar(255) | YES  |     | NULL    |                |
| refererType      | varchar(255) | NO   |     | NULL    |                |
| loadHtml         | int(11)      | YES  |     | NULL    |                |
| loadDom          | int(11)      | YES  |     | NULL    |                |
| timeVisiteUrl    | int(11)      | YES  |     | NULL    |                |
| resolution       | varchar(255) | NO   |     | NULL    |                |
| resolutionWindow | varchar(255) | NO   |     | NULL    |                |
| platform         | varchar(255) | NO   |     | NULL    |                |
| platformType     | varchar(255) | NO   |     | NULL    |                |
| browser          | varchar(255) | NO   |     | NULL    |                |
| isRobot          | tinyint(1)   | NO   |     | NULL    |                |
| countryName      | varchar(255) | NO   |     | NULL    |                |
| departmentName   | varchar(255) | NO   |     | NULL    |                |
| cityName         | varchar(255) | NO   |     | NULL    |                |
| latitude         | float        | NO   |     | NULL    |                |
| longitude        | float        | NO   |     | NULL    |                |
| updated_at       | datetime     | NO   |     | NULL    |                |
| created_at       | datetime     | NO   |     | NULL    |                |
+------------------+--------------+------+-----+---------+----------------+
23 rows in set (0.00 sec)
```

---
Creation des tables
```
CREATE DATABASE IF NOT EXISTS analytica_recettepateacrepe;

CREATE TABLE IF NOT EXISTS `stats` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ident` bigint( 20 ) UNSIGNED NOT NULL,
    `ip` varchar(255) NULL,
    `url` varchar(255) NULL,
    `referer` varchar(255) NULL,
    `refererDomain` varchar(255) NULL,
    `refererType` varchar(255) NOT NULL,
    `loadHtml` int(11) NULL,
    `loadDom` int(11) NULL,
    `timeVisiteUrl` int(11) NULL,
    `resolution` varchar(255) NOT NULL,
    `resolutionWindow` varchar(255) NOT NULL,
    `platform` varchar(255) NOT NULL,
    `platformType` varchar(255) NOT NULL,
    `browser` varchar(255) NOT NULL,
    `isRobot` tinyint(1) NOT NULL,
    `countryName` varchar(255) NOT NULL,
    `departmentName` varchar(255) NOT NULL,
    `cityName` varchar(255) NOT NULL,
    `latitude` float NOT NULL,
    `longitude` float NOT NULL,
    `updated_at` datetime NOT NULL,
    `created_at` datetime NOT NULL,

    PRIMARY KEY(id)
)
CHARACTER SET utf8 COLLATE utf8_general_ci;
```
