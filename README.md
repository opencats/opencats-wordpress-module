## Wordpress module for OpenCATS

*coded by UltraSimplified, all questions to RussH

### INSTALLING

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
	The plugin was updated to use a shortcode for jobs listing
  
##### Notes
The plugin could be further improved by creating its own job page when it is first activated. It would be better if OpenCATS exposed the jobs listing / details through an API as well, instead of through a database connection.. but that depends on a significant change to the OpenCATS codebase.
