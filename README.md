# CinkoMVC
## Author: Corey Cinkosky &bull; Version 1.0 &bull; [Demo](https://mvc.redzoneassault.com/)
A light weight PHP MVC framework with React via CDN setup on the front end. Created with simplicity in mind, it's an easy tool to get associated with MVC frameworks and/or React while still being able to work with a good old LAMP stack. The front end is currently setup as a single page React app while the back end acts as an API that serves up JSON for the front end to consume.

## Setup
The app should work right out of the box. Just load it up on your server and point your domain to the **public** folder. Make sure that **public/index.php** has access to the parent directory. If you run into trouble with access to the parent directory in a LAMP environment, you can modify your apache 2 configuration file and set it so that the base directory is the parent directory while the document root is pointed to the **public** folder. In the example below a **public_html** folder (the parent folder) is the base directory the applciation sits in, while the **public** folder is the document root.

 ``` xml
<VirtualHost 1.2.3.4:8080>
    
    ServerName your-domain.com
    
    # You'll want to set the DocumentRoot to point to your public folder
    DocumentRoot /home/some_user/web/your-domain.com/public_html/public/

    ...

    # Inside the Directory tag associated with the public folder you want
    # to make sure the php_admin_value open_basedir has the parent directory
    # of your public folder, in this case public_html
    <Directory /home/some_user/web/your-domain.com/public_html/public>
        AllowOverride All
        Options +Includes -Indexes +ExecCGI
        php_admin_value open_basedir /home/some_user/web/your-domain.com/public_html:/home/some_user/tmp
        
        ...

    </Directory>

    ...

</VirtualHost>
```
There are also two full apache2.conf examples in the repo, one for http and the other for https. Once you've got the repo loaded and your domain pointing to the public folder, pull up the app in your browser and you should see this: **[Demo](https://mvc.redzoneassault.com/)**

## Front End App
The front end is currently setup as a single page React app. It utilizes the following:
 * React
 * Babel
 * React Router
 * jQuery
 * Font Awesome
 * Markdown-it
 * github-markdown-dark
 * Pure CSS
 * Google Fonts

You can edit what CSS, JavaScript and React components are included by modifying the corresponding sections in the **config.json** file. The example below shows what is currently in the **config.json** file. For the React components, just make sure that your call to **ReactDOM.render()** is in the last component in the list.

``` json
"css" : [
    "<link rel='stylesheet' href='https://unpkg.com/purecss@2.0.6/build/pure-min.css' crossorigin>",
    "<link rel='stylesheet' href='/assets/css/github-markdown-dark.css'>",
    "<link rel='stylesheet' href='/assets/css/style.css'>"
],
"scripts" : [
    "<script src='https://code.jquery.com/jquery-3.6.0.min.js' crossorigin='anonymous'></script>",
    "<script src='https://kit.fontawesome.com/2ed00b3ff5.js' crossorigin='anonymous'></script>",
    "<script src='https://unpkg.com/react@17/umd/react.production.min.js' crossorigin></script>",
    "<script src='https://unpkg.com/react-dom@17/umd/react-dom.production.min.js' crossorigin></script>",
    "<script src='https://unpkg.com/react-router-dom@5.1.0/umd/react-router-dom.min.js' crossorigin></script>",
    "<script src='https://unpkg.com/@babel/standalone/babel.min.js'></script>",
    "<script src='https://cdnjs.cloudflare.com/ajax/libs/markdown-it/12.3.2/markdown-it.min.js'></script>"
],
"reactComponents" : [
    "<script src='/components/SplashPage.js' type='text/babel'></script>",
    "<script src='/components/ReadMe.js' type='text/babel'></script>",
    "<script src='/components/CinkoMvcApp.js' type='text/babel'></script>"
]
```

## Code Flow
 1. User visits the home page in a browser and they hit **public/index.php**.

 2. **public/index.php** then calls **Loader.php** which proceeds to load the rest of the application.

 3. **Loader.php** includes **AppObject.php** which is the base object that **AbstractController** and **AbstractModel** extend. It handles loading the **config.json** file and connecting to the database (if databaseOn = true in **config.json**), it also adds helpers for handling cookies, redirection and sending template emails utilizing the views in the **views/emails** directory.

 4. **Loader.php** includes everything in the **library**, **models** and **controllers** directories.

    * **library/Route.php** : This is a static class used to handle the routes, used in **Routes.php**.

    * **models/AbstractModel.php** : Your models should extend this class. It adds helpers to your models. Functions that make SELECT, INSERT, UPDATE and DELETE easier for the corresponding table.

    * **models/AppUsersModel.php** : This is an example model showing how to use the helper functions that are added by **AbstractModel.php**. It also has an example of how to use PDO to execute more complicated queries.

    * **controllers/AbstractController.php** : Your controllers should extend this class. It adds helpers to your controllers. Functions for verifying common request mothods (GET, POST, PUT, DELTE), functions for fetching the body of different requests, as well as functions to handle views.

    * **controllers/IndexController.php** : This controller is used to provide content to the front end app via JSON views.

 5. **Loader.php** includes the **Routes.php** file. This file takes the URL and determines the route the code flow will take. By default it assumes the request method is GET, but you can specify that the route only works for other request methods, such as POST, PUT and DELETE. If there is a custom route added, it will attempt to follow the code outlined for that route. If the URL doesn't match a custom route, then it will try to match the snake_case URL, convert it to camelCase and try to find the controller->action. If there is no action in the URL, then it will attempt to call the IndexAction() function for that controller. For example:

    * your-domain.com/examples/list_names will call ExamplesController->ListNamesAction();

    * your-domain.com/examples/main will call ExamplesController->mainAction();

    * your-domain.com/examples will call ExamplesController->indexAction();

    If it still can't find a matching controller->action, then it will check to see if the request method is POST and the URL starts with /api/. If so, the it will attempt to match the URL to a controller->action like above. For example:

    * your-domain.com/api/examples/list_names will call ExamplesController->ListNamesAction()

    * your-domain.com/api/examples/main will call ExamplesController->mainAction()

    * your-domain.com/api/examples will call ExamplesController->indexAction()

 6. In **Routes.php** the route "/" is matched so it calls **(new AbstractController)->view("index");** This loads **views/index.php**, which is the view for the one page React app. **$this** (the instance for the controller) is also available to the view.

 7. **views/index.php** loads the CSS, JavaScript and React components.
    
    * **public/components/CinkoMvcApp.js** : This is the main React app component. It adds a custom navigation function and acts as the React app's internal router. If the URL is just the domain or "/", then it loads the **SplashPage** component. If the URL has /README.md, then it loads the **ReadMe** component.

        * **public/components/SplashPage.js** : This component loads the splash page. When the component is mounted, it makes a POST request to **/api/index/splash_page** which then calls **IndexController->SplashPageAction();** and fetches the JSON returned to poulate content on the splash page.

        * **public/components/ReadMe.js** : This component loads the readme page. When the component is mounted, it makes a POST request to **/api/index/read_me** which then calls **IndexController->ReadMeAction();** and fetches the JSON returned to poulate content on the readme page. It also converts the readme content from markdown to HTML.

## Routes
In order for the routing to work, your **public/.htaccess** file must have the following:
```
DirectoryIndex index.php

# enable apache rewrite engine
RewriteEngine on

# set your rewrite base
# Edit this in your init method too if you script lives in a subfolder
RewriteBase /

# Deliver the folder or file directly if it exists on the server
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
 
# Push every request to index.php
RewriteRule ^(.*)$ index.php [QSA]
```
#### Custom Routes
You can add custom routes to the **Routes.php** file. You'll see the route for the home page (which is "/"). Since this a single page React app, there is no need to create a custom controller since all we're doing is loading the index view, so we just call the view using an instance of **AbstractController**.
``` php
/**
 * Home page
 * 
 * Triggered by your-domain.com
 */
Route::add('/',function() {
    (new AbstractController)->view("index");
});
```

There are a couple additional examples in the **Routes.php** file to show a couple other ways you can use custom routes.
``` php
/*
 * Very basic action, no controller, no model, no view, just echo some text
 * 
 * Triggered by your-domain.com/super_basic
 */
Route::add('/super_basic',function() {
    echo "<b>Difficulty Level:</b> Basic";
});

/*
 * Same as above, but only for POST request method
 * 
 * Triggered by your-domain.com/super_basic
 */
Route::add('/super_basic',function() {
    echo "<b>Difficulty Level:</b> Basic";
},"post");

/*
 * This one doesn't call a controller, it just takes the URL parameters,
 * creates and deletes a user using the AppUsersModel, and echos some text.
 * This example will only work with a database and database credentials in the 
 * config.json file, plus a table created called app_users with at least 
 * id (int primary), email (varchar), password (varchar) and 
 * registration_date (int)
 * 
 * Triggered by your-domain.com/create_user
 */
Route::add('/create_user',function() {
    $appUsers = new AppUsersModel();
    $user_id = $appUsers->createUser($_GET['email'],$_GET['pass']);
    echo "<p>User ".$_GET['email']." created!</p>";
    $appUsers->deleteUser($user_id);
    echo "<p>User ".$_GET['email']." deleted :(</p>";
});
```

#### React Routes
The React app utilizes React Router to handle the app's internal routing. The router is in **public/components/CinkoMvcApp.js**. You can see how the routes are determined in the JSX returned by the component. In the **Route** component is the **path** property that the route is trying to match. If there is a match, then the component within the **Route** element is used.
```xml
<Route exact path="/">
    <SplashPage navigate={navigate} />
</Route>
<Route path="/README.md">
    <ReadMe navigate={navigate} />
</Route>
```

For this app we've added the function **navigate(loc)** which allows us to use jQuery's **fadeOut** and **fadeIn** effects when the route changes. We pass the **navigate(loc)** function to the component's **navigate** property to pass the function down to the child components. The function then becomes available to the child elements as **this.props.navigate()**. Both the **SplashPage** and **ReadMe** components set **this.navigate = this.props.navigate;** so that they can use the function simply as **this.navigate()**. They can then use it in a **Link** component like this:
```xml
<Link onClick={()=>this.navigate("/")}>Home</Link>
<Link onClick={()=>this.navigate("/README.md")}>README.md</Link>
```

The last part is the **ReactDOM.render()** function at the bottom of the script. In order to utilize **useHistory** for the routing, we need to wrap the **CinkoMvcApp** component within a **ReactRouterDOM.HashRouter** component.
```javascript
ReactDOM.render((
    <ReactRouterDOM.HashRouter>
        <CinkoMvcApp />
    </ReactRouterDOM.HashRouter>
    ), document.getElementById('app')
);
```

Here is **public/components/CinkoMvcApp.js** in full.
```javascript
const Link = ReactRouterDOM.Link;
const Route = ReactRouterDOM.Route;
const useHistory = ReactRouterDOM.useHistory;

function CinkoMvcApp () {

    const history = useHistory();

    function navigate (loc) {
        $(".component-container").fadeOut(300,function(){
            history.push(loc);
        });
    }

    return (
        <>
            <Route exact path="/">
                <SplashPage navigate={navigate} />
            </Route>
            <Route path="/README.md">
                <ReadMe navigate={navigate} />
            </Route>
        </>
    )
}

ReactDOM.render((
    <ReactRouterDOM.HashRouter>
        <CinkoMvcApp />
    </ReactRouterDOM.HashRouter>
    ), document.getElementById('app')
);
```
## Database Connection
By default, the database connection is turned off. However, to turn it on, just set the **databaseOn** value in **config.json** to **true** and enter the database connection info into the **database** array like below:
```javascript
"databaseOn" : true,
"database" : {
    "type" : "some_db_type",
    "host" : "some_host",
    "db"   : "some_db",
    "user" : "some_db_user",
    "pass" : "some_password"
}
```

The database connection is established using PDO, which allows for many database types:
 * MySQL
 * PostgreSQL
 * Oracle
 * Firebird
 * MS SQL Server
 * Sybase
 * Informix
 * IBM
 * FreeTDS
 * SQLite
 * Cubrid
 * 4D

 The database connection is established in **AppObject.php**. When an instance of **AppObject** is created, it uses it's **dbConnect()** function to establish the database connection and then returns the PDO object to be stored in **$this->db**. Anything that extends **AppObject**, **AbsstractCOntroller** or **AbstractModel** has access to **$this->db**.

## Models
When a model that extends **AbstractModel** is created it takes the class name, removes "Model" and switches it to snake_case to get the table name, it then stores it in **$this->table**. For example:
 * class **AppUsersModel** extends AbstractModel : **$this->table** will be **app_users**
 * class **AppUserMetaDataModel** extends AbstractModel : **$this->table** will be **app_user_meta_data**
 * You can always set **$this->table** to any table you want as well.

When you use the **select()**, **insert()**, **update()** and **delete()** functions in the model, they will be executed against the table stored in **$this->table**. Below is models/**AppUsersModel.php**. It has examples of how to use these functions to execute queries against your database.
```php
<?php
/**
 * This is an example of a model that extends AbstractModel
 * to show examples of the functions AbstractModel adds
 * 
 * Since the model is called AppUsersModel, it will assume
 * that the table it is working with is "app_users", so: 
 * 
 * $this->table = "app_users";
 * 
 * ...however you can change the table anytime like so:
 * 
 * $this->table = "some_other_table";
 */
class AppUsersModel extends AbstractModel {
    
    /**
     * This is an example function to insert a new
     * record into the app_users table. Something like this
     * could be used when a new user registers
     * 
     * This creates a query like:
     * 
     * INSERT INTO app_users (email, password, registration_date)
     * VALUES ('some_email@some-domain.com', 'some_password', 1234567890);
     * 
     * ...and returns the last_insert_id()
     * 
     * @param string $email
     * @param string $pass
     * 
     * @return int
     */
    public function createUser (string $email, string $pass) : int {
        return $this->insert([
            "email"             => $email,
            "password"          => md5($pass),
            "registration_date" => time()
        ]);
    }

    /**
     * This is an example function to select records by email
     * that registered after a certain date
     * 
     * This creates a query like:
     * 
     * SELECT id, email ,registration_date
     * FROM app_users
     * WHERE email = '$email'
     * AND registration_date >= $date;
     * 
     * @param string $email
     * @param int $date
     * 
     * @return array
     */
    public function getUserByEmailAfterDate (string $email, int $date) : array {
        return $this->select(
            $fields = ["id","email","registration_date"],
            $where = [
                ["email","=",$email],
                ["registration_date",">=",$date]
            ]
        );
    }

    /**
     * This function is an example of how to update a record.
     * This particular example could be used when a user
     * updates their password
     * 
     * This creates a query like:
     * 
     * UPDATE app_users SET
     * password = 'some_password'
     * WHERE id = 12345;
     * 
     * @param int $id
     * @param string $pass
     * 
     * @return void
     */
    public function updatePassword (int $id, string $pass) {
        $this->update(
            $pairs = ["password" => md5($pass)],
            $where = [["id","=",$id]]
        );
    }

    /**
     * This is an example function showing how to delete a record
     * 
     * This creates a query like:
     * 
     * DELETE FROM app_users WHERE id = 12345;
     * 
     * @param int $id
     * 
     * @return void
     */
    public function deleteUser (int $id) {
        $this->delete([
            $where = [["id","=",$id]]
        ]);
    }

    /**
     * This function is an example of how you can use the normal
     * PDO functionality (https://phpdelusions.net/pdo). 
     * Sometimes this is needed to do more complicated queries.
     * 
     * @param int $id
     * 
     * @return array
     */
    public function pdoExample (int $id) : array {
        $sql = "SELECT 
                au.email, 
                aumd.value as color_preference
                FROM app_users au, app_users_meta_data aumd
                WHERE au.id = aumd.user_id
                AND aumd.name = 'color_preference'
                AND au.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]); 
        return $stmt->fetchAll();
    }

}
```


## Views
Controllers that extend **AbstractController** have two extra functions that make it a little easier to display views. The first is **jsonView()**. This takes a PHP array and echos the JSON representation of the array. For example:
```php
$controller = AbstractController();
$controller->jsonView([
    "value1" => "1",
    "value2" => "2",
    "value3" => "3"
]);
```
...would echo the following with a header of "Content-Type: application/json; charset=utf-8":
```json
{
    "value1" : "1",
    "value2" : "2",
    "value3" : "3"
}
```
The second function is **view()**, which loads a file from the **views** directory. You simple pass in the name of the view. The first example below will load **views/index.php**, the second will load **views/example.php**. All instance variables available to the controller are available to the view. If the view was called from within a controller method, all variables from the controller method are available to the view as well.
```php
// Example 1
$controller = new AbstractController();
$controller->view("index");

// From within a controller method
$this->view("index");

// Example 2
$controller = new AbstractController();
$controller->view("example");

// From within a controller method
$this->view("example");
```

## Send Template Emails
Objects that extend **AppObject**, **AbstractController** or **AbstractModel** have a function called **sendTemplateEmail()** that makes it simple to send out HTML emails. You setup an HTML file in views/emails and then place any variables you want replaced between %%...%% like this: **%%my_variable%%**. 

In the example below, the function will send an email to "some_email@some-domain.com" from "some_other_email@your-domain.com" with a subject of "This is a test email" and using the **views/emails/TestEmail.html** template. The function will load the contents of the template into a string, then find **%%name%%** and replace it with **Some Name** and then find **%%address%%** and replace it with **123 Main St**. The updated string is then used as the body of the email.
```php
$this->sendTemplateEmail(
    $email = "some_email@some-domain.com",    
    $from = "some_other_email@your-domain.com",
    $subject = "This is a test email",
    $template = "TestEmail",
    $data = [
        "name" => "Some Name",
        "address" =>"123 Main St"
    ]
);
```
```
<!-- TestEmail.html -->
<html>
<head>
    <title>Test Email</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif">
    <p><b>This is a test email for:</b></p>
    <p>%%name%%<br />%%address%%</p>
</body>
</html>
```

## Handling Cookies
Objects that extend **AppObject**, **AbstractController** or **AbstractModel** have a few functions for handling cookies. **setCookie()**, **getCookie()** and **deleteCookie()**. Below are some examples of how to use them.
```php
// Save a string as a cookie
$this->setCookie("email","some_email@some-domain.com");

// Save an array as a cookie
$this->setCookie("user_info",[
    "name" => "Some Name",
    "email" => "some_email@some-domain.com",
    "address" => "123 Main St"
]);

// Get a cookie. If the cookie was a string, then a string will be
// returned. If it was an array, then an array will be returned.
$user_info = $this->getCookie("user_info");

// Delete a cookie
$this->deleteCookie("user_info");
```
## To Do
 * Add a few more comments to the React Components
 * Update the splash page component to include a button that links to the github repo
 * Update the splash page component to include links to clone or download
 * Add logo
 * Add loading screen
 * Add better error handling
 * Figure out linking to hash links to a page's internal content

## Additional Resources
 * [React Guide](https://reactjs.org/docs/hello-world.html)
 * [PDO Guide](https://phpdelusions.net/pdo)
 * [React Router Guide](https://www.pluralsight.com/guides/using-react-router-with-cdn-links)
 * [jQuery Docs](https://api.jquery.com/)
 * [Pure CSS Docs](https://purecss.io/)
