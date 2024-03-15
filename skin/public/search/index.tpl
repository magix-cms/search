{extends file="layout.tpl"}
{block name="title"}{#search_results_for#|sprintf:$needle}{/block}
{block name="description"}{#search_results_for#|sprintf:$needle}{/block}
{block name='body:id'}search{/block}
{block name="styleSheet" nocache}
    {$css_files = ["form","search"]}
{/block}
{block name="webType"}SearchResultsPage{/block}
{block name='article'}
    <article class="container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <h1 itemprop="name">{#search_results_for#|sprintf:$needle}</h1>
            {if $msg}
                <p class="alert alert-warning">
                    {$msg}
                </p>
            {/if}
            <div class="row row-center">
            {if $results.s_products}
            <div class="col-12 col-sm-6 col-md-6">
                <h3 class="text-center">{#products#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row">
                        {include file="catalog/loop/product.tpl" data=$results.s_products classCol='vignette col-12 col-xs-8 col-sm-6' nocache}
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            {if $results.s_pages}
            <div class="col-12 col-sm-6 col-md-6">
                <h3 class="text-center">{#pages#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row">
                        {include file="pages/loop/pages.tpl" data=$results.s_pages classCol='vignette col-12 col-xs-8 col-sm-6' nocache}
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            </div>
            {*<pre>{print_r($results)}</pre>*}
            {*<div class="row row-center">
                {if $results.about}
                <div class="col-12 col-sm-6 col-md-4">
                    <h3>{#about#|ucfirst}</h3>
                    <div class="vignette-list">
                        <div class="section-block">
                            {include file="search/loop/item.tpl" data=$results.about classCol='list_item' section='about' img=false}
                        </div>
                    </div>
                </div>
                {/if}
                {if $results.pages}
                <div class="col-12 col-sm-6 col-md-4">
                    <h3>{#pages#|ucfirst}</h3>
                    <div class="vignette-list">
                        <div class="section-block">
                            {include file="search/loop/item.tpl" data=$results.pages classCol='list_item' section='pages'}
                        </div>
                    </div>
                </div>
                {/if}
                {if $results.categories}
                <div class="col-12 col-sm-6 col-md-4">
                    <h3>{#categories#|ucfirst}</h3>
                    <div class="vignette-list">
                        <div class="section-block">
                            {include file="search/loop/item.tpl" data=$results.categories classCol='list_item' section='categories'}
                        </div>
                    </div>
                </div>
                {/if}
                {if $results.s_products}
                <div class="col-12 col-sm-6 col-md-4">
                    <h3>{#products#|ucfirst}</h3>
                    <div class="vignette-list">
                        <div class="section-block">
                            {include file="catalog/loop/product.tpl" data=$results.s_products classCol='vignette' nocache}
                        </div>
                    </div>
                </div>
                {/if}
                {if $results.news}
                <div class="col-12 col-sm-6 col-md-4">
                    <h3>{#news#|ucfirst}</h3>
                    <div class="vignette-list">
                        <div class="section-block">
                            {include file="search/loop/item.tpl" data=$results.news classCol='list_item' section='news'}
                        </div>
                    </div>
                </div>
                {/if}
            </div>*}
        {/block}
    </article>
{/block}