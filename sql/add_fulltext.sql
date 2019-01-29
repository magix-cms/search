CREATE FULLTEXT INDEX content
  ON mc_about_page_content(`content_pages`);
CREATE FULLTEXT INDEX content
  ON mc_cms_page_content(`content_pages`);
CREATE FULLTEXT INDEX content
  ON mc_catalog_cat_content(`content_cat`);
CREATE FULLTEXT INDEX content
  ON mc_catalog_product_content(`content_p`);
CREATE FULLTEXT INDEX content
  ON mc_news_content(`content_news`);