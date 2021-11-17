This is the sourcecode for a small website i've hacked together with basically no experience.
It's currently hosted over at benstrutt.space (unless I've changed it and forgotten to update this readme).

This readme is more for me to remember stuff than it is to help others understand/use the sourcecode. I keep leaving like 6 to 12 month gaps between working on the site so I forget EVERYTHING.

Reminders:
	+Speed up development by hosting a web server locally in a virtual machine. This allows you to see what changes to the site will do immediately without needing to push to github and wait for the webhost to pick up the changes.
	+Reduce the size of images by installing imagemagick and running convert -resize 20% source.png dest.jpg in Terminal to produce an image that is 20% the size of the source.
		=Or use mogrify -resize 50% *.png to batch modify in place a bunch of files (command is also part of imagemagick)
