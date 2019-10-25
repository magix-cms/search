TRUNCATE TABLE `mc_search`;
DROP TABLE `mc_search`;
ALTER TABLE mc_about_page_content DROP INDEX content;
ALTER TABLE mc_cms_page_content DROP INDEX content;
ALTER TABLE mc_catalog_cat_content DROP INDEX content;
ALTER TABLE mc_catalog_product_content DROP INDEX content;
ALTER TABLE mc_news_content DROP INDEX content;

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'search'
);