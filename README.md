# opencats-wordpress-module
Wordpress module for OpenCATS

Do the following for it to work:

* create a page in the root of your website called job

* Edit the page and create a custom template for it that includes this line:

<?php opencats_job_details( $job_id ); ?>

That should then display all the job details properly


Notes
The plugin could be further improved by adding in a shortcode to display the job details, as well as creating its own job page 
when it is first activated. 

It would be better if OpenCATS exposed the jobs listing / details through an API as well, instead of through a database connection.

*coded by UltraSimplified

*all questions to RussH

## INSTALLING

### issues
Q. After installation in a vanilla wordpress then when you click "read more" to present the complete vacancy the link 
doesn't work. Nothing happens 

A. * create a page in the root of your website called job

* Edit the page and create a custom template for it that includes this line:

<?php opencats_job_details( $job_id ); ?>

That should then display all the job details properly


Notes
The plugin could be further improved by adding in a shortcode to display the job details, as well as creating its own job page when it is first activated. It would be better if OpenCATS exposed the jobs listing / details through an API as well, instead of through a database connection.
