# Task:
 - Process the attached CSV into a database table using Object Oriented PHP, you are free to use any framework
 - Write you code using PSR2 coding standards
 - Provide the SQL to create the database table
 - Provide instructions to run your code
 - Write unit tests,

# CSV-File-Importer
simple CSV importer


 ## Installation Steps
  
 - Clone this repo to your local machine
 - Open terminal and type: ```composer install```
 - Create the `.env` from the `.env.example`   
 - Edit the `.env` file to point at your MYSQL database 
 - ```php artisan key:generate```
 - ```php artisan migrate```
 
 
 
 ## Usage
 
 After the Laravel app is installed you can use  `php artisan import:stock` command.
 
 
 ## Running Tests:
 
 To run the tests please run `vendor/bin/phpunit` command form root of the project.
 
 ## Database SQL Query 
```sql

 CREATE TABLE `stock_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `discontinued` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_items_product_code_unique` (`product_code`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
 
 
