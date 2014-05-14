<html>
<head>
</head>
<body>
<div gm-view="catalog">View Catalog</div>

<div id="gimmie-root"></div>
<script type="text/javascript">
var _gimmie = {
  /** Create User node when user login **/
                          "user"                        : {
                             "name"                      : "Demo",
                             "realname"                  : "Demo",    
                             "email"                     : "demo@email.com",
                             "avatar"                    : ""
                           }, 
  /** Create User node End **/        
                          "endpoint"                    : "gimmie-connect.php?u=demo_user&gimmieapi=",
                          "gimmie_endpoint"             : "http://api.gimmieworld.com", 
                          "key"                         : "7be295f5c136fb42ce4d9de20659",
                          "country"                     : "SG",
                          "locale"                      : "en",
                          "options"                     : {
                            "push_notification"         : false,
                            "animate"                   : true,
                            "auto_show_notification"    : true,
                            "notification_timeout"      : 60,
                            "responsive"                : true,
                            "show_anonymous_rewards"    : true,
                            "shuffle_reward"            : true,
                            "default_level_icon"        : "",
                            "pages"                     : {
                              "catalog"                 : {
                                "hide"                  : false
                              },
                              "profile"                 : {
                                "hide"                  : false,
                                "redemptions"           : true,
                                "mayorships"            : false,
                                "badges"                : false,
                                "activities"            : true
                              },
                              "leaderboard"             : {
                                "table"                 : "alltime", //alltime,thisweek,last7days,pastweek,today,last30days,pastmonth,thismonth
                                "hide"                  : false,
                                "mostpoints"            : true,
                                "mostrewards"           : true,
                                "mostvalues"            : true
                              }
                            }
                          },
                          "events"                      : {
                            "widgetLoad"                : function () {
                              console.log ("Loaded");
                            },
                            "login"                     : function () {
                              console.log ("Login");
                            },
                            "loadLeaderboard"           : function (data, cb) {
                              //data
                              cb(data);
                            }
                          },
                          "text"                        : {
                            "help"                      : "Receive 1 points for every $1 spent.<br /><br />Collect more points and earn more rewards."
                          },
                          "templates"                   : {
                          },
                        };
                        (function(d){
                          var js, id = "gimmie-widget", ref = d.getElementsByTagName("script")[0];
                          if (d.getElementById(id)) {return;}
                          js = d.createElement("script"); js.id = id; js.async = true;
                          js.src = "//api.gimmieworld.com/cdn/gimmie-widget2.all.js";
                          ref.parentNode.insertBefore(js, ref);
                        }(document));
                        
</script>

</body>
</html>