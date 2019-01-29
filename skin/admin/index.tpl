{extends file="layout.tpl"}
{block name='head:title'}search{/block}
{block name='body:id'}search{/block}
{block name='article:header'}
    <h1 class="h2">{#search_plugin#}</h1>
{/block}
{block name="article:content"}
    {if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#root_search#}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="alert alert-info">
                            <p class="lead"><span class="fa fa-info"></span>&nbsp;<strong>{#important#}</strong></p>
                            <p>{#warning_msg_1#}</p>
                            <p>{#warning_msg_2#}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <form id="edit_config" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$search.id_config}" method="post" class="validate_form col-12 col-md-4">
                        <div class="form-group">
                            <div class="switch">
                                <input type="checkbox" id="search-fulltext" name="search[fulltext]" class="switch-native-control"{if $search.fulltext_search} checked{/if} />
                                <div class="switch-bg">
                                    <div class="switch-knob"></div>
                                </div>
                            </div>
                            <label for="search-fulltext">Activer la recherche FULLTEXT</label>
                            <a href="#" class="text-info" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Active la cherche par mot dans le contenu des pages">
                                <span class="fa fa-question-circle"></span>
                            </a>
                        </div>
                        <div id="submit">
                            <input type="hidden" id="id_config" name="search[id_config]" value="{$search.id_config}">
                            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}