<?php

/**
 * Snippet to show a BBC style share button.
 * Includes styles, markup and js.
 * 
 * Calls share API where supported.
 */

?>

<style nonnce="<?= $nonce; ?>">
    .share_butt {
        background-color: var(--accent);
        border: 0;
        transition: var(--transition);
        font-size: 1.125rem;
        font-weight: 800;
        display: flex;
        align-items: center;
    }

    .share_fill {
        fill: var(--dark)
    }

    .share_butt:hover {
        text-decoration: underline;
        transition: var(--transition);
    }

    .share_tools {
        border: 2px solid #ccc;
        padding: 1rem;
        transition: var(--transition);
        position: relative;
        margin-bottom: 1rem;
        margin-top: 0.4rem;
        background-color: var(--pale);
        color: var(--dark);
    }

    .share_tools_title{
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .share_close_butt {
        position: absolute;
        right: 0;
        top: 0;
        width: 44px;
        height: 44px;
        background-color: transparent;
        border: 0;
        transition: var(--transition);
    }

    .share_close_butt:hover {
        opacity: 0.6;
    }

    .share_to_service_butt {
        color: white;
        background-color: var(--accent);
        border: 0;
        padding: 0.5rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        cursor:pointer;
    }

    .share_service_icon{
        width:auto;
        height:22px;
        margin-right: 1rem;
    }
</style>

<div class="share-panel">

    <button id="share_butt" type="button" aria-haspopup="true" aria-expanded="false" class="share_butt">
        <svg viewBox="0 0 32 32" width="1em" height="1em" class="fill-red" focusable="false" aria-hidden="true">
            <path d="M7.8 17L25.3 7l-1.2-2.3L6.6 15l1.2 2zm-1.2 0l17.5 10.3 1.2-2.3L7.8 15l-1.2 2zm5.6-1c0-2.7-2.2-5-5-5-2.7 0-4.9 2.2-4.9 5 0 2.7 2.2 4.9 4.9 4.9 2.8 0 5-2.2 5-4.9zM29.7 5.9c0-2.7-2.2-5-5-5-2.7 0-4.9 2.2-4.9 5 0 2.7 2.2 4.9 4.9 4.9 2.8 0 5-2.2 5-4.9zm0 20.2c0-2.7-2.2-5-5-5-2.7 0-4.9 2.2-4.9 5 0 2.7 2.2 4.9 4.9 4.9 2.8.1 5-2.2 5-4.9z" fill="#fff"></path>
        </svg>
        <span class="sr-only">Share</span>
    </button>

    <div id="share_tools" class="share_tools" role="group" aria-labelledby="share_tools-title" hidden>
        <button id="share_close_butt" type="button" class="share_close_butt">
            <svg viewBox="0 0 32 32" width="1em" height="1em" class="" focusable="false" aria-hidden="true">
                <path d="M30 4.6l-2.8-2.8L2 27.4l2.8 2.8L30 4.6zM4.8 1.8L1.9 4.7l25.2 25.5 2.9-2.9L4.8 1.8z" fill="#262325"></path>
            </svg>
            <span class="sr-only">close</span>
        </button>
        <div id="share_tools_title" aria-hidden="true" class="share_tools_title">Share page</div>
        <div>
            <button id="share_copy_butt" width="full" type="button" aria-label="Copy link" class="share_to_service_butt">
                <svg class="share_service_icon share_service_icon_copy" viewBox="0 0 32 32" width="1em" height="1em" focusable="false" aria-hidden="true">
                    <path d="M20.7 9.5l-11 11.1 1.8 1.8 11.1-11.1-1.9-1.8zm.6 6.3l-2 2c2.1 1.4 5.9.8 8.7-2l.6-.6c3-3 3.9-7.6-.1-11.7-4-4-8.6-3-11.6 0l-.6.5c-3 3-3.5 6.7-2 8.7l2-2c-.3-1.1-.2-2.7 2-4.9l.6-.6c2.4-2.4 5.4-2.5 7.9 0 2.5 2.6 2.4 5.5 0 7.9l-.6.6c-2.2 2.3-3.9 2.4-4.9 2.1zm-5.5 5.5c.3 1 .2 2.7-2 4.8l-.6.6c-2.4 2.4-5.4 2.5-7.9 0s-2.4-5.5 0-7.9l.6-.6c2.1-2.1 3.8-2.3 4.9-2l2-2c-2-1.4-5.7-.9-8.7 2l-.6.6c-3 3-4 7.6 0 11.6s8.6 3.1 11.7.1l.6-.6c2.9-2.9 3.5-6.7 2-8.7l-2 2.1z" fill="#fff"></path>
                </svg> <span id="copy_link_copy"> Copy link</span></button>
        </div>
        <div>
            <button id="share_twitter_butt" width="full" type="button" aria-label="Copy link" class="share_to_service_butt">
            <svg class="share_service_icon share_service_icon_twitter" viewBox="0 0 36.552 29.7127" xmlns="http://www.w3.org/2000/svg"><path d="m36.481 3.5427a15.2271 15.2271 0 0 1 -4.3 1.18 7.5505 7.5505 0 0 0 3.294-4.147 15.4746 15.4746 0 0 1 -4.762 1.8 7.493 7.493 0 0 0 -12.769 6.826 21.2279 21.2279 0 0 1 -15.444-7.803 7.3447 7.3447 0 0 0 -1.017 3.769 7.4938 7.4938 0 0 0 3.332 6.238 7.4673 7.4673 0 0 1 -3.393-.938v.09a7.5 7.5 0 0 0 6.01 7.352 7.6083 7.6083 0 0 1 -3.369.129 7.5178 7.5178 0 0 0 7.012 5.2 15.0276 15.0276 0 0 1 -9.293 3.206 15.8814 15.8814 0 0 1 -1.782-.096 21.3137 21.3137 0 0 0 11.509 3.364c13.788 0 21.319-11.416 21.319-21.3 0-.32 0-.64-.023-.959a15.1286 15.1286 0 0 0 3.747-3.881z" fill="#fff"/></svg> <span id="copy_link_copy"> Share on Twitter</span></button>
        </div>
    </div>

</div>

<script nonce="<?= $nonce ?>">
    const sharebutt = document.getElementById('share_butt');
    const sharetools = document.getElementById('share_tools');
    const shareclose = document.getElementById('share_close_butt');
    const sharecopy = document.getElementById('share_copy_butt');
    const sharetwitter = document.getElementById('share_twitter_butt');
    const clc = document.getElementById('copy_link_copy');

    // ================= shared values ==============
    // you'll want to update these probably.

    const share_title = "Reframing Race";
    const share_text = "<?= $page->title ?>";
    const share_url = "<?= $page->httpUrl ?>";

    /**
     * Toggle share panel
     */
    function share_panel_toggle() {

        sharebutt.classList.toggle("panel-toggled");
        if (sharebutt.getAttribute("aria-expanded") == "true") {
            sharebutt.setAttribute("aria-expanded", false);
            clc.innerHTML = "Copy link";
        } else {
            sharebutt.setAttribute("aria-expanded", true);
        }


        sharetools.toggleAttribute("hidden");

        if (sharetools.style.maxHeight) {
            sharetools.style.maxHeight = null;
        } else {
            sharetools.style.maxHeight = sharetools.scrollHeight + "px";
        }

    }

    document.addEventListener('DOMContentLoaded', function() {

        sharebutt.addEventListener('click', async () => {

            if (navigator.share) { // open native share dialog
                try {

                    let shareData = {
                        title: share_title,
                        text: share_title,
                        url: share_url
                    }
                    await navigator.share(shareData)


                } catch (err) {
                    // boop

                }
            } else {
                share_panel_toggle();
            }
        });


        shareclose.addEventListener('click', event => {
            share_panel_toggle();
        });

        // copy URL button
        sharecopy.addEventListener('click', event => {
            if (!window.getSelection) {
                alert('Please copy the URL from the location bar.');
                return;
            }
            const dummy = document.createElement('p');
            dummy.textContent = window.location.href;
            document.body.appendChild(dummy);

            const range = document.createRange();
            range.setStartBefore(dummy);
            range.setEndAfter(dummy);

            const selection = window.getSelection();
            // First clear, in case the user already selected some other text
            selection.removeAllRanges();
            selection.addRange(range);

            document.execCommand('copy');

            clc.innerHTML = "Link copied";

            document.body.removeChild(dummy);

        });

        // share to Twitter button
        sharetwitter.addEventListener('click', event => {
            
            const twitter_share_uri = new URL('https://twitter.com/intent/tweet');
            const params = new URLSearchParams();
            params.append('text', encodeURIComponent(share_text));
            // hmm.. using encodeURIComponent on the url breaks things... wonder why...
            params.append('url', encodeURI(share_url));
            twitter_share_uri.search = params;

           window.open(twitter_share_uri, '_blank', 'popup,noreferrer,noopener');
            
        });


    })
</script>