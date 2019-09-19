# OAG - Magento 2 Order Review module #

Welcome to my first Magento 2 module! I'm sure that this module can be improved and some code parts could be better, but my objective is to learn Magento 2's framework, modules, new Magento's structure, etc and this is the best way to do it.

This module adds the next feature: A customer, X days after creating a sales' order in Magento 2's ecommerce, will recieve an email with a link to add a review of his experience. The values that we evaluate are:
- Shipping
- Product
- Customer Support
- Comment (optional)

All reviews are saved in a database table that the Magento admin user needs to moderate in order to decide if the review is verified to show or not in https://your_website_name/reviews. This moderation should be done in the magento backoffice.

## Technologies
This module was created with:
* Magento 2.3.2 CE
* PHP 7.2

## Install
To install this module, we used the next method:
```
cd MAGENTO_ROOT_PATH
cd app
cd code (maybe you need to create the code folder with mkdir code command)
mkdir OAG
mkdir OAG/OrderReview
cd OAG/OrderReview
drop this repository in your current path (app/code/OAG/OrderReview)
```
After this commands, you will need to install the module with the following command: `php bin/magento setup:upgrade`.

Also, you can use composer.json to install this repository. However, this kind of installation was not tested so maybe it has some error that needs to be fixed.

## Cronjob
This module has a cronjob that sends the emails to the customers. Even so, if you want to execute the cronjobs manually, you can use the next command:
```
cd MAGENTO_ROOT_PATH
php bin/magento cron:run --group oag_order_review
```
