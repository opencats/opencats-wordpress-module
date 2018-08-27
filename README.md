## Wordpress module for OpenCATS

*coded by UltraSimplified, all questions to RussH

### INSTALLING

1. In GitHub, click the green "Clone or Download" button and then click Download ZIP.
2. Extract the contents of the downloaded .zip file.
3. Upload the /wp-opencats folder to the /plugins folder on your Wordpress installation.
4. In the Wordpress Dashboard, go to the Plugins screen. You should see the OpenCATS plugin listed.
5. Click Activate below the OpenCATS plugin.
6. In the Wordpress Dashboard, under the Settings heading, click on the OpenCATS heading.
7. Enter the required information.
8. Add the "[jobs]" shortcode to a page (without the quotes) to display a list of open jobs and/or add the PHP code used in the example jobs-templates.zip file.

#### Issues
Q. After installation in a vanilla wordpress then when you click "read more" to present the complete vacancy the link 
doesn't work. Nothing happens 

A. Create a page in the root of your website called job Job and then give it a custom template with a function call to display the listings. 
I have included samples of the files in job-templates.zip - they are literally WordPress TwentyTwelve templates with the function calls in them.

* Alternatively, from scratch please edit the page and create a custom template including this line:
```
  <?php opencats_job_details( $job_id ); ?>
```
That should then display all the job details properly


##### Update
The plugin was updated to use a shortcode for jobs listing. To display a list of open positions, just use the shortcode "[jobs]" (without quotes.
  
##### Notes
The plugin could be further improved by creating its own job page when it is first activated. It would be better if OpenCATS exposed the jobs listing / details through an API as well, instead of through a database connection.. but that depends on a significant change to the OpenCATS codebase.
