<?php
/*
 * This file contains the configuration for the datastore
 * on the local machine.
 *
 * Please modify the following details before deployment.
 *
 * Author: Phillip Whittlesea <pw.github@thega.me.uk>
 * Date: 04/03/2012
 */
$config = array(
  /* db */
  'db_name' => 'db_name',
  'db_user' => 'db_user',
  'db_pwd' => 'db_pwd',
  /* store */
  'store_name' => 'magnesium',
);
$store = ARC2::getStore($config);
if (!$store->isSetUp()) {
  $store->setUp();
}
