{if isset($bestcheckout_steps) && !empty($bestcheckout_steps)}
    <div class="bestcheckout-nav-container">
        <div class="bestcheckout-nav">
            {foreach from=$bestcheckout_steps item="step" name="stepLoop"}
                <div class="bestcheckout-step 
                            {if $step.is_current}is-current{/if} 
                            {if $step.is_complete}is-complete{/if} 
                            {if !$step.is_reachable}is-disabled{/if}">
                    <div class="step-number">{$smarty.foreach.stepLoop.iteration}</div>
                    <div class="step-title">{$step.title}</div>
                </div>
                {if !$smarty.foreach.stepLoop.last}
                    <div class="step-connector"></div>
                {/if}
            {/foreach}
        </div>
    </div>
{/if}
