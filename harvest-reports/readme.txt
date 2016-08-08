=== Harvest Reports ===
Contributors: cpoteet
Tags: reports, harvest, administration, management
Requires at least: 2.0
Tested up to: 2.5b
Stable tag: 1.0

Leveraging the new API from Harvest you can embed a report in your WordPress admininstration for your customers.

== Description ==

If you use [Harvest](http://getharvest.com/) to manage time tracking then you can easily show your current time tracking to other team members or your clients.

You will need PHP5, because the plugin uses SimpleXML in PHP5 to parse the XML from the API.  Please visit the [plugin page](http://www.siolon.com/2008/harvest-reports-wordpress-plugin/) for instructions on enabling PHP5 via your .htaccess.

== Installation ==

1. Download the plugin
2. In WordPress 2.3 - 2.5 go to “Options” - “Harvest Reports”, and in 2.5 go to “Settings” - “Harvest Reports”.
3. Enter your information
4. Hit “Save”
5. Go to “Manage” - “Your Chosen Title” to see the report

To get your project ID go to your Harvest dashboard - “Manage”, and you’ll see your projects listed. When you open up one you’ll see a numerical value in the URL bar (e.g. yourname.harvestapp.com /projects/49691/). The value you want is 49691. Remember this is only meant for one project, as that was the business need I needed it to solve. Also, if you want to limit the end date on the report I have included that, but leave it blank to retrieve data up to the second.

== Caveat ==

= Default Hourly Rates =

For every task that is used for the project you have to go in and manually set the hourly rate. This is because the value is not cascaded through the API the same way it does in the Harvest interface.