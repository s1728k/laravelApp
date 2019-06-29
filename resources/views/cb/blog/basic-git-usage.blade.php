<div class="jumbotron">
	<h1>Basic Git Usage</h1>
	<h2>Step 1: Install Git</h2>

	<a class="btn btn-default" href="http://msysgit.github.io/">Install For Windows</a>
	<a class="btn btn-default" href="http://git-scm.com/book/en/Getting-Started-Installing-Git">Install For Linux</a>
	<a class="btn btn-default" href="http://git-scm.com/download/mac">Install For Mac OS</a>



	<h2>Step 2: Create github account</h2>
	<p>Visit the <a href="https://github.com/join?source=header-home">github</a> create page and create new account</p>

	<h2>Step 3: Create new repository</h2>
	<p>Press <i>Start Projcet</i> button to add new repository. fill the name of the repository(project), description. Select the public option and check the <i>Initialize this repository with a README</i>.
	and press <i>Create repository button.</i></p>

	<h2>Step 4: Clone the repository in your local folder</h2>
	<p>Visit the front page of your respository in github. press the <i>Clone or download</i> button and copy the url. Visit local folder in the terminal (command prompt in case of windows) where you want to clone the project and type </p>

	<pre style="font-size: 20px">git clone &lturl&gt</pre>

	<p>Now that you have clone the repository in you local machine. Enter the folder name of project cloned. You can add project skeliton folders and files to it and make changes as required.</p>

	<h2>Step 5: Pushing your code after changes made to your local repository (project)</h2>
	<p>Enter the folder name of project cloned in the terminal and use the following code to push the changes you made to project. </p>

	<pre style="font-size: 20px">git add .
git commit -m "commit message stating what changes you made for refference"
git push</pre>

	<h2>Step 6: Pull the code from your github repository to accept changes made by your co-worker</h2>
	<p>Enter the folder name of project cloned in the terminal and use the following code to pull the changes made by your co-worker to project. </p>

	<pre style="font-size: 20px">git add .
git commit -m "commit message stating what changes you made for refference"
git pull</pre>

	<h2>Step 7: Merge Conflicts</h2>
	<p>You may notice some merge conflicts if the same file is altered by your co-worker. you need to resolve manualy or using ide featurs. </p>
</div>