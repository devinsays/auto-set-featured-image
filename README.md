# Auto Set Featured Image

This plugin was meant for my own small little project- so, results may vary.  Don't use without a site a backup, not meant for production, and please disable after images are set.

This plugin checks if a post has a featured image.  If not it will:

1. Check if the custom field 'FeaturedImage' is set.  It is expecting a url string here.  If one is set, it will fetch the attachment id for that image url and attach that as the featured image.

2. Query for any attached images on the post.  If any images are attached, it will use the first queried image as the featured image.