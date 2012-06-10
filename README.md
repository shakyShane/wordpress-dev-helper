#Wordpress Dev Helper
###Built because I wanted a better/faster way to compress & minify JS files in Wordpress projects...

Sure there are existing tools to help us do the same thing, but none are as efficient in a real-world Wordpress workflow.

##What it does
- Loads individual JS files when in a development environment (i.e - your local machine)
- When you are ready to go live, click 1 button and all your JS files will be minified and joined.
- When it's live, your site will automatically call the single JS file and ignore everything else completely.

##Why?
- Not all projects go 'live' just once - So build scripts are not always the best solution.
- Got a Wordpress project under a constant development/deployment cycle? This tool will help you.

#Install
##Seriously, just look at the sample `functions.php` in the repo.
####Step 1
- Put the dev-helper directory in your theme folder.

####Step 2 (in functions.php)
- Include the php file at the top `include('dev-helper/dev_helper.php')`

####Step 3 (in functions.php)
- Copy the config array see the file for instructions

####Step 4 (in functions.php)
- Instantiate the object `$helper = new dev_helper($config);`

#How to use it?
- When you want to add a new JavaScript file into your project, just add it into the `$config['scripts']` array. Do NOTHING else.
- Your files will be loaded in the order you specify (so check your dependencies) into the footer of your Wordpress site.

#Who's it for?
- This tool will be especially useful for developer who author/manage their own features/plugins on Wordpress sites.

#Not for...
- This is not ideal if you're only using other people's plugins. They'll be including JS files themselves & this tool is not designed to help with that.

#That's it!
- It's all automated now. You can keep all your JS files separate whilst in dev, and auto include the Compressed single file in production

###Notes
- It uses `$_SERVER['HTTP_HOST']` to determine whether or not you are in your dev mode.



