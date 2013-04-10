<div id='wprss-container' ng-app="mainModule" >
  <div id="commandbar" class="quicklinks">
  </div>
  <div id="y-indicator" class="does not provide funding" >
  </div>
  <div id="wprss-main-content" ng-controller="EntriesCtrl">
    <div id="wprss-content" >
        <ul class="entries">
          <li class="entry" ng-repeat="entry in entries" >
              <div id="{{entry.feed_id}}_{{entry.entry_id}}"ng-class="{'is-read': entry.isRead == 1, 'is-current': entry.entry_id == selectedEntry.entry_id}" >
                <a href="{{entry.link}}"><h2 class="entry-title" ng-bind-html="entry.title"></h2></a>
                <div ng-click="selectEntry(entry)" class="entry-content" ng-bind-html="entry.content"></div>
              </div>
          </li>
        </ul>
    </div>
  </div>
  <div id="wprss-feedlist" ng-controller="FeedListCtrl" >
      <div id='feed-head'>
        <h2>The Feeds</h2>
      </div>
    <ul id='feeds' >
      <li class="feed" ng-click="select(feed)" ng-class="{'is-selected': feed.isSelected}" ng-repeat="feed in feeds">
        {{feed.feed_name}} <span class="feedcounter">{{feed.unread_count}}</span>
      </li>
    </ul>
  </div>
</div>
<script type="text/javascript">
  var startentries = 
  <?php
    require_once('backend.php');
    $entries = WprssEntries::get(array('isRead'=>0));

    echo json_encode($entries);
  ?>;
</script>
