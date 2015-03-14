=== Plugin Name ===
Contributors: dukeofharen
Donate link: http://duco.cc/
Tags: twitter, post, social media, tweet
Requires at least: 3.6
Tested up to: 4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

In a few words: a no-nonsense and straightforward plugin to announce your post to Twitter.

== Description ==

A ridiculously easy to use plugin to announce your new post on Twitter. You can also send the featured image as image with your tweet. When this plugin is activated, you'll see a new box in the post editor where you can select if you want to tweet.

In a few words: a no-nonsense and straightforward plugin to tweet your post to Twitter.

== Installation ==

**Connect the plugin to your Twitter Account**
OK, now it gets interesting. We need to ask Twitter for 4 keys.
1. Go to https://apps.twitter.com and log in with your account<br />
2. Click "Create New App"<br />
3. Fill in the necessary details<br />
4. Go to "Keys and Access Tokens"

You need 4 keys, 2 keys are already available on this page: the Consumer Key and the Consumer Secret. At "Access Level" you will see "Read-only", this needs to be "Read and write". Click "Modify app permissions", click "Read and write" and "Update Settings". Your app now has "Read and write" permissions. Go back to "Keys and Access Tokens". Now click "Create my access token". Now you’ll also have the Access Token and the Access Token Secret. Now you have the 4 necessary keys to make posts to Twitter.

**Update the settings in WordPress**
In your dashboard, go to "Settings" => "Post To Twitter". You’ll see 4 empty fields: fill in the 4 codes you’ve generated. After you’ve filled in the codes, click "Save Changes" and after that "Test". If everything went right, a tweet has been placed on your Twitter account.

**Tweet a post to Twitter**
Now you’ve successfully connected Twitter to your blog, you’ll now see this box at the bottom of the update post page: 
Here you can select whether you want a tweet to be posted linking to this WordPress post, whether you want the featured image sent to Twitter and if you want a custom text to be tweeted (by default, the title of the post is tweeted). A link to the post will be sent with the tweet if you've enabled this option in the "Post To Twitter" settings page.

== Screenshots ==

1. The settings page of WordPress post to Twitter.
2. This is the new box on the post editing page. Here you can fill in if you want to post a tweet.

== Changelog ==

= 0.0.1 =
Initial release

= 0.2 =
*   Added support for scheduling posts.
*   You can now also select if you would like "authors" and "editors" to post to Twitter. At first, only administrators could post to Twitter.
*   Thank you Abdullah for pointing out these issues.