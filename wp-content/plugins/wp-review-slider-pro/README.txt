=== WP Review Slider Pro ===
Contributors: jgwhite33, freemius
Donate link: http://ljapps.com/
Tags: reviews, slider, facebook, yelp
Requires at least: 3.0.1
Tested up to: 6.5
Stable tag: 12.1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Pro Version - Allows you to easily display your Facebook Page, Yelp, Google, Manually Input, and 80+ other site reviews in your Posts, Pages, and Widget areas.

== Description ==

Pro Version - Allows you to easily display your Facebook Page, Yelp, Google, Manually Input, and 80+ other site reviews in your Posts, Pages, and Widget areas.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `wp-review-slider-pro` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('WP_Review_Pro_hook'); ?>` in your templates



== Changelog ==
=12.1.5=
- Small CSS fix with read more text being cut-off mid word
- When you edit a review download form Title or Review Funnel Title now all the Reviews will be updated as well to the new title.
- New review template and badge template
- Minified CSS.
- Fixed bug with 3 language max in review translator.
- Added zoom on hover option for review template.
- Facebook Review Funnel can now download all reviews or 10.
- Fixed bug for Advanced Slider mobile view not closing read more on swipe.
- Fixed bug with language translator.
- Fix to Banner totals bug.



=12.1.2=
- Fixed bug with Total/Avg being deleted when running force recalculate.
- Total and Averages now get updated when importing CSV file of reviews.
- Added a last_modified column to reviews and total/average tables.
- fixed bug with dashboard widget not having an id.
- fixed bug with Google Places API review download review language translation.

=12.1.1=
- Added Social Climb reviews.
- Added Realtor reviews.
- Added ability to push reviews in to woocommerce.
- Bug fix with Tools > Google Product XML creation.
- Fix with Yelp review download.

=12.0.6=
- Automatic review source creation based on custom field value added to Tools menu.
- Added 0 decimal place for badge option.
- Fixed Nextdoor download issue.
- Fresha reviews date fixed.
- Added Yelp local download method.

=12.0.3=
- Bug fix with custom page name being saved. Now takes name from form submission.
- Bug fix with line breaks being lost in read more pop-up window.
- Bug fix with badge avg not showing decimals when whole number.
- Updated Nextdoor reviews to include Titles.
- Bug fix with pop-up form not working in banner and fix for custom html header.
- Bug fix for when updating review avatar cache avatar not clearing out.
- Added feature to create a woocommerce review when using a form > custom Page select. Allows you to capture woo reviews for different products from a different page.
- New function for returning the star html based on the average.


=12.0.0=
- Fix for Badge URLs not being saved correctly.
- Form custom page select now saves page title with review submission.
- Bug fix for Google Translate, now checks for language before translation.

=11.9.8=
- Added Creative Market review download.
- Video upload option added to form.
- Review Funnel added sites: Baidu, Bestbuy, Bol, Carvana, Drizly, Flipkart, Goodreads, Holidaycheck, Kayak, Orbitz, Priceline, Qunar, Reviews.io, Smythstoys, Steampowered, Travelocity, Trip
- Review Funnel removed sites: Bookatable, Newegg, ReserveOut, Siftery, Trulia
- Added option to hide top of banner above reviews.
- Bug fixes.

=11.9.7=
- Bug fixes from last update.

=11.9.6=
- Custom Filter to swap badge source icons.
- Added html editor to badge > header and footer settings.
- Bug Fixes: Custom margin settings for grid. Facebook cron job avatar downloads fixed. 


=11.9.5=
- Fixed bug when Read More used on hidden tab.

- Facebook API download method fixed.

- Bug fixes: Icon widths on badges. Cron jobs for FB reviews. TripAdvisor Hotel reviews.

=11.9.3=
- Update Freemius SDK to latest.

- Added owner response display option to review templates.

- Added smoother "read more".

- Added a "scroll long reviews" option along with "read more" option.

- Bug fixes for tags, TripAdisor review downloads, Load More on Grid, and other small bug fixes.


=11.9.0=
- Small bug fixes.

=11.8.9=
- Fix for Birdeye total and avg. 

- Added Fresha review download.

- Tools > Automatic translation of reviews using Google Translate API.

- Tools > Seperate CSS and JS add to page setting.

- Bug fix for slow loading on home page. 


=11.8.8=
- Small bug fixes left over from last update.

=11.8.7=

- Fixed bug for Advanced Slider when in hidden tab.

- New submitted review now auto updates total and average.

- Fixed tags query so that it must be an exact match.

- Added total and average source site values for Facebook API download.

- Fixed bug with TripAdvisor Attraction Product Review Local only downloading 10.

- Fixed bug with total and averages not updating on form submission.

- Added Google Product review XML file creation under Tools.

- Small bug fixes from 11.8.1

- Added ability for Advanced slider to be a continous slide without stops by setting time delay to 0.

- Added Google Product reviews download to crawl.

- Increased speed of Review Funnel queue.


=11.8.1=
Added raise on mouse over, drop shadow, and border color options to review template.

Added Header option to review template so you can now easily add a header with a review us button above the reviews.

Fixed VRBO download.

Added another Zillow crawl method in case local server is blocked. 

Added option to Form to only allow one submission per a person. Fix bug with border color when on php v7.

AngiesList, Bilbayt, Deliveroo, Realself, TheFork, and WebMD added to Review Funnel. 

Bug fix for editor downloading CSV of reviews from Review List.

Easy select button added for template style.

Fix for Advanced Slider having negative impact on CLS (content layout shift) Google Page Speed.

Added scroll to ID fix for masonry grid style.

Added wrtg url variable to form. If it has a value or comma seperated values they are saved with the form as tags.

Update to Google Crawl so that the url to the user profile for the review is also saved.




=11.7.5=
Fixed Experience total and avg review count download. Fixed VRBO crawl download. Other small bug fixes.

=11.7.3=
Updated Freemius SDK. Added option for TripAdvisor crawl method where you can either use your wordpress server or a remote server. Fixed another bug in Angieslist download. 

=11.7.2=
Normal slider can now use header options. Added custom Filter option for review text. Added Avatar Initials background color option for Review Template. Added Month/Year option for Review Template. Added header source option drop-down for review template. Fixed Angieslist download. 

=11.7.1=
Added VRBO to Review Funnel. Upadated Freemius SDK to 2.5.8.

=11.7.0=
Added new template style 12. Fix template style 6 tripadvisor img bug. Fixed Experience download. Added change arrow color option for review template.

=11.6.8=
Small bug fix for regular slider in horizontal slide.

=11.6.7=
Small bug fix for Google Places API method. Bug fix on Getrevs page, links not working correctly on some sites.

=11.6.5=
Backward compatible for php 7.

=11.6.4=
Small bug fixes. Updated the way the Normal slider renders with javascript to make more reliable. 

=11.6.3=
Added preview button to Template page list. Updated Get Reviews interface. Updated Google Places API so it can use Newest or Most Helpful or Both. Added option to Hide new reviews automatically on download. Fixed pagination on Review List when there are over 10 pages. Added option to update user avatar with initials icon. Added "read more" option that will pop-up entire review instead of expanding review. Added options to Bulk Edit tool on Review List and also added tag selection. Upgraded Freemius SDK. Fix TripAdvisor download for Hotel. Increased Google Crawl image size. 

=11.6.2=
Fixed bug when using mobile and desktop templates on the same page.

=11.6.1=
Fixed Get Reviews > Zillow download. Added Yotpo review support.

=11.6.0=
Fix deprecated warnings for php 8.2. Added Birdeye support. 

=11.5.9=
Bug fix for badges page jquery error on complex page names/ids.  Review list: fix sorting bug when changing page. Fixed airbnb experience reviews download.  Fixed Deprecated PHP 8.1 warning for add sub menu page.

=11.5.8=
Bug fix for Badge main image size. Fix for Badge style 6. Badge Total and Averages now show on preview. Added feature to redirect user on review form submission to another page. Added option to display the review source page name/download form title on the review. Added review type filter option to review template public facing header options. Added a custom filter hook to the review text to allow users to be able to create custom filters to modify review text.

=11.5.4=
Updated Facebook download method. Small bug fixes.

=11.5.3=
Updated moment.js to latest, used for date picker in Analytics page. Google Crawl download of images. Review Template changes so that same height setting also picks up media. Yelp download fix. Analytics page fix. Badge out of 10 option. Added duplicate review check for 100 characters. Custom field values added to sent email on form submission. Tools tab has show pages based on Role feature. Trustpilot icons removed. Updated Freemius SDK for licensing. Other small bug fixes.

=11.5.0=
Added another badge style. Fixed badge style 3. Fixed issue with Advanced slider autoplay on read more click.

=11.4.9=
Added new option under Tools that allows you to specify which pages to add the CSS and JS files rather than adding them to all pages of your site. Fixed mobile view for template 2. Updated Get Reviews > VRBO download method. Updated Get Reviews > Yelp method. Get Reviews > Yelp, Google Crawl, and TripAdvisor can now also download images. 


=11.4.8=
Bug fix with Badge icons.

=11.4.7=
Updated Get Reviews > Airbnb download method.  Google Crawl can now download 50 reviews and get images and owner responses.

=11.4.6=
Added GuildQuality review download. Added ability to add location to review. Added function on review template to turn off/on location. Also to turn on/off review media. Updated Freemius sdk. Added ability to hide review template based on screen size. Updated Get Reviews > TripAdvisor to download up to 100 reviews with media. Fixed bug of advanced slider dots not hiding on mobile option.

=11.4.4=
Fixed TripAdvisor Product Reviews for the Get Reviews > TripAdvisor method.

=11.4.3=
Fixed slider dots not hiding for regular slider.

=11.4.2=
Added CSS classes to prev and next grid pagination buttons. Updated Review Funnel auto-download to make it more reliable.

=11.4.1=
Fixed jQuery migrate warnings. Fixed Zillow bug when there is a space in URL. Fixed Experience download. Switched language detector to detectlanguage.com api.

=11.4.0=
Added Reviews.io. Added multi-select custom tag field to forms. Forms will now pre-fill with logged in user email and name. Added endless scroll option to grid load more function. Added site icon size option. CSS fix for pagespeed. Added decimal place option for badge average. Added another custom image icon for badges.

=11.3.7=
Small bug with booking reviews total and averages. Added preview option when creating Review Template. Changed format of Template page. Added star size option. Added verified option on template. Other small bug fixes.

=11.3.5=
Bug fix with duplicate Google reviews for Google Crawl method. Bug fix for TripAdvisor download.

=11.3.4=
Small bug fix for Forms custom checkboxes. 

=11.3.3=
Bug fix with Float Pop-Ups. Added Avatar size option to review template.

=11.3.2=
Bug fix with initial override for avatars. Small bug fixes.

=11.3.0=
Added Sourceforge reviews. Added WordPress.org reviews. Google Crawl hotel review fix. Bug fix for wordpress.org reviews. Bug fix for pop-up badge editing header. Bug fix for Yelp badge totals. Yelp download fix. Border setting for review template styles 2,4,5. Bug fix for TripAdvisor activities. 

=11.2.7=
Small bug fix with Google Crawl method for hotels. Bug fix for custom check box on Form input not being saved.

=11.2.6=
Bug fix with thumbs on forms.

=11.2.5=
Small bug fix with Google Crawl method.

=11.2.4=
Editor role now has access to the Review List and the Analytics. Fix for Google Crawl. Fix for woocommerce reviews that do not have a rating value. Added warning messages to review funnel.

=11.2.3=
Fix for experience.com review download.

=11.2.2=
Fix for Facebook filter by page.

=11.2.1=
Fix to cron jobs for Google Crawl method. Fix to TripAdvisor hotel reviews.

=11.2.0=
Fix to FeedbackCompany reviews. Added StyleSeat reviews. Other small bug fixes. TripAdvisor Rental review fix.

=11.1.9=
Update to Google Crawl method for hotels. Updated smileys and thumbs up/down icons for forms. 

=11.1.8=
Review List page selection now alphabetic. Review List search box will also now search page_id. Google Crawl method fixed for hotels. WooCommerce fix for auto-approved reviews. If WPML is used the language code added to review. Review Template setting added if using WPML and you want to filter by current language. Small schema update. Badge star color fix.

=11.1.7=
Small bug fix with badge style 4.

=11.1.6=
Star images use SVG instead of custom font.  Added filter by text string to review template shortcode. Added TrueLocal.com reviews. Added Feedback Company reviews. Added Experience.com reviews. Yelp link in review template can now be turned off. Added new Google Crawl download method that will return 10 newest reviews, does not cost credits. Added small thumbnails in Review List if the review has media. Added new template style 11 for woocommerce product image on left of review. 

=11.1.5=
Forms bug fix. Get Reviews > TripAdvisor bug fix for Activities. Forms custom field media upload added.

=11.1.4=
Badge bug fix. Another form shortcode paramater that will auto-popup a form. Front end filters can not be used on the Advanced Slider. Get Reviews > TripAdvisor alternative method bug fix. Get Reviews > Angies list bug fix. General optimizations with fonts and images.

=11.1.3=
Advance slider bug fix for center mode. Fixed js error in review list > tags.

=11.1.1=
Added shortcode parameters to badges. Badge will now use all WooCommerce products if no page is selected. Changed break points for Advanced slider. Hide rest of form option for rating will now respect re-ordered form. Bug fix for icons on form. Show float on all pages except is now an option. Added ability to track IP address on Form submission. Option to hide review rating after click on form. You can now use custom HTML in manually added reviews. Added custom form fields to the form. Another review template Filter, contains these words, but not these.

=11.1.0=
Added Hostelworld reviews. Added default tags to Forms. TripAdvisor bug fixes. Added lazy load attribute to avatars. Review Funnel option to use Google Place ID. Review Funnel added jobs notice. Bug fixes to Pop-in/out. Bug fix for double-line breaks in Yelp. Other bug fixes.

=11.0.9.9=
Bug fix for last name display options. Bug fix for hide arrows on mobile. Added ability to add html tags to review text.

=11.0.9.8=
Added filter by Post ID for Badge. Google Schema can show number of votes or number of reviews. Combined CSS files. Added more name options. Bug fixed when going more than 5 across for slider. Other small bug fixes.

=11.0.9.7=
Fixed Get Reviews > Yelp bug. Other small bug fixes.

=11.0.9.6=
Added qualitelis. Upgraded Freemius sdk to 2.4.2. Modified duplicate review check so same person and review can be left for 2 different review funnels. Small CSS fixes. Added link to source page on Review Link page. Added "Do not display" for tag filter on review template. Added reply-to email for form notification email. New review email is now translatable. Fixed Elementor conflict.

=11.0.9.4=
Float fix for not hiding after first view setting. Fixed CSS bug when using padding with Float. Facebook icon now links to review post.

=11.0.9.3=
Yelp review download fix. Added pop in/out float option. Added media inputs to the reviews so you can add images/video to each review. Added bulk editing of review tags, posts, categories. Insert/Edit review is now in a pop-up window. Added ability to easily copy a review. 

=11.0.9.2=
Fix with advanced slider. Zillow review download fix.

=11.0.9= 
Added advanced slider option. You can now use initials for user avatars. Can pick and choose which source icons to show on the template. Tags can be used in a shortcode and the Review List search now looks for tags. Added Shortcode and Function to return totals and averages data. Fixed login links in notifications email and forms email. Added template style 9. Updated Freemius.

=11.0.8=
*Added Apartmentratings, Apartments, FindLaw, Greatschools, and Niche to Review Funnels. Added update button to Review Template page so you don't have to close the page to make changes. Added ability to edit, insert owner responses. Added tags to reviews so you can filter by tag and use the quick tag header option with them. Added page_id to review list page. Small bug fixes.

=11.0.7=
*fix for masonry style bug on mobile, added new tripadvisor icon, added text size change on template, added hide slider arrows on mobile setting, other small bug fixes.

=11.0.6=
*schema fix for individual reviews, Masonry style runs horizontal on grid type now, added review template margins for mobile

=11.0.5=
*various bug fixes

=11.0.4=
*Added Google Shopping, BookATable, Influenster, Angie's List, and Feefo reviews. Various other small bug fixes

=11.0.3=
*small bug fixes

=11.0.2=
* Added margin setting option to review template. Various small bug fixes.

=11.0.1=
* Various small bug fixes.

=11.0.0=
* Bug fix with Get Review > Zillow not downloading full review. Added option to remove nofollow link from review source icon and avatar. Added ability to use negative sort weight. Other various small bug fixes.

=10.9.9= 
* Small bug fixes with facebook image save, rich schema for product, custom font, and css.

=10.9.8= 
* Gartner and IT Central added to Review Funnels. Better checking of duplicates for Google. Other small bug fixes.

=10.9.7= 
* Small bug fixes. Added ability to add Place names for VRBO and Airbnb. Small bug fix for duplicates. 

=10.9.6= 
* Fixed Nextdoor reviews. Fixed Get Your Guide reviews. Fixed duplicate Google Review Funnel reviews.

=10.9.4=
* Added language code and rating filter to pagination filters on front end. Added Housecall Pro reviews. Added option on review template total and avg header to use a badge setting. Various other small bug fixes.

=10.9.2=
* Various other small bug fixes. Added free Zillow method to Pro version. Added close button to slide-out. Added badge width setting. 

=10.9.0=
* Small bug fixes. Added close button to Slide-out window. Added free Zillow download. Added badge width setting.

=10.8.9=
* Various small bug fixes. Added option to get full name from WooCommerce reviews. Added page filter on Review List page. Added more fields to Google Rich snippet, and option to add individual review markup. Added tool to easily customize style of the "load more" and the "pagination" buttons. Added option to remove all line breaks in review text.

=10.8.7=
* Various small bug fixes. Added more Google and Airbnb locations. Fixed bug with mbstring extension not being loaded. Added total reviews number for Google Badges.

=10.8.6=
* Bug fixes with Default Google API key. Added button to test API key. Bug fix with template style 7 border color.

=10.8.5=
* Added ProductReview.com.au, HomeAway.com, Apple Appstore and Google Playstore reviews to Review Funnels.

=10.8.4=
* bug fixes from previous update

=10.8.3=
* beta version
* bug fixes from previous update
* Can now have more than 5 stars on a form
* Custom Sort option added for reviews. Sort Weight on Review List page. 

=10.8.2=
* beta version
* bug fixes from previous update
=10.8.1=
- Added a pop-up option and slide-out option for Badges.
- Added a pop-up option for Floats.
- Created tabs on the Templates page to simplify it.
- Added Freemius reviews.
- Added options for Grid style templates to easily add a header, search box, sort drop-down, search tags, and page number pagination.
- Re-worked badge totals and averages.
- Added free 7-day search to Twitter.
- Added another Badge style.
- Added way to delete reviews by Page ID.
- Font file optimizations.
- Fixed bug when selecting reviews for grid style.

=10.8.0=
* Plugin is now translatable. Added another custom link for badge text.

=10.7.9=
* Fixed bug with Badges and other small bugs. Added link attribute setting to badges.

=10.7.8=
* Fixed bug with Form.
=10.7.7=
* Submission Forms: Added option to use a Pop-up to display the form. Added option to use thumbs up instead of stars. Added option to only show form when using special URL variable.
* Floats: Added option to only show on Desktop, Mobile, or Both. Added option to auto hide after so many seconds.
* Reviews: Added Twitter download page. Added yelp and tripadvisor icon selection. Added Swedish time-since setting.

=10.7.6=
* Fixed bug with border on template 5. Added review site icon setting on Forms. Added Get Your Guide and Nextdoor reviews. Added recommendation icon in FB reviews. 

=10.7.5= 
*Freemius sdk upgrade.

=10.7.4= 
* Added iTunes reviews. Updated notifications settings page so they can be used with any type of review.

=10.7.3= 
* Fixed bug with review template "Select Reviews" setting not showing reviews when language code set. Fixed bug with Load More setting when using Post filter. Added Dutch to time since setting. CSS styles now retain formatting when editing templates.

=10.7.2=
* Fixed bug with badge average and total when selecting page with no reviews. Added VRBO support. Fixed bug with manual review small icon missing.

=10.7.1=
* Added animation options for Float to fly-in or fade-in on page load. Bug fix with Schema markup fields for sub-business types.

=10.7.0=
* Added review owner responses to Review Funnels. They will now show on Review List and Analytics pages. Added more categories to Rich Snippet. Bug fix for old Review Funnels when using "only get new reviews".

=10.6.9=
* Added feature for Submitted type badge so that it will default to reviews for the Post it is being displayed on.

=10.6.8=
* Added charts on Analytics page. Small bug fix with using Load More on template and Selected Reviews.

=10.6.7=
* Added option to remove double-line breaks from review text. Small php bug fixes.

=10.6.6=
* Small bug fix for Avatar link and "Show these reviews plus others" filter on template. 

=10.6.5=
* Various small bug fixes. Started on Analytics page. 

=10.6.4=
* Added multi-language filter and language detector. Added sort by oldest template setting. 

=10.6.3=
* Fix for older versions of IE error in slider js.

=10.6.2=
* Added badge option to show avg and total from Review List table. Fixed duplicate review bug. Fixed bug with modified Avatar being overwritten. Added ClassPass to Review Funnel.

=10.6.1=
* fixed badge from error.

=10.6.0=
* fixed missing badge style 1 file.

= 10.5.9 =
* Added smaller badge style. Fixed WooCommerce badge total and average.

= 10.5.8 =
* Bug fix with Google badge total not being updated with cron download. Added ability to pull reviews from Facebook page location sub-pages. Added option to filter by number of characters in review text.

= 10.5.7 =
* Bug fix with database table not being updated. Firefox bug fix on Review Funnels page.

= 10.5.6 =
* Small bug fix with Google badge totals. Cron Jobs added to Review Funnels. Added option to use Icons on Forms for the "Please review us on...." setting.

= 10.5.5 =
* New badge style added.

= 10.5.4 =
* Small bug fix with Review Funnel logo links.

= 10.5.3 =
* Small bug fix with TripAdvisor stars. More sites added to Review Funnels.

= 10.5.2 =
* Small bug fixes. Review Funnels beta version released.

= 10.5.1 =
* TripAdvisor hotel review bug fixed.

= 10.5.0 =
* Another WooCommerce sync bug fixed.

= 10.4.9 =
* Small bug fixes with badge, float, and WooCommerce sync.

= 10.4.8 =
* Bug fix with Load More button. Bug fix with submission form required rating. Bug fix with link to Google Avatar. Other small bug fixes.

= 10.4.6 =
* Small bug fix for multi-byte characters ex: Japanese

= 10.4.5 =
* Added feature for Read More based on character length.

= 10.4.4 =
* Added ability to customize the review templates using child themes. Added shortcode parameter to filter by pageid in template shortcode.

= 10.4.3 =
* fixed issue with Airbnb user reviews.

= 10.4.2 =
* Combined js files to reduce http requests. Added option to make company URL do follow. Added option to save FB recommendations as a star rating.

= 10.4.1 =
* Fix for some sites not saving and displaying avatars correctly. 

= 10.4.0 =
* Fixed bug when selecting reviews to show and using Load More feature. Moved Avatar cache to Uploads directory so they do not get deleted on plugin update. Added Airbnb user reviews. Fixed bug with changing Google avatar.

= 10.3.9 =
* fixed bug with Airbnb page

= 10.3.8 =
* fixed bug with returns in manually added review text.

= 10.3.7 =
* Added Airbnb experiences. Added WooCommerce review sync. Added ability to hide stars per a review. Added 3 more styles. Fixed "read less" small bug. Fixed small bug with form export/import. Fixed small bug with Float auto-clicking when auto advance set to true and Load More feature turned on.

= 10.3.6 =
* small bug fix for load more feature when using latest sort option in a slider.

= 10.3.5 =
* FB recommendations now count toward badge totals. added ability to customize notifications email. small bug fix with TripAdvisor download.

= 10.3.4 =
* small bug fix with css on nav. read less link is clicked on slide change now. 

= 10.3.3 =
* added ability to upload custom image for small icon on badge

= 10.3.2 =
* bug fix with widget not loading on some sites, added date display option, added form logic and social links

= 10.3.1 =
* bug fix with js files not loading on some sites

= 10.3.0 =
* added local storage setting for closing float. Changed max Airbnb to 25. FB profile pics are now saved to db by default.

= 10.2.9 =
* small bug fix with missing avatar images

= 10.2.8 =
* small bug fix when showing one slide on mobile. 

= 10.2.7 =
* small bug fixes with slider load more feature

= 10.2.6 =
* small bug fixes with slider javascript, badges, and manually input review avatar logos. Also added avatar image compression on caching option.

= 10.2.5 =
* fixed bug with PHP versions less that 7 when declaring an array as a constant.

= 10.2.4 =
* Added badge for Submitted reviews. Added option to select reviews to always display no matter filter settings in template. Fixed company info bug on edit review. Added filter based on text string for review template. Added click to hide x on float. Added Airbnb. Changed Google location limit to 40.

= 10.2.3 =
* bug fix for associating manual reviews to locations

= 10.2.2 =
* bug fix for removing slashes from schema, fix for hiding TripAdvisor logo, fix for page listing

= 10.2.1 =
* bug fix for updating expired FB images

= 10.2.0 =
* bug fixes pagination

= 10.1.9 =
* general bug fixes with Forms and Floats

= 10.1.8 =
* added Floating Badges & Reviews, and pagination option to templates.

= 10.1.7 =
* automatically refreshes Facebook Profile images that have expired.

= 10.1.6 =
* small bug with Account

= 10.1.5 =
* small bug with default google avatar

= 10.1.4 =
* added default submit value for reviewer avatar

= 10.1.3 =
* fix for FB recommendations

= 10.1.2 =
* small change with enquing script

= 10.1.1 =
* Bug fix with one slide on mobile.

= 10.1.0 =
* New slider setting to display one per a slide on mobile device

= 10.0.9 =
* now have option to use your own google api key

= 10.0.8 =
* bug fix with google review download

= 10.0.7 =
* google will now use default api key. added option for trip download

= 10.0.6 =
* new way to download Facebook reviews.

= 10.0.5 =
* add ability to select category ids on review list page.

= 10.0.4 =
* ajax form submission.

= 10.0.3 =
* fix with avatar display.

= 10.0.2 =
* added ability to add custom CSS class to form button

= 10.0.1 =
* bug fix with yelp stars not displaying

= 10.0.0 =
* added front end submission form

= 9.9.8 =
* small bug fix with tripadvisor hotel reviews

= 9.9.7 =
* small bug fix with tripadvisor badge totals

= 9.9.6 =
* small bug fix with tripadvisor badge totals

= 9.9.4 =
* small bug fix with tripadvisor attraction reviews

= 9.9.3 =
* small bug fix with Facebook reviews

= 9.9.2 =
* option to not save last name in database, option to hide avatar or display mystery avatar, bug fix with cron fb notifications

= 9.9.1 =
* small bug fix with badges logo

= 9.9 =
* small bug fix when arrows not displayed

= 9.8 =
* arrows now stay where they are when clicking read more, added badge options for custom text.

= 9.7 =
* bug fix with Yelp reviews.

= 9.6 =
* small bug fix, changed template style 4 from h3 to div for name

= 9.5 =
* memory leak fix

= 9.4 =
* bug fix with star color in widget

= 9.3 =
* star icons changed to font and template 2 star loctaion added

= 9.2 =
* bug fix with FB backup method date pull

= 9.0 =
* Added work around when FB blocks the api call. Plugin will now try a backup method to pull in most helpful 10 FB reviews.

= 8.9 =
* bug fixes with badges. bug fix with tripadvisor hotel reviews

= 8.8 =
* Added review summary badges.

= 8.7 =
* tripadvisor can now download up to 10 reviews and reviews link to actual review page.

= 8.6 =
* Fixed read more bug with fade transition, fixed tripadvisor linking to business url, added business name and address to google notifications, added notifications for high reviews

= 8.5 =
* updated Google logo

= 8.4 =
* small bug fix with javascript on Read More tag.

= 8.3 =
* small bug fix

= 8.2 =
* added subject for notifications

= 8.1 =
* read less option added, small bug fix to masonry style, added date option for time since, fixed google snippet address for widget, added subject for notifications

= 8.0 =
* added more locations to Google, Trip, and Yelp. Added a new date option. Added import reviews feature.

= 7.9 =
* fixed bug with duplicate FB reviews and added delete button for FB reviews

= 7.8 =
* fixed bug with multiple Yelp locations being same name

= 7.7 =
* fixed bug with edit FB reviews and review time based on WP admin time for FB reviews, added copy button to manual review input form

= 7.6 =
* fixed bug with TripAdvisor downloads

= 7.5 =
* added masonry style grid and fixed yelp br

= 7.4 =
* added curl support for FB cron jobs

= 7.3 =
* added titles

= 7.2 =
* fixed bug with TripAdvisor downloads

= 7.1 =
* fixed bug with custom html section

= 7.0 =
* added tripadvisor logo to manual review add, added custom html section for after widget

= 6.9 =
* hopefully fixed swipe, added notifications by email.

= 6.8 =
* ability to limit random display order to last month

= 6.7 =
* download tripadvisor, added last name display options

= 6.6 =
* moved CSS back to inline, too many problems with caching plugins minify settings

= 6.5 =
* fixed small bug with language

= 6.4 =
* added ability to link to profile page by clicking on Avatar

= 6.3 =
* fix bug with some site not saving Google Places API

= 6.1 =
* fixed small bug with image

= 6.0 =
* fixed vertical swipe for iphone, added address, phone and price to rich snippet, changed method of download for yelp

= 5.9 =
* added cURL support for Yelp

= 5.8 =
* fix bug with Yelp memory issue

= 5.7 =
* fix bug with force same height and long names

= 5.6 =
* fix bug with Greece language word count

= 5.5 =
* removed post insert select box to resolve conflict

= 5.4 =
* small bug fix with CSS

= 5.3 =
* bug fix with W3 Total Cache minify settings.

= 5.2 =
* bug fix with mobile view hiding partial slide, and added inline CSS to head via js

= 5.1 =
* bug fix with color picker js files

= 5.0 =
* fixed color picker on template page for wordpress version 4.9

= 4.9 =
* small bug fix with css

= 4.8 =
* small bug fix with css

= 4.7 =
* fb cron download, cache avatars, same height option on reviews, add nordic time, alt text on images, sorting review fix, add widget arrows to widget, cron fb reviews, compress js and css files.

= 4.6 =
* mobile slide, turn off link to social site, constant height fix

= 4.5 =
* multiple google businesses added

= 4.4 =
* multiple yelp businesses added

= 4.3 =
* small bug fix with company name

= 4.1 =
* small bug fix when transition type set to fade and change height of each slide set to no.

= 4.0 =
* add company name option

= 3.9 =
* moved enque script for slider to bottom of page, to make sure it's after jquery and set priority to 100

= 3.8 =
* added animation speed option

= 3.7 =
* fixed word count for more languages, removed font styles so it picks up site fonts, add google snippet code

= 3.6 =
* added check in yelp download

= 3.5 =
* moved public facing js to footer

= 3.4 =
* Added copy button to templates

= 3.3 =
* Yelp, don't remove reviews unless we got the news ones first, fix.

= 3.2 =
* Yelp memory problem fix.

= 3.1 =
* increased max number of slides

= 3.0 =
* fixed css style override when 2 templates of same style are used

= 2.9 =
* Increased number of facebook pages to list

= 2.8 =
* fixed widget display error

= 2.7 =
* fixed read more tag on fade transition error

= 2.6 =
* fixed google places manual entry

= 2.5 =
* Added google reviews

= 2.4 =
* Added icon to manual reviews.

= 2.3 =
* Small bug fix. Removes line breaks.

= 2.2 =
* Date format option.

= 2.1 =
* Small bug fix.

= 2.0 =
* Small bug fix.

= 1.9 =
* Slider will pause on mouse-over

= 1.8 =
* Fixed limit of FB pages, changed to 100.

= 1.7 =
* Fixed escaping of FB page name reviews not being downloaded.

= 1.6 =
* Fixed yelp cron and fb icon display.

= 1.5 =
* Fixed slider height so it changes when you click read more.

= 1.4 =
* Added read more link.

= 1.3 =
* Fixed display bug.

= 1.2 =
* Fixed template error.

= 1.1 =
* Fixed shortcode error.

= 1.0 =
* First released version

== Upgrade Notice ==
