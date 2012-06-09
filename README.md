#Wordpress Dev Helper
###Built because I wanted a better/faster way to compress & minify JS files in Wordpress projects...

Sure there are tools to help us do the same thing, but none are as efficient in a real-world Wordpress workflow.

##What it does
- Loads individual JS files when in a development environment (i.e - your local machine)
- When you are ready to go live, click 1 button and all your JS files will be minified and joined.
- When it's live, your site will automatically call the single JS file and ignore everything else completely.

###It does jQuery too.
Come on, I know you're using jQuery on your Wordpress site anyway, let me handle it. (but you don't have to)

#Install
###Step 1 -
- Put the dev-helper directory in your theme folder.

###Step 2
- Include the php file in your functions.php `include('dev-helper/dev_helper.php')`

###Step 3
- Copy the config array see the file for instructions

###Step 4
- Instantiate the object `$helper = new dev_helper($config);`

#That's it!
- It's all automated now. You can keep all your JS files separate whilst in dev, and auto include the Compressed single file in production


