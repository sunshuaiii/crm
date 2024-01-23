# Project Descriptions
A CRM system with a customer loyalty program designed for the customers and the staff of the retail stores, including marketing staff, support staff and the admin. Implemented unsupervised clustering algorithms for precise customer segmentation using K-Means Clustering with RFM Modelling using actual transaction data.

# Setting up this application

# # in pgAdmin 4
1. Database name                    `clp`
2. Database username                `postgres`
2. Database password                `root`

# # in CLI
1. Rollback previous migrations     `php artisan migrate:reset`
2. Migrate all migration files      `php artisan migrate`
3. Seed all seeder files            `php artisan db:seed`
4. Rollback, migrate, and seed      `php artisan migrate:refresh --seed`    

# # Misc
1. Clear cache                      `php artisan optimize:clear`
2. Clear route cache				`php artisan route:cache`
