<h1>Monstera</h1>
An online plant watering tracker that implements a log in and sign up feature that allows you to either sign in as a member. Non-member has the ability to browse our comprehensive plant dictionary to better understand their plant’s needs. Member will have access to the ability to add plant to their own library and set up water reminder depending on each plant’s watering period.

<h1>Authors</h1>

Jennifer McNiel - Developer  
Yadira Cervantes - Developer  
Luke Cheng - Developer

<h1>Project Requirements</h1>

<h3>1. Separates all database/business logic using the MVC pattern.</h3>

<ul>
    <li>All HTML files are under the views folder and we implemented fat-free include block to include the footer, header and nav-bar.</li>
    <li>index.php calls function in Controller to get data from the model and return views.</li>
    <li>Routes to all the HTML files are under index.php</li>
    <li>Databases and validations are under the model folder</li>
    <li>Classes (indoor-plant, outdoor-plant, plant, member, plant-image) are under the classes folder</li>
    <li>All images as in our Image folder</li>
    <li>Styles folder holds all our code that is responsible for the page styling.</li>
    <li>Script folder holds all our code that is responsible for validating forms and requesting data from API.</li>
</ul>

<h3>2. Routes all URLs and leverages a templating language using the Fat-Free framework.</h3>

<ul>
    <li>All routes are in the index.php and are utilizing our controller class</li>
</ul>

<h3>3. Has a clearly defined database layer using PDO and prepared statements.</h3>

<ul>
    <li>Our data layer is under the model folder and incorporates PDO and has various prepared statements.</li>
</ul>

<h3>4. Data can be added and viewed.</h3>

<ul>
    <li>Database layer uses PDO and prepared statements to insert and get from the database. This can be seen at the plant library page which stores each user’s plant and its information.</li>
</ul>

<h3>5. Has a history of commits from all team members to a Git repository. Commits are clearly commented.</h3>

<ul>
    <li>Each teammate (Jennifer, Yadira, and Luke) have multiple commits that are well-commented in the Git repository.</li>
</ul>

<h3>6. Uses OOP, and utilizes multiple classes, including at least one inheritance relationship.</h3>

<ul>
    <li>For this Project, we utilized five classes: indoor-plant, outdoor-plant, member, plant, and plant-image</li>
    <li>indoor-plant extends plant and contains adoption date</li>
    <li>outdoor-plant extends plant and contains planted date and location</li>
    <li>member contains user name, email, password and user id</li>
    <li>plant contains member id, plant name, species name, water period, water date, files, and plant id</li>
    <li>plant-image contains image id, plant id, and path</li>
</ul>

<h3>7. Contains full DocBlocks for all PHP files and follows PEAR standards.</h3>

<ul>
    <li>Yes, all files include DocBlocks and follow PEAR standards. This can be seen all throughout our code.</li>
</ul>

<h3>8. Has full validation on the server side through PHP.</h3>

<ul>
    <li>User sign-up and log-in and add plant form are all validated with PHP and Javascript utilizing the validation file under the model script folder.</li>
</ul>

<h3>9. All code is clean, clear, and well-commented. DRY (Don't Repeat Yourself) is practiced.</h3>

<ul>
    <li>True</li>
</ul>

<h3>10. Your submission shows adequate effort for a final project in a full-stack web development course.</h3>

<ul>
    <li>We have learned a lot throughout this quarter about fullstack web development, especially using MVC architecture, PHP, and Git. We have applied these new skills to our project, making it better and improving our understanding of fullstack development.</li>
</ul>
