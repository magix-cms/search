{if isset($data.id)}
    {$data = [$data]}
{/if}
{if !$classCol}
    {$classCol = 'col-12'}
{/if}
{if !isset($truncate)}
    {$truncate = 100}
{/if}
{if !isset($img)}
    {$img = true}
{/if}
{if is_array($data) && !empty($data)}
    {$hidden = false}
    {foreach $data as $item}
        {if isset($item.title)}{$item.name = $item.title}{/if}
        {if $item@index > 4 && !$hidden}{$hidden=true}<div class="collapse" id="more-{$section}">{/if}
        <div{if $classCol} class="{$classCol}"{/if}>
            {if $img && count($item.img) > 1}<div class="row">
                <div class="figure col-6 col-md-4">
                    {if count($item.img) > 1}
                        <img class="img-responsive lazyload" {*src="{$item.img.default|replace:'.png':'.jpg'}"*} data-src="{$item.img.medium.src}" alt="{$item.name}" title="{$item.name}" itemprop="image"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}/>
                    {/if}
                </div>
                <div class="col-6 col-md-8">{/if}
                    <div class="desc{*{if $viewport === 'mobile'} sr-only{/if}*}">
                        <h2 class="h3">{$item.name}</h2>
                        {if $item.resume}
                            <p>{$item.resume|truncate:$truncate:'...'}</p>
                        {elseif $item.content}
                            <p>{$item.content|strip_tags|truncate:$truncate:'...'}</p>
                        {/if}
                    </div>
                {if $img && count($item.img) > 1}</div>
            </div>{/if}
            <a class="all-hover" href="{$item.url}" title="{$item.name|ucfirst}">{$item.name}</a>
        </div>
        {if $item@last && $hidden}
            </div>
            <p class="text-center">
                <a class="btn btn-link collapsed" role="button" data-toggle="collapse" href="#more-{$section}" aria-expanded="false" aria-controls="more-{$section}">
                    <span class="show_more">{#show_more#}</span>
                    <span class="show_less">{#show_less#}</span>
                </a>
            </p>
        {/if}
    {/foreach}
{/if}