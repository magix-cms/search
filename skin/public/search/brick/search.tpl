{widget_search}
<div class="search-query">
    <form action="{$url}/{$lang}/search/" method="get">
        <div class="input-group">
            <input type="text" class="form-control required" name="filter[query]" placeholder="{#search#}" value="{$needle}" required>
            <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><span class="ico ico-search"></span></button>
          </span>
        </div>
    </form>
</div>