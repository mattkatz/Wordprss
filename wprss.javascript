jQuery(document).ready(function($){
 // alert('begin');
  var data = {
    action: 'wprss_get_feeds',
    nonce_a_donce:get_url.nonce_a_donce 
    
  };
  $.get(get_url.ajaxurl, data, function(response){
    //TODO: put in error checks for bad responses, errors,etc.
    Wprss.feedsController.createFeeds(response);
  });

  //TODO this should just be fed into the page on initial load
  data.action='wprss_get_entries';
  $.get(get_url.ajaxurl, data, function(response){
    //alert(response);
    Wprss.entriesController.createEntries(response);
  });
  
});

  Wprss = Ember.Application.create();
  Wprss.Feed = Em.Object.extend({
    feed_url : null,
    feed_name: null,
    feed_id:null,
    site_url: null
    

  });

  Wprss.feedsController = Em.ArrayProxy.create({
    content: [],
    createFeed: function(feed,domain,name,id){
      var feed = Wprss.Feed.create({ feed_url: feed, site_url:domain, feed_id:id,feed_name:name});
      this.pushObject(feed);
    },
    createFeeds: function(jsonFeeds){
      var feeds = JSON.parse(jsonFeeds);
      feeds.forEach(function(value){
        Wprss.feedsController.createFeed(value.feed_url,value.site_url,value.feed_name,value.id);
      });
    }
  });
  Wprss.Entry = Em.Object.extend({
    feed_id: null,
    title: null,
    link: null,
    author:null,
    isRead:null,
    marked:null,
    description: null
  });
  Wprss.entriesController = Em.ArrayProxy.create({
    content: [],
    createEntry: function(feed,head, url,by,read,mark,des){
      var entry = Wprss.Entry.create({
      feed_id: feed, 
      title:head,
      link:url,
      author:by,
      isRead:read!='0',
      marked:mark!='0',
      description:des});
      this.pushObject(entry);
    },
    createEntries: function(jsonEntries){
      var entries = JSON.parse(jsonEntries);
      entries.forEach(function(entry){
        Wprss.entriesController.createEntry(entry.feed_id,entry.title, entry.link,entry.author,entry.isRead,entry.marked,entry.content);
      });
    },
    clearEntries: function(){
      this.set('content', []);
    },
    selectFeed: function(id){
      var data = {
        action: 'wprss_get_entries',
        feed_id: id,
        nonce_a_donce:get_url.nonce_a_donce 
      };
      jQuery.get(get_url.ajaxurl, data, function(response){
        //alert(response);
        Wprss.entriesController.clearEntries();
        Wprss.entriesController.createEntries(response);
      });
    }
  });
  Wprss.selectedFeedController = Em.Object.create({
    content: null
  });

  Wprss.FeedsView = Em.View.extend({
    //templateName: feedsView,
    click: function(evt){
      var content = this.get('content');
      //alert(content.feed_id);
      Wprss.selectedFeedController.set('content', content);
      Wprss.entriesController.selectFeed(content.feed_id);
      
    },
    isSelected: function(){
      var selectedItem = Wprss.selectedFeedController.get('content'),
        content = this.get('content');
      if(content === selectedItem){return true;}
    
    }.property('Wprss.selectedFeedController.content'),
    classNameBindings:['isSelected']
  });
  Wprss.selectedEntryController = Em.Object.create({
    content: null/*,
    toggleRead: function(){
                  console.log('selectedEntry toggleRead');
                  return true;

                },*/
    //Should we put in an isread function and go from that?
/*
    isRead: function(){

    } */
  });

  Wprss.EntriesView = Em.View.extend({
    //templateName: feedsView,
    click: function(evt){
      var content = this.get('content');
      //alert(content.feed_id);
      Wprss.selectedEntryController.set('content', content);
      console.log('trying to set the check');
      content.set('isRead',true);
      Wprss.selectedEntryController.content.set('isRead',true);
      this.toggleRead();
      console.log(Wprss.selectedEntryController.content.get('isRead'));
      
      console.log('tried to set the check');
      //console.log('check is ' + Wprss.selectedEntryController.content.value);
      //set as read
      
      //Wprss.entriesController.selectFeed(content.feed_id);
      
    },
    isCurrent: function(){
      var selectedItem = Wprss.selectedEntryController.get('content'),
        content = this.get('content');
      if(content === selectedItem){return true;}
    
    }.property('Wprss.selectedEntryController.content'),
    toggleRead: function(){
                  console.log('entriesview toggleRead');
                  return false;

                },
    classNameBindings:['isCurrent']
  });

  Wprss.ReadView = Em.View.extend({
    content:null,
    templateName:'read-check',
    click: function(evt){
      console.log('in read-check');
      console.log(evt);
      console.log(this.content);
      return false;
    }

  });
  Em.Handlebars.registerHelper('checkable', function(path,options){
    options.hash.valueBinding = path;
    return Em.Handlebars.helpers.view.call(this, Wprss.ReadView,options);
  });





