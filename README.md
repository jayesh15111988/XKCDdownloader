<h2> XKCDdownloader </h2>
==============
<h3><b>Disclaimer : All Comics and data are property of respective owner(s). I do neither claim nor I intend to steal someone's copyrighted work. Please let me know if you have any complaints related to it. </b> </h3>
<br/>
<p>
<b>
Note : Please note that <a href='http://jayeshkawli.com/xkcddownloader/index.html'> this </a> demo link points to my server where project is hosted. However, due to certain restrictions it won't be possible to execute certain PHP functions (such as file_put_contents and file_get_contents). Hence, it is advised to clone the repository on your local machine and run local PHP server with help of XAMPP or similar tool. More information on how to install and run XAMPP could be found on <a href='https://www.udemy.com/blog/xampp-tutorial/'> This </a> website.
</b>
</p>
<hr>
<p>
A simple project based on the PHP to download the xkcd comics based on the input range of comics to download.
Few months ago, I got addicted to xkcd comics and wondered if there could be better way to read them while I am riding in a public transportation (I did not have phone that time, so just looking for past time with this comics).
</p>
<br>
<p>
After few days, I wondered why wouldn't I download these comics for my personal collection? So I wrote a PHP script to download xkcd comics and store on my personal machine. And this is how I solved this problem. 
</p>
<br>
<p>
However, PHP script I wrote was very crude. It required users to run the script manually through command line or through browser window. It also lacked required controls over folder and files maintenance. This project tries to solve some of these issues.
</p>

<hr>
Some of the features of this project are as follows

1. Input range for comics sequence to download
2. Remove all comics from specified folder
3. Remove selective comics from specified folder
4. Interactive UI to allow users to customize how and which comics are downloaded
5. All comics in specific folder are cached, so duplicate request to download the same comic in the same folder is ignored
<hr>
<i>
In addition to it, if you want to see additional features included in the project / if you find any bugs let me know.
</i>
<p>
<a href='http://jayeshkawli.com/xkcddownloader/index.html'> Demo </a>
</p>
<p>
<hr>
<b>Resources : </b>
<ol>
<li> Index page design taken from : http://kaylarose.github.io/Glowform/ </li>
<li> xkcd images download source :  http://xkcd.com </li>
</ol>
