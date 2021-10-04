####Build basic Laravel (back end)/Vue (front end) project that can show the functionality outlined below. Please supply a link to the app project GitHub account as part of your submission.
                
1.  Basic user authentication
2.  User can create/edit/soft-delete a task
3.  List of tasks can be reordered by the user
4.  Task should have the option to have sub-tasks (sub-tasks can have sub-task up to infinity)
5.  Task can be marked as complete/pending/cancelled/(custom)
6.  Tasks should only be accessible to owner
7.  Ability to download all the tasks in Excel/CSV/JSON format
8.  Ability to restore soft deleted tasks
9.  Soft deleted tasks should be forced delete after 30 days
10. Chart to see users completed tasks

####Steps to install the application
* Set the .env file
* composer install
* npm install
* npm run dev
* php artisan migrate
* php artisan db:seed
* Run the scheduler command for the item number 9 above located at "castle-tech/app/Console/Commands/delUser30days.php"
* php artisan serve
	* u:admin@gmail.com p:123123123 <-- admin account 
