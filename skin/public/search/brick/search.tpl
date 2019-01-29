{widget_search}
<form action="{$url}/{$lang}/search/" method="get">
    <div class="input-group">
        <input type="text" class="form-control" name="query" placeholder="{#search#}" value="{$needle}">
        <span class="input-group-btn">
        <button class="btn btn-default" type="submit"><span class="fa fa-search"></span></button>
      </span>
    </div>
</form>