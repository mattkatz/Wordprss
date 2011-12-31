<?php



# create the database tables.
function wprss_install_db()
{
  global $wpdb;
  global $wordprss_db_version;
  global $wordprss_db_version_opt_string;
  global $tbl_prefix;
  require_once(ABSPATH. 'wp-admin/includes/upgrade.php');
  add_option($wordprss_db_version_opt_string,$wordprss_db_version);
  //feeds
  $table_name = $wpdb->prefix.$tbl_prefix."feeds";

  $sql = "CREATE TABLE " . $table_name ." (
    id integer NOT NULL AUTO_INCREMENT,
    owner BIGINT NOT NULL,
    feed_url text NOT NULL,
    feed_name text NOT NULL,
    icon_url varchar(250) not null default '',
    site_url varchar(250) not null default '',
    last_updated datetime default 0,
    last_error varchar(250) not null default '',
    auth_login varchar(250) not null default '',
    auth_pass varchar(250) not null default '',
    private bool not null default false,
    UNIQUE KEY id (id)
  );";
  dbDelta($sql);
  //entries
  $table_name = $wpdb->prefix.$tbl_prefix."entries";

  $sql = "CREATE TABLE " . $table_name ." (
    id integer NOT NULL AUTO_INCREMENT,
    title text not null,
    guid varchar(255) not null unique,
    link text not null,
    updated datetime not null,
    content longtext not null,
    content_hash varchar(250) not null,
    no_orig_date bool not null default 0,
    entered datetime not null,
    author varchar(250) not null default '',
    UNIQUE KEY id (id)
  );";
  dbDelta($sql);

  //user entries
  //TODO add the foreign key refs from ref id to entries id and feed id
  //TODO add starred
  $table_name = $wpdb->prefix.$tbl_prefix."user_entries";

  $sql = "CREATE TABLE " . $table_name ." (
    int_id integer not null AUTO_INCREMENT,
    ref_id integer not null,
    feed_id integer,
    orig_feed_id integer,
    owner_uid integer not null,
    marked bool not null default false,
    unread bool not null default true,
    UNIQUE KEY id (id)
  );";
  dbDelta($sql);
  //tags
  
}
# load all the first installation data in.
function wprss_install_data(){
  global $wpdb;
  global $tbl_prefix;
  $table_name = $wpdb->prefix.$tbl_prefix."feeds";
  $wpdb->insert($table_name, array('owner'=> 1,'feed_url'=>'http://www.morelightmorelight.com/feed/','site_url'=> 'http://www.morelightmorelight.com', 'feed_name' =>'More Light! More Light!'));
  $wpdb->insert($table_name, array('owner'=> 1,'feed_url'=>'http://boingboing.net/feed/','site_url'=> 'http://boingboing.net', 'feed_name' => 'Boing Boing'));
  //$wpdb->insert($table_name, array('owner' => 1, 'feed_url', => 'http://mattkatz.github.com/Wordprss/ditz/feed.xml', 'site_url' => 'http://mattkatz.github.com/Wordprss/', 'feed_name' => 'Wordprss Changes'));


  //Insert a sample entry
  //$table_name = $wpdb->prefix.$tbl_prefix."entries";
  //$wpdb->insert($



}
/*
function wprss_uninstall_db()
{
  //We should remove the DB option for the db version
  delete_option('wordprss_db_version');
  //TODO clean up all the tables
  global $wpdb;
  $sql = "DROP TABLE ". $wpdb->prefix.$tbl_prefix."feeds;";
  $wpdb->query($sql);

}*/

?>
