<?php
/*
Plugin Name: Games News Rss Feed
Plugin URI: http://www.guyro.com/games-news-rss-plugin
Description: Adds a customizable widget which displays the latest Game related news from all over the world.
Version: 1.0
Author: Guy Roman
Author URI: http://www.guyro.com
License: GPL3
*/

function gamesnews()
{
  $options = get_option("widget_gamesnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Games News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://n4g.com/rss/news?channel=&sort=latest'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_gamesnews($args)
{
  extract($args);
  
  $options = get_option("widget_gamesnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Games News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  gamesnews();
  echo $after_widget;
}

function gamesnews_control()
{
  $options = get_option("widget_gamesnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Games News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['gamesnews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['gamesnews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['gamesnews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['gamesnews-CharCount']);
    update_option("widget_gamesnews", $options);
  }
?> 
  <p>
    <label for="gamesnews-WidgetTitle">Widget Title: </label>
    <input type="text" id="gamesnews-WidgetTitle" name="gamesnews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="gamesnews-NewsCount">Max. News: </label>
    <input type="text" id="gamesnews-NewsCount" name="gamesnews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="gamesnews-CharCount">Max. Characters: </label>
    <input type="text" id="gamesnews-CharCount" name="gamesnews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="gamesnews-Submit"  name="gamesnews-Submit" value="1" />
  </p>
  
<?php
}

function gamesnews_init()
{
  register_sidebar_widget(__('Games News'), 'widget_gamesnews');    
  register_widget_control('Games News', 'gamesnews_control', 300, 200);
}
add_action("plugins_loaded", "gamesnews_init");
?>