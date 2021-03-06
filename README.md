wp2md
=====

A basic utility for converting WordPress readme markdown to GitHub markdown


Installation
------------

To install, clone/download everything in the repository, then upload it to your server (or run it on your own machine). It doesn't use a database, so as long as you've got an Apache/Nginx server with PHP, you should be able to get it running with minimal configuration.

I will eventually flesh out the CLI handler, but for now it does nothing.


Usage
------

The tool is fairly straight forward. Browse to it, and it will show you an interface where you can specify a README file to convert. You can give it a URL, upload a file, or paste into a textarea.

If you don't want to install it yourself, you can try out the version I have running on my website: http://www.itsananderson.com/wp2md/

A few examples you can try out:

* Plugin Readme Standard: http://wordpress.org/extend/plugins/about/readme.txt
* Hello Dolly (basic example): http://plugins.svn.wordpress.org/hello-dolly/trunk/readme.txt
* Jetpack (complex example): http://plugins.svn.wordpress.org/jetpack/trunk/readme.txt

Known Issues
------------

- Non-default sections should be grouped under an "Other Notes" section
- Video embeds [youtube] [vimeo] and [wpvideo] aren't handled gracefully
