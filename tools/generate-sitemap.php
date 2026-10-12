<?php

fwrite(STDOUT, "Sitemap is served by Laravel at /sitemap.xml (App\\Services\\SitemapBuilder).\n");
fwrite(STDOUT, "This script no longer writes public/sitemap.xml, so a static file cannot hide new blogs.\n");
exit(0);
