# Customer-Loyalty-Program

The customer loyalty program for the CRM system.

# Setting up this application

# # in phpMyAdmin

1. Database name `crm`
2. Database username `mysql`

# # in CLI

1. Rollback previous migrations `php artisan migrate:reset`
2. Migrate all migration files `php artisan migrate`
3. Seed all seeder files `php artisan db:seed`
4. Rollback, migrate, and seed `php artisan migrate:refresh --seed`

# # Misc

1. Clear all caches `php artisan optimize:clear`
2. Clear application cache `php artisan cache:clear`
3. Clear route cache `php artisan route:clear`

## authentication

four guards: customer, admin, marketingStaff and supportStaff
four model: customer, admin, marketingStaff and supportStaff

## admin authentication

admin only can sign up for one time
url: localhost/register/admin
url: localhost/login/admin
admin cannot login from localhost/login/student

## authorization

gate: 'isAdmin'
only admin can

gate: 'hasLogined'
only logined user (admin and student) can
return 404 not found for non-admin

## session

cannot retrieve what guard is used for authentication
so create a session to remember the role of the user

role: to show which guard is logined, will be deleted after logout

## flash session

message: to show updated successfully message in user profile
